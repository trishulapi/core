<?php


namespace TrishulApi\Core\Log;

use Exception;

/**
 * This class is factory class for providing log features. It generates the log in logs directory in the given format.
 * @author Shyam Dubey
 * @since 2025
 */
class LoggerFactory implements LoggerInterface
{

    private $className;
    private static $log_dir;


    public static function set_log_dir($dir){
        self::$log_dir = $dir;
    }

    private function __construct($className)
    {
        $this->className = $className;
    }


    /**
     * Get the Logger Instance.
     * @author Shyam Dubey
     * @since 2025
     */
    public static function get_logger($className)
    {
        return new LoggerFactory($className);
    }



    /**
     * For Logging messages of type INFO.
     * @author Shyam Dubey
     * @since 2025
     */
    public function log($message)
    {
        $final_message = date("Y-m-d h:i:s", time()) . " | INFO | " . $this->className . ".php | " . $message;
        $this->logMessage($final_message);
    }


    /**
     * For logging messages of type INFO
     * @author Shyam Dubey
     * @since 2025
     */
    public function info($message)
    {
        $final_message = date("Y-m-d h:i:s", time()) . " | INFO | " . $this->className . ".php | " . $message;
        $this->logMessage($final_message);
    }



    /**
     * For logging messages of type WARN
     * @author Shyam Dubey
     * @since 2025
     */
    public function warn($message)
    {
        $final_message = date("Y-m-d h:i:s", time()) . " | WARN | " . $this->className . ".php | " . $message;
        $this->logMessage($final_message);
    }



    /**
     * For logging messages of type ERROR
     * @author Shyam Dubey
     * @since 2025
     */
    public function error($message)
    {
        $final_message = date("Y-m-d h:i:s", time()) . " | ERROR | " . $this->className . ".php | " . $message;
        $this->logMessage($final_message);
    }



    /**
     * This is private function which enters the data in log file for any of the function mentioned in this class like log(), info(), warn(), error():
     * @author Shyam Dubey
     * @since 2025
     */
    private function logMessage($fullMessage)
    {
        //check whether user want to log the message in custom directory
        
        $dir = self::$log_dir;
        if($dir == null){
            error_log($fullMessage);
        }
        else{
            try {
                if(!is_dir($dir)){
                    mkdir($dir, 0744);
                }
                if (is_dir($dir)) {
                    $logPattern = LogPattern::DDMMYYYY_LOGS;
                    $logPatternCases = LogPattern::cases();
                    if (!in_array($logPattern, $logPatternCases)) {
                        throw new Exception("Log pattern could not be found. It should be one of the following " . implode(", ", $logPatternCases));
                    }
                    if ($logPattern == LogPattern::DDMMYYYY_LOGS) {
                        $today = date("Y-m-d", time());
                        $file_name = $dir . "/" . $today . ".log";
                        if (!file_exists($file_name)) {
                            $f = fopen($file_name, "w");
                        } else {
                            $f = fopen($file_name, "a");
                        }
                        fwrite($f, $fullMessage . "\n");
                    }
                } 
            } catch (Exception $ex) {
                throw new Exception($ex->getMessage(), 500, $ex);
            }
        }
        
    }
}
