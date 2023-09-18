<?php

namespace Robsonvieira\RouteSystem;

Use Robsonvieira\RouteSystem\Trilha;
use Exception;

class Mapa{
    private $prefixUri;
    private $commonController;
    private $commonMiddlewares;
    private $prefixName;

    static private $routes = [];
    static private $autoIncrement;

    /**
     * @param String $group
     * @param String $controller
     * @param String|Array $middleware
     * @param String $name
     */
    public function __construct($group = '', $controller = '', $middleware = [], $name = ''){
        $this->prefixName = $group;
        $this->commonController = $controller;
        $this->commonMiddlewares = $this->middleware($middleware);
    }

    /**
     * Defina um prefixo de uri para as rotas criadas apartir da execução dessa função.
     * 
     * @param String $prefix
     * 
     * @return Mapa
     */
    public function group($prefix){
        $this->prefixUri = $prefix;
        return $this;
    }

    /**
     * Defina um controller para as rotas criadas apartir da execução dessa função.
     * 
     * @param String $controller
     * 
     * @return Mapa
     */
    public function controller($controller){
        $this->commonController = $controller;
        return $this;
    }

    /**
     * Defina um controller para as rotas criadas apartir da execução dessa função.
     * 
     * @param String $controller
     * 
     * @return Mapa
     */
    public function name($prefix){
        $this->prefixName = $prefix;
        return $this;
    }

    /**
     * Defina middlewares para as rotas criadas apartir da execução dessa função.
     * 
     * @param String|Array $controller
     * 
     * @return Mapa
     */
    public function middleware($middleware){
        $this->commonMiddlewares = $middleware;
        switch(gettype($middleware)){
            case 'string':
                $this->commonMiddlewares = [$middleware];
                break;
            case 'array':
                break;
            default:
                throw new Exception("Tipo inesperado como middleware", 1);
                break;   
        }

        return $this;
    }

    /**
     * @param string $uri
     * @param string $handle
     * @param string $method POST | GET
     * @return Trilha
     */
    public function route($uri, $handle, $method = 'GET'){
        $method = strtoupper($method);

        if($method != 'GET' && $method != 'POST') throw new Exception('Tipo de rota inesperado', 4);

        $trilha = new Trilha(
            !empty($this->prefixUri) ? $this->prefixUri . $uri : $uri, 
            !empty($this->commonController) ? $this->commonController . '@' . $handle : $handle, 
            $method, 
            !empty($this->commonMiddlewares) ? $this->commonMiddlewares : [],
            !empty($this->prefixName) ? 
                (!empty($this->commonController) ? 
                $this->prefixName . '.' . $handle : 
                $this->prefixName . '.' . explode('@', $handle, 2)[1])  
            : ''
        );

        self::addRoute($trilha);

        return $trilha;
    }

    /**
     * @param string $name
     * @return Trilha|bool
     */
    static function getRoute($name){
        return array_key_exists($name, self::$routes) ? self::$routes[$name] : false;
    }

    /**
     * @return Trilha[]
     */
    static function getRoutes(){
        return self::$routes;
    }
    
    /**
     * @param Trilha $trilha
     * @return string
     */
    static private function addRoute($trilha){
        $key = !empty($trilha->getName()) ? $trilha->getName() : uniqid('trilha.key.' . self::$autoIncrement++);
        
        if(self::getRoute($key)) throw new Exception('A rota "' . $key . '" já existe.', 2);

        self::$routes[$key] = $trilha;
        
        return $key;
    }

    /**
     * @param string $currentName
     * @param string $newName
     * @return void
     */
    static public function replaceNameFromRoute($currentName, $newName){
        if(!array_key_exists($currentName, self::$routes)) throw new Exception('A rota "' . $currentName . '" não existe.', 3);
        if(array_key_exists($newName, self::$routes)) throw new Exception('O nome de rota "' . $newName . '" já existe no contexto atual.', 4);

        self::$routes[$newName] = self::$routes[$currentName];
        unset(self::$routes[$currentName]);
    }
}