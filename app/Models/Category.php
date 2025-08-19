<?php

namespace App\Models;

use App\Traits\HasActiveScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasUuids, SoftDeletes, HasActiveScope;

    protected $fillable = [
        'uuid',
        'parent_id',
        'name',
        'slug',
        'description',
        'icon',
        'is_active',
        'sort_order',
    ];

    /**
     * The model's default values for appends.
     *
     * @var array
     */
    protected $appends = [
        'icon_url',
    ];

    protected static function boot()
    {
        parent::boot();

        // When creating a new record
        static::creating(function ($model) {
            if (!$model->slug) {
                $model->slug = static::generateSlug($model->name);
            }
        });
    }

    /**
     * Generate unique slug
     *
     * @return string
     */
    private static function generateSlug(string $string): string
    {
        $nameSlug = Str::of($string)->slug();

        // Check if the slug already exists
        $countRepeated = static::withTrashed()->whereLike('slug', "{$nameSlug}%")->count() ?? 0;
        $availableSlug = $countRepeated > 0 ? "{$nameSlug}-{$countRepeated}" : $nameSlug;

        return $availableSlug;
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

    protected function iconUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->icon ? Storage::url(config('filesystems.module_paths.categories') . $this->icon) : asset('images/default-category.png'),
        );
    }

    public function scopeMainCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

     /**
     * Get all products for the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
