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

        $content = $renderer->renderStub('repository.stub');
        $targetPath = $this->resolveAppPath('repository', "{$model}/{$className}.php");

        $written = $renderer->write($targetPath, $content, $force);

        if ($written) {
            $this->components->info("Repository [{$className}] created successfully.");
            $this->components->twoColumnDetail('File', $this->relativePath($targetPath));
        } else {
            $this->components->warn("Repository [{$className}] already exists. Use --force to overwrite.");
        }

        return self::SUCCESS;
    }
}
