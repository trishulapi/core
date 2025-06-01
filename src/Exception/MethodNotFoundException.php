<?php


namespace TrishulApi\Core\Exception;

use Exception;

class MethodNotFoundException extends Exception{


    public function __construct($message = "Method Not Found"){
        parent::__construct($message, 500);
    }
    
}