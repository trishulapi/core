<?php


namespace TrishulApi\Core\Security;

use TrishulApi\Core\Enums\HttpStatus;
use TrishulApi\Core\Exception\UnauthorizedException;
use Exception;
use TrishulApi\Core\Helpers\Environment;

/**
 * This class provides CORS configuration
 * @author Shyam Dubey
 * @since v1.0.0
 * @version v1.0.0
 */

class CorsSecurity
{

    private static $allowed_domains = [];



    public static function set_allowed_domain($allowed_domain)
    {
        self::$allowed_domains = $allowed_domain;
    }


    /**
     * This function ensures that cors and disabled or enabled and which cors are allowed
     * You can modify the behaviour in @link App\\Setting.php file.
     * @author Shyam Dubey
     * @since v1.0.0
     * @version v1.0.0
     */
    public static function init()
    {


        // Allow from any origin
        header("Access-Control-Allow-Origin: *");

        // Allow specific methods
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

        // Allow specific headers
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(HttpStatus::NO_CONTENT->value);
            exit;
        }
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "";
        $allowed_domains_env = Environment::get('ALLOWED_DOMAINS');
        try{
            $allowed_domains_env = json_decode($allowed_domains_env);
        }
        catch(Exception $e){
            throw new Exception("Please declare ALLOWED_DOMAINS in [] brackets. For Example ['localhost:9000']");
        }

        
        if ($allowed_domains_env != null || !empty($allowed_domains_env)) {
            if (gettype($allowed_domains_env) == 'array') {
                self::$allowed_domains = array_merge(self::$allowed_domains, $allowed_domains_env);
            } else {
                array_push(self::$allowed_domains, $allowed_domains_env);
            }
        }
        if (self::$allowed_domains == null || count(self::$allowed_domains) == 0) {
            throw new Exception('Please provide allowed domains array in index.php file as $app->set_allowed_domains(["*"])');
        }
        if (!in_array("*", self::$allowed_domains)) {
            if (!in_array($host, self::$allowed_domains)) {
                throw new UnauthorizedException("Invalid Domain. Add this domain [" . $host . "] in allowed_domains");
            }
        }
    }


    public static function get_allowed_domains()
    {
        return self::$allowed_domains;
    }
}

