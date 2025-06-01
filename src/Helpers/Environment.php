<?php

namespace TrishulApi\Core\Helpers;

use TrishulApi\Core\App;

class Environment
{
    private static $file_path = null;
    private static array $envs = [];
    /**
     * Get the value of an environment variable.
     *
     * @param string $key The name of the environment variable.
     * @param mixed $default The default value to return if the variable is not set.
     * @return mixed The value of the environment variable or the default value.
     */
    public static function get(string $key, $default = null, $from_file=true)
    {
        $app = new App;
        self::$file_path = $app->get_env_path();
        if(self::$file_path != null && $from_file)
        {
            if(self::$envs == null || count(self::$envs) ==0){
                self::process_env_vars();
            }
            if(self::has($key))
            {
                return self::$envs[$key];
            }
        }
        return getenv($key) ?: $default;
    }

    public static function has($key):bool
    {
        if(self::$envs != null && count(self::$envs) > 0)
        {
            if(isset(self::$envs[$key])){
                return true;
            }
        }
        return false;
    }

    /**
     * Set an environment variable.
     *
     * @param string $key The name of the environment variable.
     * @param string $value The value to set for the environment variable.
     */
    public static function set(string $key, string $value): void
    {
        putenv("$key=$value");
    }


    private static function process_env_vars()
    {

        if(self::$file_path == null){
            $app = new App();
            self::$file_path = $app->get_env_path();
        }
        $file_content = file_get_contents(self::$file_path);
        $key_val_arr = explode("\n", $file_content);
        foreach($key_val_arr as $v)
        {
            if(strpos($v, "=")){
               $exploded_arr = explode("=", $v);
                self::$envs[trim($exploded_arr[0])] = trim($exploded_arr[1]);
            }
        }
    }
}