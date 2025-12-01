<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$console = $app->make(Illuminate\Contracts\Console\Kernel::class);
$console->bootstrap();
$p = \App\Models\Payment::latest()->first();
if (!$p) { echo "no payment\n"; exit; }
echo json_encode($p->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
