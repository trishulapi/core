<?php

namespace TrishulApi\Core\Http;

use TrishulApi\Core\Exception\ResourceNotFoundException;
use TrishulApi\Core\Helpers\Environment;
use TrishulApi\Core\Log\LoggerFactory;
use TrishulApi\Core\Middleware\MiddlewareStore;

class RequestInitializer{

private $logger;
private $routes;
private RequestHandler $request_handler;
    public function __construct()
    {
        $this->routes = Router::get_routes();
        $this->logger = LoggerFactory::get_logger(self::class);
        $this->request_handler = new RequestHandler();
    }

    
    public function init(): void
    {
        $request = new Request($_SERVER['REQUEST_URI']);
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
        $this->logger->info("[" . $_SERVER['REQUEST_METHOD'] . "] Request  on Url: " . $routeUri . " ");
        if (count($this->routes) > 0) {
            $route_found = false;
            foreach ($this->routes as $route) {
                $url = trim($routeUri, '/');
                if(is_null($route)){
                    continue;
                }
                //if ? in the url
                if (strpos($url, "?")) {
                    $explode = explode("?", $url);
                    $url = trim($explode[0], "/");
                }

                // First check if route is static (no {} placeholders)
                if (strpos($url, '{') === false && $url === trim($route->get_url(), "/") && $route->get_method() === $_SERVER['REQUEST_METHOD']) {
                    $route_found = true;
                    $this->request_handler->handle($route, $request, []);
                    break;
                }
            }
            if (!$route_found) {
                foreach ($this->routes as $route) {
                    if($route == null || $route->get_url() == null){
                        continue;
                    }
                    $url = trim($routeUri, '/');
                    //if ? in the url
                    if (strpos($url, "?")) {
                        $explode = explode("?", $url);
                        $url = trim($explode[0], "/");
                    }
                    $patternRegex = preg_replace('/\{(\w+)\}/', '(?P<\1>[^/]+)', trim($route->get_url(), '/'));
                    $patternRegex = "@^" . $patternRegex . "$@";

                    if (preg_match($patternRegex, $url, $matches)) {
                        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                        $route_found = true;
                        $this->request_handler->handle($route, $request, $params);
                    }
                }
            }
            if (!$route_found) {
                throw new ResourceNotFoundException("URL Not Found");
            }
            if (!$request->get_is_request_completed()) {
                throw new ResourceNotFoundException("Not Found");
            }
        } else {
            throw new ResourceNotFoundException("Not Found");
        }
    }
}