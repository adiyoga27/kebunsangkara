<?php

use App\Enums\ActivityAction;
use App\Models\User;
use App\Support\SiteSettings;

/**
 * @return array<string, string>
 */
function validSettingsPayload(array $overrides = []): array
{
    return [...SiteSettings::defaults(), ...$overrides];
}

it('forbids editors from changing settings', function () {
    $this->actingAs(User::factory()->create())
        ->put(route('admin.settings.update'), validSettingsPayload())
        ->assertForbidden();
});

it('saves settings, shows them on the website and logs the changed keys', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->put(route('admin.settings.update'), validSettingsPayload(['site_name' => 'Durian Pak Made']))
        ->assertRedirect(route('admin.settings.edit'));

    $this->get(route('home'))->assertSee('Durian Pak Made');
    $this->assertDatabaseHas('activity_logs', ['action' => ActivityAction::Updated->value, 'description' => 'Mengubah pengaturan website']);
});

it('rejects an invalid WhatsApp number', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.settings.update'), validSettingsPayload(['whatsapp_number' => 'bukan nomor']))
        ->assertSessionHasErrors('whatsapp_number');
});

it('shows English settings on English pages and falls back to Indonesian when empty', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.settings.update'), validSettingsPayload(['tagline' => 'Buah Kebun', 'tagline_en' => 'Farm Fruit', 'opening_hours' => 'Setiap hari', 'opening_hours_en' => '']))
        ->assertSessionHasNoErrors();

    $this->get('/en')->assertSee('Farm Fruit')->assertDontSee('Buah Kebun')->assertSee('Setiap hari');
    $this->get('/id')->assertSee('Buah Kebun')->assertDontSee('Farm Fruit');
});
