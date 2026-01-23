<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price', 
        'old_price', 'stock_quantity', 'images', 'sku', 
        'technical_specs', 'is_active', 'is_hot', 'is_sponsored'
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'is_hot' => 'boolean',
        'is_sponsored' => 'boolean',
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
    ];

    // Accessor for the Flash Sale design
    public function getDiscountPercentAttribute()
    {
        if ($this->old_price > $this->price && $this->old_price > 0) {
            $reduction = $this->old_price - $this->price;
            return round(($reduction / $this->old_price) * 100);
        }
        return 0;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
{
    return $this->belongsTo(Brand::class);
}
}