<?php

namespace TrishulApi\Core\Swagger;

use TrishulApi\Core\Http\Router;

class TrishulSwaggerBuilder
{
    private $trishul_swagger;
    public function __construct()
    {
        $this->trishul_swagger = TrishulSwagger::get_instance();
    }


    public function generate_doc(){
        $this->trishul_swagger->build(Router::get_routes());
        $this->trishul_swagger->prepare();
        readfile(__DIR__."/../../swagger_ui/index.html");
        die();
    }
};