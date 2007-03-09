<?php
require_once ( 'configuracion.php' );

require_once( DOM . "/DominioFachada.class.php" );
require_once( PRE . "/PresentacionFachada.class.php" );

session_start();
DominioFachada::loginAdministrador($_SESSION['usuario'], $_SESSION['clave']);
PresentacionFachada::listarUsuarios( DominioFachada::traerUsuarios( $_GET['orden']) );

?>
