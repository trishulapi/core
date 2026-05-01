<?php

namespace TrishulApi\Core\Swagger;

use Exception;
use ReflectionClass;
use TrishulApi\Core\Helpers\Environment;

class TrishulSwagger
{



    public static $object;
    private static $instance;
    private static $securitySchemes = [];


    private function __construct() {}

    public static function get_instance(): TrishulSwagger
    {
        if (self::$instance == null) {
            return self::$instance = new TrishulSwagger;
        } else {
            return self::$instance;
        }
    }

    public static function set_info($info)
    {
        self::$object = [
            'openapi' => '3.0.0',
            'info' => $info,
            'paths' => [],
            'components' => ["schemas" => []],
            'tags' => [],
            'servers' => [
                [
                    'url' => Environment::get("APP_URL") ?? 'http://localhost:8000',
                    'description' => Environment::get("APP_NAME") ?? 'Trishul API Server'
                ]
            ]
        ];
    }

    public static function set_security_schemes(array $securitySchemes)
    {
        self::$securitySchemes = $securitySchemes;
        if (count(self::$securitySchemes) > 0) {
            foreach ($securitySchemes as $key => $value) {
                if (is_array($value)) {
                    self::$object['components']['securitySchemes'][$key] = $value;
                } else {
                    throw new Exception("Security Schemes must be an array.");
                }
            }
        }

        // self::$object['components']['securitySchemes'][] = $securitySchemes;
    }


    public static function build($routes)
    {
        $app_name = Environment::get("APP_NAME") ?? 'App';
        $app_version = Environment::get("APP_VERSION") ?? '1.0.0';
        $app_desc = Environment::get("APP_DESCRIPTION") ?? 'Welcome to My APIs.';
        self::set_info(["title" => $app_name, "version" => $app_version, "description" => $app_desc]);
        self::set_security_schemes(self::$securitySchemes);
        if (count($routes) > 0) {
            $swagger = TrishulSwagger::get_instance();
            foreach ($routes as $route) {
                if(is_null($route)){
                    continue;
                }
                if ($route->get_exclude_from_swagger()) {
                    continue;
                }
                if (!$route->get_custom_swagger()) {
                    //configuration for swagger
                    $summary = $route->get_summary();
                    if ($summary == "") {
                        $summary = "";
                    }
                    $description = $route->get_description();
                    if ($description == "") {
                        $description = "";
                    }


                    //check the response type and build
                    $response_schema = [];
                    if ($route->get_response_type() != "") {
                        if (gettype($route->get_response_type()) == 'array') {
                            $response_class = $route->get_response_type()[0];
                            if (class_exists($response_class)) {
                                self::set_component($response_class);
                                $schemaName = str_replace('\\', '.', $response_class);
                                if ($route->get_is_response_array()) {
                                    $response_schema['type'] = 'array';
                                    $response_schema["items"] = ['$ref' => '#/components/schemas/' . $schemaName];
                                } else {
                                    $response_schema = ['$ref' => '#/components/schemas/' . $schemaName];
                                }
                            } else {
                                $response_schema['type'] = 'object';
                                $response_schema["items"] = ['type' => 'array'];
                            }
                        } else {
                            if (class_exists($route->get_response_type())) {
                                self::set_component($route->get_response_type());
                                $schemaName = str_replace('\\', '.', $route->get_response_type());
                                if ($route->get_is_response_array()) {
                                    $response_schema['type'] = 'array';
                                    $response_schema["items"] = ['$ref' => '#/components/schemas/' . $schemaName];
                                } else {
                                    $response_schema = ['$ref' => '#/components/schemas/' . $schemaName];
                                }
                            } else {
                                $response_schema['type'] = 'object';
                                $response_schema["items"] = ['type' => 'array'];
                            }
                        }
                    } else {
                        $response_schema['type'] = 'object';
                        $response_schema["items"] = ['type' => 'array'];
                    }

                    //end of check response type
                    $responses = [];
                    $response_codes = $route->get_response_codes();
                    if (gettype($response_codes) == 'array') {
                        if (count($response_codes) > 0) {
                            foreach ($response_codes as $code) {
                                if ($code == 401) {
                                    $response_desc = "Unauthorised";
                                } else if ($code == 403) {
                                    $response_desc = "Forbidden";
                                } else if ($code == 404) {
                                    $response_desc = "Not Found";
                                } else if ($code == 500) {
                                    $response_desc = "Internal Server Error";
                                } else if ($code == 504) {
                                    $response_desc = "Method Not Allowed";
                                } else if ($code == 503) {
                                    $response_desc = "Service Unavailable";
                                } else {
                                    $response_desc = "OK";
                                }
                                $responses[$code] = ["description" => $response_desc, "content" => [$route->get_consumes() => ["schema" => $response_schema]]];
                            }
                        } else {
                            $responses["200"] = ["description" => "Success", "content" => [$route->get_consumes() => ["schema" => $response_schema]]];
                        }
                    } else {
                        if ($response_codes == 401) {
                            $response_desc = "Unauthorised";
                        } else if ($response_codes == 403) {
                            $response_desc = "Forbidden";
                        } else if ($response_codes == 404) {
                            $response_desc = "Not Found";
                        } else {
                            $response_desc = "OK";
                        }
                        $responses[$response_codes] = ["description" => $response_desc, "content" => [$route->get_consumes() => ["schema" => $response_schema]]];
                    }




                    $swagger_object = [];
                    $swagger_object["summary"] = $summary;
                    $swagger_object["description"] = $description;
                    $swagger_object["responses"] = $responses;

                    //for request body
                    if ($route->get_request_body() != "" && $route->get_request_body() != null) {
                        $requestBody = [];
                        if (gettype($route->get_request_body()) == 'array') {
                            $request_class = $route->get_request_body()[0];
                            if (class_exists($request_class)) {
                                self::set_component($request_class);
                                $schemaName = str_replace('\\', '.', $request_class);
                                $requestBody = ['$ref' => '#/components/schemas/' . $schemaName];
                            } else {
                                $requestBody = ['type' => 'object'];
                            }
                        } else {
                            if (class_exists($route->get_request_body())) {
                                self::set_component($route->get_request_body());
                                $schemaName = str_replace('\\', '.', $route->get_request_body());
                                $requestBody = ['$ref' => '#/components/schemas/' . $schemaName];
                            } else {
                                $requestBody = ['type' => 'object'];
                            }
                        }
                        $swagger_object["requestBody"] = [
                            "required" => true,
                            "content" => [
                                $route->get_consumes() => [
                                    "schema" => $requestBody
                                ]
                            ]
                        ];
                    }
                    //end of request body
                    //for tag
                    if ($route->get_tag() != "") {
                        $swagger_object["tags"] = [$route->get_tag()];
                    }

                    //set security
                    if (gettype($route->get_security()) == 'array' && count($route->get_security()) > 0) {
                        foreach ($route->get_security() as $securityType) {
                            $swagger_object["security"][][$securityType] = [];
                        }
                    }
                    //end of set security

                    //for parameters check {} in url 
                    $url = trim($route->get_url(), '/');

                    $patternRegex = preg_replace('/\{(\w+)\}/', '(?P<\1>[^/]+)', trim($route->get_url(), '/'));
                    $patternRegex = "@^" . $patternRegex . "$@";

                    if (preg_match($patternRegex, $url, $matches)) {
                        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                        if ($params != null) {
                            $swagger_object['parameters'] = [];
                            foreach ($params as $key => $value) {
                                array_push($swagger_object['parameters'], ['name' => $key, 'in' => 'path', 'description' => "Provide " . $key . "", "required" => true, "schema" => ['type' => 'string']]);
                            }
                        }
                    }

                    if (!$swagger->has_path($route->get_url())) {

                        $path =  [
                            strtolower($route->get_method()) => $swagger_object
                        ];
                        $swagger->addPath($route->get_url(), $path);
                    } else {
                        $obj = [strtolower($route->get_method()) => $swagger_object];
                        $swagger->set_to_existing_path($route->get_url(), $obj);
                    }

                    //end for parameters
                } else {
                    $swagger_object = $route->get_swagger_object();
                    if ($swagger_object != "") {
                        if (!$swagger->has_path($route->get_url())) {

                            $path =  [
                                strtolower($route->get_method()) => $swagger_object
                            ];
                            $swagger->addPath($route->get_url(), $path);
                        } else {
                            $obj = [strtolower($route->get_method()) => $swagger_object];
                            $swagger->set_to_existing_path($route->get_url(), $obj);
                        }
                    }
                }
            }
            $swagger->prepare();
        }
    }


