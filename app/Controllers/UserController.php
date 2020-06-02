<?php
namespace App\Controllers;
use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController{

    public function getUser(Request $request, Response $response, $args){
        if($args && $args['id']) $result = User::getUser($args['id']);
        else $result = User::getUser();
        if(!$result){
            $status = 404;
            $answer = ['result' => 'Пользователь не найден'];
        } else {
            $status = 200;
            $answer = ['result' => $result];
        }
        $newResponse = $response->withStatus($status)->withHeader('Content-Type','application/json');
        $newResponse->getBody()->write(json_encode($answer,JSON_UNESCAPED_UNICODE));
        return $newResponse;
    }

    public function createUser(Request $request, Response $response, $args){
        $params = file_get_contents('php://input');
        $params = json_decode($params,true);

        if(isset($params['name']) && !empty(trim($params['name']))){
            $result = User::createUser($params['name']);
            $status = 201;
            $answer = ['result' => $result];
        } else {
            $status = 400;
            $answer = ['result' => 'Укажите имя'];
        }
        $newResponse = $response->withStatus($status)->withHeader('Content-Type','application/json');
        $newResponse->getBody()->write(json_encode($answer,JSON_UNESCAPED_UNICODE));
        return $newResponse;
    }

    public function editUser(Request $request, Response $response, $args){
        $params = file_get_contents('php://input');
        $params = json_decode($params,true);
        if(isset($params['name']) && !empty(trim($params['name'])) && isset($params['id']) && trim($params['id']) != '' && is_numeric($params['id'])){
            $user = new User($params['id']);
            $result = $user->editUser($params['name']);
            if($result == 'Пользователь не найден'){
                $status = 404;
            } else {
                $status = 200;
            }
            $answer = ['result' => $result];
        } elseif(!isset($params['name']) || empty(trim($params['name']))) {
            $status = 400;
            $answer = ['result' => 'Укажите имя'];
        } elseif(!isset($params['id']) || trim($params['id']) == '') {
            $status = 400;
            $answer = ['result' => 'Укажите ID'];
        } elseif(!is_numeric($params['id'])){
            $status = 400;
            $answer = ['result' => 'Введите корректный ID'];
        } else {
            $status = 500;
            $answer = ['result' => 'При обработке данных возникла ошибка'];
        }
        $newResponse = $response->withStatus($status)->withHeader('Content-Type','application/json');
        $newResponse->getBody()->write(json_encode($answer,JSON_UNESCAPED_UNICODE));
        return $newResponse;
    }

    public function deleteUser(Request $request, Response $response, $args){
        $params = file_get_contents('php://input');
        $params = json_decode($params,true);
        if(isset($params['id']) && trim($params['id'] != '' && is_numeric($params['id']))){
            $user = new User($params['id']);
            $result = $user->deleteUser();
            if(!$result == 'Пользователь не найден'){
                $status = 404;
            } else {
                $status = 200;
            }
            $answer = ['result' => $result];
        } elseif(!is_numeric($params['id'])){
            $status = 400;
            $answer = ['result' => 'Введите корректный ID'];
        } else {
            $status = 400;
            $answer = ['result' => 'Укажите ID'];
        }
        $newResponse = $response->withStatus($status)->withHeader('Content-Type','application/json');
        $newResponse->getBody()->write(json_encode($answer,JSON_UNESCAPED_UNICODE));
        return $newResponse;
    }
}