<?php

namespace App\Http\Middleware;

use App\Enums\Locale;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies the language from the URL prefix to the application and to every generated link.
 */
class SetLocale
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Locale::from((string) $request->route('locale'));

        App::setLocale($locale->value);
        URL::defaults(['locale' => $locale->value]);

        // Controllers receive route parameters by position and do not expect the language.
        $request->route()->forgetParameter('locale');

        return $next($request);
    }
}
