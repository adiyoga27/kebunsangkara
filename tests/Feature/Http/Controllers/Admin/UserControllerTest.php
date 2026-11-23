<?php

use App\Enums\UserRole;
use App\Models\ActivityLog;
use App\Models\User;

it('forbids editors from managing users', function () {
    $this->actingAs(User::factory()->create())->get(route('admin.users.index'))->assertForbidden();
});

it('creates a user and masks the password in the activity log', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->post(route('admin.users.store'), [
        'name' => 'Editor Baru',
        'email' => 'editor@durian.test',
        'role' => UserRole::Editor->value,
        'is_active' => '1',
        'password' => 'rahasia123',
        'password_confirmation' => 'rahasia123',
    ])->assertRedirect(route('admin.users.index'));

    $user = User::query()->where('email', 'editor@durian.test')->sole();
    expect($user->role)->toBe(UserRole::Editor);

    $log = ActivityLog::query()->where('subject_id', $user->id)->sole();
    expect($log->properties['password'])->toBe('********');
});

it('keeps the existing password when the password field is left empty', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create(['password' => 'lama12345']);

    $this->actingAs($admin)->put(route('admin.users.update', $user), [
        'name' => 'Nama Baru',
        'email' => $user->email,
        'role' => UserRole::Editor->value,
        'is_active' => '1',
    ])->assertRedirect(route('admin.users.index'));

    expect(Hash::check('lama12345', $user->fresh()->password))->toBeTrue();
});

it('prevents an admin from deactivating their own account', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->put(route('admin.users.update', $admin), [
        'name' => $admin->name,
        'email' => $admin->email,
        'role' => UserRole::Admin->value,
    ])->assertSessionHasErrors('role');

    expect($admin->fresh()->is_active)->toBeTrue();
});

it('prevents an admin from deleting their own account', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))->assertSessionHasErrors('user');

    $this->assertModelExists($admin);
});
