<?php

namespace Framework\Database;

class QueryBuilder
{
    protected $model;
    protected $wheres = [];
    protected $selects = ['*'];
    protected $limit;
    protected $offset;
    protected $orders = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function select($columns)
    {
        $this->selects = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    public function where($column, $operator = null, $value = null)
    {
        // If only 2 arguments provided, assume '=' operator
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->wheres[] = compact('column', 'operator', 'value');
        return $this;
    }

    public function orWhere($column, $operator = null, $value = null)
    {
        // Implementation similar to where but with OR logic
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function orderBy($column, $direction = 'asc')
    {
        $this->orders[] = compact('column', 'direction');
        return $this;
    }

    public function get()
    {
        // In a real implementation, this would execute the query
        // For now, let's assume we have a getResults method that returns data
        $results = $this->getResults();

        // Convert all results to model objects
        $models = [];
        foreach ($results as $row) {
            $models[] = $this->model->newFromArray($row);
        }

        return $models;
    }

    public function first()
    {
        $this->limit(1);
        $results = $this->get();

        return !empty($results) ? $results[0] : null;
    }

    public function find($id)
    {
        return $this->where($this->model->getPrimaryKey(), $id)->first();
    }

    public function paginate($perPage = 15)
    {
        // Implementation would handle pagination
        return [
            'data' => $this->get(),
            'total' => 0,
            'per_page' => $perPage,
            'current_page' => 1,
            'last_page' => 1,
        ];
    }

    public function toSql()
    {
        // Build and return SQL string
        return '';
    }

    protected function getResults()
    {
        // This is a stub that would normally run the SQL query
        // For now, return an empty array
        return [];
    }
}
