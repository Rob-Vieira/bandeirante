<?php

class Bandeirante{
    public $routes;
    public $current_route;
    public $routes_by_name;
    private $uri;
    private $uri_levels;
    private $controllers_path;

    public function __construct($uri, $controllers_path = ''){
        $this->controllers_path = $controllers_path;
        $this->set_uri($uri);
    }

    public function get($uri, $handle, $name = ''){
        $this->set_route($uri, "GET", $handle, $name);
    }

    public function post($uri, $handle, $name = ''){
        $this->set_route($uri, "POST", $handle, $name);
    }

    public function set_route($uri, $type, $handle, $name = ''){
        $this->routes[] = [
            'uri' => $uri, 
            'type' => strtoupper($type), 
            'handle' => $handle,
            'name' => '',
        ];

        if(gettype($name) == 'string' && $name != '') $this->routes_by_name[$name] = count($this->routes) - 1;
    }

    public function match_route(){
        $this->uri_levels = $this->get_uri_levels($_SERVER['REQUEST_URI']);
        $this->current_route = null;
        
        if(!empty($this->uri_levels)){
            foreach($this->routes as $r){
                if($_SERVER['REQUEST_METHOD'] != $r['type']) continue;
        
                $uri_r_levels = $this->get_uri_levels($r['uri']);
                $is_compatible = [];
                $get_params = [];
            
                if(count($uri_r_levels) == count($this->uri_levels) || strpos($r['uri'], "?}")){
                    foreach($uri_r_levels as $key => $u_r_l){
                        $is_compatible[$key] = 0;
            
                        if($key+1 <= count($this->uri_levels)){
                            if((strpos($u_r_l, "{") === false || strpos($u_r_l, "}") === false)){
                                if($u_r_l == $this->uri_levels[$key]) $is_compatible[$key] = 1;
                            }
                            else{
                                $is_compatible[$key] = 1;
                                
                                $get_params[] = urldecode($this->uri_levels[$key]);
                            }
                        }else{
                            if(strpos($u_r_l, "{") === false || strpos($u_r_l, "}") === false) continue;
                            $is_compatible[$key] = 1;
                        }
                    }
                }
            
                if(!in_array(0, $is_compatible) && !empty($is_compatible)){
                    if($r['type'] == 'POST') $r['post'] = $_POST;
                    $r['get'] = $get_params;
                    $this->current_route = $r;
                }     
            }
        }
        else{
            foreach($this->routes as $r){
                if($r['uri'] == '/') $this->current_route = $r;
            }
        }
        
        if($this->current_route == null) die('Rota não existe.');
    }

    function get_uri_levels($uri){
        $uri_levels = explode('/', trim($uri));
    
        if(empty($uri_levels[0])) array_shift($uri_levels);
        if(empty($uri_levels[count($uri_levels) - 1])) array_pop($uri_levels);
    
        return $uri_levels;
    }

    public function set_uri($uri){
        $this->uri = $uri;
        $this->uri_levels = $this->get_uri_levels($uri);
    }

    public function execute(){
        if(gettype($this->current_route['handle']) == 'string' && strpos($this->current_route['handle'], '@')){
            $handle = explode("@", $this->current_route['handle']);

            include_once ($this->controllers_path != '' ? $this->controllers_path : '') . $handle[0] . '.php';
            
            $class = new $handle[0]();
            $func = $handle[1];
            
            $class->$func(...$this->current_route['get']);
        }
        else{
            if(!empty($this->current_route['get'])){
                $this->current_route['handle'](...$this->current_route['get']);
            }
            else{
                $this->current_route['handle']();
            }
        }
    }
}