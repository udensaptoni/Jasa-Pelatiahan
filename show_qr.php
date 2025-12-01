<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$console = $app->make(Illuminate\Contracts\Console\Kernel::class);
$console->bootstrap();

$payment = \App\Models\Payment::latest()->first();
if (!$payment) {
    echo "No payment found\n";
    exit(1);
}
$qr = $payment->qr_string ?? '';
echo "---BEGIN QR STRING---\n";
echo $qr . "\n";
echo "---END QR STRING---\n";
echo "Length: " . strlen($qr) . "\n";
// show hex snippets
$hex = unpack('H*', $qr)[1];
$hexSnippet = substr($hex, 0, 200);
echo "Hex (start): " . $hexSnippet . "\n";
// show if contains whitespace
if (preg_match('/\s/', $qr)) echo "Contains whitespace (space/newline/tab)\n"; else echo "No whitespace chars detected\n";
