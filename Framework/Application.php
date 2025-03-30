<?php

namespace Framework;

class Application
{
    protected $container = [];
    protected $environment = 'production';

    public function __construct()
    {
        $this->bootstrap();
    }

    protected function bootstrap()
    {
        // Register base services
        $this->singleton('request', function () {
            return new Request();
        });

        $this->singleton('response', function () {
            return new Response();
        });

        // Register session service
        $this->singleton('session', function () {
            return new Session();
        });

        // Register view service
        $this->singleton('view', function () {
            return new View(dirname(__DIR__) . '/resources/views');
        });

        // Register database connection
        $this->singleton('db', function () {
            // Simple PDO database connection
            $host = env('DB_HOST', 'localhost');
            $database = env('DB_DATABASE', 'database');
            $username = env('DB_USERNAME', 'root');
            $password = env('DB_PASSWORD', '');
            $charset = env('DB_CHARSET', 'utf8mb4');

            $dsn = "mysql:host={$host};dbname={$database};charset={$charset}";
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                $pdo = new \PDO($dsn, $username, $password, $options);
                return new class ($pdo) {
                    protected $pdo;

                    public function __construct($pdo)
                    {
                        $this->pdo = $pdo;
                    }

                    public function connection()
                    {
                        return $this->pdo;
                    }
                };
            } catch (\PDOException $e) {
                throw new \Exception("Database connection failed: " . $e->getMessage());
            }
        });

        // Register configuration service
        $this->singleton('config', function () {
            $configPath = dirname(__DIR__) . '/config';
            return new Config($configPath);
        });
    }

    public function singleton($key, $resolver)
    {
        $this->container[$key] = $resolver;
    }

    // Add the bind method
    public function bind($key, $resolver)
    {
        $this->container[$key] = $resolver;
    }

    /**
     * Resolve a key from the container
     * 
     * @param string $key
     * @return mixed
     */
    public function resolve($key)
    {
        if (!isset($this->container[$key])) {
            return null;
        }

        if (is_callable($this->container[$key])) {
            $this->container[$key] = call_user_func($this->container[$key]);
        }

        return $this->container[$key];
    }

    /**
     * Make an instance from the container
     * 
     * @param string $key
     * @return mixed
     */
    public function make($key)
    {
        return $this->resolve($key);
    }

    /**
     * Register middleware
     *
     * @param array $middleware
     * @return $this
     */
    public function registerMiddleware(array $middleware)
    {
        $this->bind('middleware', function () use ($middleware) {
            return $middleware;
        });

        return $this;
    }

    public function run()
    {
        $router = $this->resolve('router');
        $request = $this->resolve('request');

        $response = $this->sendRequestThroughRouter($request, $router);
        $response->send();
    }

    protected function sendRequestThroughRouter($request, $router)
    {
        return $router->dispatch($request);
    }

    /**
     * Load application configuration
     *
     * @return $this
     */
    public function loadConfig()
    {
        // Config is already loaded in bootstrap, but this method
        // is provided for explicit loading if needed
        if (!isset($this->container['config'])) {
            $this->singleton('config', function () {
                return new Config(dirname(__DIR__) . '/config');
            });
        }

        // Set application environment from config
        $config = $this->resolve('config');
        $this->environment = $config->get('app.environment', 'production');

        return $this;
    }

    /**
     * Register database connection
     *
     * @return $this
     */
    public function registerDatabase()
    {
        // Database connection is already set up in bootstrap, but this method
        // is provided for explicit registration if needed
        if (!isset($this->container['db'])) {
            $this->singleton('db', function () {
                $config = $this->resolve('config');
                return new Database\DatabaseManager($config);
            });
        }

        return $this;
    }

    /**
     * Register essential services
     *
     * @return $this
     */
    public function registerServices()
    {
        // Core services are already registered in bootstrap,
        // but this method allows for additional service registration
        return $this;
    }
}
