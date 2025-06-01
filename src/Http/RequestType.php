<?php


namespace TrishulApi\Core\Http;

/**
 * This class ensures the type of any request which is coming to the server.
 * @author Shyam Dubey
 * @since v1.0.0
 * @version v1.0.0
 */
class RequestType
{


    const GET = "GET";
    const POST = "POST";
    const PUT = "PUT";
    const DELETE = "DELETE";
    const MERGE = "MERGE";
    /**
     * This function ensures the type of any request is GET.
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public static function is_get()
    {
        if (RequestType::get_request_type() == 'GET') {
            return true;
        } else {
            return false;
        }
    }


    /**
     * This function ensures the type of any request is POST.
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public static function is_post()
    {
        if (RequestType::get_request_type() == 'POST') {
            return true;
        } else {
            return false;
        }
    }


    /**
     * This function ensures the type of any request is DELETE.
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public static function is_delete()
    {
        if (RequestType::get_request_type() == 'DELETE') {
            return true;
        } else {
            return false;
        }
    }


    /**
     * This function ensures the type of any request is PUT.
     *@author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public static function is_put()
    {
        if (RequestType::get_request_type() == 'PUT') {
            return true;
        } else {
            return false;
        }
    }


    /**
     * This function ensures the type of any request is MERGE.
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public static function is_merge()
    {
        if (RequestType::get_request_type() == 'MERGE') {
            return true;
        } else {
            return false;
        }
    }


    private static function get_request_type()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
