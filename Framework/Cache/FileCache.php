<?php

namespace Framework\Cache;

class FileCache implements CacheInterface
{
    /**
     * The cache directory.
     *
     * @var string
     */
    protected $directory;

    /**
     * Create a new file cache instance.
     *
     * @param string $directory
     */
    public function __construct($directory = null)
    {
        $this->directory = $directory ?? storage_path('cache');

        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0755, true);
        }
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $file = $this->getFilePath($key);

        if (!file_exists($file)) {
            return $default;
        }

        $contents = file_get_contents($file);
        $cached = unserialize($contents);

        // Check if the cache has expired
        if ($cached['expiration'] !== null && time() > $cached['expiration']) {
            $this->delete($key);
            return $default;
        }

        return $cached['data'];
    }

    /**
     * Store an item in the cache for a given number of seconds.
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl Time to live in seconds (null means forever)
     * @return bool
     */
    public function set($key, $value, $ttl = null)
    {
        $file = $this->getFilePath($key);

        $expiration = $ttl === null ? null : time() + $ttl;

        $contents = serialize([
            'data' => $value,
            'expiration' => $expiration
        ]);

        return file_put_contents($file, $contents) !== false;
    }

    /**
     * Determine if an item exists in the cache.
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return $this->get($key, $this) !== $this;
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     * @return bool
     */
    public function delete($key)
    {
        $file = $this->getFilePath($key);

        if (file_exists($file)) {
            return unlink($file);
        }

        return false;
    }

    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public function clear()
    {
        $files = glob($this->directory . '/*.cache');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        return true;
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param string $key
     * @param int $value
     * @return int|bool
     */
    public function increment($key, $value = 1)
    {
        $current = $this->get($key, 0);

        if (!is_numeric($current)) {
            return false;
        }

        $new = $current + $value;

        $this->set($key, $new);

        return $new;
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param string $key
     * @param int $value
     * @return int|bool
     */
    public function decrement($key, $value = 1)
    {
        return $this->increment($key, -$value);
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function forever($key, $value)
    {
        return $this->set($key, $value, null);
    }

    /**
     * Get an item from the cache, or execute the given Closure and store the result.
     *
     * @param string $key
     * @param int|null $ttl
     * @param \Closure $callback
     * @return mixed
     */
    public function remember($key, $ttl, \Closure $callback)
    {
        if ($this->has($key)) {
            return $this->get($key);
        }

        $value = $callback();

        $this->set($key, $value, $ttl);

        return $value;
    }

    /**
     * Get an item from the cache, or execute the given Closure and store the result forever.
     *
     * @param string $key
     * @param \Closure $callback
     * @return mixed
     */
    public function rememberForever($key, \Closure $callback)
    {
        return $this->remember($key, null, $callback);
    }

    /**
     * Get the full path for the given cache key.
     *
     * @param string $key
     * @return string
     */
    protected function getFilePath($key)
    {
        $hash = md5($key);
        return $this->directory . '/' . $hash . '.cache';
    }
}