# Laravel Enterprise CRUD Generator

[![Laravel](https://img.shields.io/badge/Laravel-11%20|%2012%20|%2013-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)](LICENSE)

> Generate **production-ready, enterprise-grade CRUD scaffolding** in seconds — following the **DTO → Action → Service → Controller** architecture.

One command. Thirteen files. Zero boilerplate.

```bash
php artisan make:crud Category --api
```

```
 INFO  Generating enterprise CRUD for [Category]...

 ✓ DTO
 ✓ Store Action
 ✓ Update Action
 ✓ Delete Action
 ✓ Service
 ✓ Store Request
 ✓ Update Request
 ✓ Admin Controller
 ✓ API Resource
 ✓ API Controller
 ✓ Blade Views (index, create, edit)
 ✓ Routes appended

 INFO  CRUD for [Category] generated successfully!
```

---

## Features

- ✅ Follows **SOLID principles** — Actions, Services, DTOs, Policies
- ✅ **Config-aware** paths — customize all output directories
- ✅ **Publishable stubs** — override any generated file template
- ✅ **Model + migration by default** — pass `--skip-model` if already exists
- ✅ Optional `--api` flag — API controller + Resource + routes
- ✅ `--force` flag to overwrite existing files
- ✅ **Individual commands** — generate only what you need
- ✅ Auto-registers via **Laravel package auto-discovery**
- ✅ Works with **Laravel 11, 12, and 13**

---

## Requirements

| Requirement | Version  |
|-------------|----------|
| PHP         | `^8.2`   |
| Laravel     | `^11|^12|^13` |

---

## Installation

### Via Composer (Packagist)

```bash
composer require tufikhasan/laravel-crud-generator
```

The package registers itself automatically via Laravel's package auto-discovery.

### Local Development (Path Repository)

Add to your project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/tufikhasan/laravel-crud-generator.git",
        }
    ],
    "require": {
        "tufikhasan/laravel-crud-generator": "dev-main"
    }
}
```

Then run:

```bash
composer update tufikhasan/laravel-crud-generator
```

---

## Quick Start

### Generate a full CRUD module

```bash
# Admin CRUD only (views + routes + model + migration)
php artisan make:crud Product

# Admin + API (controller, resource, routes)
php artisan make:crud Product --api

# Skip model/migration if the model already exists
php artisan make:crud Product --skip-model

# Overwrite existing files
php artisan make:crud Product --force
```

---

## Commands Reference

### `make:crud` — Full Scaffolding

```bash
php artisan make:crud {name} [--api] [--skip-model] [--force]
```

| Argument/Option | Description |
|-----------------|-------------|
| `name`          | Model name in StudlyCase (`Category`, `ProductVariant`) |
| `--api`         | Also generate API controller, API resource, and API routes |
| `--skip-model`  | Skip model + migration (use when the model already exists) |
| `--force`       | Overwrite existing files |

**Generated files (with `--api`):**

| File | Path |
|------|------|
| Model | `app/Models/Category.php` |
| Migration | `database/migrations/xxxx_create_categories_table.php` |
| DTO | `app/DTOs/Category/CategoryData.php` |
| Store Action | `app/Actions/Category/StoreCategoryAction.php` |
| Update Action | `app/Actions/Category/UpdateCategoryAction.php` |
| Delete Action | `app/Actions/Category/DeleteCategoryAction.php` |
| Service | `app/Services/Category/CategoryService.php` |
| Store Request | `app/Http/Requests/Category/StoreCategoryRequest.php` |
| Update Request | `app/Http/Requests/Category/UpdateCategoryRequest.php` |
| Admin Controller | `app/Http/Controllers/Admin/CategoryController.php` |
| API Resource | `app/Http/Resources/Category/CategoryResource.php` |
| API Controller | `app/Http/Controllers/Api/CategoryController.php` |
| Index View | `resources/views/pages/admin/categories/index.blade.php` |
| Create View | `resources/views/pages/admin/categories/create.blade.php` |
| Edit View | `resources/views/pages/admin/categories/edit.blade.php` |
| Routes | Appended to `routes/web.php` (and `routes/api.php` if `--api`) |

---

### Individual Commands

Use these when you need to generate only a single piece.

#### `make:dto`

```bash
php artisan make:dto Category
```

Generates: `app/DTOs/Category/CategoryData.php`

---

#### `make:action`

```bash
php artisan make:action Category store    # StoreCategoryAction.php
php artisan make:action Category update   # UpdateCategoryAction.php
php artisan make:action Category delete   # DeleteCategoryAction.php
```

---

#### `make:crud-service`

```bash
php artisan make:crud-service Category
```

Generates: `app/Services/Category/CategoryService.php`

---

#### `make:crud-request`

```bash
php artisan make:crud-request Category store    # StoreCategoryRequest.php
php artisan make:crud-request Category update   # UpdateCategoryRequest.php
```

---

#### `make:crud-controller`

```bash
php artisan make:crud-controller Category admin   # Admin/CategoryController.php
php artisan make:crud-controller Category api     # Api/CategoryController.php
```

---

#### `make:crud-resource`

```bash
php artisan make:crud-resource Category
```

Generates: `app/Http/Resources/Category/CategoryResource.php`

---

#### `make:crud-views`

```bash
php artisan make:crud-views Category
```

Generates: `resources/views/pages/admin/categories/{index,create,edit}.blade.php`

---

## Configuration

Publish the config file to customize all defaults:

```bash
php artisan vendor:publish --tag=crud-generator-config
```

This creates `config/crud-generator.php`:

```php
return [

    // Auto-detected from composer.json. Set to override.
    'namespace' => env('CRUD_NAMESPACE', ''),

    // Output paths, relative to app_path()
    'paths' => [
        'dto'              => 'DTOs',
        'action'           => 'Actions',
        'service'          => 'Services',
        'request'          => 'Http/Requests',
        'controller_admin' => 'Http/Controllers/Admin',
        'controller_api'   => 'Http/Controllers/Api',
        'resource'         => 'Http/Resources',
    ],

    // Blade view output path, relative to resource_path('views')
    'view_path' => 'pages/admin',

    // Route generation settings
    'routes' => [
        'web_prefix'  => env('CRUD_ROUTE_WEB_PREFIX', 'dashboard'),
        'name_prefix' => env('CRUD_ROUTE_NAME_PREFIX', 'admin'),
        'api_prefix'  => env('CRUD_ROUTE_API_PREFIX', ''),
    ],

];
```

### Configuration Reference

| Key | Default | Description |
|-----|---------|-------------|
| `namespace` | `''` (auto) | App root namespace. Auto-detected if empty. |
| `paths.dto` | `DTOs` | DTO directory relative to `app/` |
| `paths.action` | `Actions` | Action directory |
| `paths.service` | `Services` | Service directory |
| `paths.request` | `Http/Requests` | Form Request directory |
| `paths.controller_admin` | `Http/Controllers/Admin` | Admin controller directory |
| `paths.controller_api` | `Http/Controllers/Api` | API controller directory |
| `paths.resource` | `Http/Resources` | API Resource directory |
| `view_path` | `pages/admin` | Relative to `resources/views/` |
| `routes.web_prefix` | `dashboard` | URL prefix for admin routes |
| `routes.name_prefix` | `admin` | Named route prefix |
| `routes.api_prefix` | `''` | URL prefix for API routes |

---

## Customizing Stubs

### 1. Publish Stubs

```bash
# Publish stubs only
php artisan vendor:publish --tag=crud-generator-stubs

