<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\RecordsActivity;
use Database\Factories\TestimonialFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'name', 'location', 'content', 'rating', 'image_path', 'is_published'])]
class Testimonial extends Model
{
    /** @use HasFactory<TestimonialFactory> */
    use HasFactory, HasTranslations, RecordsActivity;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    /**
     * The product the customer bought.
     *
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Photo of the customer. Uses the domain of the current request, so it also works when APP_URL differs from it.
     */
    public function imageUrl(): ?string
    {
        return $this->image_path ? asset('storage/'.$this->image_path) : null;
    }

    public static function translatableAttributes(): array
    {
        return ['content' => 1000];
    }

    public static function requiredTranslations(): array
    {
        return ['content'];
    }

    public function activitySubjectLabel(): string
    {
        return 'testimoni';
    }

    public function activitySubjectTitle(): string
    {
        return $this->name;
    }
}
