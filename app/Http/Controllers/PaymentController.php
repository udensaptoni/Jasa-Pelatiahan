<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Support\Facades\Http;
use App\Models\WebhookLog;

class PaymentController extends Controller
{
    // Show payment creation page (summary)
    public function create($registrationId)
    {
        $registration = Registration::with('product')->findOrFail($registrationId);
        return view('payments.create', compact('registration'));
    }

    // Create payment via Midtrans (simple server-side call)
    public function store(Request $request, $registrationId)
    {
        $registration = Registration::findOrFail($registrationId);

        // For demo: use product price as amount
        $amount = $registration->product->price ?? 10000;

        // Build Midtrans charge payload (simplified)
        $serverKey = config('services.midtrans.server_key');
        $isProd = config('services.midtrans.is_production');
        if (empty($serverKey)) {
            return back()->with('error', 'Midtrans server key not configured.');
        }

        $payload = [
            'transaction_details' => [
                'order_id' => 'REG-' . $registration->id . '-' . time(),
                'gross_amount' => $amount,
            ],
            'payment_type' => 'qris',
            // For universal QRIS usable by multiple e-wallets, do not force a specific acquirer here.
            // Midtrans will return a standard QR payload that many e-wallets can scan.
            // Include product/item details so Midtrans shows breakdown
            'item_details' => [
                [
                    'id' => $registration->product->id ?? 'prod-'.$registration->product_id,
                    'price' => (int) ($registration->product->price ?? $amount),
                    'quantity' => 1,
                    'name' => $registration->product->name ?? 'Pendaftaran',
                ],
            ],
            'customer_details' => [
                'first_name' => $registration->nama,
                'email' => $registration->email,
                'phone' => $registration->telepon,
            ],
        ];

        $base = $isProd ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';
        $chargeUrl = $base . '/v2/charge';

        $response = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->post($chargeUrl, $payload);

        if ($response->failed()) {
            return back()->with('error', 'Gagal membuat pembayaran.');
        }


        $data = $response->json();

        // Additionally create a Snap transaction (hosted UI) so frontend can offer Midtrans Snap as an option
        try {
            $snapUrl = $base . '/snap/v1/transactions';
            $snapPayload = [
                'transaction_details' => [
                    'order_id' => $payload['transaction_details']['order_id'],
                    'gross_amount' => $amount,
                ],
                'item_details' => $payload['item_details'],
                'customer_details' => $payload['customer_details'],
            ];

            $snapResp = Http::withBasicAuth($serverKey, '')->acceptJson()->post($snapUrl, $snapPayload);
            if ($snapResp->ok()) {
                $snapData = $snapResp->json();
                // Save token in metadata
                $data['snap'] = $snapData;
                // If snap provided a redirect_url and we don't have a payment_url yet, fill it
                if (empty($paymentUrl) && !empty($snapData['redirect_url'])) {
                    $paymentUrl = $snapData['redirect_url'];
                }
            }
        } catch (\Exception $e) {
            // ignore snap errors but keep QRIS working
        }

        // Normalize external id / url depending on Midtrans response shape
        $externalId = $data['transaction_id'] ?? $data['order_id'] ?? ($data['order_id'] ?? null);
        $paymentUrl = null;
        if (!empty($data['actions'][0]['url'] ?? null)) {
            $paymentUrl = $data['actions'][0]['url'];
        } elseif (!empty($data['redirect_url'] ?? null)) {
            $paymentUrl = $data['redirect_url'];
        }

        $qrString = $data['qr_string'] ?? ($data['actions'][0]['qr_string'] ?? null) ?? null;
        // Sanitize qr string: remove CR/LF which can break simulators/e-wallet parse
        if (!empty($qrString)) {
            $qrString = preg_replace('/\r|\n/', '', $qrString);
            // also sync into metadata if present
            if (isset($data['qr_string'])) $data['qr_string'] = $qrString;
            if (isset($data['actions'][0]['qr_string'])) $data['actions'][0]['qr_string'] = $qrString;
        }

        // Attach product info to metadata for admin view if Midtrans didn't echo it back
        $meta = $data;
        if (empty($meta['item_details'])) {
            $meta['item_details'] = [
                [
                    'id' => $registration->product->id ?? null,
                    'name' => $registration->product->name ?? null,
                    'price' => (int) ($registration->product->price ?? $amount),
                    'quantity' => 1,
                    'subtotal' => (int) ($registration->product->price ?? $amount),
                ]
            ];
        }

        $payment = Payment::create([
            'registration_id' => $registration->id,
            'amount' => $amount,
            'provider' => 'midtrans',
            'external_id' => $externalId,
            'payment_url' => $paymentUrl,
            'qr_string' => $qrString,
            'status' => $data['status_message'] ?? ($data['transaction_status'] ?? 'pending'),
            'metadata' => $meta,
        ]);

        return redirect()->route('payments.show', $payment->id);
    }

