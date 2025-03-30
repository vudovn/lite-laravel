<?php

namespace Framework\Database\Relations;

use Framework\Database\Model;

class BelongsTo extends Relation
{
    /**
     * The foreign key of the parent model.
     *
     * @var string
     */
    protected $foreignKey;

    /**
     * The associated key on the parent model.
     *
     * @var string
     */
    protected $ownerKey;

    /**
     * Create a new belongs to relationship instance.
     *
     * @param \Framework\Database\Model $related
     * @param \Framework\Database\Model $parent
     * @param string $foreignKey
     * @param string $ownerKey
     * @return void
     */
    public function __construct(Model $related, Model $parent, $foreignKey, $ownerKey)
    {
        $this->foreignKey = $foreignKey;
        $this->ownerKey = $ownerKey;

        parent::__construct($related, $parent);
    }

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        if (is_null($this->parent->{$this->foreignKey})) {
            return null;
        }

        return $this->query->where($this->ownerKey, $this->parent->{$this->foreignKey})->first();
    }

    /**
     * Associate the model instance to the parent model.
     *
     * @param \Framework\Database\Model $model
     * @return \Framework\Database\Model
     */
    public function associate($model)
    {
        $this->parent->{$this->foreignKey} = $model->{$this->ownerKey};

        return $this->parent;
    }

    /**
     * Dissociate the relationship.
     *
     * @return \Framework\Database\Model
     */
    public function dissociate()
    {
        $this->parent->{$this->foreignKey} = null;

        return $this->parent;
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
            $model->setRelation($relation, null);
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
            $dictionary[$result->{$this->ownerKey}] = $result;
        }

        foreach ($models as $model) {
            if (isset($dictionary[$model->{$this->foreignKey}])) {
                $model->setRelation($relation, $dictionary[$model->{$this->foreignKey}]);
            }
        }

        return $models;
    }
}