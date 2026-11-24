<?php

use App\Models\Product;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

/**
 * @return array<string, mixed>
 */
function validTestimonialPayload(array $overrides = []): array
{
    return ['name' => 'Made', 'content' => 'Buahnya segar.', 'rating' => 5, 'is_published' => '1', ...$overrides];
}

it('saves the product the customer bought', function () {
    $product = Product::factory()->create(['name' => 'Durian Lokal']);

    $this->post(route('admin.testimonials.store'), validTestimonialPayload(['product_id' => $product->id]))
        ->assertRedirect(route('admin.testimonials.index'));

    $testimonial = Testimonial::sole();
    expect($testimonial->product->is($product))->toBeTrue();

    $this->get(route('admin.testimonials.index'))->assertSee('Durian Lokal');
    $this->get(route('admin.testimonials.edit', $testimonial))->assertSee('<option value="'.$product->id.'" selected', false);
});

it('accepts a testimonial without a product', function () {
    $this->post(route('admin.testimonials.store'), validTestimonialPayload(['product_id' => '']))->assertSessionHasNoErrors();

    expect(Testimonial::sole()->product_id)->toBeNull();
});

it('rejects an unknown product', function () {
    $this->post(route('admin.testimonials.store'), validTestimonialPayload(['product_id' => 999]))->assertSessionHasErrors('product_id');

    expect(Testimonial::count())->toBe(0);
});

it('keeps a testimonial linked to a product in the trash, also when it is edited', function () {
    $testimonial = Testimonial::factory()->for(Product::factory()->state(['name' => 'Durian Terhapus']))->create();
    $testimonial->product->delete();

    $this->get(route('admin.testimonials.edit', $testimonial))
        ->assertOk()
        ->assertSee('Durian Terhapus');

    expect($testimonial->fresh()->product_id)->not->toBeNull();
});

it('does not offer products in the trash to other testimonials', function () {
    Product::factory()->trashed()->create(['name' => 'Durian Terhapus']);

    $this->get(route('admin.testimonials.create'))
        ->assertOk()
        ->assertDontSee('Durian Terhapus');
});

it('keeps the testimonial when its product is permanently deleted', function () {
    $testimonial = Testimonial::factory()->for(Product::factory())->create();

    $testimonial->product->forceDelete();

    expect($testimonial->fresh()->product_id)->toBeNull();
});

it('publishes and hides a testimonial from the list', function () {
    $testimonial = Testimonial::factory()->create(['content' => 'Manggisnya segar', 'is_published' => false]);

    $this->patch(route('admin.testimonials.toggle-publication', $testimonial))->assertSessionHas('status', 'Testimoni sekarang tampil di website.');
    $this->get(route('home'))->assertSee('Manggisnya segar');

    $this->patch(route('admin.testimonials.toggle-publication', $testimonial))->assertSessionHas('status', 'Testimoni disembunyikan dari website.');
    $this->get(route('home'))->assertDontSee('Manggisnya segar');
});

it('requires a login to change the publication of a testimonial', function () {
    $testimonial = Testimonial::factory()->create(['is_published' => false]);
    auth()->logout();

    $this->patch(route('admin.testimonials.toggle-publication', $testimonial))->assertRedirect(route('admin.login'));

    expect($testimonial->fresh()->is_published)->toBeFalse();
});

it('filters the list by status and search term', function (array $query, array $visible, array $hidden) {
    Testimonial::factory()->create(['name' => 'Putu', 'content' => 'Duriannya manis', 'is_published' => true]);
    Testimonial::factory()->create(['name' => 'Komang', 'content' => 'Bibitnya sehat', 'is_published' => false]);

    $response = $this->get(route('admin.testimonials.index', $query))->assertOk();

    foreach ($visible as $text) {
        $response->assertSee($text);
    }

    foreach ($hidden as $text) {
        $response->assertDontSee($text);
    }
})->with([
    'all' => [[], ['Duriannya manis', 'Bibitnya sehat'], []],
    'published' => [['status' => 'tampil'], ['Duriannya manis'], ['Bibitnya sehat']],
    'drafts' => [['status' => 'draf'], ['Bibitnya sehat'], ['Duriannya manis']],
    'search by name' => [['q' => 'Komang'], ['Bibitnya sehat'], ['Duriannya manis']],
    'search within status' => [['q' => 'sehat', 'status' => 'tampil'], ['Tidak ada testimoni yang cocok.'], ['Bibitnya sehat', 'Duriannya manis']],
]);

it('stores the photo of the customer and shows it on the website', function () {
    Storage::fake('public');

    $this->post(route('admin.testimonials.store'), validTestimonialPayload(['image' => UploadedFile::fake()->image('made.jpg')]))
        ->assertSessionHasNoErrors();

    $testimonial = Testimonial::sole();
    expect($testimonial->image_path)->toStartWith('testimonials/');
    Storage::disk('public')->assertExists($testimonial->image_path);

    foreach ([route('admin.testimonials.index'), route('admin.testimonials.edit', $testimonial), route('home'), route('about')] as $url) {
        $this->get($url)->assertOk()->assertSee('/storage/'.$testimonial->image_path);
    }
});

it('replaces and removes the photo and deletes the old file', function () {
    Storage::fake('public');
    $testimonial = Testimonial::factory()->create(['image_path' => UploadedFile::fake()->image('lama.jpg')->store('testimonials', 'public')]);
    $oldPath = $testimonial->image_path;

    $this->put(route('admin.testimonials.update', $testimonial), validTestimonialPayload(['image' => UploadedFile::fake()->image('baru.jpg')]));
    $newPath = $testimonial->fresh()->image_path;
    Storage::disk('public')->assertMissing($oldPath)->assertExists($newPath);

    $this->put(route('admin.testimonials.update', $testimonial), validTestimonialPayload());
    expect($testimonial->fresh()->image_path)->toBe($newPath);

    $this->put(route('admin.testimonials.update', $testimonial), validTestimonialPayload(['remove_image' => '1']));
    expect($testimonial->fresh()->image_path)->toBeNull();
    Storage::disk('public')->assertMissing($newPath);
});

it('deletes the photo together with the testimonial', function () {
    Storage::fake('public');
    $testimonial = Testimonial::factory()->create(['image_path' => UploadedFile::fake()->image('made.jpg')->store('testimonials', 'public')]);

    $this->delete(route('admin.testimonials.destroy', $testimonial));

    Storage::disk('public')->assertMissing($testimonial->image_path);
});

it('rejects files that are not a photo or are too large', function (Closure $file) {
    Storage::fake('public');

    $this->post(route('admin.testimonials.store'), validTestimonialPayload(['image' => $file()]))->assertSessionHasErrors('image');

    expect(Testimonial::count())->toBe(0);
    expect(Storage::disk('public')->allFiles())->toBe([]);
})->with([
    'script' => [fn () => UploadedFile::fake()->createWithContent('foto.php', '<?php echo 1;')],
    'svg' => [fn () => UploadedFile::fake()->createWithContent('foto.svg', '<svg xmlns="http://www.w3.org/2000/svg"></svg>')],
    'too large' => [fn () => UploadedFile::fake()->image('besar.jpg')->size(4097)],
]);

it('shows the initial of the customer when there is no photo', function () {
    Testimonial::factory()->create(['name' => 'Komang']);

    $this->get(route('home'))->assertSee('>K</span>', false)->assertDontSee('/storage/testimonials');
});
