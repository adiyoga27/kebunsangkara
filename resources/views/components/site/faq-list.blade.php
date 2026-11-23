@props(['faqs'])

<div class="space-y-3" data-accordion data-reveal-stagger="80">
    @foreach ($faqs as $faq)
        <details data-reveal class="faq-item group rounded-3xl bg-white px-6 py-5 ring-1 ring-stone-200/70 transition open:bg-husk-950 open:text-white open:shadow-xl open:shadow-husk-950/20" @if ($loop->first) open @endif>
            <summary class="flex items-center justify-between gap-4 font-semibold">
                <h3 class="text-base sm:text-lg">{{ $faq->question }}</h3>
                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-husk-50 text-husk-700 transition duration-500 group-open:rotate-[135deg] group-open:bg-flesh-400 group-open:text-husk-950" aria-hidden="true">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                </span>
            </summary>
            <p class="faq-body mt-3 whitespace-pre-line leading-relaxed text-stone-600 group-open:text-husk-100">{{ $faq->answer }}</p>
        </details>
    @endforeach
</div>
