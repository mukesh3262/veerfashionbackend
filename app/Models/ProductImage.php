<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductImage extends Model
{
    use HasUuids;

    protected $table = 'product_images';

    protected $fillable = [
        'product_id',
        'image',
        'is_default',
    ];

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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
