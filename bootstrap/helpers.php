<?php

if (!function_exists('app')) {
    /**
     * Get the application instance or a service from it.
     *
     * @param  string|null  $key
     * @return mixed
     */
    function app($key = null)
    {
        global $app;

        if ($key === null) {
            return $app;
        }

        return $app->resolve($key);
    }
}

// Add this new function
if (!function_exists('base_path')) {
    /**
     * Get the path to the base of the installation.
     *
     * @param  string  $path
     * @return string
     */
    function base_path($path = '')
    {
        return app('paths.base') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('config')) {
    /**
     * Get a configuration value.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function config($key, $default = null)
    {
        return app('config')->get($key, $default);
    }
}

if (!function_exists('view')) {
    /**
     * Return a view response.
     *
     * @param  string  $template
     * @param  array  $data
     * @return \Framework\Response
     */
    function view($template, $data = [])
    {
        return \Framework\Response::view($template, $data);
    }
}
// debug
if (!function_exists('dd')) {
    /**
     * Dump the passed variables and end the script in a Laravel-like style.
     *
     * @param  mixed  $args
     * @return void
     */
    function dd(...$args)
    {
        // Bật output buffering để kiểm soát đầu ra
        ob_start();

        echo "<div style='font-family: monospace; background: #f8f8f8; padding: 15px; border: 1px solid #ccc; margin: 10px 0; color: #333;'>\n";

        foreach ($args as $index => $arg) {
            echo "<pre style='margin: 0 0 15px 0;'>";
            echo "<strong style='color: #0066cc;'>Argument " . ($index + 1) . ":</strong>\n";

            // Xử lý dữ liệu với định dạng giống Laravel
            if (is_null($arg)) {
                echo "<span style='color: #666;'>null</span>";
            } elseif (is_bool($arg)) {
                echo "<span style='color: #990099;'>" . ($arg ? 'true' : 'false') . "</span>";
            } elseif (is_string($arg)) {
                echo "<span style='color: #009900;'>\"" . htmlspecialchars($arg) . "\"</span>";
            } elseif (is_int($arg) || is_float($arg)) {
                echo "<span style='color: #cc0000;'>$arg</span>";
            } elseif (is_array($arg)) {
                echo "<span style='color: #666;'>array:" . count($arg) . "</span> ";
                print_r($arg);
            } elseif (is_object($arg)) {
                echo "<span style='color: #666;'>" . get_class($arg) . " object</span> ";
                print_r($arg);
            } else {
                echo "<span style='color: #666;'>unknown type</span> ";
                var_dump($arg);
            }

            echo "</pre>";
        }

        echo "</div>";
        ob_flush();
        die(1);
    }
}


if (!function_exists('redirect')) {
    /**
     * Return a redirect response.
     *
     * @param  string  $uri
     * @param  int  $status
     * @return \Framework\Response
     */
    function redirect($uri, $status = 302)
    {
        return (new \Framework\Response('', $status))
            ->setHeader('Location', $uri);
    }
}

if (!function_exists('session')) {
    /**
     * Get/set the session instance or a session value
     *
     * @param  string|null  $key
     * @param  mixed  $value
     * @return mixed|\Framework\Session
     */
    function session($key = null, $value = null)
    {
        $session = app('session');

        // If no key provided, return the session instance
        if ($key === null) {
            return $session;
        }

        // If value is provided, set the value and return it
        if ($value !== null) {
            $session->set($key, $value);
            return $value;
        }

        // Otherwise return the value for the key
        return $session->get($key);
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Generate and return a CSRF token.
     *
     * @return string
     */
    function csrf_token()
    {
        if (!isset($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generate a CSRF token hidden field.
     *
     * @return string
     */
    function csrf_field()
    {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('asset')) {
    /**
     * Generate a URL for an asset.
     *
     * @param  string  $path
     * @return string
     */
    function asset($path)
    {
        return '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    /**
     * Generate a URL for the application.
     *
     * @param  string  $path
     * @return string
     */
    function url($path = '')
    {
        $base = rtrim(env('APP_URL', ''), '/');
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('method_field')) {
    /**
     * Generate a form field for spoofing HTTP methods.
     *
     * @param  string  $method
     * @return string
     */
    function method_field($method)
    {
        return '<input type="hidden" name="_method" value="' . $method . '">';
    }
}

if (!function_exists('old')) {
    /**
     * Get a previous input value.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function old($key, $default = null)
    {
        return $_SESSION['_old_input'][$key] ?? $default;
    }
}

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        return \Framework\Environment::get($key, $default);
    }
}

if (!function_exists('auth')) {
    /**
     * Get the authenticated user
     *
     * @return \App\Models\User|null
     */
    function auth()
    {

        $userId = session('user_id');
        if (!$userId) {
            return null;
        }
        $user = \App\Models\User::find($userId);
        return $user;
    }
}

if (!function_exists('view_path')) {
    /**
     * Get the path to the views directory
     *
     * @param  string  $path
     * @return string
     */
    function view_path($path = '')
    {
        $viewsPath = app('paths.resources') . '/views';
        return $viewsPath . ($path ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('has_error')) {
    /**
     * Check if a field has a validation error
     *
     * @param  string  $field
     * @return bool
     */
    function has_error($field)
    {
        $errors = session('_errors');
        return isset($errors) && isset($errors[$field]);
    }
}
