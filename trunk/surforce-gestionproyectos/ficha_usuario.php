<?php
require_once ( 'configuracion.php' );

require_once( DOM . "/DominioFachada.class.php" );
require_once( PRE . "/PresentacionFachada.class.php" );

session_start();
DominioFachada::loginAdministrador($_SESSION['usuario'], $_SESSION['clave']);

switch ( $_GET['accion'] ) {
	case "habilitar":
		DominioFachada::habilitarUsuario( $_GET['id'] );
		break;
	case "deshabilitar":
		DominioFachada::deshabilitarUsuario( $_GET['id'] );
		break;
	case "asignarforo":
		DominioFachada::asignarForoUsuario( $_GET['id'] );
		break;
	case "desasignarforo":
		DominioFachada::desasignarForoUsuario( $_GET['id'] );
		break;
	default:
		break;
}
PresentacionFachada::mostrarUsuario( DominioFachada::traerUsuarioPorId( $_GET['id'] ), DominioFachada::traerValoresEncuesta() );
?>
