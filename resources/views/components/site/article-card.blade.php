@props(['article'])

<article class="group relative flex flex-col overflow-hidden rounded-[2rem] bg-white ring-1 ring-stone-200/70 transition duration-500 hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-husk-900/10">
    <div class="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-husk-100 to-flesh-100">
        <img src="{{ $article->imageUrl() ?? asset('images/durian.svg') }}" alt="{{ $article->title }}" width="480" height="300" loading="lazy" decoding="async"
             @class(['size-full transition duration-700 ease-out group-hover:scale-110', 'object-cover' => $article->imageUrl(), 'object-contain p-8 group-hover:rotate-6' => ! $article->imageUrl()])>
        <span class="absolute left-4 top-4 rounded-full bg-white/85 px-3 py-1 text-xs font-semibold text-husk-800 backdrop-blur">{{ $article->readingMinutes() }} menit baca</span>
    </div>
    <div class="flex flex-1 flex-col p-6">
        <time datetime="{{ $article->published_at?->toDateString() }}" class="text-xs font-bold uppercase tracking-[0.15em] text-flesh-500">{{ $article->published_at?->translatedFormat('d F Y') }}</time>
        <h3 class="mt-2 font-display text-xl font-bold leading-snug text-stone-900">
            <a href="{{ route('articles.show', $article) }}" class="after:absolute after:inset-0">{{ $article->title }}</a>
        </h3>
        <p class="mt-2 line-clamp-2 text-sm text-stone-500">{{ $article->excerpt }}</p>
        <span class="mt-auto inline-flex items-center gap-2 pt-5 text-sm font-semibold text-husk-700">
            Baca selengkapnya <span class="transition duration-300 group-hover:translate-x-1.5" aria-hidden="true">&rarr;</span>
        </span>
    </div>
</article>
