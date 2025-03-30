<?php

namespace Framework\Database;

use Framework\Database\Traits\HasRelationships;

abstract class Model
{
    use HasRelationships;

    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $guarded = ['id'];
    protected $connection = null;
    protected $attributes = [];
    protected $original = [];
    protected $relations = [];

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        $this->connection = $this->getConnection();
    }

    protected function getConnection()
    {
        return app('db');
    }

    public static function find($id)
    {
        return (new static)->newQuery()->find($id);
    }

    public static function all()
    {
        $instance = new static();
        $results = $instance->newQuery()->get();

        return $instance->newCollection($results);
    }

    public static function where($column, $operator = null, $value = null)
    {
        return (new static)->newQuery()->where($column, $operator, $value);
    }

    public static function create(array $attributes)
    {
        $model = new static($attributes);
        $model->save();
        return $model;
    }

    public function save()
    {
        if (empty($this->attributes[$this->primaryKey])) {
            $this->insert();
        } else {
            $this->update();
        }

        return $this;
    }

    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }

        return $this;
    }

    public function isFillable($key)
    {
        if (in_array($key, $this->guarded)) {
            return false;
        }

        return empty($this->fillable) || in_array($key, $this->fillable);
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * Get an attribute from the model.
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        // First check attributes array
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        // Then check for relationships
        if (method_exists($this, $key)) {
            return $this->getRelationValue($key);
        }

        return null;
    }

    /**
     * Get a relationship value from a method.
     *
     * @param string $key
     * @return mixed
     */
    public function getRelationValue($key)
    {
        // If relation is already loaded, return it
        if (array_key_exists($key, $this->relations)) {
            return $this->relations[$key];
        }

        // Otherwise, load it
        if (method_exists($this, $key)) {
            $relationship = $this->$key();

            if ($relationship instanceof Relations\Relation) {
                $this->relations[$key] = $relationship->getResults();
                return $this->relations[$key];
            }
        }

        return null;
    }

    /**
     * Set a relationship on the model.
     *
     * @param string $relation
     * @param mixed $value
     * @return $this
     */
    public function setRelation($relation, $value)
    {
        $this->relations[$relation] = $value;

        return $this;
    }

    /**
     * Get the current relationships.
     *
     * @return array
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * Determine if a relationship exists.
     *
     * @param string $key
     * @return bool
     */
    public function relationLoaded($key)
    {
        return array_key_exists($key, $this->relations);
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function newFromArray(array $attributes)
    {
        $model = new static();

        foreach ($attributes as $key => $value) {
            $model->setAttribute($key, $value);
        }

        // Set original attributes to track changes
        $model->original = $model->attributes;

        return $model;
    }

    /**
     * Create a new query builder instance.
     *
     * @return \Framework\Database\QueryBuilder
     */
    public function newQuery()
    {
        return new QueryBuilder($this);
    }

    protected function insert()
    {
        // Implementation would connect to a real database and insert
        // For now, let's use a simplified version
        $fillable = array_filter($this->attributes, function ($key) {
            return $this->isFillable($key);
        }, ARRAY_FILTER_USE_KEY);

        $cols = implode(', ', array_keys($fillable));
        $placeholders = implode(', ', array_fill(0, count($fillable), '?'));

        $sql = "INSERT INTO {$this->getTable()} ($cols) VALUES ($placeholders)";

        // In a real implementation, execute the query and get the last insert ID
        // For now we'll just simulate it
        $this->attributes[$this->primaryKey] = count($this->original) + 1;
        $this->original = $this->attributes;

        return true;
    }

    protected function update()
    {
        // Real implementation would update the database
        $this->original = $this->attributes;
        return true;
    }

    protected function newCollection(array $models = [])
    {
        return $models;
    }

    public function getTable()
    {
        if (!$this->table) {
            // Convert class name to snake_case and pluralize
            $className = (new \ReflectionClass($this))->getShortName();
            return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className)) . 's';
        }

        return $this->table;
    }
}
