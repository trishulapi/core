<?php

namespace TrishulApi\Core\Http;

/**
 * Used for session management. 
 * 
 * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
 */
class Session
{

    public function __construct()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            ob_start();
        }
    }

    /**
     * Set the session. Takes two params string $key and $value
     * 
     * @param string $key
     * @param string $value;
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }


    /**
     * Return the value of session 
     * @param string $key
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function get($key):object|null|string|int
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return null;
        }
    }


    /**
     * Returns session in array of key value pair.
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function get_all():array
    {
        return $_SESSION;
    }


    /**
     * Unset the session 
     * @param string $key
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function unset($key): bool
    {
        unset($_SESSION[$key]);
        return true;
    }

    /**
     * Returns the session id
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function get_session_id(): string
    {
        return session_id();
    }

    /**
     * Checks whether session key exists in session
     * 
     * @param string $key
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function has($key):bool{
        if(isset($_SESSION[$key])){
            return true;
        }
        return false;
    }


    /**
     * Destroy the session
     * 
     * @param string $key
     * 
     * @author Shyam Dubey
     * @version v1.0.0 
     * @since v1.0.0 
     */
    public function destroy():void
    {
        session_destroy();
        ob_clean();
    }
}
