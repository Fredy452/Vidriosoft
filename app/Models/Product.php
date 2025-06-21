<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'unit_type',
        'width',
        'height',
        'thickness',
        'area',
        'volume',
        'price',
        'price_per_unit',
        'discount',
        'glass_type_id',
        'features',
        'stock',
        'min_stock',
        'image',
        'is_active',
        'category_id',
        'provider_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount' => 'integer',
        'price' => 'integer',
        'price_per_unit' => 'integer',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'thickness' => 'decimal:3',
        'area' => 'decimal:2',
        'volume' => 'decimal:3',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'features' => 'json',
        'unit_type' => 'string',
        'image' => 'array',
    ];

    /**
     * Get the category that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the provider that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * Get the glass type associated with the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function glassType()
    {
        return $this->belongsTo(GlassType::class, 'glass_type_id');
    }
}
