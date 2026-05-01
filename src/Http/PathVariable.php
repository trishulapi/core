<?php 


namespace TrishulApi\Core\Http;

use TrishulApi\Core\Exception\NullPointerException;
/**
 * This class helps with PathVariables like /username/{username}
 * 
 * @since v1.0.0 
 * @author Shyam Dubey
 * @version v1.0.0
 */
class PathVariable{

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
 * This function checks whether path variable is available in Pathvariables
 *  
 * @since v1.0.0 
 * @author Shyam Dubey
 * @version v1.0.0
 */
    public function has($key):bool
    {
        if(isset($this->data[$key])){
            return true;
        }
        return false;
    }


    /**
     * Return the value of pathvariable 
     * @param string $key
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function get($key):string|null
    {
        if($this->has($key)){
            return urldecode($this->data[$key]);
        }
        return null;
    }

    /**
     * Private function to assert that Pathvariable is not null 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    private function assert_data_non_null(){
        if($this->data == null)
        {
            throw new NullPointerException("Pathvariables are not found.");
        }
    }


}