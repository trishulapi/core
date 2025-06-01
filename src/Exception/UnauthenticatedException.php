<?php


namespace TrishulApi\Core\Exception;

use Exception;
/**
 * This exception is thrown when user is not authenticated to access the resources.
 * 
 * 
 * @author Shyam Dubey
 * @since v1.0.0
 * @version 1.0.0
 */
class UnauthenticatedException extends Exception{

    public function __construct($message)
    {
        parent::__construct($message, 401);
    }
}