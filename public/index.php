<?php
use Slim\Factory\AppFactory;
use App\Controllers\UserController;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->group('/users', function () use ($app){
    $app->get('/users', UserController::class . ":getUser");
    $app->get('/users/{id:[0-9]+}', UserController::class . ":getUser");
    $app->post('/users', UserController::class . ":createUser");
    $app->put('/users', UserController::class . ":editUser");
    $app->delete('/users', UserController::class . ":deleteUser");
});

$app->run();