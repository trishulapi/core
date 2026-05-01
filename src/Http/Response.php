<?php

namespace TrishulApi\Core\Http;

use TrishulApi\Core\Enums\HttpStatus;
use TrishulApi\Core\Exception\InvalidResponseTypeException;

class Response
{

    private HttpStatus $statusCode;
    private $return_type;
    public const RETURN_TYPE_JSON = "JSON";
    private ?Header $header = null;
    private ?Cookie $cookie = null;
    private ?Session $session = null;
    private ?ResponseBody $body = null;


    public function __construct($data, HttpStatus $status, $return_type = Response::RETURN_TYPE_JSON)
    {
       $this->statusCode = $status;
       $this->return_type = $return_type;
       $this->cookie = new Cookie;
       $this->session = new Session;
       $this->header = new Header(Header::FOR_RESPONSE);
       $this->body = new ResponseBody($data);
    }

    public function get_body(): ResponseBody
    {
        return $this->body;
    }


    public function get_status_code()
    {
        return $this->statusCode->value;
    }

    public function get_return_type()
    {
        return$this->return_type;
    }



    public function out()
    {
        if ($this->return_type != Response::RETURN_TYPE_JSON) {
            throw new InvalidResponseTypeException("Invalid response type provided.");
        }

        header("content-type:application/json");
        http_response_code($this->statusCode->value);
        echo json_encode($this->body->data());
        die();
        return;
        exit(0);
    }

    public function get_header()
    {
       $this->header = new Header(Header::FOR_RESPONSE);
        return$this->header;
    }


    public function get_cookie(): Cookie
    {
        if (self::$cookie == null) {
            return$this->cookie = new Cookie;
        } else {
            echo "null";

            return$this->cookie;
        }
    }


    public function set_cookie(Cookie $cookie): void
    {
       $this->cookie = $cookie;
    }


    public function get_session(): Session
    {
       $this->session = new Session;
        return$this->session;
    }
}
