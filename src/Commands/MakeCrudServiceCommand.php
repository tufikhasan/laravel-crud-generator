<?php

namespace TufikHasan\CrudGenerator\Commands;

class MakeCrudServiceCommand extends BaseCrudCommand
{
    protected $signature = 'make:crud-service
        {name : The model name (e.g. Category)}
        {--S|simple : Generate simple service (accepts array data instead of DTO)}
        {--force : Overwrite existing file}';

    protected $description = 'Generate a Service class with create(), update(), and delete() methods';

    public function handle(): int
    {
        $name = (string) $this->argument('name');
        $isSimple = (bool) $this->option('simple');
        $force = (bool) $this->option('force');

        $renderer = $this->makeRenderer($name);
        $model = $renderer->getModelName();
        $className = "{$model}Service";
        
        $stubName = $isSimple ? 'service.simple.stub' : 'service.stub';
        $content = $renderer->renderStub($stubName);
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