    public function show($id)
    {
        $payment = Payment::with('registration.product')->findOrFail($id);
        return view('payments.show', compact('payment'));
    }

    // Webhook endpoint (Midtrans) — simple handler
    public function webhook(Request $request)
    {
        $payload = $request->all();

        $serverKey = config('services.midtrans.server_key');
        $orderId = $payload['order_id'] ?? null;
        $status = $payload['transaction_status'] ?? ($payload['transaction_status'] ?? null);

        // Persist webhook log (raw)
        try {
            $log = WebhookLog::create([
                'provider' => 'midtrans',
                'external_id' => $payload['transaction_id'] ?? $orderId,
                'payload' => $payload,
                'status' => $status,
            ]);
        } catch (\Throwable $e) {
            // ignore logging errors but continue
            $log = null;
        }

        // Find payment by external id/order_id
        $payment = Payment::where('external_id', $payload['transaction_id'] ?? $orderId)
            ->orWhere('external_id', $orderId)
            ->first();

        if (!$payment) {
            return response('not found', 200);
        }

        // Require signature_key in production; allow in sandbox if not present
        if ($request->has('signature_key')) {
            $signature = $request->input('signature_key');
            // Build expected signature according to Midtrans docs: order_id + status_code + gross_amount + serverKey
            $expected = hash('sha512', ($orderId . ($payload['status_code'] ?? '') . ($payload['gross_amount'] ?? '') . $serverKey));
            if ($signature !== $expected) {
                return response('invalid signature', 403);
            }
        } else {
            // If server key is set, require signature by default
            if (!empty($serverKey)) {
                return response('signature required', 403);
            }
        }

        // Idempotency: only update if status changed
        $newStatus = $status ?? ($payload['status_message'] ?? $payment->status);
        if ($payment->status === $newStatus) {
            return response('no change', 200);
        }

        // Update payment status and metadata
        $payment->status = $newStatus;
        $payment->metadata = array_merge($payment->metadata ?? [], $payload);
        $payment->save();

        // If payment succeeded, mark registration as paid
        if (in_array($payment->status, ['settlement', 'capture', 'paid'])) {
            $reg = $payment->registration;
            if ($reg) {
                $reg->is_paid = true;
                $reg->save();
            }
        }

        return response('ok', 200);
    }

    // Endpoint to get current payment status (for AJAX polling)
    public function status($id)
    {
        $payment = Payment::findOrFail($id);
        return response()->json(['status' => $payment->status]);
    }

    // Proxy-download QR PNG from provider actions[0].url (if present)
    public function downloadQr($id)
    {
        $payment = Payment::findOrFail($id);
        $actions = $payment->metadata['actions'] ?? null;
        $cachePath = storage_path('app/public/qrs');
        if (!is_dir($cachePath)) {
            @mkdir($cachePath, 0755, true);
        }
        $fileName = $cachePath.'/qris-'.$payment->id.'.png';

        // Serve cached if exists
        if (file_exists($fileName)) {
            return response()->file($fileName, [
                'Content-Type' => 'image/png',
                'Content-Disposition' => 'attachment; filename="qris-'.$payment->external_id.'.png"'
            ]);
        }

        if ($actions && !empty($actions[0]['url'])) {
            $url = $actions[0]['url'];
            try {
                $resp = Http::withOptions(['verify' => false])->get($url);
                if ($resp->ok()) {
                    // Cache response body to file
                    file_put_contents($fileName, $resp->body());
                    return response()->file($fileName, [
                        'Content-Type' => $resp->header('Content-Type', 'image/png'),
                        'Content-Disposition' => 'attachment; filename="qris-'.$payment->external_id.'.png"'
                    ]);
                }
            } catch (\Exception $e) {
                // fall through to fallback
            }
        }

        // fallback: redirect back with message
        return redirect()->route('payments.show', $payment->id)->with('error', 'Tidak dapat mengunduh QR dari provider saat ini. Silakan coba beberapa saat lagi.');
    }

    // Render invoice HTML (simple) or stream PDF if ?pdf=1
    public function invoice($id, Request $request)
    {
        $payment = Payment::with('registration.product')->findOrFail($id);
        $view = view('payments.invoice', compact('payment'));
        if ($request->query('pdf') == '1') {
            // Try to use DOMPDF if available
            if (class_exists('\Dompdf\Dompdf')) {
                $dompdf = new \Dompdf\Dompdf();
                $dompdf->loadHtml($view->render());
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                return response($dompdf->output(), 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="invoice-'.$payment->external_id.'.pdf"');
            }
            // fallback: return HTML
        }

        return $view;
    }
}
