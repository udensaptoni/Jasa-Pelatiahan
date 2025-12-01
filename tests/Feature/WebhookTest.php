<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Registration;
use App\Models\Payment;

class WebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_midtrans_webhook_marks_registration_paid()
    {
        // Arrange: create product and registration (no factories available)
        $product = \App\Models\Product::create([
            'name' => 'Test Product',
            'description' => 'Test',
            'price' => 10000,
        ]);

        $registration = Registration::create([
            'product_id' => $product->id,
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'telepon' => '08123456789',
            'catatan' => 'Test',
            'is_paid' => false,
        ]);
        $payment = Payment::create([
            'registration_id' => $registration->id,
            'amount' => 10000,
            'provider' => 'midtrans',
            'external_id' => 'TID-' . time(),
            'status' => 'pending',
            'metadata' => [],
        ]);

        // Prepare payload similar to Midtrans
    // Ensure the app config uses the same server key the controller will check
    $serverKey = 'test-server-key';
    config(['services.midtrans.server_key' => $serverKey]);
        $payload = [
            'order_id' => $payment->external_id,
            'transaction_id' => $payment->external_id,
            'status_code' => '200',
            'gross_amount' => (string) $payment->amount,
            'transaction_status' => 'settlement',
        ];

        $signature = hash('sha512', ($payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $serverKey));
        $payload['signature_key'] = $signature;

        // Act: post webhook
        $resp = $this->postJson(route('payments.webhook.midtrans'), $payload);
        $resp->assertStatus(200);

        // Reload models
        $payment->refresh();
        $registration->refresh();

        // Assert
        $this->assertEquals('settlement', $payment->status);
        $this->assertTrue((bool) $registration->is_paid);
    }
}
