<?php

namespace Framework;

use Framework\Template\BladeCompiler;
use Framework\Template\BladeDirectives;

class View
{
    protected $viewPath = '';
    protected $cachePath = '';
    protected $compiler;
    protected $layout = null;
    protected $sections = [];
    protected $sectionStack = [];

    public function __construct($viewPath = null)
    {
        // Use config or fall back to default path
        $config = config('view', []);

        // Use provided path, config path, or default to resources/views/
        $this->viewPath = $viewPath ?? ($config['paths'][0] ?? view_path());
        $this->cachePath = $config['compiled'] ?? storage_path('cache/views');
        $this->compiler = new BladeCompiler($this->cachePath);

        // Đăng ký các blade directive tùy chỉnh
        BladeDirectives::register($this->compiler);

        // Log the viewPath for debugging
        error_log("View path: " . $this->viewPath);
    }

    public function render($template, $data = [])
    {
        try {
            // Create storage directory if it doesn't exist
            if (!is_dir($this->cachePath)) {
                mkdir($this->cachePath, 0755, true);
            }

            $templatePath = $this->findTemplate($template);

            if (!$templatePath) {
                throw new \Exception("View template not found: $template");
            }

            // Compile the template if it uses Blade syntax
            if (strpos($templatePath, '.blade.php') !== false) {
                $templatePath = $this->compiler->compile($templatePath);
            }

            // Thiết lập biến toàn cục cho Blade
            global $__sections, $__currentSection, $__layout, $__componentPath, $slot;
            $__sections = [];
            $__currentSection = '';
            $__layout = null;
            $__componentPath = '';
            $slot = '';

            // Extract data to make variables available in the view
            extract($data);

            // Start output buffering
            ob_start();

            // Include the template
            include $templatePath;

            // Get the view content
            $content = ob_get_clean();

            // For debugging
            if (env('APP_ENV') === 'local' && env('APP_DEBUG') === true) {
                error_log("Rendering template: " . $template);
                error_log("Layout: " . ($__layout ?? 'none'));
                error_log("Sections: " . print_r($__sections, true));
            }

            // If there's an @extends directive in the view
            if (isset($__layout) && $__layout) {
                return $this->renderWithLayout($__layout, array_merge($data, ['content' => $content, '__sections' => $__sections ?? []]));
            }
            // If using a layout via method call or data parameter
            else if (isset($data['layout']) || $this->layout) {
                $layoutName = $data['layout'] ?? $this->layout;
                return $this->renderWithLayout($layoutName, array_merge($data, ['content' => $content]));
            }

            return $content;
        } catch (\Exception $e) {
            // Return more detailed error in development
            if (env('APP_ENV') === 'local') {
                return '<div style="color:red;padding:20px;border:1px solid red;margin:20px;font-family:monospace;">
                    <h2>View Error</h2>
                    <p>' . $e->getMessage() . '</p>
                    <h3>Template</h3>
                    <p>' . $template . '</p>
                    <h3>Stack Trace</h3>
                    <pre>' . $e->getTraceAsString() . '</pre>
                </div>';
            }

            // Generic error in production
            return 'Error rendering view: ' . $e->getMessage();
        }
    }

    protected function renderWithLayout($layout, $data)
    {
        $layoutPath = $this->findTemplate($layout);

        // Handle case where layout file doesn't exist more gracefully
        if (!$layoutPath) {
            error_log("Layout template not found: $layout, falling back to content only");
            return $data['content'] ?? '';
        }

        // Compile the layout if it uses Blade syntax
        if (strpos($layoutPath, '.blade.php') !== false) {
            $layoutPath = $this->compiler->compile($layoutPath);
        }

        // Thiết lập biến toàn cục cho Blade
        global $__sections, $__currentSection, $__layout, $__componentPath, $slot;
        $__sections = $data['__sections'] ?? [];
        $__currentSection = '';
        $__layout = null;
        $__componentPath = '';
        $slot = '';

        // Extract data to make variables available in the layout
        extract($data);

        // Start output buffering
        ob_start();

        // Include the layout
        include $layoutPath;

        // Return the final output
        return ob_get_clean();
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Find a template file using Laravel-style naming conventions
     */
    protected function findTemplate($template)
    {
        // If template is already a full path, return it
        if (file_exists($template)) {
            return $template;
        }

        // Convert dot notation to directory separator (Laravel style)
        $templatePath = str_replace('.', '/', $template);

        // Check if the view file exists with different extensions
        $possiblePaths = [
            $this->viewPath . '/' . $templatePath . '.blade.php',
            $this->viewPath . '/' . $templatePath . '.php',
            $this->viewPath . '/' . $templatePath
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        // If we got here, try a case-insensitive search for Windows environments
        $directory = dirname($this->viewPath . '/' . $templatePath);
        $filename = basename($templatePath);

        if (is_dir($directory)) {
            $files = scandir($directory);
            foreach ($files as $file) {
                if (
                    strtolower($file) === strtolower($filename . '.php') ||
                    strtolower($file) === strtolower($filename . '.blade.php')
                ) {
                    return $directory . '/' . $file;
                }
            }
        }

        // Additional debug information
        $debugInfo = "Template not found: $template\n";
        $debugInfo .= "View path: {$this->viewPath}\n";
        $debugInfo .= "Template path: $templatePath\n";
        $debugInfo .= "Checking directory: $directory\n";
        $debugInfo .= "Checking filename: $filename\n";
        $debugInfo .= "Possible paths:\n";

        foreach ($possiblePaths as $path) {
            $debugInfo .= " - $path" . (file_exists($path) ? " (exists)" : " (not found)") . "\n";
        }

        if (is_dir($directory)) {
            $debugInfo .= "Directory contents:\n";
            foreach (scandir($directory) as $file) {
                $debugInfo .= " - $file\n";
            }
        } else {
            $debugInfo .= "Directory does not exist: $directory\n";

            // Check if parent directory exists and list its contents
            $parentDir = dirname($directory);
            if (is_dir($parentDir)) {
                $debugInfo .= "Parent directory contents ($parentDir):\n";
                foreach (scandir($parentDir) as $file) {
                    $debugInfo .= " - $file\n";
                }
            }
        }

        error_log($debugInfo);

        return false;
    }

    /**
     * Start a section
     * 
     * @param string $name
     * @return void
     */
    public function startSection($name)
    {
        $this->sectionStack[] = $name;
        ob_start();
    }

    /**
     * End a section
     * 
     * @return void
     */
    public function endSection()
    {
        $name = array_pop($this->sectionStack);
        $this->sections[$name] = ob_get_clean();
    }

    /**
     * Get the content of a section
     * 
     * @param string $name
     * @param string $default
     * @return string
     */
    public function yield($name, $default = '')
    {
        return $this->sections[$name] ?? $default;
    }

    /**
     * Check if a section exists
     * 
     * @param string $name
     * @return bool
     */
    public function hasSection($name)
    {
        return isset($this->sections[$name]);
    }

    /**
     * Include a partial view
     * 
     * @param string $template
     * @param array $data
     * @return string
     */
    public function include($template, $data = [])
    {
        $view = new static($this->viewPath);
        return $view->render($template, $data);
    }
}
