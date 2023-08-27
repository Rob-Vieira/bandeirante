<?php

require_once './vendor/autoload.php';

use Robsonvieira\Trilha;

Trilha::name('blog')->group('/blog', function(){
    Trilha::get('/', 'BlogController@index', 'index');
    Trilha::get('/pesquisa', 'BlogController@pesquisa', 'pesquisa');
    Trilha::get('/{categoria}', 'BlogController@categoria', 'categoria');
});

echo '<pre>';
print_r(Trilha::get_routes());
echo '</pre>';

// include 'Bandeirante.php';

// $bandeirante = new Bandeirante($_SERVER['REQUEST_URI'], 'controllers/');

// $bandeirante->get('/', 'HomeController@index', 'home.index');
// $bandeirante->get('/blog', 'BlogController@index', 'blog.index');
// $bandeirante->get('/artigo/{name}', 'BlogController@artigo', 'blog.artigo');

// $bandeirante->match_route();

// $bandeirante->execute();

?>