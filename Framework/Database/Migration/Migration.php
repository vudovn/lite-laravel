<?php

namespace Framework\Database\Migration;

abstract class Migration
{
    /**
     * The database connection instance.
     *
     * @var \Framework\Database\DatabaseManager
     */
    protected $connection;

    /**
     * Create a new migration instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->connection = app('db');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    abstract public function up();

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    abstract public function down();

    /**
     * Run a single database statement.
     *
     * @param string $sql The SQL statement to run
     * @param array $params The parameters for the statement
     * @return bool
     */
    protected function statement($sql, array $params = [])
    {
        try {
            $statement = $this->connection->getPdo()->prepare($sql);
            return $statement->execute($params);
        } catch (\PDOException $e) {
            echo "Migration Error: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * Run multiple SQL statements as a transaction.
     *
     * @param array $statements Array of SQL statements
     * @return bool
     */
    protected function transaction(array $statements)
    {
        try {
            $connection = $this->connection->getPdo();
            $connection->beginTransaction();

            foreach ($statements as $statement) {
                $sql = $statement['sql'];
                $params = $statement['params'] ?? [];

                $stmt = $connection->prepare($sql);
                if (!$stmt->execute($params)) {
                    throw new \PDOException("Failed to execute statement: $sql");
                }
            }

            return $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            echo "Migration Transaction Error: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * Execute raw SQL query.
     *
     * @param string $sql
     * @return bool
     */
    protected function raw($sql)
    {
        try {
            return $this->connection->getPdo()->exec($sql) !== false;
        } catch (\PDOException $e) {
            echo "Migration Raw Query Error: " . $e->getMessage() . "\n";
            return false;
        }
    }
}