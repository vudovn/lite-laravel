<?php

namespace Framework\Database\Traits;

use Framework\Database\Relations\HasOne;
use Framework\Database\Relations\HasMany;
use Framework\Database\Relations\BelongsTo;
use Framework\Database\Relations\BelongsToMany;

trait HasRelationships
{
    /**
     * Define a one-to-one relationship (has one)
     *
     * @param string $related Related model class
     * @param string $foreignKey Foreign key on the related table
     * @param string $localKey Local key on the current table
     * @return \Framework\Database\Relations\HasOne
     */
    public function hasOne($related, $foreignKey = null, $localKey = null)
    {
        $instance = new $related;

        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $localKey = $localKey ?: $this->primaryKey;

        return new HasOne($instance, $this, $foreignKey, $localKey);
    }

    /**
     * Define a one-to-many relationship (has many)
     *
     * @param string $related Related model class
     * @param string $foreignKey Foreign key on the related table
     * @param string $localKey Local key on the current table
     * @return \Framework\Database\Relations\HasMany
     */
    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        $instance = new $related;

        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $localKey = $localKey ?: $this->primaryKey;

        return new HasMany($instance, $this, $foreignKey, $localKey);
    }

    /**
     * Define an inverse one-to-one or one-to-many relationship (belongs to)
     *
     * @param string $related Related model class
     * @param string $foreignKey Foreign key on the current table
     * @param string $ownerKey Owner key on the related table
     * @return \Framework\Database\Relations\BelongsTo
     */
    public function belongsTo($related, $foreignKey = null, $ownerKey = null)
    {
        $instance = new $related;

        $foreignKey = $foreignKey ?: $this->getForeignKeyForRelated($instance);
        $ownerKey = $ownerKey ?: $instance->primaryKey;

        return new BelongsTo($instance, $this, $foreignKey, $ownerKey);
    }

    /**
     * Define a many-to-many relationship (belongs to many)
     *
     * @param string $related Related model class
     * @param string $table Pivot table name
     * @param string $foreignPivotKey Foreign key for current model on pivot table
     * @param string $relatedPivotKey Foreign key for related model on pivot table
     * @param string $parentKey Local key on the current table
     * @param string $relatedKey Local key on the related table
     * @return \Framework\Database\Relations\BelongsToMany
     */
    public function belongsToMany(
        $related,
        $table = null,
        $foreignPivotKey = null,
        $relatedPivotKey = null,
        $parentKey = null,
        $relatedKey = null
    ) {
        $instance = new $related;

        $table = $table ?: $this->getJoinTable($instance);

        $foreignPivotKey = $foreignPivotKey ?: $this->getForeignKey();
        $relatedPivotKey = $relatedPivotKey ?: $instance->getForeignKey();

        $parentKey = $parentKey ?: $this->primaryKey;
        $relatedKey = $relatedKey ?: $instance->primaryKey;

        return new BelongsToMany(
            $instance,
            $this,
            $table,
            $foreignPivotKey,
            $relatedPivotKey,
            $parentKey,
            $relatedKey
        );
    }

    /**
     * Get the default foreign key name for the model
     *
     * @return string
     */
    public function getForeignKey()
    {
        // Get class name (without namespace) and convert to snake_case
        $className = (new \ReflectionClass($this))->getShortName();
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className)) . '_id';
    }

    /**
     * Get the foreign key for a related model
     *
     * @param object $model
     * @return string
     */
    public function getForeignKeyForRelated($model)
    {
        // Get class name (without namespace) and convert to snake_case
        $className = (new \ReflectionClass($model))->getShortName();
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className)) . '_id';
    }

    /**
     * Get the default join table name for many-to-many relationship
     *
     * @param object $model
     * @return string
     */
    public function getJoinTable($model)
    {
        $models = [
            strtolower((new \ReflectionClass($this))->getShortName()),
            strtolower((new \ReflectionClass($model))->getShortName())
        ];

        // Sort for alphabetical order
        sort($models);

        return implode('_', $models);
    }
}