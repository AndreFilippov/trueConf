<?php

namespace App\Models;
use App\Models\JsonDb;

class User
{
    protected $user;

    public function __construct($id = false)
    {
        if($id){
            $this->user = User::getUser($id);
        }
    }

    public static function getUser($id = false){
         $content = JsonDb::getContent();
         $result = false;
         if($content && isset($content['users'])){
             $users = array_column($content['users'],'name','id');
            if($id){
                if(array_search($id,array_keys($users))!== false){
                    $idx = array_search($id,array_keys($users));
                    $result = $content['users'][$idx];
                }
            } else {
                $result = $content['users'];
            }
         }
         return $result;
    }

    public static function createUser($name){
        $content = JsonDb::getContent();
        $users_id = array_column($content['users'],'id');
        while (in_array($content['auto_id'],$users_id)){
            $content['auto_id']++;
        }
        $id = $content['auto_id']++;
        $content['users'][] = $new_user = ['id' => $id, 'name' => $name, 'created_at' => date('d.m.Y H:i:s')];
        JsonDb::setContent($content);
        return $new_user;
    }

    public function editUser($name){
        if($this->user){
            $new_user = $this->user;
            $content = JsonDb::getContent();
            $users_id = array_column($content['users'],'id');
            if(array_search($new_user['id'],$users_id) !== false ){
                $idx = array_search($new_user['id'],$users_id);
                $new_user['name'] = $name;
                $content['users'][$idx] = $new_user;
                JsonDb::setContent($content);
                return $new_user;
            }
            return 'Пользователь не найден';
        } else {
            return 'Пользователь не найден';
        }
    }

    public function deleteUser(){
        if($this->user){
            $new_user = $this->user;
            $content = JsonDb::getContent();
            $users_id = array_column($content['users'],'id');
            if(array_search($new_user['id'],$users_id) !== false ){
                $idx = array_search($new_user['id'],$users_id);
                unset($content['users'][$idx]);
                JsonDb::setContent($content);
                return 'Пользователь '.$this->user['id'].' удален';
            }
            return 'Пользователь не найден';
        } else {
            return 'Пользователь не найден';
        }
    }
}