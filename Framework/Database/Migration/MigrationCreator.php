<?php

namespace Framework\Database\Migration;

class MigrationCreator
{
    /**
     * Get the migration stub file.
     *
     * @return string
     */
    protected function getStub($table, $create)
    {
        $stub = $create
            ? file_get_contents(__DIR__ . '/stubs/migration.create.stub')
            : file_get_contents(__DIR__ . '/stubs/migration.update.stub');

        return $this->populateStub($stub, $table);
    }

    /**
     * Populate the place-holders in the migration stub.
     *
     * @param string $stub
     * @param string $table
     * @return string
     */
    protected function populateStub($stub, $table)
    {
        $className = $this->getClassName($table);

        $stub = str_replace('{{ class }}', $className, $stub);
        $stub = str_replace('{{ table }}', $table, $stub);

        return $stub;
    }

    /**
     * Create a new migration file.
     *
     * @param string $name
     * @param string $path
     * @param string $table
     * @param bool $create
     * @return string
     */
    public function create($name, $path, $table = null, $create = false)
    {
        $table = $table ?: $this->getTableName($name);
        $className = $this->getClassName($table);

        $path = rtrim($path, '/') . '/';
        $filename = date('Y_m_d_His_') . $name . '.php';
        $filePath = $path . $filename;

        $this->ensureDirectoryExists($path);

        file_put_contents($filePath, $this->getStub($table, $create));

        return $filePath;
    }

    /**
     * Ensure the migration directory exists.
     *
     * @param string $path
     * @return void
     */
    protected function ensureDirectoryExists($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    /**
     * Get the class name for a migration name.
     *
     * @param string $name
     * @return string
     */
    protected function getClassName($name)
    {
        $name = str_replace(['create_', 'update_', '_table'], '', $name);
        $name = str_replace('_', ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);

        return $name . 'Table';
    }

    /**
     * Get the table name from the migration name.
     *
     * @param string $name
     * @return string
     */
    protected function getTableName($name)
    {
        if (preg_match('/^create_(\w+)_table$/', $name, $matches)) {
            return $matches[1];
        }

        if (preg_match('/^update_(\w+)_table$/', $name, $matches)) {
            return $matches[1];
        }

        return null;
    }
}