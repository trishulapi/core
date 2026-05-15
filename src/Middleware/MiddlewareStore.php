<?php

namespace TrishulApi\Core\Middleware;

use TrishulApi\Core\Http\Router;

class MiddlewareStore{

  private array $middlewares = [];
  public function __construct(){
    $global_middlewares = Router::get_global_middlewares();
    foreach($global_middlewares as $m){
      $middleware = new Middleware($m);
      $middleware->set_is_global(true);
      $this->add($middleware);
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