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