    private static function addPath($url, $object)
    {
        if (strlen($url) > 0) {
            if ($url[0] != '/') {
                $url = '/' . $url;
            }
            self::$object['paths'][$url] = $object;
        }
    }

    public function prepare()
    {

        file_put_contents(__DIR__ . "/openapi.json", json_encode(self::$object, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    private function has_path($path)
    {

        $data = self::$object;
        if ($data != null) {
            if (array_key_exists($path, $data['paths'])) {
                return true;
            }
        }
        return false;
    }

    public static function set_tags(array $tags)
    {
        self::$object['tags'] = $tags;
    }

    public static function set_title(string $title)
    {
        self::$object['info']['title'] = $title;
    }

    public static function set_version(string $version)
    {
        self::$object['info']['version'] = $version;
    }

    public static function set_description(string $description)
    {
        self::$object['info']['description'] = $description;
    }

    public static function set_servers(array $servers)
    {
        self::$object['servers'] = $servers;
    }


    private function set_to_existing_path($path, $object)
    {

        self::$object['paths'][$path] = array_merge(self::$object['paths'][$path], $object);
    }

    private function get_json()
    {
        if (file_exists(__DIR__ . "/openapi.json")) {
            return json_decode(file_get_contents(__DIR__ . "/openapi.json"));
        }
    }

    private static function set_component($model)
    {
        if (self::$object['components'] != null) {
            if (!array_key_exists($model, self::$object['components']['schemas'])) {
                if (class_exists($model)) {
                    $relectionclass = new ReflectionClass($model);
                    $schemaName = str_replace('\\', '.', $model);
                    $properties = $relectionclass->getProperties();
                    self::$object['components']['schemas'][$schemaName] = ["type" => 'object', 'properties' => []];

                    foreach ($properties as $property) {
                        $type = $property->getType() != null ? $property->getType()->getName() : "object";
                        try {
                            self::$object['components']['schemas'][$schemaName]['properties'][$property->name] = ['type' => $type];
                        } catch (Exception $ex) {
                            error_log($ex);
                            throw new Exception($ex->getMessage());
                        }
                    }
                }
            }
        }
    }
}

