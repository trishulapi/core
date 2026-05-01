<?php

namespace TrishulApi\Core\Http;

use TrishulApi\Core\Exception\NullPointerException;
/**
 * This class deals with QueryParams of a request
 * 
 * @author Shyam Dubey
 * @version v1.0.0 
 * @since v1.0.0 
 */
class QueryParams
{
    private $data;
    public function __construct()
    {
        $this->data = $_GET;    
    }


    /**
     * Checks whether the QueryParams exists or not
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0
     */
    public function has($key): bool
    {
        if (isset($this->data[$key])) {
            return true;
        }
        return false;
    }

    /**
     * Returns the value of provided key 
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0
     */
    public function get($key): string|null
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }
        return null;
    }


}
