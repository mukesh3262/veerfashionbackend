<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasActiveScope;

class Product extends Model
{
    use HasUuids, SoftDeletes, HasActiveScope;

    protected $table = 'products';

    protected $fillable = [
        'uuid', 'category_id' ,'name', 'code', 'description', 'base_price', 'is_active'
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $latestProduct = self::orderBy('id', 'desc')->first();

            if ($latestProduct && $latestProduct->code) {
                // extract number from last code
                $lastNumber = (int) str_replace('PROD-', '', $latestProduct->code);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $product->code = 'PROD-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }
    
    /**
     * Returns an array of unique identifiers for the model.
     *
     * By default, this includes the "uuid" field
     *
     * @return array<string>
     */
    public function uniqueIds()
    {
        return ['uuid'];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function variants() {
        return $this->hasMany(ProductVariant::class);
    }
    
    public function images() {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the category associated with the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
