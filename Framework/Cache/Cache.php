<?php

namespace Framework\Cache;

class Cache
{
    /**
     * The array of created cache drivers.
     *
     * @var array
     */
    protected $stores = [];

    /**
     * The default cache store driver.
     *
     * @var string
     */
    protected $defaultDriver;

    /**
     * Available cache drivers and their corresponding classes.
     *
     * @var array
     */
    protected $drivers = [
        'file' => FileCache::class,
        'array' => ArrayCache::class,
        // 'redis' => RedisCache::class, // You can add more drivers as needed
    ];

    /**
     * Create a new cache manager instance.
     *
     * @param string $defaultDriver
     */
    public function __construct($defaultDriver = 'file')
    {
        $this->defaultDriver = $defaultDriver;
    }

    /**
     * Get a cache store instance by name, or the default driver.
     *
     * @param string|null $driver
     * @return \Framework\Cache\CacheInterface
     */
    public function store($driver = null)
    {
        $driver = $driver ?: $this->defaultDriver;

        if (!isset($this->stores[$driver])) {
            $this->stores[$driver] = $this->createDriver($driver);
        }

        return $this->stores[$driver];
    }

    /**
     * Create a new cache driver instance.
     *
     * @param string $driver
     * @return \Framework\Cache\CacheInterface
     * @throws \InvalidArgumentException
     */
    protected function createDriver($driver)
    {
        if (!isset($this->drivers[$driver])) {
            throw new \InvalidArgumentException("Driver [$driver] not supported.");
        }

        $driverClass = $this->drivers[$driver];

        // Special configuration for each driver could be added here
        if ($driver === 'file') {
            return new $driverClass(storage_path('cache'));
        }

        return new $driverClass();
    }

    /**
     * Add a new driver to the cache manager.
     *
     * @param string $driver
     * @param string $class
     * @return self
     */
    public function addDriver($driver, $class)
    {
        $this->drivers[$driver] = $class;

        return $this;
    }

    /**
     * Set the default cache driver name.
     *
     * @param string $driver
     * @return void
     */
    public function setDefaultDriver($driver)
    {
        $this->defaultDriver = $driver;
    }

    /**
     * Get the default cache driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->defaultDriver;
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->store()->$method(...$parameters);
    }
}