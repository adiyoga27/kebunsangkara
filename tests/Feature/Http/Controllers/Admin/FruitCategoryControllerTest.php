<?php

use App\Enums\UserRole;
use App\Models\FruitCategory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

/**
 * @return array<string, mixed>
 */
function validCategoryPayload(array $overrides = []): array
{
    return ['name' => 'Rambutan', 'emoji' => '🔴', 'tagline' => 'Manis dan ngelotok', 'intro' => 'Rambutan dipetik langsung dari pohon.', 'sort_order' => 4, ...$overrides];
}

it('requires a login', function () {
    auth()->logout();

    $this->get(route('admin.categories.index'))->assertRedirect(route('admin.login'));
    $this->post(route('admin.categories.store'), validCategoryPayload())->assertRedirect(route('admin.login'));

    expect(FruitCategory::query()->where('slug', 'rambutan')->exists())->toBeFalse();
});

it('starts with the categories that used to be fixed, in both languages', function () {
    expect(FruitCategory::query()->ordered()->pluck('slug')->all())->toBe(['durian', 'alpukat', 'manggis']);

    $this->get(route('admin.categories.index'))->assertOk()->assertSeeInOrder(['Durian', 'Alpukat', 'Manggis']);
    $this->get('/en/buah/manggis')->assertOk()->assertSee('Mangosteen')->assertSee('queen of fruits');
});

it('adds a category in both languages and shows it on the website', function () {
    $this->post(route('admin.categories.store'), validCategoryPayload([
        'translations' => ['en' => ['name' => 'Rambutan Fruit', 'tagline' => 'Sweet', 'intro' => 'Picked from the tree.']],
    ]))->assertRedirect(route('admin.categories.index'))->assertSessionHas('status');

    foreach (['/id', '/id/produk', '/id/buah/durian', '/id/tentang-kami'] as $path) {
        $this->get($path)->assertOk()->assertSee(url('/id/buah/rambutan'), false);
    }

    $this->get('/id/buah/rambutan')->assertOk()->assertSee('Manis dan ngelotok')->assertSee('Rambutan dipetik langsung dari pohon.');
    $this->get('/en/buah/rambutan')->assertOk()->assertSee('Rambutan Fruit')->assertSee('Picked from the tree.');
    $this->get(route('sitemap'))->assertSee('<loc>'.url('/id/buah/rambutan').'</loc>', false)->assertSee('<loc>'.url('/en/buah/rambutan').'</loc>', false);
    $this->assertDatabaseHas('activity_logs', ['description' => 'Menambahkan kategori buah "Rambutan"']);
});

it('keeps an untranslated category out of the English search results', function () {
    FruitCategory::factory()->create(['slug' => 'rambutan']);

    $this->get('/en/buah/rambutan')->assertOk()->assertSee('noindex', false);
    $this->get(route('sitemap'))->assertDontSee(url('/en/buah/rambutan'), false);
});

it('lets an editor add a category', function () {
    $this->actingAs(User::factory()->create(['role' => UserRole::Editor]))
        ->post(route('admin.categories.store'), validCategoryPayload())
        ->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseHas('fruit_categories', ['slug' => 'rambutan']);
});

it('offers a new category for fruit and seedlings and lists their products', function (string $type) {
    $category = FruitCategory::factory()->create(['name' => 'Rambutan', 'slug' => 'rambutan']);

    $this->get(route('admin.products.create', ['type' => $type]))->assertOk()->assertSee('value="rambutan"', false);
    $this->post(route('admin.products.store'), [
        'name' => 'Rambutan Binjai', 'type' => $type, 'category' => 'rambutan', 'short_description' => 'Manis.',
        'description' => 'Manis sekali.', 'price' => 20000, 'price_unit' => 'per kg', 'is_available' => '1',
    ])->assertSessionHasNoErrors();

    expect(Product::sole()->fruitCategory->is($category))->toBeTrue();

    $this->get(route('admin.products.index', ['type' => $type, 'category' => 'rambutan']))->assertSee('Rambutan Binjai');
    $this->get(route('admin.products.index', ['type' => $type, 'category' => 'durian']))->assertDontSee('Rambutan Binjai');
    foreach (['/id/buah/rambutan', '/id/produk', '/id/produk/rambutan-binjai'] as $path) {
        $this->get($path)->assertOk()->assertSee('Rambutan Binjai');
    }
})->with(['buah', 'bibit']);

