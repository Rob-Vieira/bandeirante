<?php

namespace Robsonvieira;
use Exception;

class Trilha{
    private static $routes;
    private static $routes_by_name;
    
    private static $middlewares;
    private static $group;
    private static $name;

    public static function get_routes(){
        return self::$routes;
    }

    static function get($uri, $handle, $name = ''){
        self::set_route($uri, "GET", $handle, $name);
    }

    static function post($uri, $handle, $name = ''){
        self::set_route($uri, "POST", $handle, $name);
    }

    private static function set_route($uri, $type, $handle, $name = ''){
        self::$routes[] = [
            'uri' =>  !empty(self::$group) ? self::$group : '' . $uri, 
            'type' => strtoupper($type), 
            'handle' => $handle,
            'name' => !empty(self::$name) ? self::$name : '' . '.' . $name,
        ];

        if(gettype($name) == 'string' && $name != '') self::$routes_by_name[$name] = count(self::$routes) - 1;
    }

    public static function middleware($middlewares, $handle = null){
        switch(gettype($middlewares)){
            case 'array':
                self::$middlewares = $middlewares;
                break;
            case 'string':
                self::$middlewares[] = $middlewares;
                break;
            default:
                throw new Exception("Tipo inesperado.");
        }

        if($handle != null){
            self::clean_preset($handle);
        }else{
            return new Trilha();
        }

    }

    public static function name($name, $handle = null){
        self::$name = $name;

        return new Trilha();       
    }

    public static function group($group, $handle){
        self::$group = $group;

        if($handle != null){
            self::clean_preset($handle);
        }else{
            return new Trilha();
        }

        self::clean_preset($handle);        
    }

    private static function clean_preset($handle){
        $handle();

        self::$group = '';
        self::$middlewares = [];
        self::$name = '';
    }
}