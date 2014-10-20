<?php
    require_once './clases/leer.php';
    $resultado = Leer::get("r");

    if(!is_dir("./archivos_subidos")){
        mkdir("./archivos_subidos", 0777);
    }
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Clase Subir</title>
        <link rel="stylesheet" href="css/estilo.css" />
    </head>
    <body>
        <div class="header">
            <h1>Clase Subir</h1>
        </div>
        <div class="doc">
            <p>Metodo: <b>POST</b><br>
                Destino: <b>"./archivos_subidos"</b><a href="archivos_subidos/">Ir a destino</a>  <a href="eliminar.php" alt="Borrar todos los elementos"><img src="imagenes/bin2.png" alt="Borrar todos los elementos" width="16" height="16" /></a><br></p>
        </div>
        <div class="cuerpo">
            <form action="peticion_subida.php" method="POST" enctype="multipart/form-data">
                <label for="nombre">Nombre del archivo (No obligatorio)</label>
                <input type="text" name="nombre" /><br><br>
                <span>Fichero/os (Multiple): </span>
                <input type="file" name="archivo[]" multiple/><br><br>
                <span>Opciones de subida</span><br>
                <input type="radio" name="opcion" value="0" />Renombrar<br>
                <input type="radio" name="opcion" value="1" />Sobrescribir<br>
                <input type="radio" name="opcion" value="2" checked />Ignorar<br><br>
                <span>Opciones de Carpeta</span><br>
                <input type="radio" name="carpeta" value="0" />Permitir creación de carpetas<br>
                <input type="radio" name="carpeta" value="1" checked />Denegar creación de carpetas<br><br> 
                <span>Ruta alternativa (parte de archivos_subidos)</span><br>
                <input type="text" name="nombreCarpeta" placeholder="Ruta Ej:'carpetaNueva'" /><br><br>
                <span>Extensiones aceptadas (default: todas)</span><br>
                <input type="text" name="extensiones" placeholder="Ej: txt,jpg,png,pdf" /><br><br>
                <span>Tamaño máximo aceptado (en Bytes) (default: 2 MB)</span><br>
                <input type="number" name="maximo" /><br><br>
                
                <input type="submit" />
            </form>
        </div>
        <div class="<?php if($resultado != NULL && $resultado != "Sin errores"){echo 'resultadoError';}else{echo 'resultado';} ?>">
            <h2>Resultados:</h2><br>
            <?php 
                if($resultado != NULL){
                    echo $resultado;
                }
            ?>
        </div>
    </body>
</html>
