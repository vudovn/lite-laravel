<?php

namespace Framework\Database\Migration;

class MigrationRepository
{
    /**
     * The database connection.
     *
     * @var \Framework\Database\DatabaseManager
     */
    protected $connection;

    /**
     * The name of the migration table.
     *
     * @var string
     */
    protected $table;

    /**
     * Create a new migration repository instance.
     *
     * @param \Framework\Database\DatabaseManager $connection
     * @param string $table
     * @return void
     */
    public function __construct($connection, $table = 'migrations')
    {
        $this->connection = $connection;
        $this->table = $table;
    }

    /**
     * Get the ran migrations.
     *
     * @return array
     */
    public function getRan()
    {
        return $this->connection->table($this->table)
            ->orderBy('id')
            ->pluck('migration')
            ->toArray();
    }

    /**
     * Get the migrations by batch number.
     *
     * @param int $batch
     * @return array
     */
    public function getMigrationsByBatch($batch)
    {
        return $this->connection->table($this->table)
            ->where('batch', $batch)
            ->orderBy('id', 'desc')
            ->pluck('migration')
            ->toArray();
    }

    /**
     * Get the last batch number.
     *
     * @return int
     */
    public function getLastBatchNumber()
    {
        $batch = $this->connection->table($this->table)
            ->max('batch');

        return $batch ?: 0;
    }

    /**
     * Log that a migration was run.
     *
     * @param string $file
     * @return void
     */
    public function log($file)
    {
        $batch = $this->getLastBatchNumber() + 1;

        $this->connection->table($this->table)->insert([
            'migration' => $file,
            'batch' => $batch,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Remove a migration from the log.
     *
     * @param string $file
     * @return void
     */
    public function delete($file)
    {
        $this->connection->table($this->table)
            ->where('migration', $file)
            ->delete();
    }

    /**
     * Create the migration table if it doesn't exist.
     *
     * @return void
     */
    public function createTableIfNotExists()
    {
        if (!$this->connection->hasTable($this->table)) {
            $this->connection->statement('
                CREATE TABLE `' . $this->table . '` (
                    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `migration` VARCHAR(255) NOT NULL,
                    `batch` INT(11) NOT NULL,
                    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ');
        }
    }
}