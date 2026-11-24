<?php

use App\Enums\ActivityAction;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

beforeEach(function () {
    Storage::fake('public');
    $this->editor = User::factory()->create();
});

/**
 * @return array<string, mixed>
 */
function validProductPayload(array $overrides = []): array
{
    return [
        'name' => 'Durian Montong Bali',
        'category' => 'durian',
        'type' => 'buah',
        'short_description' => 'Daging tebal, manis lembut.',
        'description' => 'Durian Montong dari kebun Tabanan.',
        'price' => 70000,
        'price_unit' => 'per kg',
        'is_available' => '1',
        ...$overrides,
    ];
}

it('creates a product with a generated slug, uploaded image and activity log', function () {
    $this->actingAs($this->editor)
        ->post(route('admin.products.store'), validProductPayload(['image' => UploadedFile::fake()->image('durian.jpg')]))
        ->assertRedirect(route('admin.products.index', ['type' => 'buah']));

    $product = Product::sole();
    expect($product->slug)->toBe('durian-montong-bali')
        ->and($product->is_available)->toBeTrue()
        ->and($product->is_featured)->toBeFalse();
    Storage::disk('public')->assertExists($product->image_path);

    $log = ActivityLog::query()->whereMorphedTo('subject', $product)->sole();
    expect($log->action)->toBe(ActivityAction::Created)
        ->and($log->user_id)->toBe($this->editor->id)
        ->and($log->subject_id)->toBe($product->id)
        ->and($log->description)->toBe('Menambahkan produk "Durian Montong Bali"');
});

it('rejects a product without required fields', function () {
    $this->actingAs($this->editor)
        ->post(route('admin.products.store'), [])
        ->assertSessionHasErrors(['name', 'category', 'short_description', 'description', 'price', 'price_unit']);

    expect(Product::count())->toBe(0);
});

it('rejects a duplicate slug', function () {
    Product::factory()->create(['slug' => 'durian-montong-bali']);

    $this->actingAs($this->editor)
        ->post(route('admin.products.store'), validProductPayload())
        ->assertSessionHasErrors('slug');
});

it('rejects the slug of a product in the trash', function () {
    Product::factory()->trashed()->create(['slug' => 'durian-montong-bali']);

    $this->actingAs($this->editor)
        ->post(route('admin.products.store'), validProductPayload())
        ->assertSessionHasErrors(['slug' => 'Slug URL ini sudah dipakai produk lain, termasuk produk di Sampah. Gunakan slug lain atau pulihkan produk tersebut.']);

    expect(Product::count())->toBe(0);
});

it('does not list products in the trash but links to them', function () {
    Product::factory()->trashed()->create(['name' => 'Durian Terhapus']);

    $this->actingAs($this->editor)
        ->get(route('admin.products.index'))
        ->assertOk()
        ->assertDontSee('Durian Terhapus')
        ->assertSee('Sampah (1)');
});

it('rejects a non-image upload', function () {
    $this->actingAs($this->editor)
        ->post(route('admin.products.store'), validProductPayload(['image' => UploadedFile::fake()->create('virus.php', 10, 'application/x-php')]))
        ->assertSessionHasErrors('image');
});

it('updates a product and logs the old and new values', function () {
    $product = Product::factory()->create(['name' => 'Durian Lama', 'price' => 50000]);

    $this->actingAs($this->editor)
        ->put(route('admin.products.update', $product), validProductPayload(['name' => 'Durian Lama', 'slug' => $product->slug, 'price' => 65000]))
        ->assertRedirect(route('admin.products.index', ['type' => 'buah']));

    expect($product->fresh()->price)->toBe(65000);

    $log = ActivityLog::query()->where('action', ActivityAction::Updated)->sole();
    expect($log->properties['old']['price'])->toBe(50000)
        ->and($log->properties['new']['price'])->toBe(65000);
});

it('moves a product to the trash, keeps its image and logs the deletion', function () {
    Storage::disk('public')->put('products/durian.jpg', 'x');
    $product = Product::factory()->create(['image_path' => 'products/durian.jpg']);

    $this->actingAs($this->editor)
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index', ['type' => 'buah']));

    $this->assertSoftDeleted($product);
    Storage::disk('public')->assertExists('products/durian.jpg');
    $this->assertDatabaseHas('activity_logs', ['action' => ActivityAction::Deleted->value, 'subject_id' => $product->id]);
});

