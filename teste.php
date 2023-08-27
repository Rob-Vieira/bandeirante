<?php

$blog_pesquisa = function(){echo "Blog Pesquisa";};

$routes = [
    ['uri' => '/',                               'type' => 'GET', 'handle' => function(){ echo "<h1>Home</h1>"; },   'name' => 'home'           ],
    ['uri' => '/{success}',                      'type' => 'GET', 'handle' => 'HomeController@index',                'name' => 'home.index'     ],
    ['uri' => '/{fail}',                         'type' => 'GET', 'handle' => 'HomeController@index2',               'name' => 'home.index2'    ],
    ['uri' => '/sobre',                          'type' => 'GET', 'handle' => 'sobre',                               'name' => 'sobre'          ],
    ['uri' => '/blog',                           'type' => 'GET', 'handle' => 'BlogController@index',                'name' => 'blog.index'     ],
    ['uri' => '/blog/categoria/{slug}/id/{id?}', 'type' => 'GET', 'handle' => 'BlogController@categoria',            'name' => 'blog.categoria' ],
    ['uri' => '/blog/pesquisa',                  'type' => 'GET', 'handle' => $blog_pesquisa,                        'name' => 'blog.pesquisa'  ],
];

//Encontrando a rota correta

$uri_levels = get_uri_levels($_SERVER['REQUEST_URI']);
$compatible_route = null;

if(!empty($uri_levels)){
    foreach($routes as $r){
        if($_SERVER['REQUEST_METHOD'] != $r['type']) continue;

        $uri_r_levels = get_uri_levels($r['uri']);
        $is_compatible = [];
        $get_params = [];
    
        if(count($uri_r_levels) == count($uri_levels) || strpos($r['uri'], "?}")){
            foreach($uri_r_levels as $key => $u_r_l){
                $is_compatible[$key] = 0;
    
                if($key+1 <= count($uri_levels)){
                    if((strpos($u_r_l, "{") === false || strpos($u_r_l, "}") === false)){
                        if($u_r_l == $uri_levels[$key]) $is_compatible[$key] = 1;
                    }
                    else{
                        $is_compatible[$key] = 1;
                        
                        $get_params[] = urldecode($uri_levels[$key]);
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
            $compatible_route = $r;
        }     
    }
}
else{
    foreach($routes as $r){
        if($r['uri'] == '/') $compatible_route = $r;
    }
}

if($compatible_route == null) die('Rota não existe.');
//-----------------------------------------------------
if(gettype($compatible_route['handle']) == 'string' && strpos($compatible_route['handle'], '@')){
    $handle = explode("@", $compatible_route['handle']);

    include_once $handle[0] . '.php';
    
    $class = new $handle[0]();
    $func = $handle[1];
    
    $class->$func(...$compatible_route['get']);
}
else{
    if(!empty($compatible_route['get'])){
        $compatible_route['handle'](...$compatible_route['get']);
    }
    else{
        $compatible_route['handle']();
    }
}

function get_uri_levels($uri){
    $uri_levels = explode('/', trim($uri));

    if(empty($uri_levels[0])) array_shift($uri_levels);
    if(empty($uri_levels[count($uri_levels) - 1])) array_pop($uri_levels);

    return $uri_levels;
}

function sobre(){
    echo "<h1>Sobre</h1>";
}
?>