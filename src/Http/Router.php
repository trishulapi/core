<?php

namespace TrishulApi\Core\Http;

use InvalidArgumentException;
use TrishulApi\Core\Di\Container;
use TrishulApi\Core\Enums\HttpStatus;
use TrishulApi\Core\Exception\ClassNotFoundException;
use TrishulApi\Core\Exception\InvalidResponseTypeException;
use TrishulApi\Core\Exception\MethodNotFoundException;
use TrishulApi\Core\Exception\NotAnInstanceException;
use TrishulApi\Core\Exception\ResourceNotFoundException;
use TrishulApi\Core\Helpers\Environment;
use TrishulApi\Core\Log\LoggerFactory;
use TrishulApi\Core\Middleware\Middleware;
use TrishulApi\Core\Middleware\MiddlewareInterface;
use TrishulApi\Core\Routes\Route;


class Router
{

    private static $middlewaresQueue = [];
    private static $middleware_ojbects_queue = [];
    private static $global_middlewares = [];
    private static $routes = [];
    private static $exempted_routes = [];
    private static $is_request_completed;
    private static $logger;


    public static function get(
        $url,
        $callback,
        $middlewares = [],
        $summary = "",
        $description = "",
        $response_codes = [],
        $response_type = "",
        $is_response_array = false,
        $tag = "",
        $customSwagger = false,
        $swagger_object = "",
        $security = [],
        $consumes = "application/json",
        $produces = "application/json",
        $requestBody = null,
        $exclude_from_swagger = false
    ): Route {
        $route = new Route(
            RequestType::GET,
            $url,
            $callback,
            $middlewares,
            $summary,
            $description,
            $response_codes,
            $response_type,
            $is_response_array,
            $tag,
            $customSwagger,
            $swagger_object,
            $security,
            $consumes = "application/json",
            $produces = "application/json",
            $requestBody = null,
            $exclude_from_swagger = false
        );

        array_push(self::$routes, $route);
        return $route;
    }


    public static function post(
        $url,
        $callback,
        $middlewares = [],
        $summary = "",
        $description = "",
        $response_codes = [],
        $response_type = "",
        $is_response_array = false,
        $tag = "",
        $customSwagger = false,
        $swagger_object = "",
        $security = [],
        $consumes = "application/json",
        $produces = "application/json",
        $requestBody = null,
        $exclude_from_swagger = false

    ): Route {
        $route = new Route(
            RequestType::POST,
            $url,
            $callback,
            $middlewares,
            $summary,
            $description,
            $response_codes,
            $response_type,
            $is_response_array,
            $tag,
            $customSwagger,
            $swagger_object,
            $security,
            $consumes = "application/json",
            $produces = "application/json",
            $requestBody = null,
            $exclude_from_swagger = false
        );
        array_push(self::$routes, $route);
        return $route;
    }


    public static function delete(
        $url,
        $callback,
        $middlewares = [],
        $summary = "",
        $description = "",
        $response_codes = [],
        $response_type = "",
        $is_response_array = false,
        $tag = "",
        $customSwagger = false,
        $swagger_object = "",
        $security = [],
        $consumes = "application/json",
        $produces = "application/json",
        $requestBody = null,
        $exclude_from_swagger = false

    ): Route {
        $route = new Route(
            RequestType::DELETE,
            $url,
            $callback,
            $middlewares,
            $summary,
            $description,
            $response_codes,
            $response_type,
            $is_response_array,
            $tag,
            $customSwagger,
            $swagger_object,
            $security,
            $consumes = "application/json",
            $produces = "application/json",
            $requestBody = null,
            $exclude_from_swagger = false
        );
        array_push(self::$routes, $route);
        return $route;
    }


    public static function put(
        $url,
        $callback,
        $middlewares = [],
        $summary = "",
        $description = "",
        $response_codes = [],
        $response_type = "",
        $is_response_array = false,
        $tag = "",
        $customSwagger = false,
        $swagger_object = "",
        $security = [],
        $consumes = "application/json",
        $produces = "application/json",
        $requestBody = null,
        $exclude_from_swagger = false

    ): Route {
        $route = new Route(
            RequestType::PUT,
            $url,
            $callback,
            $middlewares,
            $summary,
            $description,
            $response_codes,
            $response_type,
            $is_response_array,
            $tag,
            $customSwagger,
            $swagger_object,
            $security,
            $consumes = "application/json",
            $produces = "application/json",
            $requestBody = null,
            $exclude_from_swagger = false
        );
        array_push(self::$routes, $route);
        return $route;
    }

