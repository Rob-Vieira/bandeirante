<?php

namespace Robsonvieira\Controllers;

class BlogController{
    public function index(){
        require 'views/blog/index.php';
    }
    
    public function artigo($name){

        require 'views/blog/artigo.php';
    }
    
    public function categoria($categoria, $id = null){
        echo "Categoria: " . $categoria;
        if(!empty($id)) echo "<br><br>ID: " . $id;
    }
    public function categorias($categoria, $id = null){
        echo "Categorias: " . $categoria;
        if(!empty($id)) echo "<br><br>ID: " . $id;
    }

    public function pesquisa(){
        echo "Essa é uma página de pesquisa, confia!!";
    }
}