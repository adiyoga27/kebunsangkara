<?php

use App\Models\Product;
use App\Models\WhatsappClick;
use App\Support\SiteSettings;

beforeEach(function () {
    app(SiteSettings::class)->update(['whatsapp_number' => '0812-1111-2222']);
});

it('records the click and redirects to WhatsApp with a product message', function () {
    $product = Product::factory()->create(['name' => 'Durian Montong Bali', 'price' => 70000, 'price_unit' => 'per kg']);

    $response = $this->get(route('whatsapp.order', $product), ['referer' => route('products.show', $product)]);

    $response->assertRedirect();
    expect($response->headers->get('Location'))
        ->toStartWith('https://wa.me/6281211112222?text=')
        ->and(rawurldecode($response->headers->get('Location')))->toContain('*Durian Montong Bali* (Rp 70.000 per kg)');

    $click = WhatsappClick::sole();
    expect($click->product_id)->toBe($product->id)
        ->and($click->source_url)->toBe(route('products.show', $product));
});

it('redirects to WhatsApp with the default message when no product is given', function () {
    app(SiteSettings::class)->update(['whatsapp_message' => 'Halo, mau pesan durian']);

    $response = $this->get(route('whatsapp.order'));

    expect($response->headers->get('Location'))->toBe('https://wa.me/6281211112222?text='.rawurlencode('Halo, mau pesan durian'));
    expect(WhatsappClick::sole()->product_id)->toBeNull();
});

it('redirects to WhatsApp with a message about the requested offering', function () {
    $response = $this->get(route('whatsapp.order', ['layanan' => 'produk-kesehatan']));

    expect(rawurldecode($response->headers->get('Location')))->toContain('*Produk Kesehatan*');
    expect(WhatsappClick::sole()->product_id)->toBeNull();
});

it('falls back to the default message for an invalid offering', function (array|string $offering) {
    app(SiteSettings::class)->update(['whatsapp_message' => 'Halo, mau pesan durian']);

    $response = $this->get(route('whatsapp.order', ['layanan' => $offering]));

    expect($response->headers->get('Location'))->toBe('https://wa.me/6281211112222?text='.rawurlencode('Halo, mau pesan durian'));
})->with([
    'unknown value' => ['tidak-ada'],
    'array value' => [['pupuk']],
]);

it('stores the click time in the application timezone', function () {
    $this->travelTo(now()->setTime(9, 30));

    $this->get(route('whatsapp.order'));

    expect(WhatsappClick::sole()->created_at->format('H:i'))->toBe('09:30');
});
