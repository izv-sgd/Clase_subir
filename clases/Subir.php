<?php

/**
 * Clase Subir (tipo: All Files)
 *
 * Envio de archivos (MIME - multipart)
 *
 * Permite subir archivos al servidor de manera eficiente
 * y controlada.
 * - Filtro de extensiones
 * - Soporte multiarchivo
 * - Posibilidad de crear dependencias (carpetas)
 * - Contiene tres opciones de subida (SOBREESCRIBIR, REEMPLAZAR, IGNORAR)
 * - Posibilidad de limitar el tamaño
 *
 * @author Santiago García Díaz [IES Zaidin vergeles]
 * @author http://santijs.esy.es
 *
 * @package Util
 */
class Subir {

    private $nombre, $destino, $accion, $maximo, $tipo, $extension, $file, $input, $error, $crearCarpeta, $permisos;

    /**
     * No destructivo, copia los archivos de la carpeta temporal
     * del servidor a la carpeta destino, si existe algun archivo con su mismo nombre
     * lo renombra numericamente
     * 
     * @var int 
     */
    const RENOMBRAR = 0;

    /**
     * Destructivo, busca los archivos con el mismo nombre y los reemplaza,
     * si no existen, no hace nada
     * 
     * @var int 
     */
    const REEMPLAZAR = 1;

    /**
     * No destructivo, copia los archivos de la carpeta temporal
     * del servidor a la carpeta destino, si existe algun archivo con el mismo nombre
     * ignora la subida de ese archivo
     * 
     * @var int 
     */
    const IGNORAR = 2;

    /**
     * Error por limite de tamaño
     * 
     * @var int 
     */
    const SIZE_ERROR = -1;
    
    /**
     * Error por no cumplir extension
     * 
     * @var int 
     */
    const EXTENSION_ERROR = -2;
    
    /**
     * Error por no respetar el tipo MIME establecido
     * 
     * @var int 
     */
    const TIPO_ERROR = -3;
    
    /**
     * Error de subida
     * 
     * @var int 
     */
    const UPLOAD_ERROR = -4;
    
    /**
     * Error al mover archivos de la carpeta temporal a su destino
     * 
     * @var int 
     */
    const MOVE_ERROR = -5;
    
    /**
     * Error al intentar crear una carpeta
     * 
     * @var int 
     */
    const CREATE_DIR_ERROR = -6;
    
    /**
     * Error: carpeta no encontrada
     * 
     * @var int 
     */
    const MISSING_DIR_ERROR = -7;
    
    
    /**
     * Permisos preestablecidos: todos los permisos (0777)
     * 
     * @var int 
     */
    const ALL_PRIVILEGES = 0777;
    
    /**
     * Permisos preestablecidos: permisos medios (0775)
     * 
     * @var int 
     */
    const MEDIUM_PRIVILEGES = 0775;
    
    /**
     * Permisos preestablecidos: modo estricto (0755)
     * 
     * @var int 
     */
    const LOW_PRIVILEGES = 0755;

    /**
     * Crea la instancia subir con un identificador de archivo, 
     * establece valores por defecto: Accion -> IGNORAR, Tamaño -> 2MB
     * 
     * @param type $nombre Identificador del archivo
     */
    function __construct($nombre) {
        $this->accion = Subir::IGNORAR;
        $this->destino = "./subir/";
        $this->maximo = 1024 * 1024 * 2; // 2 MB
        $this->tipo = array();
        $this->extension = array();
        $this->file = $_FILES[$nombre];
        $this->input = $nombre;
        $this->nombre = null;
        $this->crearCarpeta = false;
        $this->permisos = Subir::MEDIUM_PRIVILEGES;
    }

    public function setNombre($nombre) {
        if (!empty($nombre) || $nombre != NULL) {
            $this->nombre = $nombre;
        }
    }

    public function setDestino($destino) {

        $caracter = substr($destino, -1);
        if ($caracter != "/") {
            $destino.="/";
        }

        $this->destino = $destino;
    }

    /**
     * Permite crear carpetas en el caso de que no existan o no
     * 
     * @param boolean $crearCarpeta valor boolean
     */
    public function setCrearCarpeta($crearCarpeta) {
        $this->crearCarpeta = $crearCarpeta;
    }

    public function setAccion($accion) {
        $this->accion = $accion;
    }

    public function setMaximo($maximo) {
        $this->maximo = $maximo;
    }

    public function getPermisos() {
        return $this->permisos;
    }

