<?php

use App\Enums\ActivityAction;
use App\Models\User;
use App\Support\ActivityLogger;

it('forbids editors from viewing the activity log', function () {
    $this->actingAs(User::factory()->create())->get(route('admin.activity-logs.index'))->assertForbidden();
});

it('filters the activity log by user and action', function () {
    $admin = User::factory()->admin()->create();
    $editor = User::factory()->create();
    $logger = app(ActivityLogger::class);
    $logger->log(ActivityAction::Login, 'Login editor', user: $editor);
    $logger->log(ActivityAction::Logout, 'Logout editor', user: $editor);
    $logger->log(ActivityAction::Login, 'Login admin', user: $admin);

    $this->actingAs($admin)
        ->get(route('admin.activity-logs.index', ['user_id' => $editor->id, 'action' => 'login']))
        ->assertOk()
        ->assertSee('Login editor')
        ->assertDontSee('Logout editor')
        ->assertDontSee('Login admin');
});
