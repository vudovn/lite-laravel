<?php

namespace Framework\Cache;

class ArrayCache implements CacheInterface
{
    /**
     * The array of stored values.
     *
     * @var array
     */
    protected $storage = [];

    /**
     * Retrieve an item from the cache by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (!isset($this->storage[$key])) {
            return $default;
        }

        $item = $this->storage[$key];

        // Check if the item has expired
        if ($item['expiration'] !== null && time() > $item['expiration']) {
            $this->delete($key);
            return $default;
        }

        return $item['data'];
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
        $expiration = $ttl === null ? null : time() + $ttl;

        $this->storage[$key] = [
            'data' => $value,
            'expiration' => $expiration
        ];

        return true;
    }

    /**
     * Determine if an item exists in the cache.
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        if (!isset($this->storage[$key])) {
            return false;
        }

        $item = $this->storage[$key];

        // Check if the item has expired
        if ($item['expiration'] !== null && time() > $item['expiration']) {
            $this->delete($key);
            return false;
        }

        return true;
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     * @return bool
     */
    public function delete($key)
    {
        unset($this->storage[$key]);
        return true;
    }

    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public function clear()
    {
        $this->storage = [];
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
}