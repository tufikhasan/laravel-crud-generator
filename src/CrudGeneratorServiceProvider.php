<?php

namespace TufikHasan\CrudGenerator;

use Illuminate\Support\ServiceProvider;
use TufikHasan\CrudGenerator\Commands\MakeActionCommand;
use TufikHasan\CrudGenerator\Commands\MakeCrudCommand;
use TufikHasan\CrudGenerator\Commands\MakeCrudControllerCommand;
use TufikHasan\CrudGenerator\Commands\MakeCrudRequestCommand;
use TufikHasan\CrudGenerator\Commands\MakeCrudResourceCommand;
use TufikHasan\CrudGenerator\Commands\MakeCrudServiceCommand;
use TufikHasan\CrudGenerator\Commands\MakeCrudViewsCommand;
use TufikHasan\CrudGenerator\Commands\MakeDtoCommand;

class CrudGeneratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge package config so users don't have to publish it to use defaults
        $this->mergeConfigFrom(
            __DIR__ . '/../config/crud-generator.php',
            'crud-generator'
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeCrudCommand::class,
                MakeDtoCommand::class,
                MakeActionCommand::class,
                MakeCrudServiceCommand::class,
                MakeCrudRequestCommand::class,
                MakeCrudControllerCommand::class,
                MakeCrudResourceCommand::class,
                MakeCrudViewsCommand::class,
            ]);

            // php artisan vendor:publish --tag=crud-generator-config
            $this->publishes([
                __DIR__ . '/../config/crud-generator.php' => config_path('crud-generator.php'),
            ], 'crud-generator-config');

            // php artisan vendor:publish --tag=crud-generator-stubs
            $this->publishes([
                __DIR__ . '/../stubs' => base_path('stubs/crud'),
            ], 'crud-generator-stubs');

            // Publish both at once
            $this->publishes([
                __DIR__ . '/../config/crud-generator.php' => config_path('crud-generator.php'),
                __DIR__ . '/../stubs' => base_path('stubs/crud'),
            ], 'crud-generator');
        }
    }
}
