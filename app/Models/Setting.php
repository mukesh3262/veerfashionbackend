<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MobileVersionEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Database\Query\Expression as QueryExpression;
use Illuminate\Database\Eloquent\Builder;

class Setting extends Model
{
    /** @use HasFactory<\Database\Factories\SettingFactory> */
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'values',
    ];

    protected function casts(): array
    {
        return [
            'values' => 'array',
        ];
    }

    public static function getVersionCodeRawQuery(string $platform): QueryExpression
    {
        return DB::raw('
            json_extract(
                json_extract(
                    `values`,
                    json_unquote(
                        json_search(
                            json_extract(
                                `values`,
                                "$[*].platform"
                            ), "one", "' . $platform . '"
                        )
                    )
                ),
            "$.version"
            )
        ');
    }

    public static function whereVersionShouldBeGreater(string $platform, int $version): Builder
    {
        return self::where('key', MobileVersionEnum::KEY->value)->where(self::getVersionCodeRawQuery($platform), '<=', $version);
    }
}
