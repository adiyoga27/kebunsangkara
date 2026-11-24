<?php

use App\Enums\ActivityAction;
use App\Enums\UserRole;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\WhatsappClick;
use Database\Seeders\PupukSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->editor = User::factory()->create(['role' => UserRole::Editor]);
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

it('redirects guests to the login page', function () {
    $this->get(route('admin.trashed-products.index'))->assertRedirect(route('admin.login'));
});

it('lists only products in the trash, for every product type', function () {
    Product::factory()->create(['name' => 'Durian Aktif']);
    Product::factory()->trashed()->create(['name' => 'Durian Terhapus']);
    Product::factory()->seedling()->trashed()->create(['name' => 'Bibit Terhapus']);
    Product::factory()->fertilizer()->trashed()->create(['name' => 'Pupuk Terhapus']);
    Product::factory()->healthProduct()->trashed()->create(['name' => 'Herbal Terhapus']);

    $this->actingAs($this->editor)
        ->get(route('admin.trashed-products.index'))
        ->assertOk()
        ->assertSee(['Durian Terhapus', 'Bibit Terhapus', 'Pupuk Terhapus', 'Herbal Terhapus'])
        ->assertDontSee('Durian Aktif');
});

it('filters the trash by name and type', function () {
    Product::factory()->trashed()->create(['name' => 'Durian Terhapus']);
    Product::factory()->fertilizer()->trashed()->create(['name' => 'Pupuk Terhapus']);

    $this->actingAs($this->editor)
        ->get(route('admin.trashed-products.index', ['type' => 'pupuk']))
        ->assertOk()
        ->assertSee('Pupuk Terhapus')
        ->assertDontSee('Durian Terhapus');

    $this->actingAs($this->editor)
        ->get(route('admin.trashed-products.index', ['q' => 'durian']))
        ->assertOk()
        ->assertSee('Durian Terhapus')
        ->assertDontSee('Pupuk Terhapus');
});

it('hides products in the trash from the website', function () {
    $product = Product::factory()->trashed()->create(['name' => 'Durian Terhapus']);

    $this->get(route('products.show', $product))->assertNotFound();
    $this->get(route('products.index'))->assertOk()->assertDontSee('Durian Terhapus');
    $this->get(route('sitemap'))->assertOk()->assertDontSee($product->slug);
});

it('restores a product for editors and logs it once', function () {
    $product = Product::factory()->trashed()->create(['name' => 'Durian Pulih']);

    $this->actingAs($this->editor)
        ->patch(route('admin.trashed-products.update', $product))
        ->assertRedirect(route('admin.trashed-products.index'));

    $this->assertNotSoftDeleted($product);
    $this->get(route('products.show', $product))->assertOk();
    $log = ActivityLog::query()->whereMorphedTo('subject', $product)->whereNot('action', ActivityAction::Created)->sole();
    expect($log->action)->toBe(ActivityAction::Restored)
        ->and($log->description)->toBe('Memulihkan produk "Durian Pulih"');
});

it('deletes a product and its photos permanently for administrators and logs it', function () {
    $product = Product::factory()->trashed()->create(['name' => 'Durian Hilang', 'image_path' => 'products/utama.jpg']);
    $image = ProductImage::factory()->for($product)->create(['path' => 'products/galeri.jpg']);
    Storage::disk('public')->put('products/utama.jpg', 'x');
    Storage::disk('public')->put('products/galeri.jpg', 'x');

    $this->actingAs($this->admin)
        ->delete(route('admin.trashed-products.destroy', $product))
        ->assertRedirect(route('admin.trashed-products.index'));

    $this->assertModelMissing($product);
    $this->assertModelMissing($image);
    Storage::disk('public')->assertMissing(['products/utama.jpg', 'products/galeri.jpg']);
    $log = ActivityLog::query()->where('action', ActivityAction::ForceDeleted)->sole();
    expect($log->description)->toBe('Menghapus permanen produk "Durian Hilang"');
});

it('forbids editors from deleting a product permanently', function () {
    $product = Product::factory()->trashed()->create();

    $this->actingAs($this->editor)->get(route('admin.trashed-products.index'))->assertOk()->assertDontSee('Hapus Permanen');
    $this->actingAs($this->editor)->delete(route('admin.trashed-products.destroy', $product))->assertForbidden();

    $this->assertSoftDeleted($product);
});

it('returns 404 for products that are not in the trash', function () {
    $product = Product::factory()->create();

    $this->actingAs($this->admin)->patch(route('admin.trashed-products.update', $product))->assertNotFound();
    $this->actingAs($this->admin)->delete(route('admin.trashed-products.destroy', $product))->assertNotFound();

    $this->assertNotSoftDeleted($product);
});

it('keeps showing the product name of a WhatsApp click after the product is moved to the trash', function () {
    $product = Product::factory()->trashed()->create(['name' => 'Durian Terhapus']);
    WhatsappClick::create(['product_id' => $product->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.whatsapp-clicks.index'))
        ->assertOk()
        ->assertSee('Durian Terhapus');
});

it('does not restore products in the trash when seeding again', function () {
    $this->seed(PupukSeeder::class);
    $product = Product::query()->firstOrFail();
    $product->delete();

    $this->seed(PupukSeeder::class);

    $this->assertSoftDeleted($product);
});
