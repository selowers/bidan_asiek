<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pasien;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAdminMetricsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_paid_patients_and_top_products(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Admin Bidan']);

        $patientOne = Pasien::factory()->create(['name' => 'Siti Rahma']);
        $patientTwo = Pasien::factory()->create(['name' => 'Dewi Lestari']);
        $patientThree = Pasien::factory()->create(['name' => 'Rina Sari']);
        $patientFour = Pasien::factory()->create(['name' => 'Maya Putri']);
        $patientFive = Pasien::factory()->create(['name' => 'Nanda Wijaya']);

        $userOne = User::factory()->create(['patient_id' => $patientOne->id, 'role' => 'user']);
        $userTwo = User::factory()->create(['patient_id' => $patientTwo->id, 'role' => 'user']);
        $userThree = User::factory()->create(['patient_id' => $patientThree->id, 'role' => 'user']);
        $userFour = User::factory()->create(['patient_id' => $patientFour->id, 'role' => 'user']);
        $userFive = User::factory()->create(['patient_id' => $patientFive->id, 'role' => 'user']);

        $orderOne = Order::create([
            'user_id' => $userOne->id,
            'order_number' => 'ORD-1001',
            'total' => 90000,
            'status' => 'paid',
            'paid_at' => now()->subDay(),
        ]);

        $orderTwo = Order::create([
            'user_id' => $userTwo->id,
            'order_number' => 'ORD-1002',
            'total' => 120000,
            'status' => 'paid',
            'paid_at' => now()->subDays(2),
        ]);

        $orderThree = Order::create([
            'user_id' => $userThree->id,
            'order_number' => 'ORD-1003',
            'total' => 150000,
            'status' => 'paid',
            'paid_at' => now()->subDays(3),
        ]);

        $orderFour = Order::create([
            'user_id' => $userFour->id,
            'order_number' => 'ORD-1004',
            'total' => 210000,
            'status' => 'paid',
            'paid_at' => now()->subDays(4),
        ]);

        $orderFive = Order::create([
            'user_id' => $userFive->id,
            'order_number' => 'ORD-1005',
            'total' => 300000,
            'status' => 'pending',
            'paid_at' => null,
        ]);

        $videoOne = Product::factory()->create(['title' => 'Konseling Kebidanan']);
        $videoTwo = Product::factory()->create(['title' => 'Persalinan Aman']);
        $videoThree = Product::factory()->create(['title' => 'Nifas Sehat']);
        $videoFour = Product::factory()->create(['title' => 'Kesehatan Bayi']);
        $videoFive = Product::factory()->create(['title' => 'Makanan Ibu Hamil']);
        $videoSix = Product::factory()->create(['title' => 'Pola Hidup Sehat']);
        $videoSeven = Product::factory()->create(['title' => 'Kelas Parenting']);

        $sales = [
            [$orderOne->id, $videoOne->id, 3],
            [$orderTwo->id, $videoOne->id, 2],
            [$orderThree->id, $videoTwo->id, 4],
            [$orderFour->id, $videoTwo->id, 1],
            [$orderOne->id, $videoThree->id, 2],
            [$orderTwo->id, $videoThree->id, 1],
            [$orderThree->id, $videoFour->id, 5],
            [$orderFour->id, $videoFive->id, 2],
            [$orderThree->id, $videoSix->id, 1],
            [$orderTwo->id, $videoSeven->id, 1],
        ];

        foreach ($sales as $sale) {
            OrderItem::create([
                'order_id' => $sale[0],
                'product_id' => $sale[1],
                'product_name' => Product::find($sale[1])->title,
                'price' => 10000,
                'quantity' => $sale[2],
            ]);
        }

        $this->actingAs($admin)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Produk Terlaris')
            ->assertSee('Siti Rahma')
            ->assertSee('Dewi Lestari')
            ->assertSee('Rina Sari')
            ->assertSee('Maya Putri')
            ->assertSee('Konseling Kebidanan');
    }
}
