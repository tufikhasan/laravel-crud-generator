<?php

namespace TufikHasan\CrudGenerator\Commands;

class MakeCrudServiceCommand extends BaseCrudCommand
{
    protected $signature = 'make:crud-service
        {name : The model name (e.g. Category)}
        {--force : Overwrite existing file}';

    protected $description = 'Generate a Service class with create(), update(), and delete() methods';

    public function handle(): int
    {
        $name = (string) $this->argument('name');
        $force = (bool) $this->option('force');

        $renderer = $this->makeRenderer($name);
        $model = $renderer->getModelName();
        $className = "{$model}Service";
        $content = $renderer->renderStub('service.stub');
        $targetPath = $this->resolveAppPath('service', $model, "{$className}.php");
        $written = $renderer->write($targetPath, $content, $force);

        if ($written) {
            $this->components->info("Service [{$className}] created successfully.");
            $this->components->twoColumnDetail('File', $this->relativePath($targetPath));
        } else {
            $this->components->warn("Service [{$className}] already exists. Use --force to overwrite.");
        }

        return self::SUCCESS;
    }
}
