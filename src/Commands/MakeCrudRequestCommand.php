<?php

namespace TufikHasan\CrudGenerator\Commands;

class MakeCrudRequestCommand extends BaseCrudCommand
{
    protected $signature = 'make:crud-request
        {name : The model name (e.g. Category)}
        {type : Request type: store or update}
        {--force : Overwrite existing file}';

    protected $description = 'Generate a Form Request class (store|update)';

    private const VALID_TYPES = ['store', 'update'];

    public function handle(): int
    {
        $name = (string) $this->argument('name');
        $type = strtolower((string) $this->argument('type'));
        $force = (bool) $this->option('force');

        if (!in_array($type, self::VALID_TYPES, true)) {
            $this->components->error("Invalid type [{$type}]. Valid types: " . implode(', ', self::VALID_TYPES));
            return self::FAILURE;
        }

        $renderer = $this->makeRenderer($name);
        $model = $renderer->getModelName();
        $className = ucfirst($type) . $model . 'Request';
        $content = $renderer->renderStub("request.{$type}.stub");
        $targetPath = $this->resolveAppPath('request', $model, "{$className}.php");
        $written = $renderer->write($targetPath, $content, $force);

        if ($written) {
            $this->components->info("Request [{$className}] created successfully.");
            $this->components->twoColumnDetail('File', $this->relativePath($targetPath));
        } else {
            $this->components->warn("Request [{$className}] already exists. Use --force to overwrite.");
        }

        return self::SUCCESS;
    }
}
