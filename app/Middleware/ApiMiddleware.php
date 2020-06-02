<?php
namespace App\Middleware;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ApiMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  Request  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $logger = new Logger('api');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../../var/logs/api.log', Logger::INFO));
        $response = $handler->handle($request);
        $info = [
            'path' => $request->getRequestTarget(),
            'params' =>  file_get_contents('php://input'),
            'responseCode' => $response->getStatusCode()
        ];
        $logger->info(json_encode($info, JSON_UNESCAPED_UNICODE));
        return $response;
    }
}