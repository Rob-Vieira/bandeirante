<?php

use Robsonvieira\RouteSystem\Parameters\TypeParameter;

class IntParameter implements TypeParameter{
    static public function verify($parameter):mixed{
        return !is_numeric(filter_var(urldecode($parameter), FILTER_SANITIZE_NUMBER_INT)) ? $parameter : false;
    }

    static public function getType():string{
        return 'onlyLetters';
    }
}