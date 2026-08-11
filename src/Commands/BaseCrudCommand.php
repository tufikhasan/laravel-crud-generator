<?php

namespace TufikHasan\CrudGenerator\Commands;

use Illuminate\Console\Command;
use TufikHasan\CrudGenerator\Support\StubRenderer;

/**
 * Base class for all CRUD generator commands.
 * Provides shared config-aware helpers for path resolution,
 * namespace detection, stub rendering, and output formatting.
 */
abstract class BaseCrudCommand extends Command
{
    // ─── Config Helpers ──────────────────────────────────────────────────────

    /**
     * Read a value from the crud-generator config.
     */
    protected function crudConfig(string $key, mixed $default = null): mixed
    {
        return config("crud-generator.{$key}", $default);
    }

    /**
     * Resolve the application root namespace.
     * Prefers the config value; falls back to Laravel auto-detection.
     */
    protected function rootNamespace(): string
    {
        $configured = (string) $this->crudConfig('namespace', '');

        if (!empty($configured)) {
            return rtrim($configured, '\\');
        }

        return rtrim((string) $this->laravel->getNamespace(), '\\');
    }

    // ─── Path Helpers ─────────────────────────────────────────────────────────

    /**
     * Resolve a target app_path() using the configured directory for the given type.
     * Additional segments are appended (e.g. sub-folder, filename).
     *
     * @param  string  $type  Config key under 'paths.*' (e.g. 'dto', 'action', 'service')
     */
    protected function resolveAppPath(string $type, string ...$segments): string
    {
        $base = (string) $this->crudConfig("paths.{$type}", $type);

        return app_path(
            implode(DIRECTORY_SEPARATOR, array_filter([$base, ...$segments]))
        );
    }

    /**
     * Resolve a target resource_path('views/...') using the configured view_path.
     */
    protected function resolveViewPath(string ...$segments): string
    {
        $base = trim((string) $this->crudConfig('view_path', 'pages/admin'), '/');

        return resource_path(
            'views/' . implode('/', array_filter([$base, ...$segments]))
        );
    }

    // ─── Stub Helpers ─────────────────────────────────────────────────────────

    /**
     * Return the absolute path to the package's stubs directory.
     */
    protected function stubsPath(): string
    {
        return __DIR__ . '/../../stubs';
    }

    /**
     * Build a fully configured StubRenderer for the given model name.
     * Injects route-related tokens from config so stubs are config-aware.
     */
    protected function makeRenderer(string $name): StubRenderer
    {
        return new StubRenderer(
            modelName: $name,
            rootNamespace: $this->rootNamespace(),
            packageStubsPath: $this->stubsPath(),
            extraTokens: [
                '{{ RouteNamePrefix }}' => $this->crudConfig('routes.name_prefix') ? rtrim((string) $this->crudConfig('routes.name_prefix'), '.') . '.' : '',
                '{{ RouteWebPrefix }}' => (string) $this->crudConfig('routes.web_prefix'),
            ],
        );
    }

    // ─── Output Helpers ───────────────────────────────────────────────────────

    /**
     * Display a relative file path in component output.
     */
    protected function relativePath(string $absolutePath): string
    {
        return ltrim(str_replace(base_path(), '', $absolutePath), DIRECTORY_SEPARATOR);
    }

    /**
     * Write a file and output the result using component styling.
     */
    protected function writeAndReport(
        string $targetPath,
        string $content,
        string $label,
        bool $force = false,
    ): bool {
        $renderer = new class {
        };

        $written = (function () use ($targetPath, $content, $force) {
            if (file_exists($targetPath) && !$force) {
                return false;
            }
            $dir = dirname($targetPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            file_put_contents($targetPath, $content);
            return true;
        })();

        if ($written) {
            $this->components->info("{$label} created successfully.");
            $this->components->twoColumnDetail('File', $this->relativePath($targetPath));
        } else {
            $this->components->warn("{$label} already exists. Use --force to overwrite.");
        }

        return $written;
    }
}
