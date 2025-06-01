<?php

namespace TrishulApi\Core\Http;
/**
 * Responsible for Request and Response Headers
 * 
 * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
 */
class Header
{
    private static $headers;
    const FOR_REQUEST = "Request";
    const FOR_RESPONSE = "Response";

    /**
     * By default returns the header of Request. 
     * To get the response headers provide $for = Header::FOR_RESPONSE
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function __construct($for = Header::FOR_REQUEST)
    {
        if ($for == Header::FOR_RESPONSE) {
            self::$headers = apache_response_headers();
        } else {
            self::$headers = apache_request_headers();
        }
    }


    /**
     * Returns the array of headers
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function get_headers():array|null
    {
        return self::$headers;
    }


    /**
     * Returns the value of header by key provided.
     * 
     * @param string $key
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function get($key):object|null|string{
        if($this->has($key)){
            return self::$headers[$key];
        }
        else{
            return null;
        }
    }


    /**
     * Checks whether the header is present or not.
     * @param string $key
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function has($key):bool{
        if(array_key_exists($key, self::$headers)){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Set the new header. Takes two parameters $key and $value
     * 
     * @param string $key
     * @param string $value
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function set($key, $value):bool{
        self::$headers[$key]= $value;
        header($key .":".$value);
        return true;
    }


    /**
     * Delete the header.
     * 
     * @param string $key
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function delete($key):void
    {
        header_remove($key);
    }
}
