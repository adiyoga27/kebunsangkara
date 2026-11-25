<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\RecordsActivity;
use Database\Factories\FruitCategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'emoji', 'tagline', 'intro', 'image_path', 'meta_title', 'meta_description', 'sort_order'])]
class FruitCategory extends Model
{
    /** @use HasFactory<FruitCategoryFactory> */
    use HasFactory, HasTranslations, RecordsActivity;

    /**
     * Maximum slug length, limited by the category column of products.
     */
    public const MAX_SLUG_LENGTH = 20;

    /**
     * Farm photos for categories without their own photo, by slug.
     */
    private const DEFAULT_IMAGES = [
        'durian' => 'images/kebun/durian-matang-di-pohon.webp',
        'alpukat' => 'images/kebun/alpukat-di-pohon.webp',
        'manggis' => 'images/kebun/buah-manggis.webp',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public static function translatableAttributes(): array
    {
        return ['name' => 50, 'tagline' => 100, 'intro' => 500, 'meta_title' => 70, 'meta_description' => 170];
    }

    public static function requiredTranslations(): array
    {
        return ['name', 'tagline', 'intro'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @param  Builder<static>  $query
     */
    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category', 'slug');
    }

    /**
     * URL of the uploaded photo, if any.
     */
    public function imageUrl(): ?string
    {
        return $this->image_path ? asset('storage/'.$this->image_path) : null;
    }

    /**
     * The uploaded photo, or a photo of the farm when none was uploaded.
     */
    public function displayImageUrl(): string
    {
        return $this->imageUrl() ?? asset(self::DEFAULT_IMAGES[$this->slug] ?? 'images/kebun/kebun-alpukat.webp');
    }

    public function seoTitle(): string
    {
        return $this->meta_title ?: __('Jual :fruit Segar dari Kebun Bali', ['fruit' => $this->name]);
    }

    public function seoDescription(): string
    {
        return $this->meta_description ?: Str::limit($this->intro, 160);
    }

    public function activitySubjectLabel(): string
    {
        return 'kategori buah';
    }

    public function activitySubjectTitle(): string
    {
        return $this->name;
    }
}
