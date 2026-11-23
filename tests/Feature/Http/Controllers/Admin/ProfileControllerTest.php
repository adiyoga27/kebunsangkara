<?php

use App\Models\User;

it('requires the current password to set a new password', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->put(route('admin.profile.update'), [
        'name' => $user->name,
        'email' => $user->email,
        'current_password' => 'salah',
        'password' => 'barubaru123',
        'password_confirmation' => 'barubaru123',
    ])->assertSessionHasErrors('current_password');
});

it('changes the password when the current password is correct', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->put(route('admin.profile.update'), [
        'name' => $user->name,
        'email' => $user->email,
        'current_password' => 'password',
        'password' => 'barubaru123',
        'password_confirmation' => 'barubaru123',
    ])->assertRedirect(route('admin.profile.edit'));

    expect(Hash::check('barubaru123', $user->fresh()->password))->toBeTrue();
});
