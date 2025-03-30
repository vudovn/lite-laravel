<?php

namespace Framework\Database\Relations;

use Framework\Database\Model;

class HasMany extends Relation
{
    /**
     * The foreign key of the parent model.
     *
     * @var string
     */
    protected $foreignKey;

    /**
     * The local key of the parent model.
     *
     * @var string
     */
    protected $localKey;

    /**
     * Create a new has many relationship instance.
     *
     * @param \Framework\Database\Model $related
     * @param \Framework\Database\Model $parent
     * @param string $foreignKey
     * @param string $localKey
     * @return void
     */
    public function __construct(Model $related, Model $parent, $foreignKey, $localKey)
    {
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;

        parent::__construct($related, $parent);
    }

    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    public function addConstraints()
    {
        $this->query->where($this->foreignKey, $this->parent->{$this->localKey});
    }

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        return $this->query->where($this->foreignKey, $this->parent->{$this->localKey})->get();
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
            $dictionary[$result->{$this->foreignKey}][] = $result;
        }

        foreach ($models as $model) {
            if (isset($dictionary[$model->{$this->localKey}])) {
                $model->setRelation($relation, $dictionary[$model->{$this->localKey}]);
            }
        }

        return $models;
    }
}