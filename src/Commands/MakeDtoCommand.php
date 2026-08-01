<?php

namespace TufikHasan\CrudGenerator\Commands;

class MakeDtoCommand extends BaseCrudCommand
{
    protected $signature = 'make:dto
        {name : The model name (e.g. Category)}
        {--force : Overwrite existing file}';

    protected $description = 'Generate a DTO class with readonly properties and fromRequest() / fromArray() factories';

    public function handle(): int
    {
        $name = (string) $this->argument('name');
        $force = (bool) $this->option('force');

        $renderer = $this->makeRenderer($name);
        $model = $renderer->getModelName();
        $content = $renderer->renderStub('dto.stub');
        $targetPath = $this->resolveAppPath('dto', $model, "{$model}Data.php");

        $written = $renderer->write($targetPath, $content, $force);

        if ($written) {
            $this->components->info("DTO [{$model}Data] created successfully.");
            $this->components->twoColumnDetail('File', $this->relativePath($targetPath));
        } else {
            $this->components->warn("DTO [{$model}Data] already exists. Use --force to overwrite.");
        }

        return self::SUCCESS;
    }
}