it('rejects an unknown fruit category', function () {
    $this->actingAs($this->editor)
        ->post(route('admin.products.store'), validProductPayload(['category' => 'rambutan']))
        ->assertSessionHasErrors('category');
});

it('stores a seedling with its propagation details', function () {
    $this->actingAs($this->editor)
        ->post(route('admin.products.store'), validProductPayload([
            'type' => 'bibit',
            'propagation_method' => 'Okulasi',
            'plant_height' => '60–80 cm',
        ]))
        ->assertRedirect();

    expect(Product::sole())
        ->isSeedling()->toBeTrue()
        ->propagation_method->toBe('Okulasi')
        ->plant_height->toBe('60–80 cm');
});

it('stores a product without a fruit category for types that have none', function (string $type) {
    $this->actingAs($this->editor)
        ->post(route('admin.products.store'), validProductPayload(['type' => $type, 'category' => 'durian']))
        ->assertRedirect(route('admin.products.index', ['type' => $type]));

    $product = Product::sole();
    expect($product->type->value)->toBe($type)
        ->and($product->category)->toBeNull();

    $this->get(route('admin.products.index', ['type' => $type]))->assertOk()->assertSee($product->name);
    $this->get(route('admin.products.edit', $product))->assertOk();
})->with(['pupuk', 'kesehatan']);

it('requires a fruit category for fruit and seedlings', function (string $type) {
    $this->actingAs($this->editor)
        ->post(route('admin.products.store'), validProductPayload(['type' => $type, 'category' => '']))
        ->assertSessionHasErrors('category');
})->with(['buah', 'bibit']);

it('stores several gallery photos and uses the first one as the main photo when none is uploaded', function () {
    $this->actingAs($this->editor)
        ->post(route('admin.products.store'), validProductPayload(['images' => [
            UploadedFile::fake()->image('a.jpg'), UploadedFile::fake()->image('b.png'), UploadedFile::fake()->image('c.webp'),
        ]]))
        ->assertRedirect(route('admin.products.index', ['type' => 'buah']));

    $product = Product::sole();
    expect($product->images)->toHaveCount(2)
        ->and($product->image_path)->not->toBeNull();
    Storage::disk('public')->assertExists([$product->image_path, ...$product->images->pluck('path')]);
});

it('keeps the main photo and adds and removes gallery photos on update', function () {
    $product = Product::factory()->create(['image_path' => 'products/utama.jpg']);
    $removed = ProductImage::factory()->for($product)->create(['path' => 'products/hapus.jpg']);
    $kept = ProductImage::factory()->for($product)->create(['path' => 'products/simpan.jpg']);
    foreach (['utama', 'hapus', 'simpan'] as $name) {
        Storage::disk('public')->put("products/{$name}.jpg", 'x');
    }

    $this->actingAs($this->editor)
        ->put(route('admin.products.update', $product), validProductPayload([
            'slug' => $product->slug,
            'remove_images' => [$removed->id],
            'images' => [UploadedFile::fake()->image('baru.jpg')],
        ]))
        ->assertRedirect(route('admin.products.index', ['type' => 'buah']));

    $product->refresh();
    expect($product->image_path)->toBe('products/utama.jpg')
        ->and($product->images)->toHaveCount(2)
        ->and($product->images->first()->is($kept))->toBeTrue();
    $this->assertModelMissing($removed);
    Storage::disk('public')->assertMissing('products/hapus.jpg');
    Storage::disk('public')->assertExists(['products/utama.jpg', 'products/simpan.jpg', $product->images->last()->path]);
});

it('promotes a gallery photo when the main photo is removed', function () {
    $product = Product::factory()->create(['image_path' => 'products/utama.jpg']);
    ProductImage::factory()->for($product)->create(['path' => 'products/kedua.jpg']);
    Storage::disk('public')->put('products/utama.jpg', 'x');
    Storage::disk('public')->put('products/kedua.jpg', 'x');

    $this->actingAs($this->editor)
        ->put(route('admin.products.update', $product), validProductPayload(['slug' => $product->slug, 'remove_image' => '1']))
        ->assertRedirect(route('admin.products.index', ['type' => 'buah']));

    expect($product->fresh()->image_path)->toBe('products/kedua.jpg')
        ->and($product->images()->count())->toBe(0);
    Storage::disk('public')->assertMissing('products/utama.jpg');
    Storage::disk('public')->assertExists('products/kedua.jpg');
});

