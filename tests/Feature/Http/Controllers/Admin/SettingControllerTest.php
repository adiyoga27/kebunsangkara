<?php

use App\Enums\ActivityAction;
use App\Models\User;
use App\Support\SiteSettings;
use Illuminate\Support\Str;

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

it('shows every setting once, with the texts separated into language tabs', function () {
    $html = $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.settings.edit'))
        ->assertOk()
        ->getContent();
    $indonesianPanel = Str::between($html, 'id="language-panel-id"', 'id="language-panel-en"');
    $englishPanel = Str::between($html, 'id="language-panel-en"', 'Identitas &amp; Kontak');

    foreach (array_keys(SiteSettings::defaults()) as $key) {
        if (Str::startsWith($key, 'home_marquee')) {
            continue;
        }

        expect(substr_count($html, 'name="'.$key.'"'))->toBe(1, "Setting {$key} must appear exactly once.");
    }
    foreach (SiteSettings::TRANSLATABLE as $key) {
        expect($indonesianPanel)->toContain('name="'.$key.'"')->not->toContain('name="'.$key.'_en"');
        expect($englishPanel)->toContain('name="'.$key.'_en"')->not->toContain('name="'.$key.'"');
    }
});

it('requires the Indonesian texts but not the English ones', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.settings.update'), validSettingsPayload(['tagline' => '', 'tagline_en' => '']))
        ->assertSessionHasErrors('tagline')
        ->assertSessionDoesntHaveErrors('tagline_en');
});

it('opens the tab that has the validation error', function (string $field, string $openPanel, string $hiddenPanel) {
    $html = $this->actingAs(User::factory()->admin()->create())
        ->from(route('admin.settings.edit'))
        ->followingRedirects()
        ->put(route('admin.settings.update'), validSettingsPayload([$field => str_repeat('a', 71)]))
        ->assertOk()
        ->getContent();

    expect(Str::before(Str::after($html, 'id="language-panel-'.$openPanel.'"'), '>'))->not->toContain('hidden');
    expect(Str::before(Str::after($html, 'id="language-panel-'.$hiddenPanel.'"'), '>'))->toContain('hidden');
})->with([
    'Indonesian' => ['home_meta_title', 'id', 'en'],
    'English' => ['home_meta_title_en', 'en', 'id'],
]);

it('shows only the social media and marketplace links that are filled in', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.settings.update'), validSettingsPayload(['youtube_url' => 'https://youtube.com/@kebun', 'shopee_url' => 'https://shopee.co.id/kebun']))
        ->assertSessionHasNoErrors();

    $this->get('/id')
        ->assertSee('href="https://youtube.com/@kebun"', false)
        ->assertSee('href="https://shopee.co.id/kebun"', false)
        ->assertSee('Belanja Juga di')
        ->assertDontSee('Tokopedia')
        ->assertDontSee('Lazada');
    $this->get('/en')->assertSee('Also Available on');
});

it('hides the marketplace section when no marketplace is filled in', function () {
    $this->get('/id')->assertOk()->assertDontSee('Belanja Juga di');
});

it('rejects marketplace links that are not a web address', function (string $key, string $value) {
    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.settings.update'), validSettingsPayload([$key => $value]))
        ->assertSessionHasErrors($key);
})->with(['youtube_url', 'tokopedia_url', 'shopee_url', 'lazada_url'])->with(['javascript:alert(1)', 'toko saya']);

it('shows an icon for every filled in link on the website and the dashboard', function () {
    $admin = User::factory()->admin()->create();
    $keys = [...SiteSettings::SOCIAL_MEDIA, ...SiteSettings::MARKETPLACES];
    $urls = array_map(fn (string $label): string => 'https://'.Str::lower($label).'.test/kebun', $keys);

    $this->actingAs($admin)
        ->put(route('admin.settings.update'), validSettingsPayload($urls))
        ->assertSessionHasNoErrors();

    foreach (['/id', '/en', '/id/produk', route('admin.dashboard')] as $page) {
        $response = $this->get($page)->assertOk();

        foreach ($urls as $key => $url) {
            $response->assertSee('href="'.$url.'"', false)->assertSee('data-brand="'.Str::lower($keys[$key]).'"', false);
        }
    }

    $this->get('/id')->assertSee('id="marketplace"', false)->assertSee('id="media-sosial"', false)->assertSee('aria-label="YouTube"', false);
});

it('shows no icons on the website and the dashboard while nothing is filled in', function () {
    $this->actingAs(User::factory()->admin()->create());

    foreach (['/id', '/id/produk', route('admin.dashboard')] as $page) {
        $this->get($page)->assertOk()->assertDontSee('data-brand', false)->assertDontSee('Ikuti Kami')->assertDontSee('Media Sosial &amp; Marketplace', false);
    }
});

it('shows only the icons of the filled in links on the dashboard', function () {
    app(SiteSettings::class)->update(['shopee_url' => 'https://shopee.co.id/kebun']);

    $this->actingAs(User::factory()->create())
        ->get(route('admin.dashboard'))
        ->assertSee('data-brand="shopee"', false)
        ->assertDontSee('data-brand="tokopedia"', false)
        ->assertDontSee('data-brand="instagram"', false);
});
