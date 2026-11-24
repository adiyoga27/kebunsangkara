<?php

namespace App\Support;

use App\Enums\Locale;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

/**
 * Key/value website settings editable from the admin panel, cached forever and flushed on save.
 */
class SiteSettings
{
    private const CACHE_KEY = 'site-settings';

    /**
     * Settings with an English version stored under the same key suffixed with "_en".
     */
    public const TRANSLATABLE = [
        'tagline', 'whatsapp_message', 'opening_hours', 'service_area', 'home_meta_title', 'home_meta_description', 'about_content',
    ];

    /**
     * Texts of the home page, edited under Custom Page. Stored per language like TRANSLATABLE.
     */
    public const HOME_PAGE_TRANSLATABLE = ['home_marquee'];

    /**
     * Social media profiles by setting key.
     */
    public const SOCIAL_MEDIA = [
        'instagram_url' => 'Instagram', 'facebook_url' => 'Facebook', 'tiktok_url' => 'TikTok', 'youtube_url' => 'YouTube',
    ];

    /**
     * Online shops on marketplaces by setting key.
     */
    public const MARKETPLACES = [
        'tokopedia_url' => 'Tokopedia', 'shopee_url' => 'Shopee', 'lazada_url' => 'Lazada',
    ];

    /**
     * @var array<string, string|null>|null
     */
    private ?array $loaded = null;

    /**
     * Default values for every supported setting key.
     *
     * @return array<string, string>
     */
    public static function defaults(): array
    {
        return [
            'site_name' => 'Kebun Sangkara',
            'tagline' => 'Buah Segar & Bibit Unggul Langsung dari Kebun Bali',
            'whatsapp_number' => '6281234567890',
            'whatsapp_message' => 'Halo Kebun Sangkara, saya ingin memesan buah.',
            'phone_display' => '0812-3456-7890',
            'email' => '',
            'street_address' => 'Jl. Raya Pupuan',
            'city' => 'Tabanan',
            'region' => 'Bali',
            'postal_code' => '82163',
            'latitude' => '-8.3405',
            'longitude' => '115.0920',
            'opening_hours' => 'Setiap hari, 08.00 – 20.00 WITA',
            'service_area' => 'Denpasar, Badung, Gianyar, Tabanan, Kuta, Seminyak, Canggu, Ubud, Nusa Dua & seluruh Indonesia',
            'google_maps_url' => '',
            'instagram_url' => '',
            'facebook_url' => '',
            'tiktok_url' => '',
            'youtube_url' => '',
            'tokopedia_url' => '',
            'shopee_url' => '',
            'lazada_url' => '',
            'home_meta_title' => 'Kebun Sangkara – Jual Buah & Bibit Durian, Alpukat & Manggis Bali',
            'home_meta_description' => 'Kebun Sangkara Bali menjual buah segar & bibit unggul: durian, alpukat & manggis langsung dari kebun. Bibit okulasi siap tanam. Pesan via WhatsApp!',
            'about_content' => "**Kebun Sangkara** adalah kebun buah di Bali yang menanam dan menjual durian, alpukat, dan manggis langsung kepada pelanggan.\n\nSetiap buah kami petik saat matang, dipilih satu per satu, lalu dikirim segar ke rumah, villa, maupun hotel Anda di seluruh Indonesia — tanpa perantara, sehingga kualitas dan harga tetap terjaga.",
            'tagline_en' => 'Fresh Fruit & Quality Seedlings Straight from a Bali Farm',
            'whatsapp_message_en' => 'Hello Kebun Sangkara, I would like to order some fruit.',
            'opening_hours_en' => 'Every day, 8 am – 8 pm WITA',
            'service_area_en' => 'Denpasar, Badung, Gianyar, Tabanan, Kuta, Seminyak, Canggu, Ubud, Nusa Dua & all of Indonesia',
            'home_meta_title_en' => 'Kebun Sangkara – Durian, Avocado & Mangosteen Farm in Bali',
            'home_meta_description_en' => 'Kebun Sangkara Bali sells fresh fruit & quality seedlings: durian, avocado & mangosteen straight from the farm. Order via WhatsApp!',
            'about_content_en' => "**Kebun Sangkara** is a fruit farm in Bali that grows durian, avocado and mangosteen and sells them directly to customers.\n\nWe pick every fruit when it is ripe, select it by hand, and deliver it fresh to your home, villa or hotel anywhere in Indonesia — with no middlemen, so quality and prices stay fair.",
            'home_marquee_enabled' => '1',
            'home_marquee' => "🍈 Durian Matang Pohon\n🥑 Alpukat Mentega\n🟣 Manggis Manis\n🌱 Bibit Okulasi\n🌿 Langsung dari Kebun\n🚚 Kirim ke Seluruh Indonesia\n🛡️ Garansi Ganti",
            'home_marquee_en' => "🍈 Tree-Ripened Durian\n🥑 Butter Avocado\n🟣 Sweet Mangosteen\n🌱 Budded Seedlings\n🌿 Straight from the Farm\n🚚 Shipping across Indonesia\n🛡️ Replacement Guarantee",
            'google_site_verification' => '',
            'google_analytics_id' => '',
        ];
    }

    public function get(string $key, ?string $default = null): ?string
    {
        $value = $this->all()[$key] ?? null;

        if (! Locale::current()->isDefault() && in_array($key, [...self::TRANSLATABLE, ...self::HOME_PAGE_TRANSLATABLE], true)) {
            $value = ($this->all()[$key.'_'.Locale::current()->value] ?? null) ?: $value;
        }

        return $value === null || $value === '' ? $default : $value;
    }

    /**
     * The lines of a setting that holds one item per line.
     *
     * @return list<string>
     */
    public function lines(string $key): array
    {
        return array_values(array_filter(array_map(trim(...), explode("\n", (string) $this->get($key))), filled(...)));
    }

    /**
     * The links of the given keys that have been filled in, as label => URL.
     *
     * @param  array<string, string>  $labels
     * @return array<string, string>
     */
    public function links(array $labels): array
    {
        $links = [];

        foreach ($labels as $key => $label) {
            if ($url = $this->get($key)) {
                $links[$label] = $url;
            }
        }

        return $links;
    }

    /**
     * @return array<string, string|null>
     */
    public function all(): array
    {
        return $this->loaded ??= array_merge(
            static::defaults(),
            Cache::rememberForever(self::CACHE_KEY, fn (): array => Setting::query()->pluck('value', 'key')->all()),
        );
    }

    /**
     * @param  array<string, string|null>  $values
     */
    public function update(array $values): void
    {
        foreach (array_intersect_key($values, static::defaults()) as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget(self::CACHE_KEY);
        $this->loaded = null;
    }

    /**
     * Phone number in international format without symbols, as required by wa.me.
     */
    public function whatsappNumber(): string
    {
        $number = preg_replace('/\D+/', '', (string) $this->get('whatsapp_number'));

        return str_starts_with($number, '0') ? '62'.substr($number, 1) : $number;
    }

    public function whatsappUrl(?string $message = null): string
    {
        return 'https://wa.me/'.$this->whatsappNumber().'?text='.rawurlencode($message ?? (string) $this->get('whatsapp_message'));
    }
}
