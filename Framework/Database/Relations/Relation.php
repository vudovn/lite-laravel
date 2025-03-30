<?php

namespace Framework\Database\Relations;

use Framework\Database\Model;
use Framework\Database\QueryBuilder;

abstract class Relation
{
    /**
     * The related model instance
     *
     * @var \Framework\Database\Model
     */
    protected $related;

    /**
     * The parent model instance
     *
     * @var \Framework\Database\Model
     */
    protected $parent;

    /**
     * The query builder instance for the relation
     *
     * @var \Framework\Database\QueryBuilder
     */
    protected $query;

    /**
     * Create a new relation instance.
     *
     * @param \Framework\Database\Model $related
     * @param \Framework\Database\Model $parent
     * @return void
     */
    public function __construct(Model $related, Model $parent)
    {
        $this->related = $related;
        $this->parent = $parent;
        $this->query = $related->newQuery();
    }

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    abstract public function getResults();

    /**
     * Add a basic where clause to the query.
     *
     * @param string|array|\Closure $column
     * @param mixed $operator
     * @param mixed $value
     * @param string $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        $this->query->where($column, $operator, $value, $boolean);

        return $this;
    }

    /**
     * Add an "order by" clause to the query.
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'asc')
    {
        $this->query->orderBy($column, $direction);

        return $this;
    }

    /**
     * Set the limit value of the query.
     *
     * @param int $value
     * @return $this
     */
    public function limit($value)
    {
        $this->query->limit($value);

        return $this;
    }

    /**
     * Set the "offset" value of the query.
     *
     * @param int $value
     * @return $this
     */
    public function offset($value)
    {
        $this->query->offset($value);

        return $this;
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param array $columns
     * @return array
     */
    public function get($columns = ['*'])
    {
        return $this->query->get($columns);
    }

    /**
     * Get the first result from the relationship.
     *
     * @return \Framework\Database\Model|null
     */
    public function first()
    {
        return $this->query->first();
    }

    /**
     * Get the model instance being queried.
     *
     * @return \Framework\Database\Model
     */
    public function getModel()
    {
        return $this->related;
    }

    /**
     * Get the parent model instance being queried.
     *
     * @return \Framework\Database\Model
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get the underlying query builder instance.
     *
     * @return \Framework\Database\QueryBuilder
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Handle dynamic method calls to the relationship.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $result = $this->query->$method(...$parameters);

        if ($result === $this->query) {
            return $this;
        }

        return $result;
    }
}