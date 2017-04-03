<?php
/**
 * Author: Joaquin Garavaglia
 * Last change: 02/12/2015
 *
 * Description:
 */

function ReadStdin($prompt, $default = '') {
    while(!isset($input)) {
        echo $prompt;
        $input = trim(fgets(STDIN));
        if(empty($input) && !empty($default)) {
            $input = $default;
        }
    }
    return $input;
}

function WriteError(){
    echo utf8_encode("* No es una búsqueda válida. \n * La longitud de la palabra debe estar entre [0-40] caracteres. \n * Alfabetos válidos[Latin,Arabic,Cyrillic]");
    return true;
}

function WriteResults($arrResults){
    echo "\n\n RESULTADOS \n";
    echo    "*************\n\n";
    if(isset($arrResults)){
        foreach ($arrResults as $res ) {
            if ($res['lodging_type'] == "Hotel") {
                echo "* Hotel " . $res['name'] . "," . $res['stars'] . " Estrellas," . utf8_encode("Habitación ") . $res['room_type'] . "," . $res['city'] . "," . $res['province'];
                echo "\n";
            } elseif ($res['lodging_type'] == "Apartamento") {
                echo "* Apartamento " . utf8_encode($res['name']) . "," . $res['apartments'] . " Apartamentos," . $res['people'] . " Personas," . $res['city'] . "," . $res['province'];
                echo "\n";
            } else {
                echo utf8_encode("Entrada sin categoría de Hotel u Apartamento");
            }
        }
    }else{
        echo "No se han encontrado Resultados :( ";
    }
    return true;
}

?>