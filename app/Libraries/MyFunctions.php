<?php
 
namespace App\Libraries;

class MyFunctions {

    function mysort(&$array) {
        usort($array, "byTipo");
    }
    
    function byTipo ($a, $b) {
        return ($a['tipo']< $b['tipo']);
    }

}