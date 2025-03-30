<?php

namespace Framework;

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => []
    ];

    protected $app;
    protected $currentGroupPrefix = '';

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function get($uri, $action)
    {
        return $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        return $this->addRoute('POST', $uri, $action);
    }

    public function put($uri, $action)
    {
        return $this->addRoute('PUT', $uri, $action);
    }

    public function delete($uri, $action)
    {
        return $this->addRoute('DELETE', $uri, $action);
    }

    public function group(array $attributes, \Closure $callback)
    {
        // Save current group state
        $previousGroupPrefix = $this->currentGroupPrefix;

        // Update with new group attributes
        if (isset($attributes['prefix'])) {
            $prefix = $attributes['prefix'];
            // Ensure prefix starts with a slash
            if (substr($prefix, 0, 1) !== '/') {
                $prefix = '/' . $prefix;
            }
            // Ensure prefix doesn't end with a slash
            if (substr($prefix, -1) === '/') {
                $prefix = rtrim($prefix, '/');
            }
            $this->currentGroupPrefix = $previousGroupPrefix . $prefix;
        }

        // Execute the routes within the group
        $callback($this);

        // Restore previous group state
        $this->currentGroupPrefix = $previousGroupPrefix;

        return $this;
    }

    public function resource($name, $controller)
    {
        $this->get("/$name", "$controller@index");
        $this->get("/$name/create", "$controller@create");
        $this->post("/$name", "$controller@store");
        $this->get("/$name/{id}", "$controller@show");
        $this->get("/$name/{id}/edit", "$controller@edit");
        $this->put("/$name/{id}", "$controller@update");
        $this->delete("/$name/{id}", "$controller@destroy");

        return $this;
    }

    protected function addRoute($method, $uri, $action)
    {
        // Ensure URI starts with a slash
        if (substr($uri, 0, 1) !== '/') {
            $uri = '/' . $uri;
        }

        // Apply group prefix
        $uri = $this->currentGroupPrefix . $uri;

        $this->routes[$method][$uri] = [
            'action' => $action
        ];

        return $this;
    }

    public function middleware($middleware)
    {
        // This method is kept for backward compatibility but does nothing
        return $this;
    }

    public function dispatch(Request $request)
    {
        $method = $request->getMethod();
        $uri = $request->getUri();

        // Look for direct match
        if (isset($this->routes[$method][$uri])) {
            return $this->executeRoute($this->routes[$method][$uri], $request);
        }

        // Look for dynamic routes with parameters
        $route = $this->findDynamicRoute($method, $uri);
        if ($route) {
            return $this->executeRoute($route['route'], $request, $route['params']);
        }

        return new Response('404 Not Found', 404);
    }

    protected function findDynamicRoute($method, $uri)
    {
        foreach ($this->routes[$method] as $routeUri => $route) {
            if (strpos($routeUri, '{') !== false) {
                $pattern = preg_replace('/{([^}]+)}/', '([^/]+)', $routeUri);
                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $uri, $matches)) {
                    // Extract parameter names
                    preg_match_all('/{([^}]+)}/', $routeUri, $paramNames);

                    // Remove the full match from $matches
                    array_shift($matches);

                    // Combine parameter names with their values
                    $params = [];
                    foreach ($paramNames[1] as $index => $name) {
                        $params[$name] = $matches[$index] ?? null;
                    }

                    return [
                        'route' => $route,
                        'params' => $params
                    ];
                }
            }
        }

        return null;
    }

    protected function executeRoute($route, $request, $params = [])
    {
        $action = $route['action'];

        // Debug routes
        if (env('APP_DEBUG', false)) {
            error_log("Executing route: " . $request->getUri());
        }

        // Set route parameters in the request
        foreach ($params as $key => $value) {
            $request->setRouteParam($key, $value);
        }

        // Execute the route directly without middleware
        if (is_callable($action)) {
            return call_user_func_array($action, array_merge([$request], $params));
        }

        // Handle array-style actions [Controller::class, 'method']
        if (is_array($action) && count($action) === 2) {
            list($controller, $method) = $action;

            if (!class_exists($controller)) {
                return new Response('Controller not found: ' . $controller, 500);
            }

            $controllerInstance = new $controller();

            if (!method_exists($controllerInstance, $method)) {
                return new Response('Method not found: ' . $method, 500);
            }

            return call_user_func_array(
                [$controllerInstance, $method],
                array_merge([$request], array_values($params))
            );
        }

        // Handle string-style actions "Controller@method"
        if (is_string($action) && strpos($action, '@') !== false) {
            list($controller, $method) = explode('@', $action);
            $controllerClass = "App\\Controllers\\{$controller}";

            if (!class_exists($controllerClass)) {
                return new Response('Controller not found: ' . $controllerClass, 500);
            }

            $controllerInstance = new $controllerClass();

            if (!method_exists($controllerInstance, $method)) {
                return new Response('Method not found: ' . $method, 500);
            }

            return call_user_func_array(
                [$controllerInstance, $method],
                array_merge([$request], array_values($params))
            );
        }

        return new Response('Invalid route action: ' . (is_string($action) ? $action : gettype($action)), 500);
    }
}
