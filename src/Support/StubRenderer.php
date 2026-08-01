<?php

namespace TufikHasan\CrudGenerator\Support;

use Illuminate\Support\Str;
use RuntimeException;

class StubRenderer
{
    protected readonly string $modelName;
    protected readonly string $rootNamespace;
    protected readonly string $packageStubsPath;

    /** @var array<string, string> */
    protected array $tokens = [];

    /**
     * @param  array<string, string>  $extraTokens  Additional tokens merged into the replacement map
     */
    public function __construct(
        string $modelName,
        string $rootNamespace = 'App',
        string $packageStubsPath = '',
        array $extraTokens = [],
    ) {
        $this->modelName = Str::studly($modelName);
        $this->rootNamespace = rtrim($rootNamespace, '\\');
        $this->packageStubsPath = rtrim($packageStubsPath, '/');
        $this->buildTokens($extraTokens);
    }

    /**
     * Build the token-replacement map.
     *
     * @param  array<string, string>  $extra
     */
    protected function buildTokens(array $extra = []): void
    {
        $name = $this->modelName;

        $this->tokens = array_merge([
            // ── Model name variants ─────────────────────────────────────────
            '{{ ModelName }}' => $name,
            '{{ modelName }}' => Str::camel($name),
            '{{ model_name }}' => Str::snake($name),
            '{{ model-name }}' => Str::kebab($name),
            '{{ ModelNames }}' => (string) Str::pluralStudly($name),
            '{{ modelNames }}' => Str::camel(Str::plural($name)),
            '{{ model_names }}' => Str::snake(Str::plural($name)),
            '{{ model-names }}' => Str::kebab(Str::plural($name)),

            // ── Namespace ───────────────────────────────────────────────────
            '{{ RootNamespace }}' => $this->rootNamespace,

            // ── Route defaults (overridden by extraTokens if provided) ───────
            '{{ RouteNamePrefix }}' => 'admin',
            '{{ RouteWebPrefix }}' => 'dashboard',
        ], $extra);
    }

    /**
     * Replace all tokens in a content string.
     */
    public function render(string $content): string
    {
        return str_replace(
            array_keys($this->tokens),
            array_values($this->tokens),
            $content
        );
    }

    /**
     * Resolve stub content.
     *
     * Priority order:
     *  1. Published custom stub at  base_path('stubs/crud/{stubFile}')
     *  2. Package bundled stub
     */
    public function resolveStub(string $stubFile): string
    {
        // 1. User-published custom stubs (vendor:publish --tag=crud-generator-stubs)
        $publishedPath = base_path('stubs/crud/' . $stubFile);
        if (file_exists($publishedPath)) {
            return (string) file_get_contents($publishedPath);
        }

        // 2. Package bundled stubs
        $packagePath = $this->packageStubsPath . '/' . $stubFile;
        if (!file_exists($packagePath)) {
            throw new RuntimeException(
                "Stub file not found: [{$stubFile}].\n" .
                "Expected package stub at: {$packagePath}\n" .
                "Or published stub at: {$publishedPath}"
            );
        }

        return (string) file_get_contents($packagePath);
    }

    /**
     * Resolve + render a stub file.
     */
    public function renderStub(string $stubFile): string
    {
        return $this->render($this->resolveStub($stubFile));
    }

    /**
     * Write rendered content to the target path, creating directories as needed.
     *
     * @return bool  true if written, false if file already existed and force=false
     */
    public function write(string $targetPath, string $content, bool $force = false): bool
    {
        if (file_exists($targetPath) && !$force) {
            return false;
        }

        $directory = dirname($targetPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($targetPath, $content);

        return true;
    }

    // ─── Getters ─────────────────────────────────────────────────────────────

    public function getModelName(): string
    {
        return $this->modelName;
    }

    /** Retrieve any single token value by its placeholder key. */
    public function get(string $token): string
    {
        return $this->tokens[$token] ?? '';
    }

    public function getRootNamespace(): string
    {
        return $this->rootNamespace;
    }

    /** @return array<string, string> */
    public function getTokens(): array
    {
        return $this->tokens;
    }
}
