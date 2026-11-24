<?php

use App\Models\Service;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

/**
 * @return array<string, mixed>
 */
function validServicePayload(array $overrides = []): array
{
    return ['name' => 'Edukasi Pertanian', 'summary' => 'Belajar di kebun.', 'description' => 'Materi **lengkap**.', 'sort_order' => 1, 'is_published' => '1', ...$overrides];
}

it('requires a login', function () {
    auth()->logout();

    $this->get(route('admin.services.index'))->assertRedirect(route('admin.login'));
    $this->post(route('admin.services.store'), validServicePayload())->assertRedirect(route('admin.login'));

    expect(Service::count())->toBe(0);
});

it('adds a service in both languages and shows it on the website', function () {
    $this->post(route('admin.services.store'), validServicePayload([
        'translations' => ['en' => ['name' => 'Agricultural Education', 'summary' => 'Learn on the farm.', 'description' => 'All **topics**.']],
    ]))->assertRedirect(route('admin.services.index'));

    $this->get(route('admin.services.index'))->assertOk()->assertSee('Edukasi Pertanian');
    $this->get('/id/service')->assertSee('Edukasi Pertanian')->assertSee('<strong>lengkap</strong>', false);
    $this->get('/en/service')->assertSee('Agricultural Education')->assertSee('<strong>topics</strong>', false);
});

it('separates the languages into tabs with the visual editor', function () {
    $html = $this->get(route('admin.services.create'))->assertOk()->getContent();
    $indonesianPanel = Str::between($html, 'id="language-panel-id"', 'id="language-panel-en"');
    $englishPanel = Str::after($html, 'id="language-panel-en"');

    expect($indonesianPanel)->toContain('name="name"', 'name="description"', 'data-rich-text')->not->toContain('translations[en]');
    expect($englishPanel)->toContain('name="translations[en][description]"', 'data-rich-text');
});

it('changes and unpublishes a service', function () {
    $service = Service::factory()->create(['name' => 'Acara']);

    $this->get(route('admin.services.edit', $service))->assertOk()->assertSee('Acara');
    $this->put(route('admin.services.update', $service), validServicePayload(['name' => 'Acara Kebun', 'is_published' => null]))
        ->assertRedirect(route('admin.services.index'));

    expect($service->fresh())->name->toBe('Acara Kebun')->is_published->toBeFalse();
    $this->get('/id/service')->assertDontSee('Acara Kebun');
});

it('rejects invalid input', function (string $field, mixed $value) {
    $this->post(route('admin.services.store'), validServicePayload([$field => $value]))->assertSessionHasErrors($field);

    expect(Service::count())->toBe(0);
})->with([
    'missing name' => ['name', ''],
    'long name' => ['name', str_repeat('a', 121)],
    'missing summary' => ['summary', ''],
    'long summary' => ['summary', str_repeat('a', 301)],
    'missing description' => ['description', ''],
    'negative order' => ['sort_order', -1],
]);

it('stores the photo, keeps it without a new upload and removes it on request', function () {
    Storage::fake('public');
    $this->post(route('admin.services.store'), validServicePayload(['image' => UploadedFile::fake()->image('kebun.jpg')]));
    $service = Service::sole();
    $path = $service->image_path;

    Storage::disk('public')->assertExists($path);
    $this->get('/id/service')->assertSee('/storage/'.$path);

    $this->put(route('admin.services.update', $service), validServicePayload());
    expect($service->fresh()->image_path)->toBe($path);

    $this->put(route('admin.services.update', $service), validServicePayload(['remove_image' => '1']));
    expect($service->fresh()->image_path)->toBeNull();
    Storage::disk('public')->assertMissing($path);
});

it('moves a service to the trash with its photo and restores it', function () {
    Storage::fake('public');
    $service = Service::factory()->create(['name' => 'Acara', 'image_path' => UploadedFile::fake()->image('a.jpg')->store('services', 'public')]);

    $this->delete(route('admin.services.destroy', $service))->assertRedirect(route('admin.services.index'));
    $this->assertSoftDeleted($service);
    Storage::disk('public')->assertExists($service->image_path);
    $this->get(route('admin.services.index'))->assertSee('Sampah Layanan');

    $this->patch(route('admin.trashed-services.update', $service))->assertRedirect(route('admin.services.index'));
    $this->assertNotSoftDeleted($service);
    $this->get('/id/service')->assertSee('Acara');
});
