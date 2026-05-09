<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('TirahanTech');
        $response->assertSee('Create an account');
    }

    public function test_client_can_register_and_is_redirected_to_dashboard(): void
    {
        $response = $this->post('/register', [
            'name' => 'Client User',
            'email' => 'client@example.com',
            'role' => 'client',
            'phone' => '09123456789',
            'address' => 'Quezon City',
            'license_number' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'client@example.com',
            'role' => 'client',
        ]);
    }

    public function test_agent_registration_requires_license_number(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Agent User',
            'email' => 'agent@example.com',
            'role' => 'agent',
            'phone' => '09123456789',
            'address' => 'Makati City',
            'license_number' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('license_number');
        $this->assertGuest();
    }

    public function test_existing_user_can_log_in_and_reach_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'member@example.com',
            'password' => 'password123',
            'role' => 'agent',
            'license_number' => 'LIC-12345',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }
}
