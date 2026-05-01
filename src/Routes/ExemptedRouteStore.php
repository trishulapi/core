<?php

namespace TrishulApi\Core\Routes;

class ExemptedRouteStore{
    private array $routes;

    public function __construct(array $routes){
        $this->routes = $routes;
    }

    public function add(Route $route){
        $this->routes[] = $route;
    }

    public function all(): array{
        return $this->routes;
    }

    public function remove(Route $route){
        $this->routes = array_diff($this->routes, [$route]);
    }
}