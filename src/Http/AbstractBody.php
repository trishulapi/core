<?php

namespace TrishulApi\Core\Http;

use TrishulApi\Core\Exception\NullPointerException;

/**
 * AbstractBody class provides useful functions for RequestBody and ResponseBody classes.
 * 
 * @author Shyam Dubey
 * @since v1.0.0
 * @version v1.0.0 
 * 
 */
abstract class AbstractBody
{

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Return the data type of body
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0 
     * 
     */
    public function get_type():string
    {
        $this->assert_data_not_null();

        return gettype($this->data);
    }

    /**
     * Returns the data in Request and Response Body
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0 
     * 
     */
    public function data():array|object|string|int|null
    {
        return $this->data;
    }


    /**
     * Checks whether request or response body has key.
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0 
     * 
     */
    public function has_key($key): bool
    {
        $this->assert_data_not_null();

        if ($this->is_object()) {
            return  in_array($key, $this->get_keys());
        } else if ($this->is_assoc_array()) {
            return in_array($key, array_keys($this->data));
        } else if ($this->is_array()) {
            return in_array($key, $this->data);
        }
        return false;
    }

    /**
     * Checks whether request or response body is associative array.
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0 
     * 
     */
    public function is_assoc_array(): bool
    {
        $this->assert_data_not_null();

        if ($this->get_type() == 'array') {
            return array_keys($this->data) !== range(0, count($this->data) - 1);
        }
        return false;
    }

    /**
     * Checks whether request or response body is array .
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0 
     * 
     */
    public function is_array(): bool
    {
        $this->assert_data_not_null();

        if ($this->get_type() == 'array') {
            return true;
        }
        return false;
    }


    /**
     * Checks whether request or response body is object.
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0 
     * 
     */
    public function is_object():bool
    {
        $this->assert_data_not_null();

        if (is_object($this->data)) {
            return true;
        }
        return false;
    }

    /**
     * Returns the keys of the Request or Response Body
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0 
     * 
     */
    public function get_keys(): array|null
    {
        $this->assert_data_not_null();

        if ($this->is_object()) {
            return array_keys(get_object_vars($this->data));
        } else if ($this->is_assoc_array()) {
            return array_keys($this->data);
        } else {
            return null;
        }
    }


    /**
     * Checks whether request or response body has value.
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0 
     * 
     */
    public function has_value($value): bool
    {
        return in_array($value, $this->get_values());
    }


    /**
     * Returns the values of the Request or Response Body.
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0 
     * 
     */
    public function get_values(): array | null
    {
        $this->assert_data_not_null();
        if ($this->is_assoc_array()) {
            return array_values($this->data);
        } else if ($this->is_array()) {
            return $this->data;
        } else if ($this->is_object()) {
            $object_vars = $this->get_keys();
            $output = [];
            foreach ($object_vars as $key) {
                $output[] = $this->data->$key;
            }
            return $output;
        }
        return null;
    }

    /**
     * Checks whether request or response body is not null.
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0 
     * 
     */
    private function assert_data_not_null():void
    {
        if ($this->data == null) {
            throw new NullPointerException("Body is null.");
        }
    }


    /**
     * Returns the value for provided $key in the Request or Response Body
     * @param string $key
     * 
     * 
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0 
     * 
     */
    public function get($key):array|object|string|int|null
    {
        if($this->has_key($key)){
            if($this->is_object()){
                return $this->data()->$key;
            }
            else{
                return $this->data()[$key];
            }
        }
        return null;
    }
}
