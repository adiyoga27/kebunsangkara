@props(['edit', 'destroy', 'confirm' => 'Yakin ingin menghapus data ini?'])

<div class="flex justify-end gap-2">
    <a href="{{ $edit }}" class="rounded-lg border border-stone-300 px-3 py-1.5 text-xs font-semibold hover:bg-stone-100">Ubah</a>
    <form method="POST" action="{{ $destroy }}" onsubmit="return confirm(@js($confirm))">
        @csrf
        @method('DELETE')
        <button class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-50">Hapus</button>
    </form>
</div>
