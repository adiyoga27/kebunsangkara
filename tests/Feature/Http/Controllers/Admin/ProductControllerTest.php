<?php

use App\Enums\ActivityAction;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
        ->assertRedirect(route('admin.products.index'));

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
        ->assertSessionHasErrors(['name', 'short_description', 'description', 'price', 'price_unit']);

    expect(Product::count())->toBe(0);
});

it('rejects a duplicate slug', function () {
    Product::factory()->create(['slug' => 'durian-montong-bali']);

    $this->actingAs($this->editor)
        ->post(route('admin.products.store'), validProductPayload())
        ->assertSessionHasErrors('slug');
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
        ->assertRedirect(route('admin.products.index'));

    expect($product->fresh()->price)->toBe(65000);

    $log = ActivityLog::query()->where('action', ActivityAction::Updated)->sole();
    expect($log->properties['old']['price'])->toBe(50000)
        ->and($log->properties['new']['price'])->toBe(65000);
});

it('deletes a product with its image and logs the deletion', function () {
    Storage::disk('public')->put('products/durian.jpg', 'x');
    $product = Product::factory()->create(['image_path' => 'products/durian.jpg']);

    $this->actingAs($this->editor)
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'));

    $this->assertModelMissing($product);
    Storage::disk('public')->assertMissing('products/durian.jpg');
    $this->assertDatabaseHas('activity_logs', ['action' => ActivityAction::Deleted->value, 'subject_id' => $product->id]);
});
