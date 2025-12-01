<?php
// download_qr.php - download latest payment QR PNG from Midtrans and save to public/qrcode_download.png
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$console = $app->make(Illuminate\Contracts\Console\Kernel::class);
$console->bootstrap();

$payment = \App\Models\Payment::latest()->first();
if (!$payment) { echo "No payment found\n"; exit(1); }
$actions = $payment->metadata['actions'] ?? null;
$qrUrl = null;
if ($actions && is_array($actions)) {
    foreach ($actions as $a) {
        if (strpos($a['name'] ?? '', 'generate-qr-code') !== false) {
            $qrUrl = $a['url']; break;
        }
    }
}
if (!$qrUrl && !empty($payment->payment_url)) $qrUrl = $payment->payment_url;
if (!$qrUrl) { echo "No QR URL found in metadata\n"; exit(1); }

$serverKey = config('services.midtrans.server_key');
if (empty($serverKey)) { echo "No server key configured\n"; exit(1); }

// use Guzzle via Http facade
try {
    $resp = Illuminate\Support\Facades\Http::withBasicAuth($serverKey, '')->get($qrUrl);
    if ($resp->successful()) {
        $out = __DIR__ . '/public/qrcode_download.png';
        file_put_contents($out, $resp->body());
        echo "Saved QR PNG to $out\n";
    } else {
        echo "Failed to download QR PNG: " . $resp->status() . "\n";
        echo $resp->body();
    }
} catch (Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
