<?php
require_once( "configuracion.php" );
require_once( PRE . "/PresentacionFachada.class.php" );
require_once( FWK . "/Sesion.class.php" );

session_start();

if( $_GET['debug'] ){
	if( $_SESSION ){
		echo "sesi�n cargada";
		var_dump ( $_SESSION);
	}else{
		echo "sesi�n vac�a";
	}
}

Sesion::cargarPost2Sesion();


PresentacionFachada::autorizarUsuario( $_SESSION );

?>