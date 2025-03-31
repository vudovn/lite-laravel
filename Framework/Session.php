<?php

namespace Framework;

class Session
{
    protected $flashData = []; // Lưu trữ flash data cho request hiện tại
    protected $oldFlashData = []; // Lưu trữ flash data cho request tiếp theo
    protected $flashDataToRemove = []; // Keys cần loại bỏ sau request

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

        // Load flash data từ session
        $this->loadFlashData();
    }

    /**
     * Load flash data từ session và chuẩn bị cho request hiện tại
     */
    protected function loadFlashData()
    {
        // Lấy flash data từ previous request
        $this->flashData = $_SESSION['_flash_data'] ?? [];
        $this->flashDataToRemove = $_SESSION['_flash_data_to_remove'] ?? [];

        // Xóa dữ liệu flash cho request tiếp theo
        unset($_SESSION['_flash_data']);
        unset($_SESSION['_flash_data_to_remove']);
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

        // Kiểm tra flash data trước
        if (isset($this->flashData[$key])) {
            $value = $this->flashData[$key];
            // Đánh dấu key đã sử dụng để xóa sau request
            $this->flashDataToRemove[] = $key;
            return $value;
        }

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
        return isset($_SESSION[$key]) || isset($this->flashData[$key]);
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

        // Xóa cả từ flashData nếu có
        if (isset($this->flashData[$key])) {
            unset($this->flashData[$key]);
        }

        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Flash data to the session for the next request
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function flash($key, $value)
    {
        $this->oldFlashData[$key] = $value;
    }

    /**
     * Flash input data to the session
     * 
     * @param array $input
     * @return void
     */
    public function flashInput(array $input)
    {
        $this->flash('_old_input', $input);
    }

    /**
     * Get old input data
     * 
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function getOldInput($key = null, $default = null)
    {
        $oldInput = $this->get('_old_input', []);

        if ($key === null) {
            return $oldInput;
        }

        return isset($oldInput[$key]) ? $oldInput[$key] : $default;
    }

    /**
     * Save flash data cho request tiếp theo
     * Call at the end of each request
     */
    public function save()
    {
        // Lưu flash data mới cho request tiếp theo
        if (!empty($this->oldFlashData)) {
            $_SESSION['_flash_data'] = $this->oldFlashData;
        }

        // Lưu danh sách keys cần xóa
        if (!empty($this->flashDataToRemove)) {
            $_SESSION['_flash_data_to_remove'] = array_unique($this->flashDataToRemove);
        }
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
        $this->flashData = [];
        $this->oldFlashData = [];
        $this->flashDataToRemove = [];
    }

    /**
     * Destroy the session
     * 
     * @return void
     */
    public function destroy()
    {
        $this->clear();
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