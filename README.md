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
 ✓ Blade Views (index, create, edit, form)
 ✓ Routes appended

  INFO  CRUD for [Category] generated successfully!

  API routes:  /api/categories
  Admin routes: /admin/categories
  Route names:  admin.categories.{index|create|store|edit|update|destroy}

  Next steps:
  1. Update your migration in database/migrations/
  2. Fill in the $fillable array on the Category model
  3. Add fields to the DTO and Service 
  4. Update validation rules in app/Http/Requests/Category
  5. Map fields in the toArray() method of app/Http/Resources/Category/CategoryResource.php
```

---

## Features

- ✅ Follows **SOLID principles** — Actions, Services, DTOs, Policies
- ✅ **Config-aware** paths — customize all output directories
- ✅ **Publishable stubs** — override any generated file template
- ✅ **Model + migration by default** — pass `--skip-model` if already exists
- ✅ Optional `--api` flag — API controller + Resource + routes
- ✅ Optional `--pattern` option — Choose between `service` (default), `hybrid`, or `repository` architectures
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

### Composer (Packagist)

```bash
composer require tufikhasan/laravel-crud-generator
```

The package registers itself automatically via Laravel's package auto-discovery.

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

# Generate CRUD using the advanced Hybrid pattern (DTO + Actions)
php artisan make:crud Category --pattern=hybrid

# Overwrite existing files
php artisan make:crud Product --force
```

---

## Commands Reference

### `make:crud` — Full Scaffolding

```bash
php artisan make:crud {name} [--api] [--skip-model] [--view=tailwind|bootstrap] [--force]
```

| Argument/Option | Description |
|-----------------|-------------|
| `name`          | Model name in StudlyCase (`Category`, `ProductVariant`) |
| `--api`         | Also generate API controller, API resource, and API routes |
| `--pattern`     | Architectural pattern (`service`, `hybrid`, or `repository`, overrides config) |
| `--skip-model`  | Skip model + migration (use when the model already exists) |
| `--view`        | The view framework (`tailwind` or `bootstrap`) |
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
| Form View | `resources/views/pages/admin/categories/form.blade.php` |
| Show View | `resources/views/pages/admin/categories/show.blade.php` |
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
php artisan make:crud-views Category [--view=tailwind|bootstrap]
```

Generates: `resources/views/pages/admin/categories/{index,create,edit,form}.blade.php` (Using Tailwind or Bootstrap stubs)

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
        'repository'       => 'Repositories',
    ],

    // Default Architectural Pattern ('service', 'hybrid', or 'repository')
    'pattern' => 'service',

    // Blade view output path, relative to resource_path('views')
    'view_path' => 'pages/admin',
    
    // Default View Framework ('tailwind' or 'bootstrap')
    'view' => 'tailwind',

    // Route generation settings
    'routes' => [
        'web_prefix'  => env('CRUD_ROUTE_WEB_PREFIX', null),
        'name_prefix' => env('CRUD_ROUTE_NAME_PREFIX', null),
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
| `paths.repository` | `Repositories` | Repository directory |
| `view` | `tailwind` | Default view framework (`tailwind` or `bootstrap`) |
| `view_path` | `pages/admin` | Relative to `resources/views/` |
| `pattern` | `service` | Architectural pattern (`service`, `hybrid`, or `repository`) |
| `routes.web_prefix` | `null` | URL prefix for admin routes (nullable, not required) |
| `routes.name_prefix` | `null` | Named route prefix (nullable, not required) |
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
    ├── edit.blade.stub
    ├── form.blade.stub
    └── show.blade.stub
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
| `{{ RouteWebPrefix }}` | `admin` *(from config)* |

---

## Architecture Overview

This package supports three enterprise-grade architectural patterns. A hybrid approach is recommended for most complex applications, while the service or repository pattern works great for standard entities.

### 1. Hybrid Pattern (`--pattern=hybrid`)

The best choice for complex entities (e.g., enterprise multi-vendor eCommerce, SaaS subscriptions, POS, inventory, accounting).

```text
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

### 2. Service Pattern (Default, or `--pattern=service`)

Excellent for basic entities like `Category`, `Brand`, `Unit`, `Country`, `City`, or `Color`. It skips the DTO and Action layers entirely.

```text
Request (Validation)
   └── Controller
         └── Service (Business Logic + Data Access)
               └── Model (Eloquent)
```

### 3. Repository Pattern (`--pattern=repository`)

A structured approach that separates data access logic from business logic. It introduces a Repository layer without the overhead of DTOs and Actions.

```text
Request (Validation)
   └── Controller
         └── Service (Business Logic)
               └── Repository (Data Access)
                     └── Model (Eloquent)
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
