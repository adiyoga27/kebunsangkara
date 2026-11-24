@props(['active' => null])

<nav aria-label="{{ __('Kategori buah') }}" class="flex flex-wrap gap-2" data-reveal>
    <a href="{{ route('products.index') }}" @class([
        'rounded-full px-5 py-2.5 text-sm font-semibold transition',
        'bg-husk-950 text-white' => $active === null,
        'bg-white text-stone-700 ring-1 ring-stone-200 hover:-translate-y-0.5 hover:ring-husk-300' => $active !== null,
    ]) @if ($active === null) aria-current="page" @endif>{{ __('Semua') }}</a>
    @foreach (App\Enums\FruitCategory::cases() as $fruit)
        <a href="{{ route('products.category', $fruit) }}" @class([
            'rounded-full px-5 py-2.5 text-sm font-semibold transition',
            'bg-husk-950 text-white' => $active === $fruit,
            'bg-white text-stone-700 ring-1 ring-stone-200 hover:-translate-y-0.5 hover:ring-husk-300' => $active !== $fruit,
        ]) @if ($active === $fruit) aria-current="page" @endif>{{ $fruit->emoji() }} {{ $fruit->label() }}</a>
    @endforeach
    <a href="{{ route('seedlings.index') }}" class="rounded-full bg-husk-100 px-5 py-2.5 text-sm font-semibold text-husk-800 ring-1 ring-husk-200 transition hover:-translate-y-0.5 hover:bg-husk-200">🌱 {{ __('Bibit') }}</a>
</nav>
