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
use TrishulApi\Core\Swagger\TrishulSwaggerBuilder;

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
    private static $routes = [["url" => "/docs", "method" => "GET", "middlewares" => [], 'exclude_from_swagger' => true, "callback" => TrishulSwaggerBuilder::class . "@generate_doc"]];
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
        $tag = "",
        $customSwagger = false,
        $swagger_object = "",
        $security = [],
        $consumes = "application/json",
        $produces = "application/json",
        $requestBody = null,
        $exclude_from_swagger = false
    ): array {
        $url = $url;
        $callback = $callback;

        $route["url"] = $url;
        $route["callback"] = $callback;
        $route["method"] = 'GET';
        $route["middlewares"] = $middlewares;
        $route["summary"] = $summary;
        $route["description"] = $description;
        $route["response_codes"] = $response_codes;
        $route["response_type"] = $response_type;
        $route["consumes"] = $consumes;
        $route["produces"] = $produces;
        $route["tag"] = $tag;
        $route["security"] = $security;
        $route["customSwagger"] = $customSwagger;
        $route["swagger_object"] = $swagger_object;
        $route["exclude_from_swagger"] = $exclude_from_swagger;
        $route['requestBody'] = $requestBody;


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
        $tag = "",
        $customSwagger = false,
        $swagger_object = "",
        $security = [],
        $consumes = "application/json",
        $produces = "application/json",
        $requestBody = null,
        $exclude_from_swagger = false

    ): array {
        $url = $url;
        $callback = $callback;

        $route["url"] = $url;
        $route["callback"] = $callback;
        $route["method"] = 'POST';
        $route["middlewares"] = $middlewares;
        $route["summary"] = $summary;
        $route["description"] = $description;
        $route["summary"] = $summary;
        $route["response_codes"] = $response_codes;
        $route["response_type"] = $response_type;
        $route["consumes"] = $consumes;
        $route["produces"] = $produces;
        $route["tag"] = $tag;
        $route["security"] = $security;
        $route["customSwagger"] = $customSwagger;
        $route["swagger_object"] = $swagger_object;
        $route["exclude_from_swagger"] = $exclude_from_swagger;
        $route['requestBody'] = $requestBody;


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
        $tag = "",
        $customSwagger = false,
        $swagger_object = "",
        $security = [],
        $consumes = "application/json",
        $produces = "application/json",
        $requestBody = null,
        $exclude_from_swagger = false

    ): array {
        $url = $url;
        $callback = $callback;

        $route["url"] = $url;
        $route["callback"] = $callback;
        $route["method"] = 'DELETE';
        $route["middlewares"] = $middlewares;
        $route["summary"] = $summary;
        $route["description"] = $description;
        $route["response_codes"] = $response_codes;
        $route["response_type"] = $response_type;
        $route["consumes"] = $consumes;
        $route["produces"] = $produces;
        $route["tag"] = $tag;
        $route["security"] = $security;
        $route["customSwagger"] = $customSwagger;
        $route["swagger_object"] = $swagger_object;
        $route["exclude_from_swagger"] = $exclude_from_swagger;
        $route['requestBody'] = $requestBody;


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
        $tag = "",
        $customSwagger = false,
        $swagger_object = "",
        $security = [],
        $consumes = "application/json",
        $produces = "application/json",
        $requestBody = null,
        $exclude_from_swagger = false

    ): array {
        $url = $url;
        $callback = $callback;

        $route["url"] = $url;
        $route["callback"] = $callback;
        $route["method"] = 'PUT';
        $route["middlewares"] = $middlewares;
        $route["summary"] = $summary;
        $route["description"] = $description;
        $route["response_codes"] = $response_codes;
        $route["response_type"] = $response_type;
        $route["consumes"] = $consumes;
        $route["produces"] = $produces;
        $route["tag"] = $tag;
        $route["security"] = $security;
        $route["customSwagger"] = $customSwagger;
        $route["swagger_object"] = $swagger_object;
        $route["exclude_from_swagger"] = $exclude_from_swagger;
        $route['requestBody'] = $requestBody;


        array_push(self::$routes, $route);
        return $route;
    }

    /**
     * This method is used to handle the merge request in your application.
     * it takes two required parameters (url and callback, middlewares = []) 
     * url at which you want to perform any callback 
     * Syntax for callback function is Service@function_name 
     * @example Router::merge("/user", "UserService@any_function);
     * middlewares are optional you can put middlewares in the method the request will pass through the middleware
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public static function merge(
        $url,
        $callback,
        $middlewares = [],
        $summary = "",
        $description = "",
        $response_codes = [],
        $response_type = "",
        $tag = "",
        $customSwagger = false,
        $swagger_object = "",
        $security = [],
        $consumes = "application/json",
        $produces = "application/json",
        $requestBody = null,
        $exclude_from_swagger = false

    ): array {
        $url = $url;
        $callback = $callback;

        $route["url"] = $url;
        $route["callback"] = $callback;
        $route["method"] = 'MERGE';
        $route["middlewares"] = $middlewares;
        $route["summary"] = $summary;
        $route["description"] = $description;
        $route["response_codes"] = $response_codes;
        $route["response_type"] = $response_type;
        $route["consumes"] = $consumes;
        $route["produces"] = $produces;
        $route["tag"] = $tag;
        $route["security"] = $security;
        $route["customSwagger"] = $customSwagger;
        $route["swagger_object"] = $swagger_object;
        $route["exclude_from_swagger"] = $exclude_from_swagger;
        $route['requestBody'] = $requestBody;
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
    public static function parent($url, array $childrens, $middlewares = [], $except = []): void
    {
        if ($childrens != null) {
            if (gettype($childrens) != 'array') {
                throw new InvalidArgumentException("Childrens must be array.");
            }

            foreach ($childrens as $ch) {
                $raw_url = $ch['url'];
                $ch['url'] = $url . $ch['url'];
                if ($ch['tag'] == "") {
                    $ch['tag'] = ucfirst(trim($url, "/"));
                }
                if (count($middlewares) > 0) {
                    if (gettype($except) == 'array' && count($except) > 0) {
                        foreach ($except as $excepted_url => $excepted_method) {
                            if ($ch['url'] != $excepted_url && $ch['method'] != $excepted_method) {
                                $ch['middlewares'] = $middlewares;
                                break;
                            }
                        }
                    } else {
                        $ch['middlewares'] = $middlewares;
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
        $tag = "",
        $customSwagger = false,
        $swagger_object = "",
        $security = [],
        $consumes = "application/json",
        $produces = "application/json",
        $requestBody = null,
        $exclude_from_swagger = false
    ) {
        $route["url"] = $url;
        $route["callback"] = $callback;
        $route["method"] = $method;
        $route["middlewares"] = $middlewares;
        $route["summary"] = $summary;
        $route["description"] = $description;
        $route["response_codes"] = $response_codes;
        $route["response_type"] = $response_type;
        $route["consumes"] = $consumes;
        $route["produces"] = $produces;
        $route["tag"] = $tag;
        $route["security"] = $security;
        $route["customSwagger"] = $customSwagger;
        $route["swagger_object"] = $swagger_object;
        $route["exclude_from_swagger"] = $exclude_from_swagger;
        $route['requestBody'] = $requestBody;

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
    public static function include($routes)
    {
        if ($routes != null) {
            foreach ($routes as $route) {
                array_push(self::$routes, $route);
            }
        }
    }


    private static function handle($url, $callback, $requestMethod, $params = [], $middlewares = []): void
    {

        if ($_SERVER['REQUEST_METHOD'] != $requestMethod) {
            return;
        }
        self::$middlewaresQueue = [];
        self::$middleware_ojbects_queue = [];
        $request = new Request($url);
        if (gettype($middlewares) == 'object') {
            array_push(self::$middlewaresQueue, $middlewares);
        } else if (count($middlewares) > 0) {
            self::$middlewaresQueue = array_merge($middlewares);
        }
        if (count(self::$exempted_routes) == 0 && count(self::$global_middlewares) > 0) {
            self::$middlewaresQueue = array_merge(self::$middlewaresQueue, self::$global_middlewares);
        } else if (count(self::$exempted_routes) > 0) {
            $exempted_routes_url = array_keys(self::$exempted_routes);
            if ($url !== "/") {
                if (!in_array(rtrim($url, "/"), $exempted_routes_url) && $requestMethod != self::$exempted_routes[$url]) {
                    self::$middlewaresQueue = array_merge(self::$middlewaresQueue, self::$global_middlewares);
                }
            }
        }
        foreach (self::$middlewaresQueue as $middleware) {
            $m = new $middleware;
            if (!$m instanceof MiddlewareInterface) {
                throw new NotAnInstanceException($m::class . " is not instance of " . MiddlewareInterface::class);
            }
            array_push(self::$middleware_ojbects_queue, $m);
            $request = $m->handle_request($request);
            $request->update($request);
        }

        if (gettype($callback) == 'array' || gettype($callback) == 'object') {
            Response::out(HttpStatus::OK, $callback);
        } else if (!stripos($callback, "@")) {
            Response::out(HttpStatus::OK, $callback);
        }
        $arr = explode("@", $callback);
        $controller = $arr[0];
        $controller_method = $arr[1];


        if (class_exists($controller)) {
            if (!method_exists($controller, $controller_method)) {
                throw new MethodNotFoundException($controller_method . " method not found in class " . $controller);
            }
            $container = new Container;
            $instance = $container->provide($controller, $request);
            $request->set_path($params);
            $response = $instance->$controller_method($params);
            if (!$response instanceof Response) {
                $response = Response::json(HttpStatus::OK, $response);
            }
            foreach (self::$middleware_ojbects_queue as $m) {
                if (!$m instanceof MiddlewareInterface) {
                    throw new NotAnInstanceException($m::class . " is not instance of " . MiddlewareInterface::class);
                }
                $response = $m->handle_response($response);
            }
            if ($response instanceof Response) {
                if ($response->get_return_type() == Response::RETURN_TYPE_JSON) {
                    self::$logger->info("Sending Response [" . $response->get_status_code() . "]");
                    header("content-type:application/json");
                    http_response_code($response->get_status_code());
                    echo json_encode($response->get_body()->data());
                    self::$is_request_completed = true;
                    die();
                } else {
                    throw new InvalidResponseTypeException("Invalid Response Type. It should be Response::json()");
                }
            } else {
                throw new InvalidResponseTypeException("Invalid Response Type. It should be Response::json()");
            }
        } else {
            throw new ClassNotFoundException($controller . " Class Not Found.");
        }
    }


    /**
     * This method searches for all the routes which you have added in index.php file. 
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public static function init(): void
    {
        self::$logger = LoggerFactory::get_logger(self::class);

        $routeUri = $_SERVER['REQUEST_URI'];
        $host_path = Environment::get("HOST_PATH") ?? "";
        if ($host_path == "") {
            $host_path = "/";
        }
        if ($host_path != "/" && strpos($routeUri, $host_path) === 0) {
            $routeUri = substr($routeUri, strlen($host_path));
        }
        if (strpos($routeUri, "?") !== false) {
            $routeUri = explode("?", $routeUri)[0];
        }
        $routeUri = rtrim($routeUri, "/");

        self::$logger->info("Incoming Request[" . $_SERVER['REQUEST_METHOD'] . "] on Url: " . $routeUri . " ");

        if (count(self::$routes) > 0) {
            $route_found = false;
            foreach (self::$routes as $route) {
                $url = trim($routeUri, '/');
                //if ? in the url
                if (strpos($url, "?")) {
                    $explode = explode("?", $url);
                    $url = trim($explode[0], "/");
                }
                $patternRegex = preg_replace('/\{(\w+)\}/', '(?P<\1>[^/]+)', trim($route['url'], '/'));
                $patternRegex = "@^" . $patternRegex . "$@";

                if (preg_match($patternRegex, $url, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    $route_found = true;
                    self::handle($route['url'], $route['callback'], $route['method'], $params, $middleware = $route['middlewares']);
                }
            }
            if (!$route_found) {
                throw new ResourceNotFoundException("Not Found");
            }
            if (!self::$is_request_completed) {
                throw new ResourceNotFoundException("Not Found");
            }
        } else {
            throw new ResourceNotFoundException("Not Found");
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
}

