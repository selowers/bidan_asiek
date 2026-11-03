<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_edit_existing_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create([
            'name' => 'Yono',
            'email' => 'yono@gmail.com',
            'role' => 'user',
        ]);

        $this->actingAs($admin)
            ->put(route('users.update', $user), [
                'name' => 'Yono Updated',
                'email' => 'yono.updated@gmail.com',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
                'account_type' => 'patient',
                'phone' => '081234567890',
            ])
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Yono Updated',
            'email' => 'yono.updated@gmail.com',
        ]);
    }
}
