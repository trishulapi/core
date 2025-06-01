<?php


namespace TrishulApi\Core\Http;

/**
 * ResponseBody class for manipulating the ResponseBody and check that it has 
 * keys and values exists. 
 * Also checks for data type of ResponseBody.
 * 
 * @author Shyam Dubey
 * @since v1.0.0 
 * @version v1.0.0 
 */
class ResponseBody extends AbstractBody
{

    public function __construct($data)
    {
        parent::__construct($data);
    }  

     /**
 * Converts the ResponseBody to RequestBody
 * 
 * @author Shyam Dubey
 * @since v1.0.0
 * @version v1.0.0 
 * 
 */
    public function to_request_body(){
        return new RequestBody(self::$data);
    }

}