<?php

namespace Framework\Database\Migration;

class Blueprint
{
    /**
     * The table the blueprint describes.
     *
     * @var string
     */
    protected $table;

    /**
     * The columns that should be added to the table.
     *
     * @var array
     */
    protected $columns = [];

    /**
     * The commands that should be run for the table.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Flag to indicate if the table already exists.
     *
     * @var bool
     */
    protected $tableExists = false;

    /**
     * Create a new schema blueprint.
     *
     * @param  string  $table
     * @return void
     */
    public function __construct($table)
    {
        $this->table = $table;
        $this->commands[] = ['name' => 'create'];
    }

    /**
     * Set whether the table already exists.
     *
     * @param bool $exists
     * @return $this
     */
    public function setTableExists($exists)
    {
        $this->tableExists = $exists;
        $this->commands = [];
        return $this;
    }

    /**
     * Execute the blueprint against the database.
     *
     * @param \Framework\Database\DatabaseManager $connection
     * @return array
     */
    public function toSql($connection)
    {
        $statements = [];

        if (!$this->tableExists && $this->commandExists('create')) {
            $statements[] = $this->createTable();
        }

        if ($this->tableExists && $this->hasColumns()) {
            $statements[] = $this->alterTable();
        }

        return $statements;
    }

    /**
     * Add a new column to the blueprint.
     *
     * @param string $type
     * @param string $name
     * @param array $parameters
     * @return \Framework\Database\Migration\ColumnDefinition
     */
    protected function addColumn($type, $name, array $parameters = [])
    {
        $column = new ColumnDefinition([
            'type' => $type,
            'name' => $name,
            'parameters' => $parameters,
        ]);

        $this->columns[] = $column;

        return $column;
    }

    /**
     * Create a new auto-incrementing integer (primary key) column on the table.
     *
     * @param string $name
     * @return \Framework\Database\Migration\ColumnDefinition
     */
    public function id($name = 'id')
    {
        return $this->addColumn('id', $name)->autoIncrement()->primary();
    }

    /**
     * Create a new string column on the table.
     *
     * @param string $name
     * @param int $length
     * @return \Framework\Database\Migration\ColumnDefinition
     */
    public function string($name, $length = 255)
    {
        return $this->addColumn('string', $name, ['length' => $length]);
    }

    /**
     * Create a new text column on the table.
     *
     * @param string $name
     * @return \Framework\Database\Migration\ColumnDefinition
     */
    public function text($name)
    {
        return $this->addColumn('text', $name);
    }

    /**
     * Create a new integer column on the table.
     *
     * @param string $name
     * @return \Framework\Database\Migration\ColumnDefinition
     */
    public function integer($name)
    {
        return $this->addColumn('integer', $name);
    }

    /**
     * Create a new big integer column on the table.
     *
     * @param string $name
     * @return \Framework\Database\Migration\ColumnDefinition
     */
    public function bigInteger($name)
    {
        return $this->addColumn('bigInteger', $name);
    }

    /**
     * Create a new float column on the table.
     *
     * @param string $name
     * @param int $total
     * @param int $places
     * @return \Framework\Database\Migration\ColumnDefinition
     */
    public function float($name, $total = 8, $places = 2)
    {
        return $this->addColumn('float', $name, ['total' => $total, 'places' => $places]);
    }

    /**
     * Create a new decimal column on the table.
     *
     * @param string $name
     * @param int $total
     * @param int $places
     * @return \Framework\Database\Migration\ColumnDefinition
     */
    public function decimal($name, $total = 8, $places = 2)
    {
        return $this->addColumn('decimal', $name, ['total' => $total, 'places' => $places]);
    }

    /**
     * Create a new boolean column on the table.
     *
     * @param string $name
     * @return \Framework\Database\Migration\ColumnDefinition
     */
    public function boolean($name)
    {
        return $this->addColumn('boolean', $name);
    }

    /**
     * Create a new date column on the table.
     *
     * @param string $name
     * @return \Framework\Database\Migration\ColumnDefinition
     */
    public function date($name)
    {
        return $this->addColumn('date', $name);
    }

    /**
     * Create a new datetime column on the table.
     *
     * @param string $name
     * @return \Framework\Database\Migration\ColumnDefinition
     */
    public function dateTime($name)
    {
        return $this->addColumn('dateTime', $name);
    }

    /**
     * Create a new timestamp column on the table.
     *
     * @param string $name
     * @return \Framework\Database\Migration\ColumnDefinition
     */
    public function timestamp($name)
    {
        return $this->addColumn('timestamp', $name);
    }

    /**
     * Create a new timestamps columns on the table.
     *
     * @return void
     */
    public function timestamps()
    {
        $this->timestamp('created_at')->nullable();
        $this->timestamp('updated_at')->nullable();
    }

    /**
     * Add the soft deletes column to the table.
     *
     * @return \Framework\Database\Migration\ColumnDefinition
     */
    public function softDeletes()
    {
        return $this->timestamp('deleted_at')->nullable();
    }

