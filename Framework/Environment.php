<?php

namespace Framework;

class Environment
{
    /**
     * Load environment variables from .env file
     *
     * @param string $path Path to the directory containing .env file
     * @return void
     */
    public static function load($path)
    {
        $file = rtrim($path, '/') . '/.env';

        if (!file_exists($file)) {
            return;
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Parse line
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);

                // Remove quotes if present
                if (strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) {
                    $value = substr($value, 1, -1);
                } elseif (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1) {
                    $value = substr($value, 1, -1);
                }

                // Replace ${VAR} with existing environment variables
                $value = preg_replace_callback('/\${([a-zA-Z0-9_]+)}/', function ($matches) {
                    return getenv($matches[1]) ?: $_ENV[$matches[1]] ?? $_SERVER[$matches[1]] ?? $matches[0];
                }, $value);

                // Set environment variable
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }

    /**
     * Get environment variable value
     *
     * @param string $key Variable name
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        return $value;
    }
}
