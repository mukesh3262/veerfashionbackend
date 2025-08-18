<?php

declare(strict_types=1);

namespace Tests\Feature\Admin\Auth;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page->component('Admin/Auth/Login')
            );

        $this->assertGuest();
    }

    public function test_admin_users_can_authenticate_using_the_login_screen(): void
    {
        $this->seed();

        $admin = Admin::whereEmail(Admin::SUPER_ADMIN_EMAIL)->first();

        $response = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'admin@spaceo',
        ]);

        $response
            ->assertRedirectToRoute('admin.dashboard');

        $this->assertAuthenticated('admin');
    }
}
