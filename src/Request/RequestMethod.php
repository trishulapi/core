<?php

namespace TrishulApi\Core\Request;

class RequestMethod {

   private $method;

   public function __construct() {
    $this->method = $_SERVER["REQUEST_METHOD"];
   }

   public function get_method(){
    return $this->method;
   }



}