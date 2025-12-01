<?php
// simulate_payment.php - bootstrap Laravel and call POST /payments/{registration}
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Http\Request;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// Bootstrap the framework (console kernel) so config, facades and Eloquent are available
$console = $app->make(Illuminate\Contracts\Console\Kernel::class);
$console->bootstrap();

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

try {
    // ensure we have a registration
    $reg = \App\Models\Registration::first();
    if (!$reg) {
        // try to create one using first product
        $prod = \App\Models\Product::first();
        if (!$prod) {
            echo "No products available to create registration. Aborting.\n";
            exit(1);
        }
        $reg = \App\Models\Registration::create([
            'product_id' => $prod->id,
            'nama' => 'Test User',
            'email' => 'test+sim@example.com',
            'telepon' => '08123456789',
            'catatan' => 'Simulasi pembayaran',
        ]);
        echo "Created registration id={$reg->id}\n";
    } else {
        echo "Using registration id={$reg->id}\n";
    }

    // create request to POST /payments/{registration}
    $url = '/payments/' . $reg->id;
    $request = Request::create($url, 'POST', [], [], [], ['HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json']);
    // handle request
    $response = $kernel->handle($request);

    $status = $response->getStatusCode();
    $content = $response->getContent();

    echo "Response status: $status\n";
    echo "Response content:\n";
    echo $content . "\n";

    // show latest payment
    $payment = \App\Models\Payment::with('registration.product')->latest()->first();
    if ($payment) {
        echo "Stored payment (latest):\n";
        echo json_encode($payment->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    } else {
        echo "No payment stored.\n";
    }

    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
