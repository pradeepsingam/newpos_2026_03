<?php

namespace App\Support\Plugins;

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class PluginManifest
{
    public function __construct(
        protected array $data,
        protected string $basePath
    ) {
    }

    public static function fromFile(string $manifestFile): self
    {
        if (! is_file($manifestFile)) {
            throw ValidationException::withMessages([
                'plugin' => 'The plugin package is missing plugin.json.',
            ]);
        }

        $payload = json_decode((string) file_get_contents($manifestFile), true);

        if (! is_array($payload)) {
            throw ValidationException::withMessages([
                'plugin' => 'The plugin.json file is not valid JSON.',
            ]);
        }

        foreach (['name', 'slug', 'version', 'provider', 'signature'] as $field) {
            if (blank(Arr::get($payload, $field))) {
                throw ValidationException::withMessages([
                    'plugin' => "The plugin.json file must include {$field}.",
                ]);
            }
        }

        return new self($payload, dirname($manifestFile));
    }

    public function name(): string
    {
        return (string) $this->data['name'];
    }

    public function slug(): string
    {
        return (string) $this->data['slug'];
    }

    public function version(): string
    {
        return (string) $this->data['version'];
    }

    public function description(): ?string
    {
        return Arr::get($this->data, 'description');
    }

    public function provider(): string
    {
        return (string) $this->data['provider'];
    }

    public function signature(): string
    {
        return (string) $this->data['signature'];
    }

    public function routePrefix(): string
    {
        return (string) Arr::get($this->data, 'route_prefix', $this->slug());
    }

    public function routesPath(): ?string
    {
        return $this->resolveOptionalPath(Arr::get($this->data, 'paths.routes'));
    }

    public function viewsPath(): ?string
    {
        return $this->resolveOptionalPath(Arr::get($this->data, 'paths.views'));
    }

    public function providersPath(): ?string
    {
        return $this->resolveOptionalPath(Arr::get($this->data, 'paths.providers'));
    }

    public function migrationsPath(): ?string
    {
        return $this->resolveOptionalPath(Arr::get($this->data, 'paths.migrations'));
    }

    public function tables(): array
    {
        return Arr::get($this->data, 'tables', []);
    }

    public function all(): array
    {
        return $this->data;
    }

    public function unsignedPayload(): string
    {
        $payload = $this->data;
        unset($payload['signature']);

        ksort($payload);

        return (string) json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function validatePackageStructure(): void
    {
        if ($this->providersPath() !== null && ! is_dir($this->providersPath())) {
            throw ValidationException::withMessages([
                'plugin' => 'The plugin providers path declared in plugin.json does not exist.',
            ]);
        }

        if ($this->routesPath() !== null && ! is_file($this->routesPath())) {
            throw ValidationException::withMessages([
                'plugin' => 'The plugin routes file declared in plugin.json does not exist.',
            ]);
        }

        if ($this->viewsPath() !== null && ! is_dir($this->viewsPath())) {
            throw ValidationException::withMessages([
                'plugin' => 'The plugin views path declared in plugin.json does not exist.',
            ]);
        }

        if ($this->migrationsPath() !== null && ! is_dir($this->migrationsPath())) {
            throw ValidationException::withMessages([
                'plugin' => 'The plugin migrations path declared in plugin.json does not exist.',
            ]);
        }
    }

    protected function resolveOptionalPath(?string $relativePath): ?string
    {
        if (blank($relativePath)) {
            return null;
        }

        return $this->basePath . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);
    }
}
