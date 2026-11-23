<?php

namespace App\Http\Controllers\Site;

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

        $message = $product
            ? sprintf(
                'Halo, saya ingin memesan *%s* (%s %s). Apakah masih tersedia? %s',
                $product->name,
                $product->formattedPrice(),
                $product->price_unit,
                route('products.show', $product),
            )
            : $site->get('whatsapp_message');

        return redirect()->away($site->whatsappUrl($message));
    }
}
