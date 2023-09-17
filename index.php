<?php

require_once './vendor/autoload.php';

use Robsonvieira\Controllers\BlogController;
use Robsonvieira\RouteSystem\Bandeirante;
use Robsonvieira\RouteSystem\Mapa;


$bandeirante = new Bandeirante();
$mapa = new Mapa();

$mapa->controller(BlogController::class)->group('/blog')->name('blog');


$mapa->route('/', 'index', 'GET');
$mapa->route('/{categoria}/{slug}/{id?}', 'categorias', 'GET');
$mapa->route('/{categoria}/id/{id?}', 'categoria', 'GET');
$mapa->route('/pesquisa', 'pesquisa', 'GET');

$bandeirante->match_route();
$bandeirante->execute();

?>