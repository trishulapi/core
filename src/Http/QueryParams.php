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
    private static $data;
    public function __construct($data)
    {
        if ($data == null) {
            if (RequestType::is_get() || RequestType::is_put() || RequestType::is_merge() || RequestType::is_delete()) {
                self::$data = $_GET;
            } else if (RequestType::is_post()) {
                self::$data = $_POST;
            }
        }
        else 
        {
            self::$data = $data;
        }
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
        $this->assert_data_not_null();
        if (isset(self::$data[$key])) {
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
        $this->assert_data_not_null();
        if ($this->has($key)) {
            return self::$data[$key];
        }
        return null;
    }

    /**
     * Used internally to assert that QueryParams is not null
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0
     */
    private function assert_data_not_null()
    {
        if (self::$data == null) {
            throw new NullPointerException("No Query Params Found in the request.");;
        }
    }
}
