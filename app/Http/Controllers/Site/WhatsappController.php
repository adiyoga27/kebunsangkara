<?php

namespace App\Http\Controllers\Site;

use App\Enums\Offering;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WhatsappClick;
use App\Support\SiteSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Records an order intent (lead) and forwards the visitor to the shop's WhatsApp chat.
 */
class WhatsappController extends Controller
{
    public function __invoke(Request $request, SiteSettings $site, ?Product $product = null): RedirectResponse
    {
        WhatsappClick::create([
            'product_id' => $product?->getKey(),
            'source_url' => Str::limit((string) $request->headers->get('referer'), 490, ''),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $offering = is_string($request->query('layanan')) ? Offering::tryFrom($request->query('layanan')) : null;

        $message = match (true) {
            $product !== null => __('Halo, saya ingin memesan *:name* (:price :unit). Apakah masih tersedia? :url', [
                'name' => $product->name,
                'price' => $product->formattedPrice(),
                'unit' => $product->price_unit,
                'url' => route('products.show', $product),
            ]),
            $offering !== null => __('Halo, saya ingin bertanya tentang *:topic*. :url', ['topic' => $offering->label(), 'url' => route('offerings.show', $offering)]),
            default => $site->get('whatsapp_message'),
        };

        return redirect()->away($site->whatsappUrl($message));
    }
}
