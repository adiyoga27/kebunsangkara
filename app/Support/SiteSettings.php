<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

/**
 * Key/value website settings editable from the admin panel, cached forever and flushed on save.
 */
class SiteSettings
{
    private const CACHE_KEY = 'site-settings';

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
            'site_name' => 'Durian Bali Asli',
            'tagline' => 'Durian Asli Bali, Dipetik Matang dari Kebun',
            'whatsapp_number' => '6281234567890',
            'whatsapp_message' => 'Halo, saya ingin memesan durian asli Bali.',
            'phone_display' => '0812-3456-7890',
            'email' => '',
            'street_address' => 'Jl. Raya Pupuan',
            'city' => 'Tabanan',
            'region' => 'Bali',
            'postal_code' => '82163',
            'latitude' => '-8.3405',
            'longitude' => '115.0920',
            'opening_hours' => 'Setiap hari, 08.00 – 20.00 WITA',
            'service_area' => 'Denpasar, Badung, Gianyar, Tabanan, Kuta, Seminyak, Canggu, Ubud, Nusa Dua & seluruh Bali',
            'google_maps_url' => '',
            'instagram_url' => '',
            'facebook_url' => '',
            'tiktok_url' => '',
            'home_meta_title' => 'Jual Durian Bali Asli Matang Pohon – Pesan via WhatsApp',
            'home_meta_description' => 'Jual durian asli Bali langsung dari kebun petani Tabanan. Matang pohon, manis legit, garansi ganti jika busuk. Antar se-Bali. Pesan via WhatsApp!',
            'about_content' => "Kami adalah penjual durian asli Bali yang bekerja sama langsung dengan petani di daerah Tabanan dan Buleleng.\n\nSetiap durian dipilih satu per satu, dipetik saat matang, dan dikirim segar ke pelanggan di seluruh Bali.",
            'google_site_verification' => '',
            'google_analytics_id' => '',
        ];
    }

    public function get(string $key, ?string $default = null): ?string
    {
        $value = $this->all()[$key] ?? null;

        return $value === null || $value === '' ? $default : $value;
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
