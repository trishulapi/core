<?php


namespace TrishulApi\Core\Http;

/**
 * RequestBody class for manipulating the requestbody and check that it has 
 * keys and values exists. 
 * Also checks for data type of RequestBody.
 * 
 * @author Shyam Dubey
 * @since v1.0.0 
 * @version v1.0.0 
 */
class RequestBody extends AbstractBody
{

    public function __construct($data)
    {
        parent::__construct($data);
        
    }

     /**
 * Converts the RequestBody to ResponseBody
 * 
 * @author Shyam Dubey
 * @since v1.0.0
 * @version v1.0.0 
 * 
 */
    public function to_response_body(){
        return new ResponseBody(self::$data);
    }

   
}