    public static function merge(
        $url,
        $callback,
        $middlewares = [],
        $summary = "",
        $description = "",
        $response_codes = [],
        $response_type = "",
        $is_response_array = false,
        $tag = "",
        $customSwagger = false,
        $swagger_object = "",
        $security = [],
        $consumes = "application/json",
        $produces = "application/json",
        $requestBody = null,
        $exclude_from_swagger = false

    ): Route {
        $route = new Route(
            RequestType::MERGE,
            $url,
            $callback,
            $middlewares,
            $summary,
            $description,
            $response_codes,
            $response_type,
            $is_response_array,
            $tag,
            $customSwagger,
            $swagger_object,
            $security,
            $consumes = "application/json",
            $produces = "application/json",
            $requestBody = null,
            $exclude_from_swagger = false
        );
        array_push(self::$routes, $route);
        return $route;
    }


    public static function parent(string $url, array $childrens, array $middlewares = [], array $except = [])
    {
        $routes = [];
        if ($childrens != null) {
            if (gettype($childrens) != 'array') {
                throw new InvalidArgumentException("Childrens must be array.");
            }
            foreach ($childrens as $ch) {
                $ch->set_url($url . $ch->get_url());
                if ($ch->get_tag() == "") {
                    $ch->set_tag(ucfirst(trim($url, "/")));
                }
                if (count($middlewares) > 0) {
                    if (gettype($except) == 'array' && count($except) > 0) {
                        foreach ($except as $excepted_url => $excepted_method) {
                            if ($ch->get_url() == $excepted_url && $ch->get_method() == $excepted_method) {
                            } else {
                                $ch->set_middlewares($middlewares);
                            }
                        }
                    } else {
                        $ch->set_middlewares($middlewares);
                    }
                }
                array_push(self::$routes, $ch);
                array_push($routes, $ch);
            }
        }
        return $routes;
    }


    public static function children(
        $method,
        $url,
        $callback,
        $middlewares = [],
        $summary = "",
        $description = "",
        $response_codes = [],
        $response_type = "",
        $is_response_array = false,
        $tag = "",
        $customSwagger = false,
        $swagger_object = "",
        $security = [],
        $consumes = "application/json",
        $produces = "application/json",
        $requestBody = null,
        $exclude_from_swagger = false
    ) {
        $route = new Route(
            $method,
            $url,
            $callback,
            $middlewares,
            $summary,
            $description,
            $response_codes,
            $response_type,
            $is_response_array,
            $tag,
            $customSwagger,
            $swagger_object,
            $security,
            $consumes = "application/json",
            $produces = "application/json",
            $requestBody = null,
            $exclude_from_swagger = false
        );
        return $route;
    }


    public static function include(array $routes)
    {
        if ($routes != null) {
            foreach ($routes as $route) {
                array_push(self::$routes, $route);
            }
        }
    }


    public static function set_global_middlewares($middlewares, $except = []): void
    {
        $except["/docs"] = "GET";
        if (gettype($middlewares) == 'array') {
            self::$global_middlewares = array_merge(self::$global_middlewares, $middlewares);
        } else {
            array_push(self::$global_middlewares, $middlewares);
        }
        if (count($except) > 0) {
            foreach ($except as $path => $method) {
                $route = Router::get_route($path, $method);
                if ($route != null) {
                    array_push(self::$exempted_routes, $route);
                }
            }
        }
    }

    public static function get_exempted_routes(): array
    {
        return self::$exempted_routes;
    }

    public static function get_global_middlewares(): array
    {
        $global_middlewares = [];
        if (count(self::$global_middlewares) > 0) {
            foreach (self::$global_middlewares as $m) {
                $global_middlewares[] = new Middleware($m, true, self::$exempted_routes);
            }
        }
        return $global_middlewares;
    }

    public static function get_routes(): array
    {
        return self::$routes;
    }

    public static function get_route($path, $method)
    {
        $routes = self::get_routes();
        foreach ($routes as $route) {
            if ($route->get_url() == $path && $route->get_method() == $method) {
                return $route;
            }
        }
        return null;
    }

    public static function _remove_route($route){
       self::$routes = array_diff($route, self::$routes);
    }
}
