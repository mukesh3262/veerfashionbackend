<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasActiveScope;

class ProductVariant extends Model
{
    use HasUuids, SoftDeletes, HasActiveScope;

    protected $table = 'product_variants';

    protected $guarded = [];


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
    
    
    public function product() {
        return $this->belongsTo(Product::class);
    }
    
    public function images() {
        return $this->hasMany(ProductVariantImage::class);
    }
}
