<?php

namespace TrishulApi\Core\Http;

use TrishulApi\Core\Di\Container;
use TrishulApi\Core\Enums\HttpStatus;
use TrishulApi\Core\Exception\ClassNotFoundException;
use TrishulApi\Core\Exception\InvalidResponseTypeException;
use TrishulApi\Core\Exception\MethodNotFoundException;
use TrishulApi\Core\Exception\NotAnInstanceException;
use TrishulApi\Core\Log\LoggerFactory;
use TrishulApi\Core\Middleware\MiddlewareInterface;
use TrishulApi\Core\Middleware\MiddlewareQueue;
use TrishulApi\Core\Middleware\MiddlewareStore;
use TrishulApi\Core\Response\ResponseHandler;
use TrishulApi\Core\Routes\ExemptedRouteStore;
use TrishulApi\Core\Routes\Route;

class RequestHandler
{

    private $logger;
    private MiddlewareStore $middlewareStore;
    private MiddlewareQueue $middlewaresQueue;
    private ExemptedRouteStore $exempted_route_store;
    private array $middlewaresObjectsQueue = [];
    public function __construct()
    {
        $this->logger = LoggerFactory::get_logger(self::class);
        $this->middlewareStore = new MiddlewareStore();
        $this->middlewaresQueue = new MiddlewareQueue([]);
        $this->exempted_route_store = new ExemptedRouteStore([]);
    }

    public function handle(Route $route, Request $request, $params): void
    {
        if (gettype($route->get_middlewares()) == 'object') {
            $this->middlewaresQueue->add_multiple($route->get_middlewares());
        } else if (count($route->get_middlewares()) > 0) {
            $this->middlewaresQueue->add_multiple($route->get_middlewares());
        }
        $global_middlewares = $this->middlewareStore->get_global();
        if (count($global_middlewares) > 0) {
            foreach ($global_middlewares as $middleware) {
                if (!in_array($route, $middleware->get_except_routes())) {
                    $this->middlewaresObjectsQueue[] = $middleware;
                }
            }
        } else if (count($this->exempted_route_store->all()) > 0) {
            if (!in_array($route, $this->exempted_route_store->all())) {
                $this->logger->info("Implementing Middlewares on route " . $route->get_url() . "");
                $this->middlewaresQueue->add_multiple($route->get_middlewares());
            }
        }
        foreach ($this->middlewaresQueue->get_queue() as $middleware) {
            $m_class_name = $middleware->get_middleware_class_name();
            $m = new $m_class_name;
            if (!$m instanceof MiddlewareInterface) {
                throw new NotAnInstanceException($m::class . " is not instance of " . MiddlewareInterface::class);
            }
            array_push($this->middlewaresObjectsQueue, $m);
            $request = $m->handle_request($request);
            $request->update($request);
        }
        if (gettype($route->get_callback()) == 'array' || gettype($route->get_callback()) == 'object') {
            new ResponseHandler(new Response($route->get_callback(), HttpStatus::OK), $request, $this->middlewaresObjectsQueue);
        } else if (!stripos($route->get_callback(), "@")) {
            new ResponseHandler(new Response($route->get_callback(), HttpStatus::OK), $request, $this->middlewaresObjectsQueue);
        }
        $arr = explode("@", $route->get_callback());
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
            new ResponseHandler($response, $request, $this->middlewaresObjectsQueue);
        } else {
            throw new ClassNotFoundException($controller . " Class Not Found.");
        }
    }
}
