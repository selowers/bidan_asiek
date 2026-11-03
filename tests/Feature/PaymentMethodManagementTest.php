<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_bank_and_qris_payment_methods(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('payment-methods.store'), [
                'name' => 'Bank BCA',
                'type' => 'bank',
                'bank_name' => 'Bank Central Asia',
                'account_name' => 'Bidan Asiek',
                'account_number' => '1234567890',
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.store'));

        $this->assertDatabaseHas('payment_methods', [
            'name' => 'Bank BCA',
            'type' => 'bank',
            'account_number' => '1234567890',
        ]);

        $this->actingAs($admin)
            ->post(route('payment-methods.store'), [
                'name' => 'QRIS',
                'type' => 'qris',
                'account_name' => 'Bidan Asiek',
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.store'));

        $this->assertDatabaseHas('payment_methods', [
            'name' => 'QRIS',
            'type' => 'qris',
        ]);
    }
}
