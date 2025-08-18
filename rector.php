<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap',
        __DIR__.'/config',
        __DIR__.'/lang',
        __DIR__.'/node_modules',
        __DIR__.'/public',
        __DIR__.'/resources',
        __DIR__.'/routes',
        __DIR__.'/swagger',
        __DIR__.'/tests',
    ])
    ->withRules([
        // register a single rule
        InlineConstructorDefaultToPropertyRector::class,
        TypedPropertyFromStrictConstructorRector::class,
    ])
    ->withSets([
        // LevelSetList::UP_TO_PHP_81,
        SetList::PHP_83,
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        // SetList::CODING_STYLE
    ]);
