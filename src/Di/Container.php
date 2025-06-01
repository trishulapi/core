<?php

namespace TrishulApi\Core\Di;

use Exception;
use TrishulApi\Core\Exception\ClassNotFoundException;
use ReflectionClass;
use TrishulApi\Core\Http\Request;

class Container 
{
    public function __construct()
    {
        
    }

    public function provide($class, $request)
    {
        try{
            $dependencies = [];
            if(!class_exists($class)){
                throw new ClassNotFoundException($class ." class could not found.");
            }
            $reflection = new ReflectionClass($class);
            $constructor = $reflection->getConstructor();   
            
            if(!$constructor && $class != Request::class){
                return new $class;
            }
            else if($class == Request::class){
                return new $class($request->get_url());
            }

            $params = $constructor->getParameters();
            foreach($params as $param)
            {
                $type = $param->gettype();
                if(!$type){
                    throw new Exception("Can not resolve untyped parameters in class ".$class);
                }
                if($type != 'string' && $type != 'int' && $type != 'bool'){
                $dependencies[] = $this->provide($type->getName(), $request);
                }

            }
            return $reflection->newInstanceArgs($dependencies);

        }
        catch(Exception $e){
            throw new Exception($e);
        }

    }
}