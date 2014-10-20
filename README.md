Clase_subir
===========

Practica 1


Descripción:
Clase PHP para entorno servidor que gestiona las peticiones de archivos binarios del servidor subidos por el cliente, de forma eficiente.
Caracteristicas:
Permite subir archivos al servidor de manera eficiente y controlada.
- Filtro de extensiones.
- Soporte multiarchivo.
- Posibilidad de crear dependencias (directorios).
- Contiene tres opciones de subida (RENOMBRAR, REEMPLAZAR, IGNORAR).
- Posibilidad de limitar el tamaño.
- Filtrado de tipo MIME
- Control de errores


Funciones y uso:


RENOMBRAR (No destructiva): se establece desde la función ‘setAccion’, copia los archivos de la carpeta temporal del servidor a la carpeta destino (‘setDestino’), si existe un archivo con el mismo nombre y extensión, lo renombra numéricamente.

REEMPLAZAR (Destructiva): se establece desde la función ‘setAccion’, copia los archivos de la carpeta temporal del servidor a la carpeta destino (‘setDestino’) y reemplaza solamente los archivos que existen.

IGNORAR(No destructivo)(Por defecto): se establece desde la función ‘setAccion’, copia los archivos de la carpeta temporal del servidor a la carpeta destino (‘setDestino’), si existe un archivo con el mismo nombre y extensión, no lo copia.



Errores y Mensajes:

Error por exceder el tamaño máximo:
SIZE_ERROR

Error por no cumplir extensión:
EXTENSION_ERROR

Error por no respetar el tipo MIME establecido:
TIPO_ERROR

Error de subida:
UPLOAD_ERROR

Error al mover archivos de la carpeta temporal a su destino:
MOVE_ERROR

Error al intentar crear una carpeta:
CREATE_DIR_ERROR

Error: carpeta no encontrada:
MISSING_DIR_ERROR




3.- Uso de la Clase Subir

Contexto:

Formularios HTML5 con servidor PHP5 o superior. Subida de cualquier tipo de archivos binarios, soporta solo un campo múltiple de ‘input file’
Uso de la web de ejemplo

En el primer cuadro ofrece información sobre el tipo de petición y el destino.
Podemos acceder al destino en cualquier momento para comprobar nuestros resultados, también posee un icono en forma de papelera a modo de enlace para poder limpiar la carpeta.
El segundo recuadro encontramos los parámetros de subida como el nombre, archivo, tipo, extensión, acción, permiso de crear carpetas y tamaño máximo.
El tercer recuadro muestra los resultados de la subida, en caso de error, mostrara un mensaje con información sobre el error.
