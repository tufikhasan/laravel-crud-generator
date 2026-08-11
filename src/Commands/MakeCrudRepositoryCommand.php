<?php

namespace TufikHasan\CrudGenerator\Commands;

class MakeCrudRepositoryCommand extends BaseCrudCommand
{
    protected $signature = 'make:crud-repository
        {name : The model name (e.g. Category)}
        {--force : Overwrite existing file}';

    protected $description = 'Generate a Repository class with basic CRUD data access methods';

    public function handle(): int
    {
        $name = (string) $this->argument('name');
        $force = (bool) $this->option('force');

        $renderer = $this->makeRenderer($name);
        $model = $renderer->getModelName();
        $className = "{$model}Repository";
        $interfaceName = "{$model}RepositoryInterface";

        // 1. Generate Interface
        $interfaceContent = $renderer->renderStub('repository.interface.stub');
        $interfacePath = $this->resolveAppPath('repository', "{$model}/{$interfaceName}.php");
        $writtenInterface = $renderer->write($interfacePath, $interfaceContent, $force);

        if ($writtenInterface) {
            $this->components->info("Interface [{$interfaceName}] created successfully.");
        } else {
            $this->components->warn("Interface [{$interfaceName}] already exists.");
        }

        // 2. Generate Concrete Repository
        $content = $renderer->renderStub('repository.stub');
        $targetPath = $this->resolveAppPath('repository', "{$model}/{$className}.php");
        $written = $renderer->write($targetPath, $content, $force);

        if ($written) {
            $this->components->info("Repository [{$className}] created successfully.");
            $this->components->twoColumnDetail('File', $this->relativePath($targetPath));
        } else {
            $this->components->warn("Repository [{$className}] already exists. Use --force to overwrite.");
        }

        // 3. Handle RepositoryServiceProvider
        $this->registerRepositoryBinding($model, $renderer);

        return self::SUCCESS;
    }

    protected function registerRepositoryBinding(string $model, $renderer): void
    {
        $providerPath = app_path('Providers/RepositoryServiceProvider.php');

        // Create provider if it doesn't exist
        if (!file_exists($providerPath)) {
            $providerContent = $renderer->renderStub('repository.provider.stub');
            $renderer->write($providerPath, $providerContent, false);
            $this->components->info("RepositoryServiceProvider created successfully.");
            $this->components->warn("Please ensure App\Providers\RepositoryServiceProvider is registered in your bootstrap/providers.php or config/app.php!");
        }

        $providerContent = file_get_contents($providerPath);
        
        $rootNamespace = rtrim($this->rootNamespace(), '\\');
        $repositoryDir = str_replace('/', '\\', $this->crudConfig('paths.repository', 'Repositories'));
        
        $interfaceFqn = "{$rootNamespace}\\{$repositoryDir}\\{$model}\\{$model}RepositoryInterface";
        $concreteFqn = "{$rootNamespace}\\{$repositoryDir}\\{$model}\\{$model}Repository";

        $binding = "        \$this->app->bind(\n            \\{$interfaceFqn}::class,\n            \\{$concreteFqn}::class\n        );";

        if (!str_contains($providerContent, "\\{$interfaceFqn}::class")) {
            $providerContent = preg_replace(
                '/(public function register\(\)(?:\s*:\s*void)?\s*\{)/',
                "$1\n{$binding}",
                $providerContent
            );
            file_put_contents($providerPath, $providerContent);
            $this->components->info("Binding added to RepositoryServiceProvider.");
        }
    }
}
