<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'price', 'old_price', 'unit', 'icon', 'image', 'stock', 'is_active',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'old_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** Відсоток знижки */
    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->old_price && $this->old_price > $this->price) {
            return (int) round((1 - $this->price / $this->old_price) * 100);
        }
        return null;
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/placeholder.png');
    }
}
