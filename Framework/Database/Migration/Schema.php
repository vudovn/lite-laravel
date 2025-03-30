<?php

namespace Framework\Database\Migration;

use Framework\Database\Migration\Blueprint;

class Schema
{
    /**
     * The database connection.
     *
     * @var \Framework\Database\DatabaseManager
     */
    protected static $connection;

    /**
     * Set the database connection to use.
     *
     * @param \Framework\Database\DatabaseManager $connection
     * @return void
     */
    public static function setConnection($connection)
    {
        static::$connection = $connection;
    }

    /**
     * Get the database connection.
     *
     * @return \Framework\Database\DatabaseManager
     */
    public static function getConnection()
    {
        return static::$connection ?: app('db');
    }

    /**
     * Create a new table.
     *
     * @param string $table
     * @param \Closure $callback
     * @return void
     */
    public static function create($table, \Closure $callback)
    {
        $blueprint = new Blueprint($table);

        $callback($blueprint);

        $statements = $blueprint->toSql(static::getConnection());

        static::getConnection()->transaction(function ($connection) use ($statements) {
            foreach ($statements as $statement) {
                $connection->statement($statement);
            }
        });
    }

    /**
     * Drop a table.
     *
     * @param string $table
     * @return void
     */
    public static function drop($table)
    {
        static::getConnection()->statement("DROP TABLE IF EXISTS `{$table}`");
    }

    /**
     * Drop a table if it exists.
     *
     * @param string $table
     * @return void
     */
    public static function dropIfExists($table)
    {
        static::drop($table);
    }

    /**
     * Modify a table.
     *
     * @param string $table
     * @param \Closure $callback
     * @return void
     */
    public static function table($table, \Closure $callback)
    {
        $blueprint = new Blueprint($table);
        $blueprint->setTableExists(true);

        $callback($blueprint);

        $statements = $blueprint->toSql(static::getConnection());

        static::getConnection()->transaction(function ($connection) use ($statements) {
            foreach ($statements as $statement) {
                $connection->statement($statement);
            }
        });
    }

    /**
     * Rename a table.
     *
     * @param string $from
     * @param string $to
     * @return void
     */
    public static function rename($from, $to)
    {
        static::getConnection()->statement("RENAME TABLE `{$from}` TO `{$to}`");
    }

    /**
     * Check if a table exists.
     *
     * @param string $table
     * @return bool
     */
    public static function hasTable($table)
    {
        $sql = "SELECT COUNT(*) as count FROM information_schema.tables " .
            "WHERE table_schema = ? AND table_name = ?";

        $database = static::getConnection()->getDatabaseName();
        $result = static::getConnection()->selectOne($sql, [$database, $table]);

        return $result && $result->count > 0;
    }

    /**
     * Check if a column exists.
     *
     * @param string $table
     * @param string $column
     * @return bool
     */
    public static function hasColumn($table, $column)
    {
        $sql = "SELECT COUNT(*) as count FROM information_schema.columns " .
            "WHERE table_schema = ? AND table_name = ? AND column_name = ?";

        $database = static::getConnection()->getDatabaseName();
        $result = static::getConnection()->selectOne($sql, [$database, $table, $column]);

        return $result && $result->count > 0;
    }
}