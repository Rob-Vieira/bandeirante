<?php

namespace Robsonvieira\RouteSystem;

use Robsonvieira\RouteSystem\Mapa;
use Exception;

class Trilha{
    private $uri;
    private $referenceHandle;
    private $middlewares;
    private $name;
    private $type;

    /**
     * @param String $uri
     * @param String $referenceHandle
     * @param String $type
     * @param String|Array $middleware
     * @param String $name
     */
    public function __construct($uri, $referenceHandle, $type, $middleware = [], $name = ''){
        $this->uri = $uri;
        $this->referenceHandle = $referenceHandle;
        $this->type = $type;
        $this->middlewares = $this->middleware($middleware);
        $this->name = $name;
    }

    /**
     * Nomeia a rota.
     * 
     * @param String $name
     * 
     * @return Mapa
     */
    public function name($name){
        $this->name = $name;
        return $this;
    }

    /**
     * Defina middlewares para a rota.
     * 
     * @param String $middleware
     * 
     * @return Mapa
     */
    public function middleware($middleware){
        if(gettype($middleware) == 'array') throw new Exception("Tipo inesperado como middleware", 1);
        $this->middlewares = $middleware;

        return $this;
    }

    /**
     * @return string
     */
    public function getUri(){
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getReferenceHandle(){
        return $this->referenceHandle;
    }

    /**
     * @return array
     */
    public function getMiddlewares(){
        return $this->middlewares;
    }

    /**
     * @return string
     */
    public function getType(){
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(){
        return $this->name;
    }
}