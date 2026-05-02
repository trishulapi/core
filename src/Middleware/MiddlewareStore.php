<?php

namespace TrishulApi\Core\Middleware;

class MiddlewareStore{

  private array $middlewares = [];
  public function __construct(array $middlewares, array $exempted_routes){
    if(count($middlewares) > 0){
      foreach($middlewares as $m){
        $m->add_new_except_routes($exempted_routes);
        array_push($this->middlewares, $m);
      }
    }
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