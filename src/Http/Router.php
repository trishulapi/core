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
use TrishulApi\Core\Middleware\MiddlewareInterface;
use TrishulApi\Core\Routes\Route;

/**
 * This class contains functions for handling requests in application.
 * @author Shyam Dubey
 * @since v1.0.0
 * @version v1.0.0
 */
class Router
{

    private static $middlewaresQueue = [];
    private static $middleware_ojbects_queue = [];
    private static $global_middlewares = [];
    private static $routes = [];
    private static $exempted_routes = ["/docs" => RequestType::GET];
    private static $is_request_completed;
    private static $logger;


    /**
     * This method is used to handle the get request in your application.
     * it takes two required parameters (url and callback, middlewares = []) 
     * url at which you want to perform any callback 
     * Syntax for callback function is Service@function_name 
     * @example Router::get("/user", "UserService@get_all_users); 
     * This Router will now call the function get_all_users() of UserService.
     * middlewares are optional you can put middlewares in the method the request will pass through the middleware
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
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

    /**
     * This method is used to handle the post request in your application.
     * it takes two required parameters (url and callback, middlewares = []) 
     * url at which you want to perform any callback 
     * Syntax for callback function is Service@function_name 
     * @example Router::post("/user", "UserService@save_user);
     * This Router will now call the function get_all_users() of UserService.
     * middlewares are optional you can put middlewares in the method the request will pass through the middleware
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
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

    /**
     * This method is used to handle the delete request in your application.
     * it takes two required parameters (url and callback, middlewares = []) 
     * url at which you want to perform any callback 
     * Syntax for callback function is Service@function_name 
     * @example Router::delete("/user/{user_id}", "UserService@delete_by_id);
     * Now how you will get the {user_id} value in function delete_by_id(Request $req) 
     * 
     * 
     * --------------------------------------------------------
     * 
     * 
     * class UserService {
     * 
     *    public function delele_by_id(Request $req){
     *          $user_id = $req->path()->get('user_id'); ////// the user_id should be same as you have written in your Router::delete
     *      }
     * }
     * 
     * After gettig the $user_id you can perform action to delete the user. 
     * middlewares are optional you can put middlewares in the method the request will pass through the middleware
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
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

    /**
     * This method is used to handle the put request in your application.
     * it takes two required parameters (url and callback, middlewares = []) 
     * url at which you want to perform any callback 
     * Syntax for callback function is Service@function_name 
     * @example Router::put("/user", "UserService@update_user);
     * 
     * 
     * You can get the Request_body by using the Request::get_body() function anywhere in the classes.
     * 
     * middlewares are optional you can put middlewares in the method the request will pass through the middleware
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
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


    /**
     * The function acts as a parent route. It requires two required params
     * @param string $url
     * @param array $childrens
     * 
     * childrens are basically build by another function Router::children()
     * 
     * @param array $middlewares - These will be applied on every child of this parent.
     * @param array $except - Takes urls which you want to exempt from Middlewares applied on
     * parent.
     * 
     * $except = ["/users" => RequestType::POST]
     * 
     * 
     * This functions also act as group urls under a parent for example. Methods of UserService class 
     * can be grouped under the parent url /users 
     * followed by the childrens like /getAll, /username/{username}
     * 
     * 
     * @author Shyam Dubey
     * @since v1.0.0 
     * @version v1.0.0 
     */
    public static function parent(string $url, array $childrens, array $middlewares = [], array $except = []): void
    {
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
                            if ($ch->get_url() != $excepted_url && $ch->get_method() != $excepted_method) {
                                $ch->set_middlewares($middlewares);
                                break;
                            }
                        }
                    } else {
                        $ch->set_middlewares($middlewares);
                    }
                }
                array_push(self::$routes, $ch);
            }
        }
    }

    /**
     * This function is alled inside the Router::parent() 
     * This function builds the child urls in the parent url. 
     * 
     * @author Shyam Dubey
     * @since v1.0.0 
     * @version v1.0.0
     */
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



    /**
     * This function is used to include the routes from another file.
     * 
     * @param array $routes - Array of routes which you want to include in the current Router.
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public static function include(array $routes)
    {
        if ($routes != null) {
            foreach ($routes as $route) {
                array_push(self::$routes, $route);
            }
        }
    }



    /** 
     * This function takes array of middlewares which applied on each request. It sets these global middlewares on every route.
     * Use this to set the middlewares on each route.
     * 
     * 
     * If you want to exempt some routes from these global middlewares, you can provide those routes as a second parameter in the form of array. 
     * 
     * The name should be exact match of what you have written in Route::get()post() ... etc functions.
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public static function set_global_middlewares($middlewares, $except = []): void
    {
        if (gettype($middlewares) == 'array') {
            self::$global_middlewares = array_merge(self::$global_middlewares, $middlewares);
        } else {
            array_push(self::$global_middlewares, $middlewares);
        }

        self::$exempted_routes = array_merge(self::$exempted_routes, $except);
    }

    public static function get_routes(): array
    {
        return self::$routes;
    }

    public static function get_route($path, $method)
    {
        $routes = self::get_routes();
        foreach ($routes as $route) {
            if ($route->url == $path && $route->method = $method) {
                return $route;
            }
        }
        return null;
    }
}
