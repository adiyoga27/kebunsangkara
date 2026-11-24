<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\RecordsActivity;
use App\Support\SiteMarkdown;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

#[Fillable([
    'user_id', 'title', 'slug', 'excerpt', 'body', 'image_path', 'is_published', 'published_at',
    'meta_title', 'meta_description',
])]
class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory, HasTranslations, RecordsActivity;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public static function translatableAttributes(): array
    {
        return ['title' => 150, 'excerpt' => 320, 'body' => 100000, 'meta_title' => 70, 'meta_description' => 170];
    }

    public static function requiredTranslations(): array
    {
        return ['title', 'excerpt', 'body'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Articles that are published and whose publish date has passed.
     *
     * @param  Builder<Article>  $query
     */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Render the Markdown body to safe HTML (raw HTML input is stripped).
     */
    public function bodyHtml(): HtmlString
    {
        return SiteMarkdown::render($this->body);
    }

    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->body)) / 200));
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : null;
    }

    public function seoTitle(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function seoDescription(): string
    {
        return $this->meta_description ?: $this->excerpt;
    }

    public function activitySubjectLabel(): string
    {
        return 'artikel';
    }

    public function activitySubjectTitle(): string
    {
        return $this->title;
    }
}
