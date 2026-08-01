<?php

declare(strict_types=1);

namespace TufikHasan\CrudGenerator\Commands;

class MakeCrudResourceCommand extends BaseCrudCommand
{
    protected $signature = 'make:crud-resource
        {name : The model name (e.g. Category)}
        {--force : Overwrite existing file}';

    protected $description = 'Generate an API Resource class';

    public function handle(): int
    {
        $name  = (string) $this->argument('name');
        $force = (bool) $this->option('force');

        $renderer   = $this->makeRenderer($name);
        $model      = $renderer->getModelName();
        $className  = "{$model}Resource";
        $content    = $renderer->renderStub('resource.stub');
        $targetPath = $this->resolveAppPath('resource', $model, "{$className}.php");
        $written    = $renderer->write($targetPath, $content, $force);

        if ($written) {
            $this->components->info("Resource [{$className}] created successfully.");
            $this->components->twoColumnDetail('File', $this->relativePath($targetPath));
        } else {
            $this->components->warn("Resource [{$className}] already exists. Use --force to overwrite.");
        }

        return self::SUCCESS;
    }
}
