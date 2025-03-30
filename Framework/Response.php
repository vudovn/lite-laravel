<?php

namespace Framework;

class Response
{
    protected $content = '';
    protected $statusCode = 200;
    protected $headers = [];

    public function __construct($content = '', $statusCode = 200, $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function send()
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        echo $this->content;
        return $this;
    }

    public function redirect($path)
    {
        $this->setStatusCode(302);
        $this->setHeader('Location', $path);
        return $this;
    }

    public static function json($data, $statusCode = 200)
    {
        return new static(
            json_encode($data),
            $statusCode,
            ['Content-Type' => 'application/json']
        );
    }

    public static function view($template, $data = [], $statusCode = 200)
    {
        // Use view_path helper instead of hardcoded path
        $view = new View(view_path());

        // Get view config
        $viewConfig = config('view', []);

        // Set default layout if not specified in data
        if (!isset($data['layout'])) {
            $view->setLayout($viewConfig['default_layout'] ?? 'layouts.app');
        }

        try {
            $content = $view->render($template, $data);
            return new static($content, $statusCode);
        } catch (\Exception $e) {
            // Log error
            error_log('View error: ' . $e->getMessage());

            // Return a nice error in development
            if (env('APP_ENV', 'production') === 'development' || config('app.debug', false)) {
                $errorContent = '<h1>View Error</h1>';
                $errorContent .= '<p>' . $e->getMessage() . '</p>';
                $errorContent .= '<h2>Template</h2>';
                $errorContent .= '<p>' . $template . '</p>';
                $errorContent .= '<h2>Stack Trace</h2>';
                $errorContent .= '<pre>' . $e->getTraceAsString() . '</pre>';
                return new static($errorContent, 500);
            }

            // Generic error in production
            return new static('Error rendering view', 500);
        }
    }

    /**
     * Create a response that downloads a file
     * 
     * @param string $file
     * @param string $name
     * @return static
     */
    public static function download($file, $name = null)
    {
        $headers = [
            'Content-Description' => 'File Transfer',
            'Content-Type' => mime_content_type($file),
            'Content-Disposition' => 'attachment; filename="' . ($name ?: basename($file)) . '"',
            'Content-Length' => filesize($file),
            'Pragma' => 'public',
            'Cache-Control' => 'must-revalidate',
            'Expires' => '0'
        ];

        return new static(file_get_contents($file), 200, $headers);
    }
}
