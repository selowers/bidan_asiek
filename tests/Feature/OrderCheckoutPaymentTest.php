<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrderCheckoutPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_must_upload_payment_proof_before_order_can_be_created(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 50000, 'is_active' => true]);
        CartItem::create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1]);

        $paymentMethod = PaymentMethod::create([
            'name' => 'Bank BCA',
            'type' => 'bank',
            'bank_name' => 'Bank Central Asia',
            'account_name' => 'Bidan Asiek',
            'account_number' => '1234567890',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->post(route('orders.store'), [
                'payment_method_id' => $paymentMethod->id,
                'payment_proof' => UploadedFile::fake()->image('bukti.jpg'),
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'payment_method_id' => $paymentMethod->id,
        ]);
    }

    public function test_patient_cannot_create_order_without_payment_proof_upload(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 50000, 'is_active' => true]);
        CartItem::create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1]);

        $paymentMethod = PaymentMethod::create([
            'name' => 'QRIS',
            'type' => 'qris',
            'account_name' => 'Bidan Asiek',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->from(route('orders.checkout'))
            ->post(route('orders.store'), [
                'payment_method_id' => $paymentMethod->id,
            ])
            ->assertSessionHasErrors(['payment_proof']);
    }
}
