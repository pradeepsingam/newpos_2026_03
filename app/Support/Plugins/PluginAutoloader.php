<?php

namespace App\Support\Plugins;

class PluginAutoloader
{
    protected static array $registered = [];

    public static function register(string $namespacePrefix, string $basePath): void
    {
        $key = $namespacePrefix . '|' . $basePath;

        if (isset(static::$registered[$key])) {
            return;
        }

        spl_autoload_register(function (string $class) use ($namespacePrefix, $basePath): void {
            $prefix = rtrim($namespacePrefix, '\\') . '\\';

            if (! str_starts_with($class, $prefix)) {
                return;
            }

            $relativeClass = substr($class, strlen($prefix));
            $file = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

            if (is_file($file)) {
                require_once $file;
            }
        });

        static::$registered[$key] = true;
    }
}
