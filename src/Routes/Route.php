<?php

namespace TrishulApi\Core\Routes;

use TrishulApi\Core\Middleware\Middleware;


class Route
{
    private $method;
    private $url;
    private $callback;
    private array $middlewares = [];
    private string $summary = "";
    private string $description = "";
    private array $response_codes = [];
    private  $response_type = null;
    private bool $is_response_array = false;
    private string $tag = "";
    private bool $customSwagger = false;
    private  $swagger_object = null;
    private array $security = [];
    private string $consumes = "application/json";
    private string $produces = "application/json";
    private ?object $request_body = null;
    private bool $exclude_from_swagger = false;


    public function __construct(
        $method,
        $url,
        $callback,
        $middlewares = [],
        $summary = "",
        $description = "",
        $response_codes = [],
        $response_type = null,
        $is_response_array = false,
        $tag = "",
        $customSwagger = false,
        $swagger_object = null,
        $security = [],
        $consumes = "application/json",
        $produces = "application/json",
        $request_body = null,
        $exclude_from_swagger = false
    ) {
        $this->url = $url;
        $this->callback = $callback;
        $this->method = $method;
        $this->summary = $summary;
        $this->description = $description;
        $this->response_codes = $response_codes;
        $this->response_type = $response_type;
        $this->is_response_array = $is_response_array;
        $this->consumes = $consumes;
        $this->produces = $produces;
        $this->tag = $tag;
        $this->security = $security;
        $this->customSwagger = $customSwagger;
        $this->swagger_object = $swagger_object;
        $this->exclude_from_swagger = $exclude_from_swagger;
        $this->request_body = $request_body;

        if (count($middlewares) > 0) {
            foreach ($middlewares as $middleware) {
                $this->middlewares[] = new Middleware($middleware, false, []);
            }
        }
    }

    public function get_is_response_array(){
        return $this->is_response_array;
    }

    public function get_url()
    {
        return $this->url;
    }

    public function set_url($url){
        $this->url = $url;
    }

    public function get_callback()
    {
        return $this->callback;
    }

    public function get_method()
    {
        return $this->method;
    }

    public function get_middlewares():array
    {
        return $this->middlewares;
    }

    public function set_middlewares($middlewares){
        if(gettype($middlewares) == 'object' && !$middlewares instanceof Middleware){
            array_push($this->middlewares, new Middleware($middlewares, false, []));
        }
        else if (gettype($middlewares) == 'array' && !$middlewares instanceof Middleware){
            foreach($middlewares as $m){
                array_push($this->middlewares, new Middleware($m, false, []));
            }
        }
        else if($middlewares instanceof Middleware){
            array_push($this->middlewares, $middlewares);
        }
    }

    public function get_summary()
    {
        return $this->summary;
    }

    public function get_description()
    {
        return $this->description;
    }

    public function get_response_codes()
    {
        return $this->response_codes;
    }

    public function get_response_type()
    {
        return $this->response_type;
    }

    public function get_consumes()
    {
        return $this->consumes;
    }

    public function get_produces()
    {
        return $this->produces;
    }

    public function get_tag()
    {
        return $this->tag;
    }

    public function set_tag($tag){
        $this->tag = $tag;
    }

    public function get_security()
    {
        return $this->security;
    }

    public function get_is_customSwagger()
    {
        return $this->customSwagger;
    }

    public function get_swagger_object()
    {
        return $this->swagger_object;
    }

    public function get_exclude_from_swagger()
    {
        return $this->exclude_from_swagger;
    }

    public function get_request_body()
    {
        return $this->request_body;
    }

    public function get_custom_swagger(){
        return $this->customSwagger;
    }


}
