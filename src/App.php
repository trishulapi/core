<?php

namespace TrishulApi\Core;

use InvalidArgumentException;
use TrishulApi\Core\Exception\ExceptionHandler;
use TrishulApi\Core\Helpers\Environment;
use TrishulApi\Core\Http\RequestInitializer;
use TrishulApi\Core\Http\Router;
use TrishulApi\Core\Log\LoggerFactory;
use TrishulApi\Core\Security\CorsSecurity;
use TrishulApi\Core\Swagger\TrishulSwaggerBuilder;
use TrishulApi\Core\Swagger\TrishulSwagger;


class App
{

    private  $global_exception_handler_class;
    private static $env_path;

    public function __construct() {
        Router::get("/docs", TrishulSwaggerBuilder::class . "@generate_doc", exclude_from_swagger: true);
    }

    public function initialize() {}

    public function start()
    {
        if (!Environment::get('ENABLE_SWAGGER')) {
           Router::_remove_route(Router::get_route("/docs", "GET"));
        }

        if ($this->global_exception_handler_class == null) {
            $this->global_exception_handler_class = ExceptionHandler::class;
        }
        ExceptionHandler::init($this->global_exception_handler_class);
        CorsSecurity::init();
        $requestInitiazer = new RequestInitializer();
        $requestInitiazer->init();
    }


    public function set_global_exception_handler($exception_class)
    {
        $this->global_exception_handler_class = $exception_class;
    }



    public function set_allowed_domains($domains)
    {
        CorsSecurity::set_allowed_domain($domains);
    }


    public function get_allowed_domains()
    {
        return CorsSecurity::get_allowed_domains();
    }

    public function set_log_dir($dir)
    {
        if (strlen($dir) == 0) {
            throw new InvalidArgumentException("Please provide valid log directory");
        }

        LoggerFactory::set_log_dir($dir);
    }


    public function get_swagger()
    {
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
