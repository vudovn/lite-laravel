<?php

namespace Framework;

class Config
{
    protected $configs = [];
    protected $configPath = '';

    public function __construct($configPath)
    {
        $this->configPath = $configPath;
        $this->loadConfigs();
    }

    protected function loadConfigs()
    {
        foreach (glob($this->configPath . '/*.php') as $file) {
            $key = basename($file, '.php');
            $this->configs[$key] = require $file;
        }
    }

    /**
     * Get a configuration value
     * 
     * @param string $key The configuration key (e.g., 'app.name', 'database.default')
     * @param mixed $default Default value if key not found
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $parts = explode('.', $key);
        $configFile = $parts[0];

        // Check if we're looking for a nested value in a module (e.g., 'view.paths')
        if (
            count($parts) > 1 && ($configFile === 'app' || $configFile === 'database' ||
                $configFile === 'view' || $configFile === 'session')
        ) {
            // Try to load modular config directly first
            $moduleConfig = $this->loadModularConfig($configFile);

            // If we have a modular config, look for the nested key
            if ($moduleConfig !== null) {
                array_shift($parts); // Remove the first element (config filename)
                $nestedValue = $moduleConfig;

                // Traverse the nested config
                foreach ($parts as $part) {
                    if (!isset($nestedValue[$part])) {
                        return $default;
                    }
                    $nestedValue = $nestedValue[$part];
                }

                return $nestedValue;
            }
        }

        // Otherwise, fall back to the standard config loading behavior
        return $this->loadConfig($key, $default);
    }

    /**
     * Load a modular configuration file
     * 
     * @param string $module The module name (e.g., 'app', 'database', 'view')
     * @return array|null
     */
    protected function loadModularConfig($module)
    {
        $path = $this->configPath . '/' . $module . '/config.php';

        if (file_exists($path)) {
            return require $path;
        }

        return null;
    }

    protected function loadConfig($key, $default)
    {
        $parts = explode('.', $key);
        $config = $this->configs;

        foreach ($parts as $part) {
            if (!isset($config[$part])) {
                return $default;
            }

            $config = $config[$part];
        }

        return $config;
    }
}
