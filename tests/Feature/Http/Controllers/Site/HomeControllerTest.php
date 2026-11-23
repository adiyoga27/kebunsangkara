<?php

use App\Models\Faq;
use App\Models\Product;
use App\Models\Testimonial;

it('renders the home page with products, published testimonials and SEO metadata', function () {
    $product = Product::factory()->create(['name' => 'Durian Montong Bali']);
    Testimonial::factory()->create(['content' => 'Durian paling enak se-Bali']);
    Testimonial::factory()->create(['content' => 'Ulasan tersembunyi', 'is_published' => false]);
    Faq::factory()->create(['question' => 'Kapan musim durian di Bali?']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Durian Montong Bali')
        ->assertSee('Durian paling enak se-Bali')
        ->assertDontSee('Ulasan tersembunyi')
        ->assertSee('<link rel="canonical" href="'.route('home').'">', false)
        ->assertSee('"@type":"FAQPage"', false)
        ->assertSee('"@type":"Store"', false)
        ->assertSee(route('whatsapp.order', $product), false);
});

it('escapes closing script tags inside JSON-LD data', function () {
    Faq::factory()->create(['question' => '</script><script>alert(1)</script>']);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('</script><script>alert(1)', false);
});
