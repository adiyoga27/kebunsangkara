<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('administer') ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'site_name' => ['required', 'string', 'max:80'],
            'tagline' => ['required', 'string', 'max:150'],
            'whatsapp_number' => ['required', 'string', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'whatsapp_message' => ['required', 'string', 'max:300'],
            'phone_display' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'street_address' => ['required', 'string', 'max:200'],
            'city' => ['required', 'string', 'max:80'],
            'region' => ['required', 'string', 'max:80'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'opening_hours' => ['required', 'string', 'max:150'],
            'service_area' => ['required', 'string', 'max:300'],
            'google_maps_url' => ['nullable', 'url', 'max:500'],
            'instagram_url' => ['nullable', 'url', 'max:300'],
            'facebook_url' => ['nullable', 'url', 'max:300'],
            'tiktok_url' => ['nullable', 'url', 'max:300'],
            'home_meta_title' => ['required', 'string', 'max:70'],
            'home_meta_description' => ['required', 'string', 'max:170'],
            'about_content' => ['required', 'string', 'max:10000'],
            'google_site_verification' => ['nullable', 'string', 'max:100', 'alpha_dash'],
            'google_analytics_id' => ['nullable', 'string', 'max:30', 'regex:/^G-[A-Z0-9]+$/'],
        ];
    }
}
