<?php

use Robsonvieira\RouteSystem\Parameters\TypeParameter;

class IntParameter implements TypeParameter{
    static public function verify($parameter):mixed{
        return is_numeric($parameter) ? $parameter : false;
    }

    static public function getType():string{
        return 'int';
    }
}