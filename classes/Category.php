<?php

namespace application;

require_once __DIR__ . "../vendor/autoload.php";

use MongoDB\Driver\Manager;
use MongoDB\Driver\Command;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Exception;
use MongoDB\Driver\WriteConcern;
use MongoDB\Driver\Query;

class Category
{
    private static $DATABASE_PATH = 'mongodb://localhost:27017';
    private static $DATABASE_NAME = 'OnlineCafeDatabase';
    private static $COLLECTION_NAME = 'Category';
    private static $connectionManager;
    private static $bulkOperationManager;
    private static $operationResult;
    private static $writeConcern;
    private static $queryManager;

    public function __construct()
    {
        self::$connectionManager = new MongoDB\Driver\Manager(self::$DATABASE_PATH);
        self::$bulkOperationManager = new MongoDB\Driver\BulkWrite;
        self::$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    }

    // set schema validation on collection
    public static function setSchemaValidation()
    {

    }

    // create category collection
    public static function createCategoryCollection()
    {
    }

    // drop category collection
    public static function dropCategoryCollection()
    {
    }

    // get all categories
    public static function getAllCategories()
    {
        try {
                self::$queryManager = new MongoDB\Driver\Query();
                self::$operationResult = self::$connectionManager->executeQuery(self::$DATABASE_NAME . '.' . self::$COLLECTION_NAME, self::$bulkOperationManager, self::$writeConcern);
                return self::$operationResult;
        } catch (MongoDB\Driver\Exception\Exception $exception) {
            return $exception->getMessage();
        }
    }

    // get one category
    public static function getOneCategory($categoryName)
    {
        try {
            if (isset($categoryName) && !empty($categoryName)) {
                $filter = ["categoryName" => $categoryName];
                $options = ['limit' => 1];
                self::$queryManager = new MongoDB\Driver\Query($filter, $options);
                self::$operationResult = self::$connectionManager->executeQuery(self::$DATABASE_NAME . '.' . self::$COLLECTION_NAME, self::$bulkOperationManager, self::$writeConcern);
                return self::$operationResult;
            } else {
                return false;
            }
        } catch (MongoDB\Driver\Exception\Exception $exception) {
            return $exception->getMessage();
        }
    }

    // insert categories group documents
    public static function insertCategoriesDocuments($multiCategoryNameArray)
    {
        try {
            if (isset($multiCategoryNameArray) && !empty($multiCategoryNameArray) && sizeof($multiCategoryNameArray) > 0) {
                foreach ($multiCategoryNameArray as $categoryName) {
                    self::$bulkOperationManager->insert(["categoryName" => $categoryName]);
                }
                self::$operationResult = self::$connectionManager->executeBulkWrite(self::$DATABASE_NAME . '.' . self::$COLLECTION_NAME, self::$bulkOperationManager, self::$writeConcern);
                var_dump(self::$operationResult);
                return true;
            } else {
                return false;
            }
        } catch (MongoDB\Driver\Exception\BulkWriteException $exception) {
            return $exception;
        }
    }

    // insert one category
    public static function insertCategoryDocument($categoryName)
    {
        try {
            if (isset($categoryName) && !empty($categoryName)) {
                self::$bulkOperationManager->insert(["categoryName" => $categoryName]);
                self::$operationResult = self::$connectionManager->executeBulkWrite(self::$DATABASE_NAME . '.' . self::$COLLECTION_NAME, self::$bulkOperationManager, self::$writeConcern);
                var_dump(self::$operationResult);
                return true;
            } else {
                return false;
            }
        } catch (MongoDB\Driver\BulkWriteException $exception) {
            return $exception->getMessage();
        }
    }

    // delete category documents
    public static function deleteCategoryDocuments($categoryName, $isAll)
    {
        try {
            if (isset($categoryName) && !empty($categoryName)) {
                $filter = ['categoryName' => $categoryName];
                if ($isAll == false) {
                    $options = ['limit' => 1];
                    self::$bulkOperationManager->delete($filter, $options);
                } else {
                    self::$bulkOperationManager->delete($filter);
                }
                self::$operationResult = self::$connectionManager->executeBulkWrite(self::$DATABASE_NAME . '.' . self::$COLLECTION_NAME, self::$bulkOperationManager, self::$writeConcern);
            } else {
                return false;
            }
        } catch (MongoDB\Driver\BulkWriteException $exception) {
            return $exception->getMessage();
        }
    }

    // update one category
    public static function updateOneCategory($old_categoryName, $new_categoryName, $isAll)
    {
        try {
            if (isset($old_categoryName) && !empty($old_categoryName) && isset($new_categoryName)
                && !empty($new_categoryName) && $old_categoryName != $new_categoryName) {
                $options = ['multi' => $isAll, 'upsert' => false];
                $filter = ['categoryName' => $old_categoryName];
                $update = ['$set' => ['categoryName' => $new_categoryName]];
                self::$bulkOperationManager->update($filter, $update, $options);
                self::$operationResult = self::$connectionManager->executeBulkWrite(self::$DATABASE_NAME . '.' . self::$COLLECTION_NAME, self::$bulkOperationManager, self::$writeConcern);
            } else {
                return false;
            }
        } catch (MongoDB\Driver\BulkWriteException $exception) {
            return $exception->getMessage();
        }
    }
}

///// todos
/// todo: setSchemaCollection : ()
/// todo: createCollection : ()
/// todo: dropCollection : ()
/////
/// Build Category - New
/// Build Room - New
/// Build Category - Legacy
/// Build Room - Legacy
/// Test Classes
/// But a Schema - Validation