it('rejects a gallery upload that is not an image', function () {
    $this->actingAs($this->editor)
        ->post(route('admin.products.store'), validProductPayload(['images' => [UploadedFile::fake()->create('virus.php', 10, 'application/x-php')]]))
        ->assertSessionHasErrors('images.0');

    expect(Product::count())->toBe(0);
});

it('rejects more than ten photos including the existing ones', function () {
    $product = Product::factory()->create(['image_path' => 'products/utama.jpg']);
    ProductImage::factory()->for($product)->count(8)->create();

    $this->actingAs($this->editor)
        ->put(route('admin.products.update', $product), validProductPayload([
            'slug' => $product->slug,
            'images' => [UploadedFile::fake()->image('a.jpg'), UploadedFile::fake()->image('b.jpg')],
        ]))
        ->assertSessionHasErrors(['images' => 'Maksimal 10 foto per produk, termasuk foto utama.']);

    expect($product->images()->count())->toBe(8);
});

it('does not remove a gallery photo of another product', function () {
    $product = Product::factory()->create();
    $other = ProductImage::factory()->create();

    $this->actingAs($this->editor)
        ->put(route('admin.products.update', $product), validProductPayload(['slug' => $product->slug, 'remove_images' => [$other->id]]))
        ->assertSessionHasErrors('remove_images.0');

    $this->assertModelExists($other);
});

it('keeps the gallery photos of a product in the trash', function () {
    $product = Product::factory()->create();
    $image = ProductImage::factory()->for($product)->create(['path' => 'products/galeri.jpg']);
    Storage::disk('public')->put('products/galeri.jpg', 'x');

    $this->actingAs($this->editor)->delete(route('admin.products.destroy', $product))->assertRedirect();

    $this->assertSoftDeleted($product);
    $this->assertModelExists($image);
    Storage::disk('public')->assertExists('products/galeri.jpg');
});

it('shows the gallery photos on the edit form', function () {
    $image = ProductImage::factory()->create();

    $this->actingAs($this->editor)
        ->get(route('admin.products.edit', $image->product))
        ->assertOk()
        ->assertSee($image->url(), false)
        ->assertSee('name="images[]"', false);
});

it('saves the English version and drops empty translations', function () {
    $this->actingAs($this->editor)
        ->post(route('admin.products.store'), validProductPayload(['translations' => ['en' => ['name' => 'Bali Montong Durian', 'variety' => '']]]))
        ->assertRedirect(route('admin.products.index', ['type' => 'buah']));

    $product = Product::sole();
    expect($product->name)->toBe('Durian Montong Bali')
        ->and($product->translations)->toBe(['en' => ['name' => 'Bali Montong Durian']]);

    $this->actingAs($this->editor)
        ->get(route('admin.products.edit', $product))
        ->assertSee('name="translations[en][name]"', false)
        ->assertSee('value="Bali Montong Durian"', false);
});

it('rejects invalid translations', function (array $translations, string $error) {
    $this->actingAs($this->editor)
        ->post(route('admin.products.store'), validProductPayload(['translations' => $translations]))
        ->assertSessionHasErrors($error);

    expect(Product::count())->toBe(0);
})->with([
    'unknown language' => [['fr' => ['name' => 'Durian']], 'translations'],
    'unknown attribute' => [['en' => ['price' => '1']], 'translations.en'],
    'too long' => [['en' => ['name' => str_repeat('a', 151)]], 'translations.en.name'],
]);

it('lists products of the chosen type only', function (string $type, string $visible) {
    Product::factory()->create(['name' => 'Durian Segar']);
    Product::factory()->seedling()->create(['name' => 'Bibit Durian']);
    Product::factory()->fertilizer()->create(['name' => 'Kompos Organik']);
    Product::factory()->healthProduct()->create(['name' => 'Madu Murni']);
    $hidden = array_diff(['Durian Segar', 'Bibit Durian', 'Kompos Organik', 'Madu Murni'], [$visible]);

    $this->actingAs($this->editor)
        ->get(route('admin.products.index', ['type' => $type]))
        ->assertOk()
        ->assertSee($visible)
        ->assertDontSee($hidden);
})->with([
    'fruit' => ['buah', 'Durian Segar'],
    'seedlings' => ['bibit', 'Bibit Durian'],
    'fertilizer' => ['pupuk', 'Kompos Organik'],
    'health products' => ['kesehatan', 'Madu Murni'],
    'unknown type falls back to fruit' => ['lainnya', 'Durian Segar'],
]);

