<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Lunar\Models\Product as LunarProduct;

class Product extends LunarProduct
{
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
}
