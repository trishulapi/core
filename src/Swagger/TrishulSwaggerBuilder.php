<?php

namespace TrishulApi\Core\Swagger;

use TrishulApi\Core\Http\Router;

class TrishulSwaggerBuilder
{
    private $orion_swagger;
    public function __construct()
    {
        $this->orion_swagger = TrishulSwagger::get_instance();
    }


    public function generate_doc(){
        $this->orion_swagger->build(Router::get_routes());
        $this->orion_swagger->prepare();
        readfile(__DIR__."/../../swagger_ui/index.html");
        die();
    }
};