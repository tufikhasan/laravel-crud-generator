<?php

namespace TufikHasan\CrudGenerator\Commands;

class MakeCrudControllerCommand extends BaseCrudCommand
{
    protected $signature = 'make:crud-controller
        {name : The model name (e.g. Category)}
        {target : Target type: admin or api}
        {--pattern= : The architectural pattern to use (service or hybrid)}
        {--force : Overwrite existing file}';

    protected $description = 'Generate a Controller class (admin|api)';

    private const VALID_TARGETS = ['admin', 'api'];

    public function handle(): int
    {
        $name = (string) $this->argument('name');
        $target = strtolower((string) $this->argument('target'));
        $pattern = $this->option('pattern') ?: $this->crudConfig('pattern', 'service');
        $isSimple = in_array($pattern, ['service', 'repository'], true);
        $force = (bool) $this->option('force');

        if (!in_array($target, self::VALID_TARGETS, true)) {
            $this->components->error("Invalid target [{$target}]. Valid targets: " . implode(', ', self::VALID_TARGETS));
            return self::FAILURE;
        }

        $renderer = $this->makeRenderer($name);
        $model = $renderer->getModelName();
        $className = "{$model}Controller";
        $configKey = "controller_{$target}";
        
        if ($pattern === 'repository') {
            $stubName = "controller.{$target}.repository.stub";
        } else {
            $stubName = $isSimple ? "controller.{$target}.simple.stub" : "controller.{$target}.stub";
        }
        $content = $renderer->renderStub($stubName);
        $targetPath = $this->resolveAppPath($configKey, "{$className}.php");
        $written = $renderer->write($targetPath, $content, $force);

        $label = ucfirst($target) . " Controller [{$className}]";

        if ($written) {
            $this->components->info("{$label} created successfully.");
            $this->components->twoColumnDetail('File', $this->relativePath($targetPath));
        } else {
            $this->components->warn("{$label} already exists. Use --force to overwrite.");
        }

        return self::SUCCESS;
    }
}
