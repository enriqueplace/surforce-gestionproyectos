<?php
require_once( "configuracion.php" );

require_once( PRE . DIRECTORY_SEPARATOR . "PresentacionFachada.class.php" );
require_once( DOM . DIRECTORY_SEPARATOR . "DominioFachada.class.php" );

session_start();
DominioFachada::loginAdministrador($_SESSION['usuario'], $_SESSION['clave']);

$titulo = "Gestión Taller PHP5";
$texto = "texto";

PresentacionFachada::mostartIndexAdmin();

?>
