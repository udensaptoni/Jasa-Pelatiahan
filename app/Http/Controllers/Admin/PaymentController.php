<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use App\Models\Registration;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = Payment::with('registration.product')->latest();
        if ($status) $query->where('status', $status);
        $payments = $query->paginate(20);
        return view('admin.payments.index', compact('payments','status'));
    }

    // Re-query Midtrans for a payment and update local status
    public function reconcile(Request $request, Payment $payment)
    {
        $serverKey = config('services.midtrans.server_key');
        if (empty($serverKey) || empty($payment->external_id)) {
            return redirect()->back()->with('error', 'Cannot reconcile: missing server key or external id');
        }

        // Call Midtrans API to get status (status endpoint)
        // Retry up to 3 times for transient errors
        $attempts = 0;
        $data = null;
        while ($attempts < 3) {
            $resp = Http::withBasicAuth($serverKey, '')->acceptJson()->get("https://api.sandbox.midtrans.com/v2/{$payment->external_id}/status");
            if ($resp->successful()) {
                $data = $resp->json();
                break;
            }
            $attempts++;
            sleep(1 * $attempts);
        }

        if (is_null($data)) {
            return redirect()->back()->with('error', 'Failed to fetch status from Midtrans after retries');
        }
        $newStatus = $data['transaction_status'] ?? ($data['status_message'] ?? null);
        if ($newStatus && $payment->status !== $newStatus) {
            $payment->status = $newStatus;
            $payment->metadata = array_merge($payment->metadata ?? [], $data);
            $payment->save();

            if (in_array($newStatus, ['settlement','capture','paid'])) {
                $reg = $payment->registration;
                if ($reg) { $reg->is_paid = true; $reg->save(); }
            }
        }

        return redirect()->back()->with('success', 'Reconciled payment status.');
    }
}
