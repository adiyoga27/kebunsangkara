<?php

use App\Enums\Locale;
use App\Models\Testimonial;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\TestimoniSeeder;

it('seeds labelled testimonials with the product bought on the home page in both languages', function () {
    $this->seed(DatabaseSeeder::class);

    expect(Testimonial::count())->toBe(6);

    foreach (Testimonial::all() as $testimonial) {
        expect($testimonial->product)->not->toBeNull()
            ->and($testimonial->name)->toStartWith('Contoh: ')
            ->and($testimonial->isAvailableIn(Locale::English))->toBeTrue();
    }

    $this->get('/id')->assertSee('Contoh: Komang')->assertSee('Bibitnya sehat dan dikemas rapi.');
    $this->get('/en')->assertSee('Contoh: Komang')->assertSee('The seedlings were healthy and neatly packed.');
});

it('keeps the example testimonials unpublished in production', function () {
    app()->detectEnvironment(fn (): string => 'production');

    (new TestimoniSeeder)->run();

    expect(Testimonial::count())->toBe(6)
        ->and(Testimonial::query()->where('is_published', true)->count())->toBe(0);
});

it('does not duplicate or overwrite testimonials when run again', function () {
    $this->seed(TestimoniSeeder::class);
    Testimonial::query()->firstWhere('name', 'Contoh: Putu')->update(['content' => 'Ulasan asli', 'is_published' => false]);

    $this->seed(TestimoniSeeder::class);

    $testimonial = Testimonial::query()->firstWhere('name', 'Contoh: Putu');
    expect(Testimonial::count())->toBe(6)
        ->and($testimonial->content)->toBe('Ulasan asli')
        ->and($testimonial->is_published)->toBeFalse();
});
