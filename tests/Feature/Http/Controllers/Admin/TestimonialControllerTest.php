<?php

use App\Models\Product;
use App\Models\Testimonial;
use App\Models\User;

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

it('keeps the testimonial when its product is deleted', function () {
    $testimonial = Testimonial::factory()->for(Product::factory())->create();

    $testimonial->product->delete();

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
