<?php

namespace TrishulApi\Core\Http;

use TrishulApi\Core\Exception\NullPointerException;
use TrishulApi\Core\Http\Session;
use TrishulApi\Core\Http\QueryParams;
use TrishulApi\Core\Http\Header;
use TrishulApi\Core\Http\RequestBody;
use TrishulApi\Core\Http\Cookie;
use TrishulApi\Core\Http\PathVariable;
use TrishulApi\Core\Request\RequestMethod;

/**
 * Handles all requests in this framework. This class is useful for getting URL Params, Request Body and Request Headers, 
 * URL on which the request is coming.
 * @author Shyam Dubey
 * @since 2025
 */
class Request
{

    private ?QueryParams $query_params = null;
    private ?Header $header = null;
    private ?RequestBody $body = null;
    private string $url;
    private ?Session $session = null;
    private ?Cookie $cookie = null;
    private ?PathVariable $path = null;
    private ?RequestFile $file = null;

    private RequestMethod $request_method;

    private bool $is_request_completed = false;


    public function __construct(string $url)
    {
        $this->query_params = new QueryParams();
        $this->header = new Header();
        $this->body = $this->body();
        $this->url = $url;
        $this->session = new Session;
        $this->cookie = new Cookie;
        $this->path = new PathVariable(null);
        $this->file = new RequestFile($_FILES);
        $this->request_method = new RequestMethod();
        $this->is_request_completed = false;
    }

    /**
     * This function is used to get the Request Body of any request. Mostly used for POST, PUT type requests.
     * @author Shyam Dubey
     * @since 2025
     */
    public function body():RequestBody
    {
        $this->body = new RequestBody(json_decode(file_get_contents("php://input")));
        return $this->body;
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
        if ($this->query_params == null) {
            return $this->query_params = new QueryParams();
        } else {
            return $this->query_params;
        }
    }


    public function get_request_method() : RequestMethod{
        return $this->request_method;
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
        $this->query_params = $request->query_params();
        $this->header = $request->header();
        $this->body = $request->body();
        $this->url = $request->get_url();
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
        if ($this->body == null) {
            throw new NullPointerException("Request body is null. Can not set the value");
        }
        $this->body = $body;
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
        return $this->url;
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
        if($this->header == null){
            return new Header();
        }
        else{
            return $this->header;
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
        $this->header = $header;
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
        return $this->session;
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
        $this->session = $session;
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
        return $this->cookie;
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
        $this->cookie = $cookie;
    }

    public function set_path($data)
    {
        $this->path = new PathVariable($data);
    }

    
    public function path():PathVariable
    {
        return $this->path;
    }


    public function file():RequestFile
    {
        return $this->file;
    }

    public function set_is_request_completed(bool $val){
        $this->is_request_completed = $val;
    }

     public function get_is_request_completed(){
        return $this->is_request_completed;
    }
}