    /**
     * Determine if the blueprint has a create command.
     *
     * @return bool
     */
    protected function commandExists($command)
    {
        foreach ($this->commands as $cmd) {
            if ($cmd['name'] === $command) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the SQL to create a new table.
     *
     * @return string
     */
    protected function createTable()
    {
        $columns = [];

        foreach ($this->columns as $column) {
            $columns[] = $this->getColumnDefinition($column);
        }

        $sql = sprintf(
            'CREATE TABLE `%s` (%s)',
            $this->table,
            implode(', ', $columns)
        );

        return $sql;
    }

    /**
     * Get the SQL to alter an existing table.
     *
     * @return string
     */
    protected function alterTable()
    {
        $columnDefs = [];

        foreach ($this->columns as $column) {
            $columnDefs[] = 'ADD COLUMN ' . $this->getColumnDefinition($column);
        }

        $sql = sprintf(
            'ALTER TABLE `%s` %s',
            $this->table,
            implode(', ', $columnDefs)
        );

        return $sql;
    }

    /**
     * Get the column definition SQL string.
     *
     * @param \Framework\Database\Migration\ColumnDefinition $column
     * @return string
     */
    protected function getColumnDefinition($column)
    {
        $sql = "`{$column->name}` {$this->getColumnType($column)}";

        if ($column->unsigned) {
            $sql .= ' UNSIGNED';
        }

        if ($column->nullable === false) {
            $sql .= ' NOT NULL';
        } else {
            $sql .= ' NULL';
        }

        if ($column->autoIncrement) {
            $sql .= ' AUTO_INCREMENT';
        }

        if ($column->default !== null) {
            $sql .= ' DEFAULT ' . $this->getColumnDefaultValue($column);
        }

        if ($column->primary) {
            $sql .= ' PRIMARY KEY';
        }

        return $sql;
    }

    /**
     * Get the SQL for the column type.
     *
     * @param \Framework\Database\Migration\ColumnDefinition $column
     * @return string
     */
    protected function getColumnType($column)
    {
        switch ($column->type) {
            case 'id':
                return 'INT(10) UNSIGNED';
            case 'string':
                return 'VARCHAR(' . ($column->parameters['length'] ?? 255) . ')';
            case 'text':
                return 'TEXT';
            case 'integer':
                return 'INT';
            case 'bigInteger':
                return 'BIGINT';
            case 'float':
                $total = $column->parameters['total'] ?? 8;
                $places = $column->parameters['places'] ?? 2;
                return "FLOAT($total, $places)";
            case 'decimal':
                $total = $column->parameters['total'] ?? 8;
                $places = $column->parameters['places'] ?? 2;
                return "DECIMAL($total, $places)";
            case 'boolean':
                return 'TINYINT(1)';
            case 'date':
                return 'DATE';
            case 'dateTime':
                return 'DATETIME';
            case 'timestamp':
                return 'TIMESTAMP';
            default:
                return 'VARCHAR(255)';
        }
    }

    /**
     * Get the SQL for the column's default value.
     *
     * @param \Framework\Database\Migration\ColumnDefinition $column
     * @return string
     */
    protected function getColumnDefaultValue($column)
    {
        if (is_string($column->default)) {
            return "'{$column->default}'";
        }

        if (is_bool($column->default)) {
            return $column->default ? '1' : '0';
        }

        if (is_null($column->default)) {
            return 'NULL';
        }

        return $column->default;
    }

    /**
     * Determine if the blueprint has any columns.
     *
     * @return bool
     */
    protected function hasColumns()
    {
        return count($this->columns) > 0;
    }
}

/**
 * Column definition class.
 */
class ColumnDefinition
{
    /**
     * The attributes for the column.
     *
     * @var array
     */
    public $attributes = [];

    /**
     * Create a new column definition.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->nullable = false;
        $this->default = null;
        $this->unsigned = false;
        $this->autoIncrement = false;
        $this->primary = false;
    }

    /**
     * Allow NULL values for this column.
     *
     * @return $this
     */
    public function nullable()
    {
        $this->nullable = true;

        return $this;
    }

    /**
     * Set the default value for this column.
     *
     * @param mixed $value
     * @return $this
     */
    public function default($value)
    {
        $this->default = $value;

        return $this;
    }

    /**
     * Set this column as the primary key.
     *
     * @return $this
     */
    public function primary()
    {
        $this->primary = true;

        return $this;
    }

    /**
     * Set the column as unsigned.
     *
     * @return $this
     */
    public function unsigned()
    {
        $this->unsigned = true;

        return $this;
    }

    /**
     * Set this column to auto-increment.
     *
     * @return $this
     */
    public function autoIncrement()
    {
        $this->autoIncrement = true;

        return $this;
    }

    /**
     * Handle dynamic property access.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? $this->$key ?? null;
    }

    /**
     * Handle dynamic property assignment.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }
}