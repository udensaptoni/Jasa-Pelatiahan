<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$admin = \App\Models\Admin::first();
if ($admin) {
    echo $admin->email . "\n";
} else {
    echo "NO_ADMIN\n";
}
