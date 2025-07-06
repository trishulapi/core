<?php

namespace TrishulApi\Core\Http;

use TrishulApi\Core\File\MultipartFile;

/**
 * This class handles the files coming in request.
 * @author Shyam Dubey
 * @since v1.0.1
 * @version v1.0.1
 */
class RequestFile
{

    private static $data;
    public function __construct($file_data)
    {
        self::$data = $file_data;
    }
    /**
     * Returns how many files are attached in the request.
     * @author Shyam Dubey
     * @return int 
     * @since v1.0.1
     * @version v1.0.1
     */
    public function length($paramName)
    {
        if (gettype(self::$data[$paramName]['name']) == 'array') {
            return count(self::$data[$paramName]['name']);
        } else {
            return 1;
        }
    }

    /**
     * This function returns the file/files coming in request. 
     * @return MultipartFile $partFile | null | array
     * @param string $paramName
     * @param bool $multi if you want to get all the files coming in single name for example. User sending multiple files in same param name then you can pass multi=true it will return array.
     * @author Shyam Dubey
     * @since v1.0.1
     * @version v1.0.1
     */
    public function get($paramName, $multi = false): MultipartFile | null | array
    {
        $file = self::$data[$paramName];
        if ($file != null) {
            if ($this->length($paramName) == 1) {
                return new MultipartFile($file['name'], $file['full_path'], $file['type'], $file['tmp_name'], $file['error'], $file['size']);
            } else {
                if (!$multi) {
                    return new MultipartFile($file['name'][0], $file['full_path'][0], $file['type'][0], $file['tmp_name'][0], $file['error'][0], $file['size'][0]);
                } else {
                    $arr = [];
                    for ($i = 0; $i < $this->length($paramName); $i++) {
                        array_push($arr, new MultipartFile($file['name'][$i], $file['full_path'][$i], $file['type'][$i], $file['tmp_name'][$i], $file['error'][$i], $file['size'][$i]));
                    }
                    return $arr;
                }
            }
        }
        return null;
    }
}
