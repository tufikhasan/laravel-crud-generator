<?php

declare(strict_types=1);

namespace TufikHasan\CrudGenerator\Commands;

class MakeCrudViewsCommand extends BaseCrudCommand
{
    protected $signature = 'make:crud-views
        {name : The model name (e.g. Category)}
        {--force : Overwrite existing files}';

    protected $description = 'Generate Blade view files (index, create, edit) for the admin panel';

    public function handle(): int
    {
        $name  = (string) $this->argument('name');
        $force = (bool) $this->option('force');

        $renderer   = $this->makeRenderer($name);
        $modelNames = $renderer->get('{{ model_names }}'); // e.g. "categories"

        $views = [
            'index'  => 'views/index.blade.stub',
            'create' => 'views/create.blade.stub',
            'edit'   => 'views/edit.blade.stub',
        ];

        $created = [];
        $skipped = [];

        foreach ($views as $viewName => $stubFile) {
            $content    = $renderer->renderStub($stubFile);
            $targetPath = $this->resolveViewPath($modelNames, "{$viewName}.blade.php");
            $written    = $renderer->write($targetPath, $content, $force);

            $written ? $created[] = $viewName : $skipped[] = $viewName;
        }

        if (!empty($created)) {
            $this->components->info('Blade views created: ' . implode(', ', $created));
            $this->components->twoColumnDetail('Directory', $this->relativePath($this->resolveViewPath($modelNames)));
        }

        if (!empty($skipped)) {
            $this->components->warn(
                'Skipped (already exist): ' . implode(', ', $skipped) . '. Use --force to overwrite.'
            );
        }

        return self::SUCCESS;
    }
}