    /**
     * Otorga permisos para la carpeta creada
     * @param numeric $permisos permisos de consola Linux
     */
    public function setPermisos($permisos) {
        $this->permisos = $permisos;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDestino() {
        return $this->destino;
    }

    public function getAccion() {
        return $this->accion;
    }

    public function getMaximo() {
        return $this->maximo;
    }

    public function getExtensiones() {
        return $this->extension;
    }

    /**
     * 
     * Añade extensiones a la lista de extensiones, para filtrar
     * 
     * @param type $param array de extensiones o cadena
     */
    public function addExtension($param) {
        if (is_array($param)) {
//            foreach ($param as $key => $value) {
//
//                $this->extension[] = $value;
//            }

            $this->extension = array_merge($this->extension, $param);
        } else {
            $this->extension[] = $param;
        }
    }

    
    /**
     * Añade diferentes tipos MIME para filtrar la subida
     * 
     * @param type $param array de tipo o cadena
     */
    public function addTipo($param) {
        if (is_array($param)) {
//            foreach ($param as $key => $value) {
//
//                $this->tipo[] = $value;
//            }

            $this->tipo = array_merge($this->tipo, $param); // añade todos los elementos de un array a otra array
        } else {
            $this->tipo[] = $param;
        }
    }

    private function isCarpeta() {
        return is_dir($this->destino);
    }

    public function crearCarpeta($param) {
        return mkdir($param, $this->permisos, true);
    }

    /**
     * 
     * Comprueba si la extension existe en la lista de extensiones
     * 
     * @param type $extension array
     * @return boolean 
     */
    private function comprobarExtension($extension) {
        if (count($this->extension) > 0) {

            if (in_array($extension, $this->extension)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true; // ALL extension
        }
    }

    private function comprobarTipo($tipo) {
        if (count($this->tipo) > 0) {

            if (in_array($tipo, $this->tipo)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true; // ALL type
        }
    }

    /**
     * Comprueba si el archivo sobrepasa el limite permitido
     * 
     * @param numeric $valor en bytes
     * @return boolean resultado de la comprobacion
     */
    private function comprobarMaximo($valor) {
        if ($valor <= $this->maximo) {
            return true;
        } else {
            return false;
        }
    }
    
    public static function getMessageError($codigo) {
        switch ($codigo){
            case Subir::SIZE_ERROR:
                return "tamaño maximo excedido";
            case Subir::EXTENSION_ERROR:
                return "extensión no permitida";
            case Subir::TIPO_ERROR:
                return "tipo MIME no permitido";
            case Subir::UPLOAD_ERROR:
                return "no se ha cargado o subido correctamente";
            case Subir::MOVE_ERROR:
                return "no se pudo mover el fichero";
            case Subir::CREATE_DIR_ERROR:
                return "no se pudo crear la carpeta";
            case Subir::MISSING_DIR_ERROR:
                return "ruta no encontrada";
        }
    }
    
    
    public static function getMessageErrorHTML($error) {
        if(count($error) > 0){
            $message = "";
        }else{
            $message = "Sin errores";
        }
        
        foreach ($error as $key => $value){
            $message .= "<p>Archivo o carpeta: " . $key . "  | error: " . Subir::getMessageError($value) . "<br>";
        }
        
        return $message;
    }
    

    /**
     * Mueve los archivos temporales del servidor al directorio de destino
     * con la configuración instanciada
     * 
     * @return numeric Código de error
     */
    public function subir() {

        $error = array();

        if (!$this->isCarpeta() && $this->crearCarpeta) {
            if (!$this->crearCarpeta($this->destino)) {
                $error["carpeta"] = Subir::CREATE_DIR_ERROR;
                return $error;
            }
        }

        if (!$this->isCarpeta()) {
            $error["ruta"] = Subir::MISSING_DIR_ERROR;
            return $error;
        }


        for ($i = 0; $i < count($this->file['error']); $i++) {

            if ($this->nombre != NULL) {
                $s = pathinfo($this->file['name'][$i]);
                $n = pathinfo($this->destino . $this->nombre . "." .$s['extension']);
            } else {
                $n = pathinfo($this->destino . $this->file['name'][$i]);
            }
            //echo var_dump($this->file)."<br>";

            if (!$this->file['error'][$i] == UPLOAD_ERR_OK) {
                $error[$this->file['name'][$i]] = Subir::UPLOAD_ERROR; //FAIL
            }

            if (!$this->comprobarExtension($n['extension'])) {
                $error[$this->file['name'][$i]] = Subir::EXTENSION_ERROR;
            }

            if (!$this->comprobarTipo($this->file['type'][$i])) {
                $error[$this->file['name'][$i]] = Subir::TIPO_ERROR;
            }

            if (!$this->comprobarMaximo($this->file['size'][$i])) {
                $error[$this->file['name'][$i]] = Subir::SIZE_ERROR;
            }

            switch ($this->accion) {
                case Subir::RENOMBRAR:
                    $temp = $n['filename'] . "." . $n['extension'];
                    $cont = 1;

                    while (file_exists($this->destino . $temp)) {
                        $temp = $n['filename'] . "_" . $cont . "." . $n['extension'];
                        $cont++;
                    }
                    if (!move_uploaded_file($this->file['tmp_name'][$i], $this->destino . $temp)) {
                        $error[$this->file['name'][$i]] = Subir::MOVE_ERROR;
                    }
                    break;

                case Subir::REEMPLAZAR:
                    if (file_exists($this->destino . $n['filename'] . "." . $n['extension'])) {
                        if (!move_uploaded_file($this->file['tmp_name'][$i], $this->destino . $n['filename'] . "." . $n['extension'])) {
                            $error[$this->file['name'][$i]] = Subir::MOVE_ERROR;
                        }
                    }
                    break;

                default:
                    if (!file_exists($this->destino . $n['filename'] . "." . $n['extension'])) {
                        if (!move_uploaded_file($this->file['tmp_name'][$i], $this->destino . $n['filename'] . "." . $n['extension'])) {
                            $error[$this->file['name'][$i]] = Subir::MOVE_ERROR;
                        }
                    }
                    break;
            }
        }
        return $error;
    }

}
