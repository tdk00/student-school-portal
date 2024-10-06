<?php

return [
    'paths' => [
        'docs' => storage_path('api-docs'),
        'docs_json' => 'api-docs.json',
        'annotations' => [
            base_path('app'),
        ],
    ],
    'generate_always' => true,
    'swagger_version' => '3.0',
    'constants' => [
        'SWAGGER_VERSION' => env('SWAGGER_VERSION', '1.0.0'),
        'SWAGGER_TITLE' => env('SWAGGER_TITLE', 'API Documentation'),
        'SWAGGER_DESCRIPTION' => env('SWAGGER_DESCRIPTION', 'API Documentation'),
    ],
];
