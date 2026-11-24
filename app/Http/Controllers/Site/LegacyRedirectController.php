<?php

namespace App\Http\Controllers\Site;

use App\Enums\Locale;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Permanently redirects URLs without a language prefix to the default language.
 */
class LegacyRedirectController extends Controller
{
    public function __invoke(Request $request, string $path): RedirectResponse
    {
        $locale = Locale::default()->value;

        if ($path === 'kontak') {
            return redirect("/{$locale}/tentang-kami#kontak", 301);
        }

        $query = $request->getQueryString();

        return redirect("/{$locale}/{$path}".($query ? "?{$query}" : ''), 301);
    }
}
