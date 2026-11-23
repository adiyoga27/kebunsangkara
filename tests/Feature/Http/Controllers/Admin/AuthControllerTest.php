<?php

use App\Enums\ActivityAction;
use App\Models\ActivityLog;
use App\Models\User;

it('redirects guests from the admin panel to the login page', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
});

it('logs in an active user and records the login', function () {
    $user = User::factory()->create(['password' => 'rahasia123']);

    $this->post(route('admin.login.store'), ['email' => $user->email, 'password' => 'rahasia123'])
        ->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user);
    expect($user->fresh()->last_login_at)->not->toBeNull();
    $this->assertDatabaseHas('activity_logs', ['user_id' => $user->id, 'action' => ActivityAction::Login->value]);
});

it('rejects a wrong password and records the failed attempt', function () {
    $user = User::factory()->create();

    $this->post(route('admin.login.store'), ['email' => $user->email, 'password' => 'salah'])
        ->assertSessionHasErrors(['email' => 'Email atau password salah, atau akun tidak aktif.']);

    $this->assertGuest();
    $log = ActivityLog::query()->where('action', ActivityAction::LoginFailed)->sole();
    expect($log->properties)->toBe(['email' => $user->email]);
});

it('rejects login for an inactive user', function () {
    $user = User::factory()->inactive()->create(['password' => 'rahasia123']);

    $this->post(route('admin.login.store'), ['email' => $user->email, 'password' => 'rahasia123'])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('logs out a user whose account was deactivated during the session', function () {
    $user = User::factory()->inactive()->create();

    $this->actingAs($user)->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));

    $this->assertGuest();
});

it('logs out and records the logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('admin.logout'))->assertRedirect(route('admin.login'));

    $this->assertGuest();
    $this->assertDatabaseHas('activity_logs', ['user_id' => $user->id, 'action' => ActivityAction::Logout->value]);
});
