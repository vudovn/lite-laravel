<?php

namespace Framework\Database\Relations;

use Framework\Database\Model;

class BelongsToMany extends Relation
{
    /**
     * The pivot table name.
     *
     * @var string
     */
    protected $table;

    /**
     * The foreign key of the parent model.
     *
     * @var string
     */
    protected $foreignPivotKey;

    /**
     * The foreign key of the related model.
     *
     * @var string
     */
    protected $relatedPivotKey;

    /**
     * The local key of the parent model.
     *
     * @var string
     */
    protected $parentKey;

    /**
     * The local key of the related model.
     *
     * @var string
     */
    protected $relatedKey;

    /**
     * Create a new belongs to many relationship instance.
     *
     * @param \Framework\Database\Model $related
     * @param \Framework\Database\Model $parent
     * @param string $table
     * @param string $foreignPivotKey
     * @param string $relatedPivotKey
     * @param string $parentKey
     * @param string $relatedKey
     * @return void
     */
    public function __construct(
        Model $related,
        Model $parent,
        $table,
        $foreignPivotKey,
        $relatedPivotKey,
        $parentKey,
        $relatedKey
    ) {
        $this->table = $table;
        $this->foreignPivotKey = $foreignPivotKey;
        $this->relatedPivotKey = $relatedPivotKey;
        $this->parentKey = $parentKey;
        $this->relatedKey = $relatedKey;

        parent::__construct($related, $parent);
    }

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        // Build a query that joins the pivot table to get all related models
        $query = $this->related->newQuery();

        // Add join to pivot table
        $query->join(
            $this->table,
            $this->related->getTable() . '.' . $this->relatedKey,
            '=',
            $this->table . '.' . $this->relatedPivotKey
        );

        // Add constraint for parent model
        $query->where(
            $this->table . '.' . $this->foreignPivotKey,
            $this->parent->{$this->parentKey}
        );

        return $query->get();
    }

    /**
     * Attach models to the parent.
     *
     * @param mixed $ids
     * @param array $attributes
     * @return void
     */
    public function attach($ids, array $attributes = [])
    {
        $ids = is_array($ids) ? $ids : [$ids];

        // Build array of pivot records
        $records = [];
        foreach ($ids as $id) {
            $records[$this->foreignPivotKey] = $this->parent->{$this->parentKey};
            $records[$this->relatedPivotKey] = $id;

            // Add additional attributes
            foreach ($attributes as $key => $value) {
                $records[$key] = $value;
            }

            // Insert the record
            // For a real implementation, this would use a query builder to insert
            // $this->connection->table($this->table)->insert($records);
        }
    }

    /**
     * Detach models from the parent.
     *
     * @param mixed $ids
     * @return void
     */
    public function detach($ids = null)
    {
        // For a real implementation, this would use a query builder to delete

        // Start a base query for the pivot table
        $query = "DELETE FROM {$this->table} WHERE {$this->foreignPivotKey} = ?";
        $params = [$this->parent->{$this->parentKey}];

        // If specific IDs are provided, add them to the condition
        if ($ids !== null) {
            $ids = is_array($ids) ? $ids : [$ids];

            $query .= " AND {$this->relatedPivotKey} IN (";
            $query .= implode(', ', array_fill(0, count($ids), '?'));
            $query .= ")";

            $params = array_merge($params, $ids);
        }

        // In a real implementation, execute the query
        // $this->connection->execute($query, $params);
    }

    /**
     * Initialize the relation on a set of models.
     *
     * @param array $models
     * @param string $relation
     * @return array
     */
    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation($relation, []);
        }

        return $models;
    }

    /**
     * Match the eagerly loaded results to their parents.
     *
     * @param array $models
     * @param array $results
     * @param string $relation
     * @return array
     */
    public function match(array $models, array $results, $relation)
    {
        $dictionary = [];

        foreach ($results as $result) {
            $pivotKey = $result->pivot->{$this->foreignPivotKey};
            $dictionary[$pivotKey][] = $result;
        }

        foreach ($models as $model) {
            $key = $model->{$this->parentKey};

            if (isset($dictionary[$key])) {
                $model->setRelation($relation, $dictionary[$key]);
            }
        }

        return $models;
    }
}