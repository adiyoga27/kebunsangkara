{{-- Support offered with every seedling purchase. --}}
<div class="grid gap-4 sm:grid-cols-3" data-reveal-stagger="120">
    @foreach ([
        ['💬', 'Konsultasi gratis', 'Tanya cara tanam, pemupukan, hingga perawatan bibit via WhatsApp — sebelum maupun sesudah membeli.'],
        ['🌿', 'Bibit dari kebun sendiri', 'Bibit diperbanyak dari pohon induk di kebun kami, sehingga varietasnya jelas.'],
        ['📦', 'Dikemas aman', 'Bibit dikemas dalam polybag dan dibungkus rapi agar tetap segar sampai di tujuan.'],
    ] as [$icon, $title, $text])
        <div data-reveal class="group rounded-[2rem] bg-white p-6 ring-1 ring-stone-200/70 transition duration-500 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-husk-900/10">
            <span class="inline-flex size-12 items-center justify-center rounded-2xl bg-husk-100 text-2xl transition duration-500 group-hover:-rotate-12 group-hover:scale-110">{{ $icon }}</span>
            <h3 class="mt-4 font-display text-lg font-bold text-husk-950">{{ $title }}</h3>
            <p class="mt-1 text-sm leading-relaxed text-stone-600">{{ $text }}</p>
        </div>
    @endforeach
</div>
