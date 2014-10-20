<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once './clases/leer.php';
require_once './clases/Subir.php';

$nombre = Leer::post("nombre");
$op = Leer::post("opcion");
$nombreCarpeta = Leer::post("nombreCarpeta");
$opCarpetas = Leer::post("carpeta");
$extensiones = Leer::post("extensiones");
$maximo = Leer::post("maximo");

$archivo = new Subir("archivo");
$archivo->setNombre($nombre);

if($op == 0){
    $archivo->setAccion(Subir::RENOMBRAR);
}else if($op == 1){
    $archivo->setAccion(Subir::SOBREESCRIBIR);
}


if($nombreCarpeta != NULL){
    $archivo->setDestino("./archivos_subidos/" . $nombreCarpeta);
}else{
    $archivo->setDestino("./archivos_subidos/");
}



if($opCarpetas == 0){
    $archivo->setCrearCarpeta(true);
}else{
    $archivo->setCrearCarpeta(false);
}

if($extensiones != NULL){
    
    $matriz = split(",", $extensiones);
    
    foreach ($matriz as $key => $value){
        $matriz[$key] = trim($value);
    }
    
    $archivo->addExtension($matriz);
    
}

if($maximo != NULL && $maximo > 0){
    $archivo->setMaximo($maximo);
}

$archivo->setPermisos(Subir::ALL_PRIVILEGES);

$error = $archivo->subir();

$resultado = Subir::getMessageErrorHTML($error);

header("Location: ./index.php?r=" . $resultado);
