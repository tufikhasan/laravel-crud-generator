<?php

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
        'dto' => 'DTOs',
        'action' => 'Actions',
        'service' => 'Services',
        'request' => 'Http/Requests',
        'controller_admin' => 'Http/Controllers/Admin',
        'controller_api' => 'Http/Controllers/Api',
        'resource' => 'Http/Resources',
        'repository' => 'Repositories',
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
    | Default View Framework
    |--------------------------------------------------------------------------
    |
    | The default CSS framework used for generated Blade views.
    | Supported values: 'tailwind', 'bootstrap'
    |
    */
    'view' => 'tailwind',

    /*
    |--------------------------------------------------------------------------
    | View Layout Configuration
    |--------------------------------------------------------------------------
    |
    | Define how the generated views should extend your application's layout.
    | type: 'component' (e.g. <x-layouts.app>) or 'extend' (e.g. @extends('layouts.app'))
    | name: The name of the component or layout file.
    | section: If using 'extend', the name of the section to yield content into.
    |
    */
    'layout' => [
        'type' => 'component',
        'name' => 'layouts.dashboard',
        'section' => 'content',
    ],

    /*
    |--------------------------------------------------------------------------
    | Architectural Pattern
    |--------------------------------------------------------------------------
    |
    | The default architectural pattern to use.
    | Supported values: 'service', 'hybrid', 'repository'
    | - 'service'    : Controller -> Service -> Model (Simple)
    | - 'hybrid'     : Controller -> DTO -> Action -> Service -> Model (Advanced)
    | - 'repository' : Controller -> Service -> Repository -> Model
    |
    */
    'pattern' => 'service',

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | web_prefix   : URL prefix for admin routes. e.g. 'dashboard' (nullable)
    |                → Route::resource('dashboard/categories', ...)
    |
    | name_prefix  : Named route prefix. e.g. 'admin' (nullable)
    |                → route('admin.categories.index')
    |
    | api_prefix   : URL prefix for API routes (leave empty for no prefix).
    |                → Route::apiResource('categories', ...)
    |
    */
    'routes' => [
        'web_prefix' => env('CRUD_ROUTE_WEB_PREFIX', null),
        'name_prefix' => env('CRUD_ROUTE_NAME_PREFIX', null),
        'api_prefix' => env('CRUD_ROUTE_API_PREFIX', ''),
    ],

];
