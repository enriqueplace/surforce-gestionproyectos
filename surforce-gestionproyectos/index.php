<?php
require_once( "configuracion.php" );
require_once( PRE . DIRECTORY_SEPARATOR . "PresentacionFachada.class.php" );

$titulo = "Gesti�n Taller PHP5";
$texto = "texto";

PresentacionFachada::mostrarIndex($titulo, $texto);

?>
