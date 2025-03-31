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

    /**
     * Create a view response
     * 
     * @param string $template
     * @param array $data
     * @return static
     */
    public static function view($template, $data = [])
    {
        try {
            $view = app('view');
            $content = $view->render($template, $data);
            return new static($content);
        } catch (\Exception $e) {
            // Log error
            error_log("View error: " . $e->getMessage());
            // Return error response in development
            if (env('APP_ENV') === 'local') {
                return new static('<div style="color:red;"><h1>View Error</h1><p>' . $e->getMessage() . '</p><pre>' . $e->getTraceAsString() . '</pre></div>', 500);
            }
            // Generic error in production
            return new static('Internal Server Error', 500);
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
