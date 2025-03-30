<?php

namespace Framework\Database;

class DatabaseManager
{
    protected $connections = [];
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function connection($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        if (!isset($this->connections[$name])) {
            $this->connections[$name] = $this->makeConnection($name);
        }

        return $this->connections[$name];
    }

    protected function makeConnection($name)
    {
        $config = $this->configuration($name);

        $dsn = $this->getDsn($config);

        $connection = new \PDO(
            $dsn,
            $config['username'],
            $config['password'],
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );

        return $connection;
    }

    protected function getDsn($config)
    {
        return "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
    }

    protected function configuration($name)
    {
        $name = $name ?: $this->getDefaultConnection();

        $connections = $this->config->get('database.connections');

        if (!isset($connections[$name])) {
            throw new \Exception("Database connection [$name] not configured.");
        }

        return $connections[$name];
    }

    public function getDefaultConnection()
    {
        return $this->config->get('database.default');
    }
}
