@props(['product' => null, 'offering' => null, 'service' => null, 'size' => 'md', 'url' => null])

<a href="{{ $url ?? route('whatsapp.order', $service ? ['jasa' => $service->getKey()] : ($offering ? ['layanan' => $offering->value] : $product)) }}" rel="nofollow"
   {{ $attributes->class([
       'btn-shine group/wa inline-flex items-center justify-center gap-2 rounded-full bg-whatsapp font-semibold text-white shadow-lg shadow-whatsapp/25 transition duration-300 hover:-translate-y-0.5 hover:bg-whatsapp-dark hover:shadow-xl hover:shadow-whatsapp/30 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-whatsapp',
       'px-4 py-2 text-sm' => $size === 'sm',
       'px-6 py-3' => $size === 'md',
       'px-8 py-4 text-lg' => $size === 'lg',
   ]) }}>
    <x-site.whatsapp-icon @class(['transition duration-300 group-hover/wa:-rotate-12 group-hover/wa:scale-110', 'size-4' => $size === 'sm', 'size-5' => $size !== 'sm']) />
    {{ $slot->isEmpty() ? __('Pesan via WhatsApp') : $slot }}
</a>
