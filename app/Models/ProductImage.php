<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use Database\Factories\ProductImageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Additional gallery photo of a product; the main photo is stored in products.image_path.
 */
#[Fillable(['product_id', 'path'])]
class ProductImage extends Model
{
    /** @use HasFactory<ProductImageFactory> */
    use HasFactory, RecordsActivity;

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function url(): string
    {
        return asset('storage/'.$this->path);
    }

    public function activitySubjectLabel(): string
    {
        return 'foto produk';
    }

    public function activitySubjectTitle(): string
    {
        return (string) $this->product?->name;
    }
}
