<?php

namespace TufikHasan\CrudGenerator\Commands;

class MakeCrudViewsCommand extends BaseCrudCommand
{
    protected $signature = 'make:crud-views
        {name : The model name (e.g. Category)}
        {--view= : The view framework (tailwind or bootstrap)}
        {--force : Overwrite existing files}';

    protected $description = 'Generate Blade view files (index, create, edit, show) for the admin panel';

    public function handle(): int
    {
        $name = (string) $this->argument('name');
        $force = (bool) $this->option('force');
        $viewType = $this->option('view') ?: $this->crudConfig('view', 'tailwind');

        $renderer = $this->makeRenderer($name);
        $modelNames = $renderer->get('{{ model_names }}'); // e.g. "categories"

        $views = [
            'index' => "views/{$viewType}/index.blade.stub",
            'create' => "views/{$viewType}/create.blade.stub",
            'edit' => "views/{$viewType}/edit.blade.stub",
            'show' => "views/{$viewType}/show.blade.stub",
            'form' => "views/{$viewType}/form.blade.stub",
        ];

        $created = [];
        $skipped = [];

        foreach ($views as $viewName => $stubFile) {
            $content = $renderer->renderStub($stubFile);
            $targetPath = $this->resolveViewPath($modelNames, "{$viewName}.blade.php");
            $written = $renderer->write($targetPath, $content, $force);

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
