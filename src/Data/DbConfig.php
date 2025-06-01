<?php


namespace TrishulApi\Core\Data; 

/**
 * This class is responsible for providing database configuration to the @link{DbConfig} class. 
 * It takes $host, $port, $database_type, $username, $password, $database_name parameters
 * @param :host the Database URL
 * @param :port port on which database can be accessed.
 * @param :database_type [MYSQL, ORACLE, POSTGRES ETC]
 * @param :username database username
 * @param :password database password
 * @param :database_name database name (schema name like orion_api_db)
 * 
 * 
 * @author Shyam Dubey
 * @since v1.0.0 
 * @version v1.0.0 
 */
class DbConfig
{

    private static $host;
    private static $username;
    private static $password;
    private static $database_name;
    private static $database_type;
    private static $port;


    public function __construct($host, $port, $database_type, $username, $password, $database_name)
    {
        self::$host = $host;
        self::$username = $username;
        self::$password = $password;
        self::$database_name = $database_name;
        self::$database_type = $database_type;
        self::$port = $port;
    }


    public function get_host()
    {
        return self::$host;
    }

    public function get_username()
    {
        return self::$username;
    }

    public function get_password()
    {
        return self::$password;
    }

    public function get_database_name()
    {
        return self::$database_name;
    }

    public function get_database_type()
    {
        return self::$database_type;
    }

    public function get_port()
    {
        return self::$port;
    }

}
