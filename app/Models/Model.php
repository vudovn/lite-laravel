<?php

namespace App\Models;

class Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $connection = null;

    public function __construct()
    {
        $this->connection = $this->getConnection();
    }

    protected function getConnection()
    {
        // Get database connection from the service container
        // The DatabaseManager returns a PDO instance
        return app('db')->connection();
    }

    public function all()
    {
        try {
            $statement = $this->connection->prepare("SELECT * FROM {$this->table}");
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

            // Convert results to collection of model instances
            return $this->hydrate($results);
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }

    // Update to static method with better error handling
    public static function find($id)
    {
        $instance = new static();

        try {
            $statement = $instance->connection->prepare(
                "SELECT * FROM {$instance->table} WHERE {$instance->primaryKey} = :id LIMIT 1"
            );

            $statement->execute(['id' => $id]);
            $result = $statement->fetch(\PDO::FETCH_ASSOC);

            if (!$result) {
                return null;
            }

            // Create a model instance with the result data
            return $instance->newFromArray($result);
        } catch (\PDOException $e) {
            // Log the error for debugging
            error_log("Database error: " . $e->getMessage());
            return null;
        }
    }

    // Change to static method
    public static function create(array $data)
    {
        $instance = new static();
        $filteredData = array_intersect_key($data, array_flip($instance->fillable));

        $columns = implode(', ', array_keys($filteredData));
        $placeholders = implode(', ', array_fill(0, count($filteredData), '?'));

        $statement = $instance->connection->prepare(
            "INSERT INTO {$instance->table} ({$columns}) VALUES ({$placeholders})"
        );

        $statement->execute(array_values($filteredData));
        $id = $instance->connection->lastInsertId();

        // Create a new model instance with the inserted data
        $newInstance = new static();
        $newInstance->{$instance->primaryKey} = $id;
        foreach ($filteredData as $key => $value) {
            $newInstance->{$key} = $value;
        }

        return $newInstance;
    }

    /**
     * Save the model instance to the database
     * 
     * @return bool
     */
    public function save()
    {
        try {
            // Check if the model exists by primary key
            $id = $this->{$this->primaryKey} ?? null;

            if ($id) {
                // Update an existing record
                return $this->update();
            } else {
                // Insert a new record
                $data = [];
                foreach ($this->fillable as $field) {
                    if (isset($this->{$field})) {
                        $data[$field] = $this->{$field};
                    }
                }

                $columns = implode(', ', array_keys($data));
                $placeholders = implode(', ', array_fill(0, count($data), '?'));

                $statement = $this->connection->prepare(
                    "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})"
                );

                $result = $statement->execute(array_values($data));
                if ($result) {
                    $this->{$this->primaryKey} = $this->connection->lastInsertId();
                }
                return $result;
            }
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update the model in the database
     * 
     * @return bool
     */
    public function update()
    {
        try {
            $data = [];
            foreach ($this->fillable as $field) {
                if (isset($this->{$field})) {
                    $data[$field] = $this->{$field};
                }
            }

            if (empty($data)) {
                return false;
            }

            $setClause = implode(' = ?, ', array_keys($data)) . ' = ?';
            $statement = $this->connection->prepare(
                "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = ?"
            );

            $values = array_values($data);
            $values[] = $this->{$this->primaryKey}; // Add primary key for WHERE clause

            return $statement->execute($values);
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete the model from the database
     * 
     * @return bool
     */
    public function delete()
    {
        try {
            if (!isset($this->{$this->primaryKey})) {
                return false;
            }

            $statement = $this->connection->prepare(
                "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?"
            );

            return $statement->execute([$this->{$this->primaryKey}]);
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    // Helper method to create a new model instance from array
    protected function newFromArray(array $attributes)
    {
        $model = new static();
        foreach ($attributes as $key => $value) {
            $model->{$key} = $value;
        }
        return $model;
    }

    // Helper method to create a collection of model instances
    protected function hydrate(array $items)
    {
        $models = [];
        foreach ($items as $item) {
            $models[] = $this->newFromArray($item);
        }
        return $models;
    }
}
