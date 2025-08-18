<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentPage extends Model
{
    /** @use HasFactory<\Database\Factories\ContentPageFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'content_pages';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_active',
    ];
}
