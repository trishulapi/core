<?php

namespace TrishulApi\Core\Data;

use Exception;
use PDO;
use TrishulApi\Core\Helpers\Environment;

/**
 * Responsible for providing Database Connection Object. 
 * 
 * @author Shyam Dubey
 * @since v1.0.0 
 * @version v1.0.0 
 */
class DB
{


    private static DbConfig $db_config;
    private static $connection;


    public function __construct()
    {
        if(Environment::get('DB_HOST') == null || Environment::get('DB_PORT') == null || Environment::get('DB_TYPE') == null || Environment::get('DB_USERNAME') == null || Environment::get('DB_PASSWORD') == null || Environment::get('DB_NAME') == null) {
            throw new Exception("Database configuration is not set properly. Please check your environment variables.");
        }
        if (self::$connection != null) {
            return;
        }
        // Initialize the DbConfig with environment variables
        if (self::$db_config != null) {
            return;
        }
        self::$db_config = new DbConfig(Environment::get("DB_HOST"),Environment::get("DB_PORT"),Environment::get("DB_TYPE"),Environment::get("DB_USERNAME"),Environment::get("DB_PASSWORD"),Environment::get("DB_NAME"));
    }


    public static function get_connection()
    {
        if (self::$connection == null) {
             if(Environment::get('DB_HOST') == null || Environment::get('DB_PORT') == null || Environment::get('DB_TYPE') == null || Environment::get('DB_USERNAME') == null || Environment::get('DB_PASSWORD') == null || Environment::get('DB_NAME') == null) {
            throw new Exception("Database configuration is not set properly. Please check your environment variables.");
        }
        self::$db_config = new DbConfig(Environment::get("DB_HOST"),Environment::get("DB_PORT"),Environment::get("DB_TYPE"),Environment::get("DB_USERNAME"),Environment::get("DB_PASSWORD"),Environment::get("DB_NAME"));
            try {
                switch (strtolower(self::$db_config->get_database_type())) {
                    case "mysql":
                        $dsn = "mysql:host=" . self::$db_config->get_host() . ";dbname=" . self::$db_config->get_database_name() . ";charset=utf8mb4";
                        break;
                    case "postgres":
                        $port = self::$db_config->get_port() ?? 5432;
                        $dsn = "pgsql:host=" . self::$db_config->get_host() . ";port=" . $port . ";dbname=" . self::$db_config->get_database_name() . "";
                        break;
                    case "oracle":
                        $port = self::$db_config->get_port() ?? 1521;
                        $dsn = "oci:dbname=//" . self::$db_config->get_host() . ":" . $port . "/" . self::$db_config->get_database_name() . "";
                        break;
                    default:
                        throw new Exception("Unsupported database type: " . self::$db_config->get_database_type());
                }

                self::$connection = new PDO($dsn, self::$db_config->get_username(), self::$db_config->get_password());
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return self::$connection;
            } catch (Exception $e) {
                throw new Exception("Database connection failed " . $e->getMessage());
            }
        } else {
            return self::$connection;
        }
    }
}
