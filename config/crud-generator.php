<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Application Root Namespace
    |--------------------------------------------------------------------------
    |
    | The root namespace of your application. Leave empty ('') to auto-detect
    | from your application's composer.json (recommended).
    |
    | Example: 'App'
    |
    */
    'namespace' => env('CRUD_NAMESPACE', ''),

    /*
    |--------------------------------------------------------------------------
    | Generated File Paths
    |--------------------------------------------------------------------------
    |
    | Customize where each generated file type is placed, relative to
    | app_path(). Change these if your project uses a non-standard structure.
    |
    */
    'paths' => [
        'dto'              => 'DTOs',
        'action'           => 'Actions',
        'service'          => 'Services',
        'request'          => 'Http/Requests',
        'controller_admin' => 'Http/Controllers/Admin',
        'controller_api'   => 'Http/Controllers/Api',
        'resource'         => 'Http/Resources',
    ],

    /*
    |--------------------------------------------------------------------------
    | View Path
    |--------------------------------------------------------------------------
    |
    | The directory (relative to resource_path('views')) where generated
    | Blade views will be placed. A subdirectory named after the plural
    | model (e.g. "brands/") is automatically appended.
    |
    | Example: 'pages/admin'  →  resources/views/pages/admin/brands/
    |
    */
    'view_path' => 'pages/admin',

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | web_prefix   : URL prefix for admin routes. e.g. 'dashboard'
    |                → Route::resource('dashboard/categories', ...)
    |
    | name_prefix  : Named route prefix. e.g. 'admin'
    |                → route('admin.categories.index')
    |
    | api_prefix   : URL prefix for API routes (leave empty for no prefix).
    |                → Route::apiResource('categories', ...)
    |
    */
    'routes' => [
        'web_prefix'  => env('CRUD_ROUTE_WEB_PREFIX', 'dashboard'),
        'name_prefix' => env('CRUD_ROUTE_NAME_PREFIX', 'admin'),
        'api_prefix'  => env('CRUD_ROUTE_API_PREFIX', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Code Style
    |--------------------------------------------------------------------------
    |
    | strict_types : Whether to add `declare(strict_types=1);` to generated
    |                PHP files. Recommended for modern PHP projects.
    |
    */
    'strict_types' => (bool) env('CRUD_STRICT_TYPES', true),

];
