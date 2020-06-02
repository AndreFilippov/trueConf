<?php

use App\Controllers\UserController;
use App\Middleware\ApiMiddleware;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addRoutingMiddleware();

$customErrorHandler = function (
    ServerRequestInterface $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {

    $logger = new Logger('error_log');
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../var/logs/error.log', 400));
    $error = [
        'code' => $exception->getCode(),
        'error' => $exception->getMessage(),
        'request_url' => $request->getRequestTarget()
    ];
    $logger->error(json_encode($error, JSON_UNESCAPED_UNICODE));
    $payload = ['error' => $exception->getMessage()];
    $response = $app->getResponseFactory()->createResponse()->withStatus($exception->getCode())->withHeader('Content-Type', 'application/json');
    $response->getBody()->write(
        json_encode($payload, JSON_UNESCAPED_UNICODE)
    );

    return $response;
};

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->group('/users', function () use ($app) {
    $app->get('/users', UserController::class . ":getUser");
    $app->get('/users/{id:[0-9]+}', UserController::class . ":getUser");
    $app->post('/users', UserController::class . ":createUser");
    $app->put('/users', UserController::class . ":editUser");
    $app->delete('/users', UserController::class . ":deleteUser");
})->add(new ApiMiddleware());

$app->run();