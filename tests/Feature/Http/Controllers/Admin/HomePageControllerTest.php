<?php

use App\Enums\ActivityAction;
use App\Models\User;
use App\Support\SiteSettings;
use Illuminate\Support\Str;

/**
 * @return array<string, string>
 */
function validHomePagePayload(array $overrides = []): array
{
    return ['home_marquee_enabled' => '1', 'home_marquee' => "🍈 Durian Montong\n🛵 Antar Cepat", 'home_marquee_en' => '🛵 Fast Delivery', ...$overrides];
}

function homeMarquee(string $html): string
{
    return Str::between($html, 'id="marquee"', 'id="kategori"');
}

it('is only available to administrators', function () {
    $this->get(route('admin.home-page.edit'))->assertRedirect(route('admin.login'));

    $this->actingAs(User::factory()->create());
    $this->get(route('admin.home-page.edit'))->assertForbidden();
    $this->put(route('admin.home-page.update'), validHomePagePayload())->assertForbidden();
    $this->get(route('admin.dashboard'))->assertDontSee('Custom Page');
});

it('shows the current moving text in language tabs', function () {
    $html = $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.home-page.edit'))
        ->assertOk()
        ->assertSee('Custom Page')
        ->getContent();

    expect(Str::between($html, 'id="language-panel-id"', 'id="language-panel-en"'))->toContain('name="home_marquee"', 'Durian Matang Pohon');
    expect(Str::after($html, 'id="language-panel-en"'))->toContain('name="home_marquee_en"');
});

it('shows the default moving text until it is changed', function () {
    expect(homeMarquee($this->get('/id')->getContent()))->toContain('Durian Matang Pohon');
    expect(homeMarquee($this->get('/en')->getContent()))->not->toContain('Durian Matang Pohon');
});

it('changes the moving text in both languages and logs it', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.home-page.update'), validHomePagePayload(['home_marquee' => "  🍈 Durian Montong \r\n\r\n🛵 Antar Cepat\r\n"]))
        ->assertRedirect(route('admin.home-page.edit'))
        ->assertSessionHasNoErrors();

    expect(app(SiteSettings::class)->lines('home_marquee'))->toBe(['🍈 Durian Montong', '🛵 Antar Cepat']);
    expect(homeMarquee($this->get('/id')->getContent()))->toContain('🍈 Durian Montong', '🛵 Antar Cepat')->not->toContain('Durian Matang Pohon');
    expect(homeMarquee($this->get('/en')->getContent()))->toContain('🛵 Fast Delivery')->not->toContain('Antar Cepat');
    $this->assertDatabaseHas('activity_logs', ['action' => ActivityAction::Updated->value, 'description' => 'Mengubah halaman Home Page']);
});

it('uses the Indonesian text on the English page when the English text is empty', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.home-page.update'), validHomePagePayload(['home_marquee_en' => '']))
        ->assertSessionHasNoErrors();

    expect(homeMarquee($this->get('/en')->getContent()))->toContain('🛵 Antar Cepat');
});

it('hides the moving text', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.home-page.update'), validHomePagePayload(['home_marquee_enabled' => null]))
        ->assertSessionHasNoErrors();

    $this->get('/id')->assertOk()->assertDontSee('id="marquee"', false)->assertDontSee('#marquee', false);
    $this->get(route('admin.home-page.edit'))->assertSee('Durian Montong');
});

it('repeats a short text so it fills the screen', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.home-page.update'), validHomePagePayload(['home_marquee' => 'Promo']));

    expect(substr_count(homeMarquee($this->get('/id')->getContent()), 'Promo'))->toBeGreaterThan(10);
});

it('escapes HTML in the moving text', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.home-page.update'), validHomePagePayload(['home_marquee' => '<b>Promo</b>']));

    $this->get('/id')->assertDontSee('<b>Promo</b>', false)->assertSee('<b>Promo</b>');
});

it('does not change other settings', function () {
    app(SiteSettings::class)->update(['site_name' => 'Kebun Saya']);

    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.home-page.update'), validHomePagePayload(['site_name' => 'Diretas']));

    expect(app(SiteSettings::class)->get('site_name'))->toBe('Kebun Saya');
});

it('rejects invalid moving text', function (string $field, mixed $value) {
    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.home-page.update'), validHomePagePayload([$field => $value]))
        ->assertSessionHasErrors($field);
})->with([
    'empty' => ['home_marquee', " \n "],
    'array' => ['home_marquee', ['a']],
    'long line' => ['home_marquee', str_repeat('a', 61)],
    'too many lines' => ['home_marquee', implode("\n", array_fill(0, 21, 'a'))],
    'long English line' => ['home_marquee_en', str_repeat('a', 61)],
]);
