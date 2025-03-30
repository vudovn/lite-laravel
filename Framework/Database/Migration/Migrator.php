<?php

namespace Framework\Database\Migration;

class Migrator
{
    /**
     * The database connection.
     *
     * @var \Framework\Database\DatabaseManager
     */
    protected $connection;

    /**
     * The migration repository implementation.
     *
     * @var \Framework\Database\Migration\MigrationRepository
     */
    protected $repository;

    /**
     * The migrations path.
     *
     * @var string
     */
    protected $path;

    /**
     * Create a new migrator instance.
     *
     * @param \Framework\Database\DatabaseManager $connection
     * @param \Framework\Database\Migration\MigrationRepository $repository
     * @return void
     */
    public function __construct($connection, $repository, $path = null)
    {
        $this->connection = $connection;
        $this->repository = $repository;
        $this->path = $path ?: database_path('migrations');
    }

    /**
     * Run the pending migrations.
     *
     * @param array $migrations
     * @return array
     */
    public function run($migrations = [])
    {
        $this->ensureMigrationTableExists();

        $ran = [];
        $migrations = $this->getMigrations($migrations);
        $pendingMigrations = $this->getPendingMigrations($migrations);

        if (count($pendingMigrations) == 0) {
            return $ran;
        }

        foreach ($pendingMigrations as $file) {
            $this->runMigration($file, 'up');
            $ran[] = $file;
        }

        return $ran;
    }

    /**
     * Rollback the last migration or specified migrations.
     *
     * @param array $migrations
     * @return array
     */
    public function rollback($migrations = [])
    {
        $this->ensureMigrationTableExists();

        $ran = [];

        // If no specific migrations are specified, get the last batch
        if (empty($migrations)) {
            $lastBatch = $this->repository->getLastBatchNumber();
            $migrations = $this->repository->getMigrationsByBatch($lastBatch);
        }

        foreach ($migrations as $migration) {
            $this->runMigration($migration, 'down');
            $ran[] = $migration;
            $this->repository->delete($migration);
        }

        return $ran;
    }

    /**
     * Run a migration.
     *
     * @param string $file
     * @param string $method
     * @return void
     */
    protected function runMigration($file, $method)
    {
        $migration = $this->resolveMigration($file);

        $this->runMethod($migration, $method);

        if ($method === 'up') {
            $this->repository->log($file);
        }
    }

    /**
     * Run a migration method.
     *
     * @param object $migration
     * @param string $method
     * @return void
     */
    protected function runMethod($migration, $method)
    {
        $migration->$method();
    }

    /**
     * Resolve a migration instance from a file.
     *
     * @param string $file
     * @return object
     */
    protected function resolveMigration($file)
    {
        $class = $this->getMigrationClass($file);

        // Include the file
        require_once $this->path . '/' . $file . '.php';

        return new $class();
    }

    /**
     * Get the migration class name from a file.
     *
     * @param string $file
     * @return string
     */
    protected function getMigrationClass($file)
    {
        // Extract the timestamp and name
        $parts = explode('_', $file);

        // Remove timestamp
        array_shift($parts);

        // Build class name
        $class = '';
        foreach ($parts as $part) {
            $class .= ucfirst($part);
        }

        return $class;
    }

    /**
     * Get all the migration files.
     *
     * @param array $migrations
     * @return array
     */
    protected function getMigrations($migrations = [])
    {
        if (!empty($migrations)) {
            return $migrations;
        }

        // Get all PHP files from the migrations directory
        $files = glob($this->path . '/*.php');

        if ($files === false) {
            return [];
        }

        // Extract just the filename without extension
        $migrations = [];
        foreach ($files as $file) {
            $migrations[] = pathinfo($file, PATHINFO_FILENAME);
        }

        // Sort by timestamp
        sort($migrations);

        return $migrations;
    }

    /**
     * Get the pending migrations.
     *
     * @param array $migrations
     * @return array
     */
    protected function getPendingMigrations($migrations)
    {
        $ran = $this->repository->getRan();

        return array_diff($migrations, $ran);
    }

    /**
     * Ensure the migration table exists.
     *
     * @return void
     */
    protected function ensureMigrationTableExists()
    {
        $this->repository->createTableIfNotExists();
    }

    /**
     * Set the migrations path.
     *
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }
}