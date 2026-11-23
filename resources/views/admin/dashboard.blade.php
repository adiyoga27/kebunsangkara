<x-layouts.admin title="Dashboard">
    <x-admin.page-header title="Dashboard" description="Ringkasan website dan pesanan via WhatsApp." />

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['label' => 'Klik WhatsApp hari ini', 'value' => $stats['clicksToday']],
            ['label' => 'Klik WhatsApp (30 hari)', 'value' => $stats['clicks30Days']],
            ['label' => 'Produk tersedia', 'value' => $stats['availableProducts'].' / '.$stats['products']],
            ['label' => 'Artikel terbit', 'value' => $stats['articles']],
        ] as $stat)
            <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">{{ $stat['label'] }}</p>
                <p class="mt-2 font-display text-3xl font-bold text-husk-900">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <x-admin.card title="Klik WhatsApp 14 hari terakhir" class="lg:col-span-2">
            @php($max = max(1, $chart->max('total')))
            <div class="flex h-48 items-end gap-1.5" role="img" aria-label="Grafik klik WhatsApp per hari">
                @foreach ($chart as $day)
                    <div class="flex flex-1 flex-col items-center justify-end gap-1">
                        <span class="text-[10px] font-semibold text-stone-500">{{ $day['total'] ?: '' }}</span>
                        <div class="w-full rounded-t-md bg-husk-500" style="height: {{ max(2, $day['total'] / $max * 160) }}px" title="{{ $day['label'] }}: {{ $day['total'] }} klik"></div>
                        <span class="text-[10px] text-stone-400">{{ Str::before($day['label'], ' ') }}</span>
                    </div>
                @endforeach
            </div>
        </x-admin.card>

        <x-admin.card title="Produk paling diminati (30 hari)">
            <ol class="space-y-3 text-sm">
                @forelse ($topProducts as $product)
                    <li class="flex justify-between gap-3">
                        <span class="truncate">{{ $product->name }}</span>
                        <span class="font-semibold text-husk-800">{{ $product->whatsapp_clicks_count }} klik</span>
                    </li>
                @empty
                    <li class="text-stone-500">Belum ada data.</li>
                @endforelse
            </ol>
        </x-admin.card>
    </div>

    <x-admin.card title="Aktivitas terbaru" class="mt-6">
        <ul class="divide-y divide-stone-100 text-sm">
            @forelse ($recentActivity as $log)
                <li class="flex flex-wrap items-center gap-3 py-3">
                    <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $log->action->badgeClasses() }}">{{ $log->action->label() }}</span>
                    <span class="flex-1">{{ $log->description }}</span>
                    <span class="text-xs text-stone-500">{{ $log->user?->name ?? 'Sistem' }} · {{ $log->created_at?->diffForHumans() }}</span>
                </li>
            @empty
                <li class="py-3 text-stone-500">Belum ada aktivitas.</li>
            @endforelse
        </ul>
        @can('administer')
            <a href="{{ route('admin.activity-logs.index') }}" class="mt-4 inline-block text-sm font-semibold text-husk-700 hover:underline">Lihat semua log &rarr;</a>
        @endcan
    </x-admin.card>
</x-layouts.admin>
