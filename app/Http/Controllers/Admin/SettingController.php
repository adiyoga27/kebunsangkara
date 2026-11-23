<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActivityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Support\ActivityLogger;
use App\Support\SiteSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(SiteSettings $site): View
    {
        return view('admin.settings.edit', ['settings' => $site->all()]);
    }

    public function update(UpdateSettingsRequest $request, SiteSettings $site, ActivityLogger $logger): RedirectResponse
    {
        $before = $site->all();
        $site->update($request->validated());

        $changedKeys = array_keys(array_diff_assoc(array_map('strval', $request->validated()), array_map('strval', $before)));

        if ($changedKeys !== []) {
            $logger->log(ActivityAction::Updated, 'Mengubah pengaturan website', properties: ['changed' => $changedKeys]);
        }

        return redirect()->route('admin.settings.edit')->with('status', 'Pengaturan berhasil disimpan.');
    }
}
