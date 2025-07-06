<?php

namespace TrishulApi\Core\Http;

use ResourceBundle;
use TrishulApi\Core\Enums\HttpStatus;
use TrishulApi\Core\Exception\InvalidResponseTypeException;
use TrishulApi\Core\Exception\ResourceNotFoundException;

/**
 * This class is responsible for returning the response back to the user.
 * 
 * @author Shyam Dubey
 * @since v1.0.0
 * @version v1.0.0
 */
class Response
{

    private static HttpStatus $statusCode;
    private static $return_type;
    public const RETURN_TYPE_JSON = "JSON";
    private static ?Header $header = null;
    private static ?Cookie $cookie = null;
    private static ?Session $session = null;
    private static ?ResponseBody $body = null;


    private function __construct($data, HttpStatus $status, $return_type = Response::RETURN_TYPE_JSON)
    {
        self::$statusCode = $status;
        self::$return_type = $return_type;
        self::$cookie = new Cookie;
        self::$session = new Session;
        self::$header = new Header(Header::FOR_RESPONSE);
        self::$body = new ResponseBody($data);
    }


    /**
     * Ensures that the output is json. Takes two params HttpStatus and $data
     * Content-type:application/json
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public static function json(HttpStatus $statusCode, $data)
    {
        self::$statusCode = $statusCode;
        // self::$data = $data;
        self::$body = new ResponseBody($data);
        $return_type = self::RETURN_TYPE_JSON;
        return new Response($data, $statusCode, $return_type);
    }


    /**
     * Ensures that file is being sent to the user. Takes multiple params
     * @param string $content_desc Content Description
     * @param string $content_type Content Type
     * @param string $file File name along with the full path.
     * @param string $content_length Content Length
     * @param string $content_disposition Content-Disposition attachement | inline 
     * @since v1.0.1 
     * @version v1.0.1 
     * @author Shyam Dubey
     */
    public static function file($content_desc, $content_type, $file, $content_length, $content_disposition = "attachment", $cache_control = "must-revalidate", $pragma = "public", $expires = 0)
    {
        if (file_exists($file)) {
            header('Content-Description: '.$content_desc);
            header('Content-Type: '.$content_type); // or actual MIME type
            header('Content-Disposition: '.$content_disposition.'; filename="' . basename($file) . '"');
            header('Content-Length: ' . $content_length);
            header('Cache-Control: '.$cache_control);
            header('Pragma: '.$pragma);
            header('Expires: '.$expires);
            flush(); // Clean output buffer
            readfile($file);
            exit;
        } else {
            throw new ResourceNotFoundException("File not found");
        }
    }


    /**
     * Return the data in the response
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public static function get_body(): ResponseBody
    {
        return self::$body;
    }


    /**
     * Returns the response status code [200,404,500 etc]
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public static function get_status_code()
    {
        return self::$statusCode->value;
    }


    /**
     * returns the Return type of response [JSON]
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public static function get_return_type()
    {
        return self::$return_type;
    }


    /**
     * This function prints the output to the end user directly. Execution of request terminates here. 
     * As soon as you write Response::out() anywhere in the code. The request terminates there and
     * JSON output is generated.
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public static function out(HttpStatus $status, $data, $return_type = Response::RETURN_TYPE_JSON)
    {
        if ($return_type != Response::RETURN_TYPE_JSON) {
            throw new InvalidResponseTypeException("Invalid response type provided.");
        }

        header("content-type:application/json");
        http_response_code($status->value);
        echo json_encode($data);
        die();
        return;
        exit(0);
    }


    /**
     * Returns the headers in response
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public static function get_header()
    {
        self::$header = new Header(Header::FOR_RESPONSE);
        return self::$header;
    }


    public static function get_cookie(): Cookie
    {
        if (self::$cookie == null) {
            return self::$cookie = new Cookie;
        } else {
            echo "null";

            return self::$cookie;
        }
    }


    public static function set_cookie(Cookie $cookie): void
    {
        self::$cookie = $cookie;
    }


    public static function get_session(): Session
    {
        self::$session = new Session;
        return self::$session;
    }
}
