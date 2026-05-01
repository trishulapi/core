<?php

namespace TrishulApi\Core\Response;

use TrishulApi\Core\Enums\HttpStatus;
use TrishulApi\Core\Exception\InvalidResponseTypeException;
use TrishulApi\Core\Exception\NotAnInstanceException;
use TrishulApi\Core\Http\Request;
use TrishulApi\Core\Http\Response;
use TrishulApi\Core\Log\LoggerFactory;
use TrishulApi\Core\Middleware\MiddlewareInterface;

class ResponseHandler{


    private $logger;

    public function __construct(Response $response, Request $request, array $middlewaresObjectsQueue)
    {
        $this->logger = LoggerFactory::get_logger(self::class);
        $this->handle($response, $request, $middlewaresObjectsQueue);
    }


    private function handle(Response $response, Request $request, array $middlewaresObjectsQueue){
            if (!$response instanceof Response) {
                $response = new Response($response, HttpStatus::OK);
            }
            foreach ($middlewaresObjectsQueue as $m) {
                if (!$m instanceof MiddlewareInterface) {
                    throw new NotAnInstanceException($m::class . " is not instance of " . MiddlewareInterface::class);
                }
                $response = $m->handle_response($response);
            }
            if ($response instanceof Response) {
                if ($response->get_return_type() == Response::RETURN_TYPE_JSON) {
                    $this->logger->info("[" . $response->get_status_code() . "] Writing response ".json_encode($response));
                    $request->set_is_request_completed(true);
                    $response->out();
                } else {
                    throw new InvalidResponseTypeException("Invalid Response Type.");
                }
            } else {
                throw new InvalidResponseTypeException("Invalid Response Type");
            }
    }
}