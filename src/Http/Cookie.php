<?php

namespace TrishulApi\Core\Http;

class Cookie
{

    private static $cookies;

    public function __construct()
    {
        self::$cookies = $_COOKIE;
    }

    /**
     * Return the cookie value 
     * @param string $key
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     * 
     */
    public function get_all():array|null
    {
        return self::$cookies;
    }

    /**
     * Return the cookie value 
     * @param string $key
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     * 
     */
    public function get($key):string|null|object|array
    {
        if (isset(self::$cookies[$key])) {
            return self::$cookies[$key];
        } else {
            return null;
        }
    }



    /**
     * Checks whether any cookie is set 
     * @param string $key
     * @return bool
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function has($key):bool
    {
        if(isset($this->get_all()[$key])){
            return true;
        }
        return false;
    }

    /**
     * This function sets the cookie. It takes three required params. internally this function
     * uses the setcookie() inbuild function of php.
     * @param string $key 
     * @param string $value
     * @param int $expire 
     * 
     * The time the cookie expires. This is a Unix timestamp so is
     * in number of seconds since the epoch. In other words, you'll
     * most likely set this with the time function
     * plus the number of seconds before you want it to expire. Or
     * you might use mktime.
     * time()+60*60*24*30 will set the cookie to
     * expire in 30 days. If set to 0, or omitted, the cookie will expire at
     * the end of the session (when the browser closes). 
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function set($key, $value, $expire, $domain = "", $httponly = false, $secure = false, $path = "/"):void
    {
        setcookie($key, $value, $expire, $path, $domain, $secure, $httponly);
    }


    /**
     * Delete the cookie. 
     * @param string $key 
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function delete($key):void
    {
        setcookie($key, "", time() - 3600, "/");
    }

    /**
     * Clears all the cookies
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function clear():void 
    {
        foreach($this->get_all() as $key => $value){
            $this->delete($key);
        }
    }
}
