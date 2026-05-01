<?php

namespace TrishulApi\Core\Middleware;

use TrishulApi\Core\Routes\Route;

class Middleware{
    private bool $is_global = false;
    private string $middleware_class_name;

    private array $except_routes = [];

    public function __construct(string $middleware_class_name, bool $is_global = false, array $except_routes = []){
        $this->middleware_class_name = $middleware_class_name;
        $this->is_global = $is_global;
        $this->except_routes = $except_routes;
    }

    public function get_is_global(){
        return $this->is_global;
    }

    public function set_is_global(bool $is_global){
        $this->is_global = $is_global;
    }

    public function add_new_except_route(Route $route){
        $this->except_routes[] = $route;
    }

    public function get_except_routes(){
        return $this->except_routes;
    }

    public function get_middleware_class_name(){
        return $this->middleware_class_name;
    }
}