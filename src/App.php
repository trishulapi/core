<?php

namespace TrishulApi\Core;

use InvalidArgumentException;
use TrishulApi\Core\Exception\ExceptionHandler;
use TrishulApi\Core\Http\Router;
use TrishulApi\Core\Log\LoggerFactory;
use TrishulApi\Core\Security\CorsSecurity;
use TrishulApi\Core\Swagger\OrionSwagger;
use TrishulApi\Core\Swagger\TrishulSwagger;

/**
 * This is the main Class of this framework. It acts as main entry point for all reqeusts.
 * @author Shyam Dubey
 * @since v1.0.0
 * @version v1.0.0
 *
 */
class App
{

    private  $global_exception_handler_class;
    private static $env_path;

    /**
     * This function starts the application by ensuring that Routes are initialized and global exception handling is started.
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public function start()
    {
        //keep this function on the first line so that it can handle all exceptions globally.
        if($this->global_exception_handler_class == null){
            $this->global_exception_handler_class = ExceptionHandler::class;
        }

        ExceptionHandler::init($this->global_exception_handler_class);
        CorsSecurity::init();

        Router::init();

    }

    /**
     * This function sets the global exception handler class. By default we have added class @link OrionApi\Exception\ExceptionHandler 
     * which handles all the exceptions globally and generates the logs and output. 
     * Keep this function on the top of the index.php file. so that it can handle and generate logs without any unwanted output.
     * 
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public function set_global_exception_handler($exception_class)
    {
        $this->global_exception_handler_class = $exception_class;
    }



    public function set_allowed_domains($domains)
    {
        CorsSecurity::set_allowed_domain($domains);
    }


    public function get_allowed_domains(){
        return CorsSecurity::get_allowed_domains();
    }

    public function set_log_dir($dir){
        if(strlen($dir) == 0){
            throw new InvalidArgumentException("Please provide valid log directory");
        }

        LoggerFactory::set_log_dir($dir);
    }


    public function get_swagger(){
        return TrishulSwagger::get_instance();
    }


    public function set_env_path($path)
    {
        self::$env_path = $path;
    }

    public function get_env_path()
    {
        return self::$env_path;
    }
}

