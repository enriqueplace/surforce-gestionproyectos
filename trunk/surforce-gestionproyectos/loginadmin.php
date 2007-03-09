<?php
session_start();

require_once( "configuracion.php" );
require_once( PRE . DIRECTORY_SEPARATOR . "PresentacionFachada.class.php" );
require_once( DOM . DIRECTORY_SEPARATOR . "DominioFachada.class.php" );

if($_GET['accion']=='login'){
	if($_POST){

		$clave = strtoupper( md5($_POST['clave']) );

		DominioFachada::loginAdministrador($_POST['usuario'], $clave );

		$_SESSION['usuario'] = $_POST['usuario'];
		$_SESSION['clave'] = $clave;

		header("Location: index_admin.php");
		exit();
	}
}
PresentacionFachada::mostrarLoginAdmin();
?>
