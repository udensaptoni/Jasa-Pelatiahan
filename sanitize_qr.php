<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$console = $app->make(Illuminate\Contracts\Console\Kernel::class);
$console->bootstrap();

$payment = \App\Models\Payment::latest()->first();
if (!$payment) { echo "No payment found\n"; exit(1); }
$qr = $payment->qr_string ?? '';
// Remove CR/LF only (keep normal spaces inside merchant name)
$clean = preg_replace('/\r|\n/', '', $qr);
echo $clean . "\n";
