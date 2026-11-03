<?php

namespace Tests\Feature;

use App\Models\LayananKlinik;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ServiceCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_service_with_an_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.clinic-services.store'), [
                'name' => 'Pemeriksaan Kehamilan',
                'category' => 'kia',
                'description' => 'Pemeriksaan ibu dan janin.',
                'duration_minutes' => 45,
                'image' => UploadedFile::fake()->image('kehamilan.jpg'),
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.clinic-services.index'));

        $service = LayananKlinik::where('name', 'Pemeriksaan Kehamilan')->firstOrFail();

        $this->assertDatabaseHas('clinic_services', [
            'id' => $service->id,
            'category' => 'kia',
            'is_active' => true,
        ]);
        $this->assertTrue(Storage::disk('public')->exists($service->image_path));
    }

    public function test_patient_catalog_shows_only_active_services_in_the_selected_category(): void
    {
        $activeService = LayananKlinik::factory()->create([
            'name' => 'Konsultasi Kehamilan',
            'category' => 'kia',
            'is_active' => true,
        ]);
        LayananKlinik::factory()->create([
            'name' => 'Layanan Nonaktif',
            'category' => 'kia',
            'is_active' => false,
        ]);
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('catalog.index', ['category' => 'kia']))
            ->assertOk()
            ->assertSee('Konsultasi Kehamilan')
            ->assertSee('storage/'.$activeService->image_path)
            ->assertDontSee('Layanan Nonaktif');
    }

    public function test_non_admin_cannot_manage_service_catalog(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.clinic-services.index'))
            ->assertForbidden();
    }
}
