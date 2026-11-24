<?php

namespace App\Models;

use App\Enums\FruitCategory;
use App\Enums\Locale;
use App\Enums\Offering;
use App\Enums\ProductType;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\RecordsActivity;
use App\Support\SiteMarkdown;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\HtmlString;

#[Fillable([
    'name', 'slug', 'category', 'type', 'variety', 'origin', 'propagation_method', 'plant_height', 'short_description', 'description', 'price', 'price_unit',
    'image_path', 'is_available', 'is_featured', 'sort_order', 'meta_title', 'meta_description',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, HasTranslations, RecordsActivity;

    /**
     * Maximum number of photos per product, including the main photo.
     */
    public const MAX_PHOTOS = 10;

    /**
     * Hide fruit and seedlings whose category is no longer sold; their rows stay in the database.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('sold_category', function (Builder $query): void {
            $query->where(fn (Builder $query) => $query
                ->whereIn($query->qualifyColumn('category'), FruitCategory::cases())
                ->orWhereNotIn($query->qualifyColumn('type'), ProductType::withFruitCategory()));
        });
    }

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

    public static function translatableAttributes(): array
    {
        return [
            'name' => 150,
            'variety' => 100,
            'propagation_method' => 50,
            'price_unit' => 50,
            'short_description' => 300,
            'description' => 20000,
            'meta_title' => 70,
            'meta_description' => 170,
        ];
    }

    public static function requiredTranslations(): array
    {
        return ['name', 'short_description', 'description'];
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
     * @return HasMany<ProductImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('id');
    }

    /**
     * Render the Markdown description to safe HTML (raw HTML input is stripped).
     */
    public function descriptionHtml(): HtmlString
    {
        return SiteMarkdown::render($this->description);
    }

    public function formattedPrice(): string
    {
        return Locale::current()->formatPrice($this->price);
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? asset('storage/'.$this->image_path) : null;
    }

    /**
     * The uploaded photo, or the category's default photo when none was uploaded.
     */
    public function displayImageUrl(): string
    {
        if ($this->imageUrl()) {
            return $this->imageUrl();
        }

        return asset(match ($this->type) {
            ProductType::Bibit => 'images/bibit.svg',
            ProductType::Pupuk => 'images/pupuk.svg',
            ProductType::Kesehatan => 'images/produk-kesehatan.svg',
            default => ($this->category ?? FruitCategory::Durian)->imagePath(),
        });
    }

    /**
     * Every photo shown in the product slider, main photo first.
     *
     * @return list<string>
     */
    public function galleryUrls(): array
    {
        return [$this->displayImageUrl(), ...$this->images->map->url()->all()];
    }

    public function isSeedling(): bool
    {
        return $this->type === ProductType::Bibit;
    }

    public function imageAlt(): string
    {
        return $this->type->hasFruitCategory() ? __(':name segar dari kebun Bali', ['name' => $this->name]) : $this->name;
    }

    /**
     * Short badge text, e.g. "🍈 Durian", "🌱 Bibit Durian" or "🌾 Pupuk".
     */
    public function badge(): string
    {
        return match ($this->type) {
            ProductType::Buah => $this->category->emoji().' '.$this->category->label(),
            ProductType::Bibit => $this->type->emoji().' '.__('Bibit :fruit', ['fruit' => $this->category->label()]),
            default => $this->type->emoji().' '.$this->type->label(),
        };
    }

    /**
     * The page this product is listed on, used for breadcrumbs.
     *
     * @return array{label: string, url: string}
     */
    public function listingPage(): array
    {
        return match ($this->type) {
            ProductType::Buah => ['label' => $this->category->label(), 'url' => route('products.category', $this->category)],
            ProductType::Bibit => ['label' => __('Bibit'), 'url' => route('seedlings.index')],
            ProductType::Pupuk => ['label' => Offering::Pupuk->label(), 'url' => route('offerings.show', Offering::Pupuk)],
            ProductType::Kesehatan => ['label' => Offering::ProdukKesehatan->label(), 'url' => route('offerings.show', Offering::ProdukKesehatan)],
        };
    }

    /**
     * @param  Builder<Product>  $query
     */
    #[Scope]
    protected function withFruitCategory(Builder $query): void
    {
        $query->whereIn('type', ProductType::withFruitCategory());
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
        return $this->meta_title ?: __(match ($this->type) {
            ProductType::Buah => ':name Segar – Harga :price :unit',
            ProductType::Bibit => ':name Siap Tanam – Harga :price :unit',
            default => ':name – Harga :price :unit',
        }, ['name' => $this->name, 'price' => $this->formattedPrice(), 'unit' => $this->price_unit]);
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
