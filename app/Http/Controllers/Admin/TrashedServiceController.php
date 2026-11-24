<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;

class TrashedServiceController extends Controller
{
    public function update(Service $service): RedirectResponse
    {
        $service->restore();

        return redirect()->route('admin.services.index')->with('status', 'Layanan berhasil dipulihkan.');
    }
}