it('shortens the automatic slug to fit', function () {
    $this->post(route('admin.categories.store'), validCategoryPayload(['name' => 'Jambu Kristal Merah Muda Bali']))->assertSessionHasNoErrors();

    expect(FruitCategory::query()->where('slug', 'jambu-kristal-merah')->exists())->toBeTrue();
});

it('changes a category but keeps its slug', function () {
    $product = Product::factory()->create();
    $category = FruitCategory::query()->where('slug', 'durian')->sole();

    $this->get(route('admin.categories.edit', $category))->assertOk()->assertSee('/buah/durian')->assertDontSee('name="slug"', false);
    $this->put(route('admin.categories.update', $category), validCategoryPayload(['name' => 'Durian Bali', 'slug' => 'lain']))
        ->assertRedirect(route('admin.categories.index'));

    expect($category->fresh())->name->toBe('Durian Bali')->slug->toBe('durian');
    $this->get(route('products.show', $product))->assertOk()->assertSee('Durian Bali');
});

it('rejects invalid input', function (string $field, mixed $value) {
    $this->post(route('admin.categories.store'), validCategoryPayload([$field => $value]))->assertSessionHasErrors($field);

    expect(FruitCategory::count())->toBe(3);
})->with([
    'missing name' => ['name', ''],
    'long name' => ['name', str_repeat('a', 51)],
    'existing name' => ['name', 'Durian'],
    'existing slug' => ['slug', 'durian'],
    'long slug' => ['slug', str_repeat('a', 21)],
    'missing emoji' => ['emoji', ''],
    'missing tagline' => ['tagline', ''],
    'missing intro' => ['intro', ''],
    'long intro' => ['intro', str_repeat('a', 501)],
    'negative order' => ['sort_order', -1],
]);

it('stores the photo, uses it on the website and removes it on request', function () {
    Storage::fake('public');
    $this->post(route('admin.categories.store'), validCategoryPayload(['image' => UploadedFile::fake()->image('rambutan.jpg')]));
    $category = FruitCategory::query()->where('slug', 'rambutan')->sole();
    $path = $category->image_path;

    Storage::disk('public')->assertExists($path);
    $this->get('/id/buah/rambutan')->assertSee(asset('storage/'.$path), false);
    $this->get('/id')->assertSee(asset('storage/'.$path), false);

    $this->put(route('admin.categories.update', $category), validCategoryPayload());
    expect($category->fresh()->image_path)->toBe($path);

    $this->put(route('admin.categories.update', $category), validCategoryPayload(['remove_image' => '1']));
    expect($category->fresh()->image_path)->toBeNull();
    Storage::disk('public')->assertMissing($path);
});

it('deletes an unused category with its photo', function () {
    Storage::fake('public');
    $category = FruitCategory::factory()->create(['image_path' => UploadedFile::fake()->image('a.jpg')->store('categories', 'public')]);

    $this->delete(route('admin.categories.destroy', $category))->assertRedirect(route('admin.categories.index'));

    $this->assertModelMissing($category);
    Storage::disk('public')->assertMissing($category->image_path);
});

it('does not delete a category that still has products', function (string $state) {
    $category = FruitCategory::factory()->create(['slug' => 'rambutan']);
    Product::factory()->{$state}()->create(['category' => 'rambutan']);

    $this->from(route('admin.categories.index'))->followingRedirects()
        ->delete(route('admin.categories.destroy', $category))
        ->assertSee('masih dipakai produk');

    $this->assertModelExists($category);
})->with(['seedling', 'trashed']);

it('renders the website without any category', function () {
    FruitCategory::query()->delete();

    foreach (['/id', '/id/produk', '/id/bibit', '/sitemap.xml'] as $path) {
        $this->get($path)->assertOk();
    }

    $this->get(route('admin.products.create'))->assertOk();
});
