<?php
namespace App\functions;

class Func{
    public static function redirect_to($path){
        header("Location:/ProjectComponents/public/". $path);
    }
}