<?php

use Framework\Validation;

if (!function_exists('app')) {
    /**
     * Get an instance from the application container or the application itself
     * 
     * @param string|null $key
     * @return mixed
     */
    function app($key = null)
    {
        global $app;
        if ($key === null) {
            return $app;
        }
        return $app->make($key);
    }
}

if (!function_exists('env')) {
    /**
     * Get an environment variable
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        return $value;
    }
}

if (!function_exists('config')) {
    /**
     * Get a configuration value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function config($key, $default = null)
    {
        return app('config')->get($key, $default);
    }
}

if (!function_exists('view')) {
    /**
     * Render a view with Laravel-like syntax
     * 
     * @param string $name
     * @param array $data
     * @return \Framework\Response
     */
    function view($name, $data = [])
    {
        return \Framework\Response::view($name, $data);
    }
}

if (!function_exists('asset')) {
    /**
     * Get the URL for an asset
     * 
     * @param string $path
     * @return string
     */
    function asset($path)
    {
        return url('/' . ltrim($path, '/'));
    }
}

if (!function_exists('url')) {
    /**
     * Generate a URL
     * 
     * @param string $path
     * @return string
     */
    function url($path = '')
    {
        $baseUrl = env('APP_URL', '');
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('session')) {
    /**
     * Get the session instance, get a session value, or set a session value
     * 
     * @param string|null $key
     * @param mixed $value
     * @return mixed|\Framework\Session
     */
    function session($key = null, $value = null)
    {
        $session = app('session');

        // If no arguments, return the session instance
        if ($key === null) {
            return $session;
        }

        // If a value is provided, set the value
        if ($value !== null) {
            $session->set($key, $value);
            return $value;
        }

        // Otherwise, get the value
        return $session->get($key);
    }
}

if (!function_exists('redirect')) {
    /**
     * Create a redirect response
     * 
     * @param string $path
     * @return \Framework\Response
     */
    function redirect($path)
    {
        $response = app('response');
        return $response->redirect($path);
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generate a CSRF token field
     * 
     * @return string
     */
    function csrf_field()
    {
        $token = session('_token');

        if (!$token) {
            $token = bin2hex(random_bytes(32));
            session('_token', $token);
        }

        return '<input type="hidden" name="_token" value="' . $token . '">';
    }
}

if (!function_exists('validate')) {
    /**
     * Create a new validator instance.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $attributes
     * @return \Framework\Validation\Validator
     */
    function validate(array $data, array $rules, array $messages = [], array $attributes = [])
    {
        return new \Framework\Validation\Validator($data, $rules, $messages, $attributes);
    }
}

if (!function_exists('old')) {
    /**
     * Get old input value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function old($key, $default = '')
    {
        return session()->getOldInput($key, $default);
    }
}

if (!function_exists('has_error')) {
    /**
     * Check if a field has an error
     * 
     * @param string $field
     * @return bool
     */
    function has_error($field)
    {
        $errors = session('_errors');
        return isset($errors) && isset($errors[$field]) && !empty($errors[$field]);
    }
}

if (!function_exists('error')) {
    /**
     * Get error message for a field
     * 
     * @param string $field
     * @return string
     */
    function error($field)
    {
        $errors = session('_errors');
        return $errors[$field] ?? '';
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get the CSRF token
     * 
     * @return string
     */
    function csrf_token()
    {
        $token = session('_token');

        if (!$token) {
            $token = bin2hex(random_bytes(32));
            session('_token', $token);
        }

        return $token;
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

        return \App\Models\User::find($userId);
    }
}

// Add Laravel-like view helpers
if (!function_exists('view_path')) {
    /**
     * Get the path to the views directory
     * 
     * @param string $path
     * @return string
     */
    function view_path($path = '')
    {
        return base_path('resources/views/' . ltrim($path, '/'));
    }
}

if (!function_exists('base_path')) {
    /**
     * Get the path to the base directory
     * 
     * @param string $path
     * @return string
     */
    function base_path($path = '')
    {
        return dirname(__DIR__) . '/' . ltrim($path, '/');
    }
}

if (!function_exists('app_path')) {
    /**
     * Get the path to the application directory
     * 
     * @param string $path
     * @return string
     */
    function app_path($path = '')
    {
        return base_path('app/' . ltrim($path, '/'));
    }
}

if (!function_exists('public_path')) {
    /**
     * Get the path to the public directory
     * 
     * @param string $path
     * @return string
     */
    function public_path($path = '')
    {
        return base_path('public/' . ltrim($path, '/'));
    }
}

if (!function_exists('storage_path')) {
    /**
     * Get the path to the storage directory
     * 
     * @param string $path
     * @return string
     */
    function storage_path($path = '')
    {
        return base_path('storage/' . ltrim($path, '/'));
    }
}

if (!function_exists('config_path')) {
    /**
     * Get the path to the config directory
     * 
     * @param string $path
     * @return string
     */
    function config_path($path = '')
    {
        return base_path('config/' . ltrim($path, '/'));
    }
}

if (!function_exists('resource_path')) {
    /**
     * Get the path to the resources directory
     * 
     * @param string $path
     * @return string
     */
    function resource_path($path = '')
    {
        return base_path('resources/' . ltrim($path, '/'));
    }
}

if (!function_exists('__')) {
    /**
     * Translate the given message
     * 
     * @param string $key
     * @param array $replace
     * @param string $locale
     * @return string
     */
    function __($key, $replace = [], $locale = null)
    {
        // Simple implementation - can be expanded later
        return $key;
    }
}

if (!function_exists('trans')) {
    /**
     * Translate the given message
     * 
     * @param string $key
     * @param array $replace
     * @param string $locale
     * @return string
     */
    function trans($key, $replace = [], $locale = null)
    {
        return __($key, $replace, $locale);
    }
}

// Blade template helpers
if (!function_exists('section')) {
    /**
     * Start a new template section
     * 
     * @param string $name
     * @return void
     */
    function section($name)
    {
        $view = app('view');
        $view->startSection($name);
    }
}

if (!function_exists('end_section')) {
    /**
     * End the current template section
     * 
     * @return void
     */
    function end_section()
    {
        $view = app('view');
        $view->endSection();
    }
}

if (!function_exists('yield_section')) {
    /**
     * Display the content of a section
     * 
     * @param string $name
     * @param string $default
     * @return void
     */
    function yield_section($name, $default = '')
    {
        $view = app('view');
        echo $view->yield($name, $default);
    }
}

if (!function_exists('has_section')) {
    /**
     * Check if a section exists
     * 
     * @param string $name
     * @return bool
     */
    function has_section($name)
    {
        $view = app('view');
        return $view->hasSection($name);
    }
}

if (!function_exists('include_view')) {
    /**
     * Include a partial view
     * 
     * @param string $view
     * @param array $data
     * @return void
     */
    function include_view($view, $data = [])
    {
        $renderer = app('view');
        echo $renderer->include($view, $data);
    }
}

if (!function_exists('extend')) {
    /**
     * Extend a layout
     * 
     * @param string $layout
     * @return void
     */
    function extend($layout)
    {
        $view = app('view');
        $view->setLayout($layout);
    }
}

if (!function_exists('component')) {
    /**
     * Render a component
     * 
     * @param string $name
     * @param array $data
     * @return void
     */
    function component($name, $data = [])
    {
        echo include_view('components.' . $name, $data);
    }
}

if (!function_exists('csrf')) {
    /**
     * Generate a CSRF token input field
     * 
     * @return string
     */
    function csrf()
    {
        return csrf_field();
    }
}

if (!function_exists('method')) {
    /**
     * Generate a hidden method input field
     * 
     * @param string $method
     * @return string
     */
    function method($method)
    {
        return '<input type="hidden" name="_method" value="' . $method . '">';
    }
}

if (!function_exists('method_field')) {
    /**
     * Generate a hidden method input field
     * 
     * @param string $method
     * @return string
     */
    function method_field($method)
    {
        return method($method);
    }
}

if (!function_exists('cache')) {
    /**
     * Get the cache instance or store an item in the cache.
     *
     * @param string|null $key
     * @param mixed $default
     * @param int|null $ttl
     * @return mixed|\Framework\Cache\Cache
     */
    function cache($key = null, $default = null, $ttl = null)
    {
        $cache = app('cache');

        if (is_null($key)) {
            return $cache;
        }

        if (is_array($key)) {
            return $cache->setMultiple($key, $ttl);
        }

        if (func_num_args() === 1) {
            return $cache->get($key);
        }

        if (is_null($ttl)) {
            return $cache->get($key, $default);
        }

        $cache->set($key, $default, $ttl);

        return $default;
    }
}

if (!function_exists('database_path')) {
    /**
     * Get the path to the database directory.
     *
     * @param string $path
     * @return string
     */
    function database_path($path = '')
    {
        return base_path('database/' . ltrim($path, '/'));
    }
}

if (!function_exists('migrate')) {
    /**
     * Run database migrations.
     *
     * @param array $options
     * @return array
     */
    function migrate(array $options = [])
    {
        $connection = app('db');
        $repository = new \Framework\Database\Migration\MigrationRepository($connection);
        $migrator = new \Framework\Database\Migration\Migrator($connection, $repository);

        if (isset($options['path'])) {
            $migrator->setPath($options['path']);
        }

        return $migrator->run();
    }
}

if (!function_exists('migrate_rollback')) {
    /**
     * Rollback database migrations.
     *
     * @param array $options
     * @return array
     */
    function migrate_rollback(array $options = [])
    {
        $connection = app('db');
        $repository = new \Framework\Database\Migration\MigrationRepository($connection);
        $migrator = new \Framework\Database\Migration\Migrator($connection, $repository);

        if (isset($options['path'])) {
            $migrator->setPath($options['path']);
        }

        return $migrator->rollback();
    }
}

if (!function_exists('make_migration')) {
    /**
     * Create a new migration file.
     *
     * @param string $name
     * @param string $table
     * @param bool $create
     * @return string
     */
    function make_migration($name, $table = null, $create = false)
    {
        $creator = new \Framework\Database\Migration\MigrationCreator();
        return $creator->create($name, database_path('migrations'), $table, $create);
    }
}

if (!function_exists('library')) {
    /**
     * Truy cập đến LibraryManager
     * 
     * @return \Framework\Library\LibraryManager
     */
    function library()
    {
        return app('library');
    }
}

if (!function_exists('Library')) {
    /**
     * Sử dụng trực tiếp directive @Library trong Blade
     * Đánh dấu thư viện để tải, nhưng không trả về nội dung
     * 
     * @param string $name Tên thư viện cần load hoặc 'js' để đánh dấu chỗ chèn JS
     * @return string
     */
    function Library($name = null)
    {
        if (!$name) {
            return '';
        }

        if ($name === 'js') {
            return '<!-- js -->'; // Đánh dấu vị trí chèn JS
        }

        if (app()->has('library')) {
            app('library')->load($name);
        }

        return '';
    }
}

/**
 * Tạo thông báo toast và lưu vào session flash
 * 
 * @param string $message Nội dung thông báo
 * @param string $type Loại thông báo: success, error, warning, info
 * @param string $title Tiêu đề thông báo
 * @param int $duration Thời gian hiển thị (ms)
 * @return void
 */
function toast($message, $type = 'info', $title = null, $duration = 5000)
{
    // Kiểm tra nếu session chưa được khởi tạo
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Tiêu đề mặc định dựa theo loại thông báo
    if ($title === null) {
        switch ($type) {
            case 'success':
                $title = 'Thành công';
                break;
            case 'error':
                $title = 'Lỗi';
                break;
            case 'warning':
                $title = 'Cảnh báo';
                break;
            default:
                $title = 'Thông báo';
                break;
        }
    }

    // Lưu thông báo vào session flash
    $_SESSION['_toarts'] = [
        'message' => $message,
        'type' => $type,
        'title' => $title,
        'duration' => $duration
    ];

    // Đảm bảo session được lưu ngay lập tức
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_write_close();
        session_start();
    }
}

/**
 * Tạo thông báo thành công
 * 
 * @param string $message Nội dung thông báo
 * @param string $title Tiêu đề thông báo
 * @param int $duration Thời gian hiển thị (ms)
 * @return void
 */
function toast_success($message, $title = null, $duration = 5000)
{
    toast($message, 'success', $title, $duration);
}

/**
 * Tạo thông báo lỗi
 * 
 * @param string $message Nội dung thông báo
 * @param string $title Tiêu đề thông báo
 * @param int $duration Thời gian hiển thị (ms)
 * @return void
 */
function toast_error($message, $title = null, $duration = 5000)
{
    toast($message, 'error', $title, $duration);
}

/**
 * Tạo thông báo cảnh báo
 * 
 * @param string $message Nội dung thông báo
 * @param string $title Tiêu đề thông báo
 * @param int $duration Thời gian hiển thị (ms)
 * @return void
 */
function toast_warning($message, $title = null, $duration = 5000)
{
    toast($message, 'warning', $title, $duration);
}

/**
 * Tạo thông báo thông tin
 * 
 * @param string $message Nội dung thông báo
 * @param string $title Tiêu đề thông báo
 * @param int $duration Thời gian hiển thị (ms)
 * @return void
 */
function toast_info($message, $title = null, $duration = 5000)
{
    toast($message, 'info', $title, $duration);
}

/**
 * Tạo script khởi tạo Toarts từ session flash
 * 
 * @return string HTML script để khởi tạo Toarts
 */
function toasts_script()
{
    // Kiểm tra nếu session chưa được khởi tạo
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Nếu không có thông báo toast trong session thì trả về rỗng
    if (empty($_SESSION['_toarts'])) {
        return '';
    }

    // Lấy thông báo từ session
    $toast = $_SESSION['_toarts'];
    // Xóa thông báo khỏi session để không hiển thị lại
    unset($_SESSION['_toarts']);

    $script = '<script>';
    $script .= 'document.addEventListener("DOMContentLoaded", function() {
        console.log("Toasts script executing");
        if (typeof Toarts !== "undefined") {
            try {
                Toarts.create({
                    type: "' . $toast['type'] . '",
                    title: "' . addslashes($toast['title']) . '",
                    text: "' . addslashes($toast['message']) . '",
                    duration: ' . $toast['duration'] . '
                });
            } catch (error) {
                console.error("Error creating toast:", error);
            }
        } else {
            console.error("Toarts library not loaded. Please include the Toarts JS and CSS files.");
        }
    });';
    $script .= '</script>';

    return $script;
}