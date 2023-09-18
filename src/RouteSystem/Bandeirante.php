<?php

namespace Robsonvieira\RouteSystem;

use Exception;
use Robsonvieira\RouteSystem\Mapa;

class Bandeirante{
    public $current_route;
    private $post;
    private $get;
    private $uri_levels;

    public function match_route(){
        $this->uri_levels = $this->get_uri_levels($_SERVER['REQUEST_URI']);
        $this->current_route = null;
        
        if(!empty($this->uri_levels)){
            foreach(Mapa::getRoutes() as $r){
                if($_SERVER['REQUEST_METHOD'] != $r->getMethod()) continue;
        
                $uri_r_levels = $this->get_uri_levels($r->getUri());
                $is_compatible = [];
                $get_params = [];
            
                if(count($uri_r_levels) == count($this->uri_levels) || strpos($r->getUri(), "?}")){
                    foreach($uri_r_levels as $key => $u_r_l){
                        $is_compatible[$key] = 0;
            
                        if($key+1 <= count($this->uri_levels)){
                            if((strpos($u_r_l, "{") === false || strpos($u_r_l, "}") === false)){
                                if($u_r_l == $this->uri_levels[$key]) $is_compatible[$key] = 2;
                            }
                            else{
                                if(strpos($u_r_l, "::") === false){
                                    $is_compatible[$key] = 1;
                                    
                                    $get_params[] = urldecode($this->uri_levels[$key]);
                                }
                                else{
                                    $type = explode('::', $u_r_l)[0];
                                    
                                    switch($type){
                                        case 'int':
                                            if(is_numeric(urldecode($this->uri_levels[$key]))){
                                                $is_compatible[$key] = 1;
                                    
                                                $get_params[] = urldecode($this->uri_levels[$key]);
                                            }
                                            break;
                                        case 'string':
                                            if(!is_numeric(filter_var(urldecode($this->uri_levels[$key]), FILTER_SANITIZE_NUMBER_INT))){
                                                $is_compatible[$key] = 1;
                                    
                                                $get_params[] = urldecode($this->uri_levels[$key]);
                                            }
                                            break;
                                    }
                                }
                            }
                        }else{
                            if(strpos($u_r_l, "{") === false || strpos($u_r_l, "}") === false) continue;
                            $is_compatible[$key] = 1;
                        }
                    }

                    if(!in_array(0, $is_compatible) && !empty($is_compatible)){
                        if($this->current_route != null && in_array(1, $is_compatible)) continue;

                        if($r->getMethod() == 'POST') $this->post = $_POST;
                        $this->get = $get_params;
                        $this->current_route = $r;
                    }     
                }
            
            }
        }
        else{
            foreach(Mapa::getRoutes() as $r){
                if($r->getUri() == '/') $this->current_route = $r;
            }
        }
        
        if($this->current_route == null) throw new Exception('Rota não existe');
    }

    public function execute(){
        $handle = explode("@", $this->current_route->getReferenceHandle(), 2);
        
        $class = new $handle[0]();
        $func = $handle[1];
        
        $class->$func(...$this->get);
    }

    function get_uri_levels($uri){
        $uri_levels = explode('/', trim($uri));
    
        if(empty($uri_levels[0])) array_shift($uri_levels);
        if(empty($uri_levels[count($uri_levels) - 1])) array_pop($uri_levels);
    
        return $uri_levels;
    }
}