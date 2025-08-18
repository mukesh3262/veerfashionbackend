<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppVersionLog extends Model
{
    /** @use HasFactory<\Database\Factories\AppVersionLogFactory> */
    use HasFactory;

    protected $table = 'app_version_logs';

    protected $fillable = [
        'android_version',
        'is_android_force_update',
        'ios_version',
        'is_ios_force_update',
    ];
}
