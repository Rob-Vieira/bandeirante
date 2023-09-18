<?php

require_once './vendor/autoload.php';

use Robsonvieira\Controllers\BlogController;
use Robsonvieira\RouteSystem\Bandeirante;
use Robsonvieira\RouteSystem\Mapa;


$bandeirante = new Bandeirante();
$mapa = new Mapa();

$mapa->controller(BlogController::class)->group('/blog')->name('blog');


$mapa->route('/', 'index', 'GET');
$mapa->route('/pesquisa', 'pesquisa', 'GET');
$mapa->route('/pesquisas/teste', 'pesquisas', 'GET');
$mapa->route('/pesquisas/gera', 'pesquisas_g', 'GET');
$mapa->route('/pesquisas/{teste}', 'pesquisas_t', 'GET');
$mapa->route('/int::{categoria}', 'categoria', 'get');

$bandeirante->match_route();
$bandeirante->execute();

?>