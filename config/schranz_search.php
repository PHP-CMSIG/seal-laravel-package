<?php

declare(strict_types=1);

/*
 * This file is part of the Schranz Search package.
 *
 * (c) Alexander Schranz <alexander@sulu.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Schema prefix
    |--------------------------------------------------------------------------
    |
    | Define the prefix used for the index names to avoid conflicts.
    */

    'prefix' => '',

    /*
    |--------------------------------------------------------------------------
    | Schema configs
    |--------------------------------------------------------------------------
    |
    | Define different directories for the schema loader.
    */

    'schemas' => [
        'app' => [
            'dir' => resource_path('schemas'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | engines
    |--------------------------------------------------------------------------
    |
    | Directory where the latte templates can be found.
    */

    'engines' => [
        'default' => [
            'adapter' => env(
                'ENGINE_URL',
                'meilisearch://127.0.0.1:7700',
            ),
        ],
    ],
];
