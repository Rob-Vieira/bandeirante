<?php

namespace Robsonvieira\RouteSystem\Parameters;

interface TypeParameter{
    static public function verify($parameter):mixed;
    static public function getType():string;
}