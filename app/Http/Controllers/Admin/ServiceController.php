<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    use HandlesImageUploads;

    public function index(): View
    {
        return view('admin.services.index', [
            'services' => Service::query()->ordered()->get(),
            'trashedServices' => Service::onlyTrashed()->latest('deleted_at')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.services.form', ['service' => new Service(['is_published' => true, 'sort_order' => Service::query()->max('sort_order') + 1])]);
    }

    public function store(SaveServiceRequest $request): RedirectResponse
    {
        Service::create([
            ...$request->safe()->except(['image', 'remove_image']),
            'image_path' => $this->resolveImagePath($request, 'services'),
        ]);

        return redirect()->route('admin.services.index')->with('status', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.form', ['service' => $service]);
    }

    public function update(SaveServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update([
            ...$request->safe()->except(['image', 'remove_image']),
            'image_path' => $this->resolveImagePath($request, 'services', $service->image_path),
        ]);

        return redirect()->route('admin.services.index')->with('status', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('status', 'Layanan dipindahkan ke sampah dan bisa dipulihkan.');
    }
}
