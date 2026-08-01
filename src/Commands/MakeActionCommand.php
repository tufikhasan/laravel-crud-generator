<?php

declare(strict_types=1);

namespace TufikHasan\CrudGenerator\Commands;

class MakeActionCommand extends BaseCrudCommand
{
    protected $signature = 'make:action
        {name : The model name (e.g. Category)}
        {type : Action type: store, update, or delete}
        {--force : Overwrite existing file}';

    protected $description = 'Generate a single Action class (store|update|delete)';

    private const VALID_TYPES = ['store', 'update', 'delete'];

    public function handle(): int
    {
        $name  = (string) $this->argument('name');
        $type  = strtolower((string) $this->argument('type'));
        $force = (bool) $this->option('force');

        if (!in_array($type, self::VALID_TYPES, true)) {
            $this->components->error("Invalid type [{$type}]. Valid types: " . implode(', ', self::VALID_TYPES));
            return self::FAILURE;
        }

        $renderer   = $this->makeRenderer($name);
        $model      = $renderer->getModelName();
        $className  = ucfirst($type) . $model . 'Action';
        $content    = $renderer->renderStub("action.{$type}.stub");
        $targetPath = $this->resolveAppPath('action', $model, "{$className}.php");
        $written    = $renderer->write($targetPath, $content, $force);

        if ($written) {
            $this->components->info("Action [{$className}] created successfully.");
            $this->components->twoColumnDetail('File', $this->relativePath($targetPath));
        } else {
            $this->components->warn("Action [{$className}] already exists. Use --force to overwrite.");
        }

        return self::SUCCESS;
    }
}
