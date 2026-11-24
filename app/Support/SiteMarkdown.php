<?php

namespace App\Support;

use App\Enums\Locale;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/**
 * Renders Markdown content to safe HTML and keeps internal links in the current language.
 */
class SiteMarkdown
{
    /**
     * First path segments of the public pages, as written in content without a language prefix.
     */
    public const PAGE_SEGMENTS = [
        'produk', 'produk-kesehatan', 'bibit', 'buah', 'artikel', 'tentang-kami', 'kontak', 'pesan', 'pupuk', 'service',
    ];

    public static function render(string $markdown): HtmlString
    {
        $html = Str::markdown($markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return new HtmlString((string) preg_replace(
            '~href="/('.implode('|', self::PAGE_SEGMENTS).')(?=[/"?\#])~',
            'href="/'.Locale::current()->value.'/$1',
            $html,
        ));
    }
}
