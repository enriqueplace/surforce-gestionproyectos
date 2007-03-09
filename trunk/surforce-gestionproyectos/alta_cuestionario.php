<?php
require_once ( 'configuracion.php' );

require_once( DOM . "/DominioFachada.class.php" );
require_once( PRE . "/PresentacionFachada.class.php" );

if($_POST){
	$alta = DominioFachada::altaCuestionario( $_POST );
	if( ! $alta['error'] ){
		unset( $_POST );
	}
}

PresentacionFachada::altaCuestionario( $alta );
?>
