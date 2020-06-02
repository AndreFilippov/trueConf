<?php

namespace App\Models;

class JsonDb
{
    protected $id;
    protected $name;
    protected $created_at;

    public static function getContent(){
        $path = __DIR__ . '/../../var/users.json';
        if(!file_exists($path)) file_put_contents($path, json_encode(['auto_id' => 0,'users' => []]));
        $file = file_get_contents($path);
        if($file) return json_decode($file, true);
        else return false;
    }

    public static function setContent($content){
        $result = file_put_contents(__DIR__ . '/../../var/users.json', json_encode($content, JSON_UNESCAPED_UNICODE));
        return $result;
    }
}