<?php

namespace Framework;

class Request
{
    protected $parameters = [];
    protected $routeParams = [];

    public function __construct()
    {
        $this->bootRequest();
    }

    protected function bootRequest()
    {
        $this->parameters = array_merge($_GET, $_POST);

        // Handle JSON requests
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true) ?? [];
            $this->parameters = array_merge($this->parameters, $data);
        }
    }

    public function getMethod()
    {
        // Handle method spoofing (like Laravel)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->has('_method')) {
            return strtoupper($this->get('_method'));
        }

        return $_SERVER['REQUEST_METHOD'];
    }

    public function getUri()
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (strpos($uri, '?') !== false) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

        return $uri;
    }

    public function all()
    {
        return $this->parameters;
    }

    public function only($keys)
    {
        if (!is_array($keys)) {
            $keys = [$keys];
        }

        return array_intersect_key($this->parameters, array_flip($keys));
    }

    public function except($keys)
    {
        if (!is_array($keys)) {
            $keys = [$keys];
        }

        return array_diff_key($this->parameters, array_flip($keys));
    }

    public function get($key, $default = null)
    {
        return $this->parameters[$key] ?? $default;
    }

    public function has($key)
    {
        return isset($this->parameters[$key]);
    }

    public function setRouteParam($key, $value)
    {
        $this->routeParams[$key] = $value;
        return $this;
    }

    public function param($key, $default = null)
    {
        return $this->routeParams[$key] ?? $default;
    }

    public function route($key, $default = null)
    {
        return $this->param($key, $default);
    }

    public function isMethod($method)
    {
        return $this->getMethod() === strtoupper($method);
    }

    public function ajax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Validate the request data
     * 
     * @param array $rules
     * @param array $messages
     * @return array
     */
    public function validate(array $rules, array $messages = [])
    {
        $validator = new Validation($this->all(), $rules, $messages);

        if ($validator->validate()) {
            return $validator->validated();
        }

        // Store validation errors in session
        session('_errors', $validator->errors());

        // Store old input in session
        session('_old_input', $this->except(['password', 'password_confirmation']));

        // Redirect back
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
