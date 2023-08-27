<?php

class HomeController{
    public function index($success = ''){
        echo 'Essa função não será executada enquanto estiver atrás da rota /{fail} e o parâmetro é: ' . $success;
    }
    
    public function index2($fail){
        echo "Essa rota aparece por que está na frente da rota /{success} e o parâmetro é: " . $fail;
    }
}