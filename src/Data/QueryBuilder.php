<?php

namespace TrishulApi\Core\Data;
use TrishulApi\Core\Data\DB;
/**
 * Base Model class for interacting with database tables.
 * Provides methods for CRUD operations and relationships.
 * 
 * @package TrishulApi\Core\Data\Model
 */

class QueryBuilder
{
    private string $table;
    private string $modelClass;
    private array $fields = [];
    private array $conditions = [];
    private array $orderBy = [];
    private int $limit = 0;

    public function __construct(string $modelClass)
    {
        $model = new $modelClass();
        if (!$model instanceof Model) {
            throw new \InvalidArgumentException("The provided class must be an instance of Model.");
        }
        $this->table = $model::getTableName();
        $this->modelClass = $modelClass;
    }

    public function select(array $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    public function where(string $condition): self
    {
        $this->conditions[] = $condition;
        return $this;
    }

    public function orderBy(string $field, string $direction = 'ASC'): self
    {
        $this->orderBy[] = "$field $direction";
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function build(): string
    {
        $query = "SELECT " . implode(", ", $this->fields) . " FROM " . $this->table;

        if (!empty($this->conditions)) {
            $query .= " WHERE " . implode(" AND ", $this->conditions);
        }

        if (!empty($this->orderBy)) {
            $query .= " ORDER BY " . implode(", ", $this->orderBy);
        }

        if ($this->limit > 0) {
            $query .= " LIMIT " . $this->limit;
        }

        return $query;
    }

    public function getModelClass(): string
    {
        return $this->modelClass;
    }


    public function get(): array
    {
        // This method would typically execute the built query against the database
        // and return the results as an array.
        // For now, we will just return the built query as a placeholder.
        $clz = new $this->modelClass();
        return $clz::rawQuery($this->build());
    }
    public function getFirst(): array
    {
        // This method would typically execute the built query against the database
        // and return the first result as an array.
        // For now, we will just return the built query as a placeholder.
        $this->limit(1);
         $clz = new $this->modelClass();
        return $clz::rawQuery($this->build());;
    }
    public function getCount(): int
    {
         $clz = new $this->modelClass();
        return $clz::rawQueryCount($this->build());
    }
    public function getTable(): string
    {
        return $this->table;
    }
    public function getFields(): array
    {
        return $this->fields;
    }
    public function getConditions(): array
    {
        return $this->conditions;
    }
    public function getOrderBy(): array
    {
        return $this->orderBy;
    }
    public function getLimit(): int
    {
        return $this->limit;
    }
    public function __toString(): string
    {
        return $this->build();
    }
}