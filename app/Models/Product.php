<?php

namespace App\Models;

use App\Enums\FruitCategory;
use App\Enums\ProductType;
use App\Models\Concerns\RecordsActivity;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

#[Fillable([
    'name', 'slug', 'category', 'type', 'variety', 'origin', 'propagation_method', 'plant_height', 'short_description', 'description', 'price', 'price_unit',
    'image_path', 'is_available', 'is_featured', 'sort_order', 'meta_title', 'meta_description',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, RecordsActivity;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category' => FruitCategory::class,
            'type' => ProductType::class,
            'price' => 'integer',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @param  Builder<Product>  $query
     */
    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * @return HasMany<WhatsappClick, $this>
     */
    public function whatsappClicks(): HasMany
    {
        return $this->hasMany(WhatsappClick::class);
    }

    /**
     * Render the Markdown description to safe HTML (raw HTML input is stripped).
     */
    public function descriptionHtml(): HtmlString
    {
        return new HtmlString(Str::markdown($this->description, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]));
    }

    public function formattedPrice(): string
    {
        return 'Rp '.number_format($this->price, 0, ',', '.');
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : null;
    }

    /**
     * The uploaded photo, or the category's default photo when none was uploaded.
     */
    public function displayImageUrl(): string
    {
        if ($this->imageUrl()) {
            return $this->imageUrl();
        }

        return $this->isSeedling()
            ? asset('images/bibit.svg')
            : asset(($this->category ?? FruitCategory::Durian)->imagePath());
    }

    public function isSeedling(): bool
    {
        return $this->type === ProductType::Bibit;
    }

    /**
     * @param  Builder<Product>  $query
     */
    #[Scope]
    protected function ofType(Builder $query, ProductType $type): void
    {
        $query->where('type', $type);
    }

    public function seoTitle(): string
    {
        return $this->meta_title ?: $this->name.($this->isSeedling() ? ' Siap Tanam' : ' Segar').' – Harga '.$this->formattedPrice().' '.$this->price_unit;
    }

    public function seoDescription(): string
    {
        return $this->meta_description ?: $this->short_description;
    }

    public function activitySubjectLabel(): string
    {
        return 'produk';
    }

    public function activitySubjectTitle(): string
    {
        return $this->name;
    }
}
