<?php

namespace TrishulApi\Core\Middleware;

class MiddlewareStore{

  private array $middlewares = [];
  public function __construct(){
  }

  public function add(Middleware $middleware){
    $this->middlewares[] = $middleware;
  }

  public function remove(Middleware $middleware){
    foreach($this->middlewares as $key => $middleware){
        if($middleware === $middleware){
            unset($this->middlewares[$key]);
        }
    }
  }


  public function get_global(){
    $global = [];
    foreach($this->middlewares as $middleware){
      if($middleware->get_is_global()){
        $global[] = $middleware;
      }
    }
    return $global;
  }

}