<?php
require_once ( 'configuracion.php' );

require_once( DOM . "/DominioFachada.class.php" );
require_once( PRE . "/PresentacionFachada.class.php" );

PresentacionFachada::listarCuestionarios( DominioFachada::traerCuestionarios() );
?>
