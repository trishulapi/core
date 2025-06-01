<?php

namespace TrishulApi\Core\Http;

use TrishulApi\Core\Exception\NullPointerException;
use TrishulApi\Core\Http\Session;
use TrishulApi\Core\Http\QueryParams;
use TrishulApi\Core\Http\Header;
use TrishulApi\Core\Http\RequestBody;
use TrishulApi\Core\Http\Cookie;
use TrishulApi\Core\Http\PathVariable;

/**
 * Handles all requests in this framework. This class is useful for getting URL Params, Request Body and Request Headers, 
 * URL on which the request is coming.
 * @author Shyam Dubey
 * @since 2025
 */
class Request
{

    private static ?QueryParams $query_params = null;
    private static ?Header $header = null;
    private static ?RequestBody $body = null;
    private static string $url;
    private static ?Session $session = null;
    private static ?Cookie $cookie = null;
    private static ?PathVariable $path = null;


    public function __construct(string $url)
    {
        self::$query_params = new QueryParams($_REQUEST);
        self::$header = new Header();
        self::$body = self::body();
        self::$url = $url;
        self::$session = new Session;
        self::$cookie = new Cookie;
        self::$path = new PathVariable(null);
    }

    /**
     * This function is used to get the Request Body of any request. Mostly used for POST, PUT type requests.
     * @author Shyam Dubey
     * @since 2025
     */
    public function body():RequestBody
    {
        self::$body = new RequestBody(json_decode(file_get_contents("php://input")));
        return self::$body;
    }


    /**
     * This function returns the object of QueryParams 
     * QueryParams consists all useful methods to handle the query params
     * 
     * @author Shyam Dubey
     * @since v1.0.0 
     * @version v1.0.0 
     */
    public function query_params():QueryParams
    {
        if (self::$query_params == null) {
            return self::$query_params = new QueryParams($_REQUEST);
        } else {
            return self::$query_params;
        }
    }


    /**
     * This function updates the current request. You must use this function if you are changing the Request in middleware.
     * After making changes, Don't forget to update the request.
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version 1.0.0
     */
    public function update(Request $request)
    {
        self::$query_params = $request->query_params();
        self::$header = $request->header();
        self::$body = $request->body();
        self::$url = $request->get_url();
    }


    /**
     * To set the request body, this function is used. It throws NullPointerException when the request body is null and you want to set
     * some value to the request body.
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version 1.0.0
     */
    public function set_body(RequestBody $body) :void
    {
        if (self::$body == null) {
            throw new NullPointerException("Request body is null. Can not set the value");
        }
        self::$body = $body;
    }


    /**
     * To get the url on which request is coming.
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version 1.0.0
     */
    public function get_url():string
    {
        return self::$url;
    }
 

    /**
     * Returns the IP of Remote Address
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function get_ip() :string|null
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Returns the object Header class. which has many useful functions
     * for getting headers values
     * 
     * @author Shyam Dubey
     * @since v1.0.0 
     * @version v1.0.0
     */
    public function header():Header
    {
        if(self::$header == null){
            return new Header();
        }
        else{
            return self::$header;
        }
    }


    /**
     * Used internally to set the headers
     * 
     * @author Shyam Dubey
     * @since v1.0.0 
     * @version v1.0.0 
     */
    public function set_header(Header $header) :void 
    {
        self::$header = $header;
    }

    /**
     * Returns the object of Session class
     * which has many useful methods for creating session variables
     * and getting session variables. Session can also be destroyed.
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0 
     */
    public function session():Session
    {
        return self::$session;
    }

    /**
     * Used internally by core
     * 
     * @author Shyam Dubey
     * @since v1.0.0 
     * @version v1.0.0 
     */
    public function set_session(Session $session):void
    {
        self::$session = $session;
    }


    /**
     * Returns the object of Cookie class
     * 
     * @author Shyam Dubey
     * @since v1.0.0 
     * @version v1.0.0 
     */
    public function cookie():Cookie
    {
        return self::$cookie;
    }

    /**
     * Used internally by the core
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function set_cookie(Cookie $cookie):void
    {
        self::$cookie = $cookie;
    }


    /**
     * Used internally by the core
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function set_path($data)
    {
        self::$path = new PathVariable($data);
    }

    /**
     * Returns the PathVariables of the request
     * 
     * @return PathVariable
     * 
     * @author Shyam Dubey
     * @since v1.0.0 
     * @version v1.0.0
     * 
     */
    public function path():PathVariable
    {
        return self::$path;
    }
}
