<?php

namespace Framework;

class Session
{
    /**
     * Start the session
     * 
     * @return void
     */
    public function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Get all session data
     * 
     * @return array
     */
    public function all()
    {
        $this->start();
        return $_SESSION;
    }

    /**
     * Get a specific session value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $this->start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Set a session value
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value)
    {
        $this->start();
        $_SESSION[$key] = $value;
    }

    /**
     * Check if a session value exists
     * 
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        $this->start();
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a session value
     * 
     * @param string $key
     * @return void
     */
    public function forget($key)
    {
        $this->start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Flash data to the session
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function flash($key, $value)
    {
        $this->set($key, $value);
        $this->set('_flash', array_merge($this->get('_flash', []), [$key]));
    }

    /**
     * Clear all session data
     * 
     * @return void
     */
    public function clear()
    {
        $this->start();
        session_unset();
    }

    /**
     * Destroy the session
     * 
     * @return void
     */
    public function destroy()
    {
        $this->start();
        session_unset();
        session_destroy();
    }

    /**
     * Regenerate the session ID
     * 
     * @param bool $deleteOldSession
     * @return bool
     */
    public function regenerate($deleteOldSession = true)
    {
        $this->start();
        return session_regenerate_id($deleteOldSession);
    }
}