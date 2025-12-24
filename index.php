<?php

use TrishulApi\Core\App;
use TrishulApi\Core\Http\Router;

require_once "vendor/autoload.php";


/* * This is the main entry point for the application.
 * It initializes the application and handles the request.
 * 
 * Project Structure
 * 
 * src 
 *  ├
*  ├── Models
*  ├── Controllers
*  ├── Middlwares
*  ├── Services
*  ├── Dto
*  ├── Helpers
*  ├── Exceptions
*  |-- logs
*      ├── 2025-06-01_log.log
 * index.php
 * .env
 * composer.json
 * composer.lock
 * 
 * 
 * * This file is responsible for bootstrapping the application.    
 * 
 * The TrishulApi\Core\App class is responsible for initializing the application.
 * 
 * $app = new \TrishulApi\Core\App();
 * 
 * Router::get("/",["hello"=>"world"]);
 * 
 * 
 * 
 * it has several useful and must have methods to configure the application.
 * 
 * 
 * 
 *
 *  */

$app = new App;
$app->set_env_path(".env");


Router::get("/", ["hello"=>"Welcome To Trishul API"]);




$app->start();
