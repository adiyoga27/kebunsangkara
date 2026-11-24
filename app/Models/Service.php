<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\RecordsActivity;
use App\Support\SiteMarkdown;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;

#[Fillable(['name', 'summary', 'description', 'image_path', 'sort_order', 'is_published'])]
class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasFactory, HasTranslations, RecordsActivity, SoftDeletes;

    /**
     * Farm photos for services without their own photo, used in turn by sort order.
     */
    private const DEFAULT_IMAGES = [
        'images/kebun/alpukat-di-pohon.webp',
        'images/kebun/pohon-durian.webp',
        'images/kebun/durian-matang-di-pohon.webp',
        'images/kebun/buah-manggis.webp',
        'images/kebun/kebun-alpukat.webp',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public static function translatableAttributes(): array
    {
        return ['name' => 120, 'summary' => 300, 'description' => 10000];
    }

    public static function requiredTranslations(): array
    {
        return ['name', 'summary', 'description'];
    }

    /**
     * @param  Builder<static>  $query
     */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /**
     * @param  Builder<static>  $query
     */
    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    public function descriptionHtml(): HtmlString
    {
        return SiteMarkdown::render($this->description);
    }

    /**
     * Photo of the service. Uses the domain of the current request, so it also works when APP_URL differs from it.
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
        return $this->imageUrl() ?? asset(self::DEFAULT_IMAGES[max(0, $this->sort_order - 1) % count(self::DEFAULT_IMAGES)]);
    }

    /**
     * HTML id of this service on the services page.
     */
    public function anchor(): string
    {
        return 'layanan-'.$this->getKey();
    }

    public function activitySubjectLabel(): string
    {
        return 'layanan';
    }

    public function activitySubjectTitle(): string
    {
        return $this->name;
    }
}
