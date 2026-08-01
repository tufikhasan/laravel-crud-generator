<?php

namespace TufikHasan\CrudGenerator\Commands;

use TufikHasan\CrudGenerator\Support\StubRenderer;

class MakeCrudCommand extends BaseCrudCommand
{
    protected $signature = 'make:crud
        {name : The model name in StudlyCase (e.g. Category, ProductVariant)}
        {--api : Also generate API controller, API resource, and API routes}
        {--skip-model : Skip generating the Eloquent model and migration (useful if the model already exists)}
        {--force : Overwrite existing files}';

    protected $description = 'Generate full enterprise CRUD scaffolding (DTO → Actions → Service → Requests → Controller → Views → Routes)';

    public function handle(): int
    {
        $name = (string) $this->argument('name');
        $withApi = (bool) $this->option('api');
        $skipModel = (bool) $this->option('skip-model');
        $force = (bool) $this->option('force');

        $renderer = $this->makeRenderer($name);
        $model = $renderer->getModelName();

        $this->newLine();
        $this->components->info("Generating enterprise CRUD for [{$model}]...");
        $this->newLine();

        $opts = ['name' => $name, '--force' => $force];

        // 1. DTO
        $this->runSub('make:dto', $opts, '✓ DTO');

        // 2. Actions
        $this->runSub('make:action', $opts + ['type' => 'store'], '✓ Store Action');
        $this->runSub('make:action', $opts + ['type' => 'update'], '✓ Update Action');
        $this->runSub('make:action', $opts + ['type' => 'delete'], '✓ Delete Action');

        // 3. Service
        $this->runSub('make:crud-service', $opts, '✓ Service');

        // 4. Form Requests
        $this->runSub('make:crud-request', $opts + ['type' => 'store'], '✓ Store Request');
        $this->runSub('make:crud-request', $opts + ['type' => 'update'], '✓ Update Request');

        // 5. Admin Controller
        $this->runSub('make:crud-controller', $opts + ['target' => 'admin'], '✓ Admin Controller');

        // 6. API (optional)
        if ($withApi) {
            $this->runSub('make:crud-resource', $opts, '✓ API Resource');
            $this->runSub('make:crud-controller', $opts + ['target' => 'api'], '✓ API Controller');
        }

        // 7. Blade Views
        $this->runSub('make:crud-views', $opts, '✓ Blade Views (index, create, edit)');

        // 8. Routes
        $this->appendRoutes($renderer, $withApi);
        $this->components->twoColumnDetail('<fg=green>✓ Routes appended</>', '');

        // 9. Model + Migration (always, unless --skip-model is passed)
        if (!$skipModel) {
            $this->generateModelAndMigration($model, $force);
        } else {
            $this->components->twoColumnDetail('<fg=yellow>~ Model + Migration skipped</>', '(--skip-model)');
        }

        $this->newLine();
        $this->components->info("CRUD for [{$model}] generated successfully!");

        $webPrefix = (string) $this->crudConfig('routes.web_prefix', 'dashboard');
        $namePrefix = (string) $this->crudConfig('routes.name_prefix', 'admin');
        $kebab = $renderer->get('{{ model-names }}');

        if ($withApi) {
            $this->line("  <fg=cyan>API routes:</>  /api/{$kebab}");
        }
        $this->line("  <fg=cyan>Admin routes:</> /{$webPrefix}/{$kebab}");
        $this->line("  <fg=cyan>Route names:</>  {$namePrefix}.{$renderer->get('{{ model_names }}')}.{index|create|store|edit|update|destroy}");
        $this->newLine();
        if (!$skipModel) {
            $this->line('  <fg=yellow>Next steps:</>');
            $this->line("  1. Update your migration in <fg=cyan>database/migrations/</>");
            $this->line("  2. Fill in the <fg=cyan>\$fillable</> array on the {$model} model");
            $this->line("  3. Add fields to the DTO and Service");
            $this->newLine();
        }

        return self::SUCCESS;
    }

    protected function runSub(string $command, array $arguments, string $label): void
    {
        $this->callSilent($command, $arguments);
        $this->components->twoColumnDetail("<fg=green>{$label}</>", '');
    }

    protected function generateModelAndMigration(string $model, bool $force): void
    {
        $modelPath = app_path("Models/{$model}.php");

        if (file_exists($modelPath) && !$force) {
            // Model exists — create only the migration
            $this->call('make:migration', [
                'name' => 'create_' . strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model)) . 's_table',
                '--create' => strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model)) . 's',
            ]);
            $this->components->twoColumnDetail('<fg=green>✓ Migration</>', '(model already existed, only migration created)');
        } else {
            // Create both model and migration together
            $args = ['name' => $model, '-m' => true];
            if ($force) {
                $args['--force'] = true;
            }
            $this->call('make:model', $args);
            $this->components->twoColumnDetail('<fg=green>✓ Model + Migration</>', '');
        }
    }

    protected function appendRoutes(StubRenderer $renderer, bool $withApi): void
    {
        $model = $renderer->getModelName();
        $modelNames = $renderer->get('{{ model-names }}');   // kebab plural, e.g. "product-variants"
        $modelSnake = $renderer->get('{{ model_names }}');   // snake plural, e.g. "product_variants"
        $namespace = $renderer->getRootNamespace();

        // ── Config values ──────────────────────────────────────────────────
        $webPrefix = (string) $this->crudConfig('routes.web_prefix', 'dashboard');
        $namePrefix = (string) $this->crudConfig('routes.name_prefix', 'admin');
        $apiPrefix = (string) $this->crudConfig('routes.api_prefix', '');

        // ── Web (admin) route ──────────────────────────────────────────────
        $adminController = "\\{$namespace}\\Http\\Controllers\\Admin\\{$model}Controller";
        $routeUri = $webPrefix ? "{$webPrefix}/{$modelNames}" : $modelNames;

        $webRoute = "\n// {$model} CRUD\n"
            . "Route::resource('{$routeUri}', {$adminController}::class)\n"
            . "    ->names('{$namePrefix}.{$modelSnake}');\n";

        $webPath = base_path('routes/web.php');
        if (file_exists($webPath) && !str_contains((string) file_get_contents($webPath), "{$namePrefix}.{$modelSnake}")) {
            file_put_contents($webPath, $webRoute, FILE_APPEND);
        }

        // ── API route ──────────────────────────────────────────────────────
        if ($withApi) {
            $apiController = "\\{$namespace}\\Http\\Controllers\\Api\\{$model}Controller";
            $apiUri = $apiPrefix ? "{$apiPrefix}/{$modelNames}" : $modelNames;

            $apiRoute = "\n// {$model} API\n"
                . "Route::apiResource('{$apiUri}', {$apiController}::class);\n";

            $apiPath = base_path('routes/api.php');
            if (file_exists($apiPath) && !str_contains((string) file_get_contents($apiPath), "'{$apiUri}'")) {
                file_put_contents($apiPath, $apiRoute, FILE_APPEND);
            }
        }
    }
}
