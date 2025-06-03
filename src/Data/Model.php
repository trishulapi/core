<?php

namespace TrishulApi\Core\Data;

use TrishulApi\Core\Data\QueryBuilder;
/**
 * Base Model class for interacting with database tables.
 * Provides methods for CRUD operations and relationships.
 * 
 * @package TrishulApi\Core\Data\Model
 */
abstract class Model
{
    public static string $table_name;
    public static string $primary_key = 'id';

    public function __construct($table_name)
    {
        self::$table_name = $table_name;
    }

    /**
     * Retrieves all records from the table.
     * 
     * @return array An array of associative arrays representing the records.
     */
    public static function all()
    {
        $query = "SELECT * FROM " . static::class::$table_name;
        $pdo = DB::get_connection();
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        // $data = [];
        // while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        //     $data[] = self::mapWithModel($row, static::class);
        // }
        // return $data;
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);  
    }

    /** 
     * Retrieves a record by its ID.
     */
    public static function getById($id)
    {
        $query = "SELECT * FROM " . static::class::$table_name . " WHERE " . static::class::$primary_key . " = :id";
        $pdo = DB::get_connection();
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    /**
     * Creates a new record in the table.
     * 
     * @param array $data An associative array of column names and their values.
     * @return int The ID of the newly created record.
     */
    public static function create(array $data)
    {
        $fields = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $query = "INSERT INTO " . static::class::$table_name . " ($fields) VALUES ($placeholders)";
        $pdo = DB::get_connection();
        $stmt = $pdo->prepare($query);
        foreach ($data as $key => &$value) {
            $stmt->bindParam(":$key", $value);
        }
        $stmt->execute();
        return $pdo->lastInsertId();
    }
    /**
     * Updates a record by its ID.
     * 
     * @param int $id The ID of the record to update.
     * @param array $data An associative array of column names and their new values.
     * @return bool Returns true on success, false on failure.
     */
    public static function update($id, array $data)
    {
        $set = "";
        foreach ($data as $key => $value) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ", ");
        $query = "UPDATE " . static::class::$table_name . " SET $set WHERE " . static::class::$primary_key . " = :id";
        $pdo = DB::get_connection();
        $stmt = $pdo->prepare($query);
        foreach ($data as $key => &$value) {
            $stmt->bindParam(":$key", $value);
        }
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }
    /**
     * Deletes a record by its ID.
     * If soft deletes are enabled, it will set a deleted_at timestamp instead of actually deleting the record. 
     */
    public static function delete($id)
    {
        $query = "DELETE FROM " . static::class::$table_name . " WHERE " . static::class::$primary_key . " = :id";
        $pdo = DB::get_connection();
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Finds records based on the given conditions, limit, and offset.
     * 
     * @param array $conditions An array of conditions to filter the records.
     * @param int $limit The maximum number of records to return.
     * @param int $offset The offset for pagination.
     * @return array An array of associative arrays representing the records.
     */
    public static function softDelete($id)
    {
        $query = "UPDATE " . static::class::$table_name . " SET deleted_at = NOW() WHERE " . static::class::$primary_key . " = :id";
        $pdo = DB::get_connection();
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }


    /**
     *
     * Finds records based on the given conditions, limit, and offset.
     */
    public static function find($conditions = [], $limit = 10, $offset = 0)
    {
        $query = "SELECT * FROM " . static::class::$table_name;
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        $query .= " LIMIT :limit OFFSET :offset";
        $pdo = DB::get_connection();
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    
    /**
     * Finds records based on the given conditions, limit, and offset.
     * 
     * @param array $conditions An array of conditions to filter the records.
     * @param int $limit The maximum number of records to return.
     * @param int $offset The offset for pagination.
     * @return array An array of associative arrays representing the records.
     */
    public static function where($conditions = [], $limit = 10, $offset = 0)
    {
        $query = "SELECT * FROM " . static::class::$table_name;
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        $query .= " LIMIT :limit OFFSET :offset";
        $pdo = DB::get_connection();
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Finds records based on the given conditions, limit, and offset.
     * 
     * @param array $conditions An array of conditions to filter the records.
     * @param int $limit The maximum number of records to return.
     * @param int $offset The offset for pagination.
     * @return array An array of associative arrays representing the records.
     */
    public static function filter($conditions = [], $limit = 10, $offset = 0)
    {
        $query = "SELECT * FROM " . static::class::$table_name;
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        $query .= " LIMIT :limit OFFSET :offset";
        $pdo = DB::get_connection();
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * Counts the number of records in the table based on the given conditions.
     */
    public static function count($conditions = [])
    {
        $query = "SELECT COUNT(*) FROM " . static::class::$table_name;
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        $pdo = DB::get_connection();
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    /**
     * Checks if any records exist in the table based on the given conditions.
     * 
     * @param array $conditions An array of conditions to filter the records.
     * @return bool Returns true if at least one record exists, false otherwise.
     */
    public static function exists($conditions = [])
    {
        $query = "SELECT COUNT(*) FROM " . static::class::$table_name;
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        $pdo = DB::get_connection();
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    /**
     * Executes a raw SQL query and returns the result.
     */
    public static function rawQuery($query, $params = [])
    {
        $pdo = DB::get_connection();
        $stmt = $pdo->prepare($query);
        foreach ($params as $key => &$value) {
            $stmt->bindParam(":$key", $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    /**
     * Executes a raw SQL query and returns a single record.
     */
    public static function rawQuerySingle($query, $params = [])
    {
        $pdo = DB::get_connection();
        $stmt = $pdo->prepare($query);
        foreach ($params as $key => &$value) {
            $stmt->bindParam(":$key", $value);
        }
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    /**
     * Executes a raw SQL query and returns the count of records.
     */
    public static function rawQueryCount($query, $params = [])
    {
        $pdo = DB::get_connection();
        $stmt = $pdo->prepare($query);
        foreach ($params as $key => &$value) {
            $stmt->bindParam(":$key", $value);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    /**
     * Executes a raw SQL query and checks if any records exist.
     */
    public static function rawQueryExists($query, $params = [])
    {
        $pdo = DB::get_connection();
        $stmt = $pdo->prepare($query);
        foreach ($params as $key => &$value) {
            $stmt->bindParam(":$key", $value);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    /**
     * Executes a transaction with the provided callback.
     * 
     * @param callable $callback A function that contains the database operations to be executed within the transaction.
     * @return mixed The result of the callback function.
     * @throws \Exception If an error occurs during the transaction.
     */
    public static function transaction(callable $callback)
    {
        $pdo = DB::get_connection();
        try {
            $pdo->beginTransaction();
            $result = $callback($pdo);
            $pdo->commit();
            return $result;
        } catch (\Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Returns the name of the table associated with the model.
     * 
     * @return string The name of the table.
     */
    public static function getTableName()
    {
        return static::$table_name;
    }

    /*   * Returns all related records based on the foreign key and local key.
     * 
     * @param string $relatedModel The fully qualified class name of the related model.
     * @param string $foreignKey The foreign key in the related model.
     * @param string $localKey The local key in the main model (default is 'id').
     * @return array An array of instances of the related model.
     */
    public static function hasMany($relatedModel, $foreignKey, $localKey = 'id')
    {
        $relatedTable = $relatedModel::getTableName();
        $query = "SELECT * FROM " . static::class::$table_name . " AS main JOIN " . $relatedTable . " AS related ON main.$localKey = related.$foreignKey";
        return self::rawQuery($query);
    }

    /**
     * Returns a single related record based on the foreign key and local key.
     * 
     * @param string $relatedModel The fully qualified class name of the related model.
     * @param string $foreignKey The foreign key in the related model.
     * @param string $localKey The local key in the main model (default is 'id').
     * @return Model|null An instance of the related model or null if no record is found.
     */
    public static function belongsTo($relatedModel, $foreignKey, $localKey = 'id')
    {
        $relatedTable = $relatedModel::getTableName();
        $query = "SELECT * FROM " . static::class::$table_name . " AS main JOIN " . $relatedTable . " AS related ON main.$foreignKey = related.$localKey";
        return self::rawQuerySingle($query);
    }
    /**
     * Returns a single related record based on the foreign key and local key.
     * 
     * @param string $relatedModel The fully qualified class name of the related model.
     * @param string $foreignKey The foreign key in the related model.
     * @param string $localKey The local key in the main model (default is 'id').
     * @return Model|null An instance of the related model or null if no record is found.
     */
    public static function hasOne($relatedModel, $foreignKey, $localKey = 'id')
    {
        $relatedTable = $relatedModel::getTableName();
        $query = "SELECT * FROM " . static::class::$table_name . " AS main JOIN " . $relatedTable . " AS related ON main.$localKey = related.$foreignKey LIMIT 1";
        return self::rawQuerySingle($query);
    }


    /**
     * Returns all related records based on the foreign key and local key.
     * 
     * @param string $relatedModel The fully qualified class name of the related model.
     * @param string $foreignKey The foreign key in the related model.
     * @param array $conditions An array of conditions to filter the related records.
     * @param string $localKey The local key in the main model (default is 'id').
     * @return array An array of instances of the related model.
     */
    public static function hasManyWithConditions($relatedModel, $foreignKey, $conditions = [], $localKey = 'id')
    {
        $relatedTable = $relatedModel::getTableName();
        $query = "SELECT * FROM " . static::class::$table_name . " AS main JOIN " . $relatedTable . " AS related ON main.$localKey = related.$foreignKey";
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        return self::rawQuery($query);
    }
    /*     * Returns a single record from the related model based on the foreign key and conditions.
     * 
     * @param string $relatedModel The fully qualified class name of the related model.
     * @param string $foreignKey The foreign key in the related model.
     * @param array $conditions An array of conditions to filter the records.
     * @param string $localKey The local key in the main model (default is 'id').
     * @return Model|null An instance of the related model or null if no record is found.
     */
    public static function belongsToWithConditions($relatedModel, $foreignKey, $conditions = [], $localKey = 'id')
    {
        $relatedTable = $relatedModel::getTableName();
        $query = "SELECT * FROM " . static::class::$table_name . " AS main JOIN " . $relatedTable . " AS related ON main.$foreignKey = related.$localKey";
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        return self::rawQuerySingle($query);
    }
    /*     * Returns a single related record based on the foreign key and local key.
     * 
     * @param string $relatedModel The fully qualified class name of the related model.
     * @param string $foreignKey The foreign key in the related model.
     * @param array $conditions An array of conditions to filter the related records.
     * @param string $localKey The local key in the main model (default is 'id').
     * @return Model|null An instance of the related model or null if no record is found.
     */
    public static function hasOneWithConditions($relatedModel, $foreignKey, $conditions = [], $localKey = 'id')
    {
        $relatedTable = $relatedModel::getTableName();
        $query = "SELECT * FROM " . static::class::$table_name . " AS main JOIN " . $relatedTable . " AS related ON main.$localKey = related.$foreignKey LIMIT 1";
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        return self::rawQuerySingle($query);
    }
    /**
     * Returns the primary key of the model.
     * 
     * @return string The primary key of the model.
     */
    public static function getPrimaryKey()
    {
        return static::$primary_key;
    }

    /**
     * Returns the fully qualified class name of the model.
     * 
     * @return string The fully qualified class name of the model.
     */
    public static function getModelClass()
    {
        return static::class;
    }

    /**
     * Returns a new instance of QueryBuilder for the model.
     * 
     * @return QueryBuilder A new instance of QueryBuilder.
     */
    public static function queryBuilder()
    {
        return new QueryBuilder(static::class);
    }


    /**
     * Maps an associative array to a model instance.
     * 
     * @param array $data The associative array containing the data.
     * @param string $modelClass The fully qualified class name of the model.
     * @return Model An instance of the model with the data mapped to its properties.
     * @throws \Exception If the model class does not exist or does not extend Model.
     */
    private static function mapWithModel(array $data, string $modelClass)
    {
        $model = new $modelClass();

        $relection = new \ReflectionClass($model);
        if (!$relection->isSubclassOf(Model::class)) {
            throw new \Exception("Model class must extend Model");
        }
        if (!class_exists($modelClass)) {
            throw new \Exception("Model class does not exist: " . $modelClass);
        }

        $vars = $relection->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);

        foreach ($vars as $var) {
            $var->setAccessible(true);
            $type = $var->getType();
            $type_name = ($type ? $type->getName() : 'None');
            $varName = $var->getName();
            if (isset($data[$varName])) {
                $var->setValue($model, $data[$varName]);
            }
        }
        return $model;
    }
}