# Publish everything (config + stubs)
php artisan vendor:publish --tag=crud-generator
```

Stubs are copied to `stubs/crud/` in your project root:

```
stubs/crud/
├── dto.stub
├── action.store.stub
├── action.update.stub
├── action.delete.stub
├── service.stub
├── request.store.stub
├── request.update.stub
├── controller.admin.stub
├── controller.api.stub
├── resource.stub
└── views/
    ├── index.blade.stub
    ├── create.blade.stub
    └── edit.blade.stub
```

### 2. Edit a Stub

The generator **always checks your published stubs first**, then falls back to the package defaults.

Example — add a `Policy` check to the generated Store Request:

```php
// stubs/crud/request.store.stub

public function authorize(): bool
{
    return $this->user()->can('create', {{ ModelName }}::class);
}
```

### 3. Available Stub Tokens

Every stub supports these replacement tokens:

| Token | Category → |
|-------|-----------|
| `{{ ModelName }}` | `Category` |
| `{{ modelName }}` | `category` |
| `{{ model_name }}` | `category` |
| `{{ model-name }}` | `category` |
| `{{ ModelNames }}` | `Categories` |
| `{{ modelNames }}` | `categories` |
| `{{ model_names }}` | `categories` |
| `{{ model-names }}` | `categories` |
| `{{ RootNamespace }}` | `App` |
| `{{ RouteNamePrefix }}` | `admin` *(from config)* |
| `{{ RouteWebPrefix }}` | `dashboard` *(from config)* |

---

## Architecture Overview

This package generates code following a strict enterprise architecture:

```
HTTP Request
     │
     ▼
Controller  (HTTP only — validate, authorize, build DTO, call Action)
     │
     ▼
Form Request (validation + authorization)
     │
     ▼
DTO  (readonly value object — carries validated data)
     │
     ▼
Action  (one use-case — wraps service in DB::transaction)
     │
     ▼
Service  (reusable business logic — create / update / delete)
     │
     ▼
Model  (Eloquent)
```

### Example generated DTO

```php
class CategoryData
{
    public function __construct(
        public readonly string $name,
        public readonly bool $status,
    ) {}

    public static function fromRequest(StoreCategoryRequest|UpdateCategoryRequest $request): self
    {
        return new self(
            name:   $request->string('name')->toString(),
            status: $request->boolean('status'),
        );
    }
}
```

### Example generated Action

```php
class StoreCategoryAction
{
    public function __construct(
        private readonly CategoryService $service,
    ) {}

    public function execute(CategoryData $data): Category
    {
        return DB::transaction(
            fn () => $this->service->create($data)
        );
    }
}
```

---

## Publishing to Packagist

1. Push `packages/tufikhasan/laravel-crud-generator/` to its own GitHub repository.
2. Go to [packagist.org](https://packagist.org) → Submit Package → enter your GitHub URL.
3. Add a webhook for auto-updates (Packagist shows instructions).

Users install with:

```bash
composer require tufikhasan/laravel-crud-generator
```

---

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/my-feature`
3. Commit your changes
4. Push and open a Pull Request

---

## License

MIT © [Towfik Hasan](https://github.com/tufikhasan)