it('shows only the fields that fit the product type on the form', function (string $type, bool $hasCategory, bool $hasSeedlingFields, string $priceUnit) {
    $response = $this->actingAs($this->editor)
        ->get(route('admin.products.create', ['type' => $type]))
        ->assertOk()
        ->assertSee('name="type" value="'.$type.'"', false)
        ->assertSee('value="'.$priceUnit.'"', false);

    $hasCategory ? $response->assertSee('name="category"', false) : $response->assertDontSee('name="category"', false);
    $hasSeedlingFields ? $response->assertSee('name="plant_height"', false) : $response->assertDontSee('name="plant_height"', false);
})->with([
    'fruit' => ['buah', true, false, 'per kg'],
    'seedlings' => ['bibit', true, true, 'per batang'],
    'fertilizer' => ['pupuk', false, false, 'per karung'],
    'health products' => ['kesehatan', false, false, 'per kemasan'],
]);

it('keeps the product type when a product is edited', function () {
    $product = Product::factory()->fertilizer()->create();

    $this->actingAs($this->editor)
        ->get(route('admin.products.edit', $product))
        ->assertOk()
        ->assertSee('name="type" value="pupuk"', false);
});

it('separates Indonesian and English fields into tabs and opens the Indonesian one', function (string $type) {
    $html = $this->actingAs($this->editor)
        ->get(route('admin.products.create', ['type' => $type]))
        ->assertOk()
        ->getContent();
    $indonesianPanel = Str::between($html, 'id="language-panel-id"', 'id="language-panel-en"');
    $englishPanel = Str::between($html, 'id="language-panel-en"', 'Detail Produk');

    expect($indonesianPanel)->toContain('name="name"', 'name="price_unit"', 'name="meta_title"')->not->toContain('translations[en]');
    expect($englishPanel)->toContain('name="translations[en][name]"')->not->toContain('name="name"');
    expect(Str::before($indonesianPanel, '>'))->not->toContain('hidden');
    expect(Str::before($englishPanel, '>'))->toContain('hidden');
    expect($html)->toContain('name="price"', 'name="slug"');
    expect(substr_count($html, 'name="price_unit"'))->toBe(1);
})->with(['buah', 'bibit', 'pupuk', 'kesehatan']);

it('opens the English tab when only the translation has errors', function () {
    $html = $this->actingAs($this->editor)
        ->from(route('admin.products.create'))
        ->followingRedirects()
        ->post(route('admin.products.store'), validProductPayload(['translations' => ['en' => ['name' => str_repeat('a', 151)]]]))
        ->assertOk()
        ->getContent();

    expect(Str::before(Str::after($html, 'id="language-panel-id"'), '>'))->toContain('hidden');
    expect(Str::before(Str::after($html, 'id="language-panel-en"'), '>'))->not->toContain('hidden');
    expect(Product::count())->toBe(0);
});

it('saves both languages and the shared fields in one submit', function () {
    $this->actingAs($this->editor)
        ->post(route('admin.products.store'), validProductPayload([
            'type' => 'bibit',
            'name' => 'Bibit Alpukat',
            'plant_height' => '50 cm',
            'translations' => ['en' => ['name' => 'Avocado Seedling', 'propagation_method' => 'Grafting']],
        ]))
        ->assertRedirect(route('admin.products.index', ['type' => 'bibit']));

    expect(Product::sole())
        ->name->toBe('Bibit Alpukat')
        ->plant_height->toBe('50 cm')
        ->translations->toBe(['en' => ['name' => 'Avocado Seedling', 'propagation_method' => 'Grafting']]);
});

it('offers the visual editor for the full description in both languages only', function () {
    $html = $this->actingAs($this->editor)->get(route('admin.products.create'))->assertOk()->getContent();
    preg_match_all('/<textarea[^>]*>/', $html, $matches);
    $richTextFields = array_values(array_filter($matches[0], fn (string $tag): bool => str_contains($tag, 'data-rich-text')));

    expect($richTextFields)->toHaveCount(2);
    expect($richTextFields[0])->toContain('name="description"');
    expect($richTextFields[1])->toContain('name="translations[en][description]"');
});
