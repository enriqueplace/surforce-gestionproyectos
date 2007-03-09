<?php
// Carga de configuracion local
$confLocal = ".." . DIRECTORY_SEPARATOR . "gestionproyectos.php";

if( file_exists( $confLocal ) ){
	require_once( $confLocal );
}else{
	die( __FILE__ . ": No se encontró el archivo de configuración local $confLocal");
}

// Sistema

date_default_timezone_set("America/Montevideo");

// Configuracion base
// define('SRV', '/home/tallerphp5admin');
define('APP', HOME_APLICA . DIRECTORY_SEPARATOR . "surforce-gestionproyectos" ); // ¡no olvidar cambiar por el nombre de nuestro proyecto!
define('MOD', APP . DIRECTORY_SEPARATOR . "modulos" );

if(!file_exists(APP)){
	die("El directorio de la aplicación no existe " . APP);
}

// 3 capas
define('DOM', APP . DIRECTORY_SEPARATOR . "dominio");
define('PRE', APP . DIRECTORY_SEPARATOR . "presentacion");
define('PER', APP . DIRECTORY_SEPARATOR . "persistencia");

// Utilidades
define('SMARTY', APP . DIRECTORY_SEPARATOR . "Smarty-2.6.18");
define('PEAR', APP . DIRECTORY_SEPARATOR . "pear");
define('AJAX', APP . DIRECTORY_SEPARATOR . "xajax");
// Es un proyecto distinto que está fuera de la rama del proyecto actual
define('FWK', APP . DIRECTORY_SEPARATOR . "surforce-frameworkphp");

define('JS', APP);

define('FOTOS', APP . DIRECTORY_SEPARATOR . "fotos");
define('TMP_C', APP . DIRECTORY_SEPARATOR . "templates_c");

// Alias - permite usar indistintamente la
// constante abreviada o el nombre literal
// del paquete (uso: con la función "import"
// implementada a continuación)
define( 'dominio', DOM );
define( 'presentacion', PRE );
define( 'persistencia', PER );
define( 'framework', FWK );
define( 'smarty', SMARTY );
define( 'pear', PEAR );
define( 'ajax', AJAX );

/** Permite importar completamente un paquete sin tener
 * que estar nombrando cada clase que se necesita del paquete.
 *
 * Notas importantes:
 *
 * 1) usa Funciones SPL (Standard PHP Library)
 * http://www.php.net/manual/es/ref.spl.php
 * Forma de uso:
 *
 * 2) usa recursividad para resolver el caso que vuelva
 * a recorrer un directorio (uno dentro de otro, un paquete
 * dentro de otro).
 *
 * 3) Esta función es muy elemental, pues podría recargar el sistema
 * traer un paquete con muchas clases, pero que solo se van a usar
 * algunas de ellas (esta versión del parche sirve solo para contextos
 * reducidos ).
 * import( DOM ); // importa todas las clases del dominio
 *
 * Usando alias, así queda más nemotécnico
 *
 * import( dominio );
 *
 * */
function import($paquete){
	if( is_null( $paquete ) ){
		die( __FILE__ . ": el paquete no puede ser vacío");
	}

	$debug = false;

	$dir = new DirectoryIterator( $paquete );

	// Recorre el contenido del directorio
	foreach( $dir as $file ) {

		// Si es un "." o "..", obviarlo.
	   	if ( $dir->isDot() ){
	       continue;
	   	}

	   	if ( $debug ){ echo $file . "\n"; }

	   	// Sacar la extensión para validar luego
	   	// que incluya únicamente archivos .php y
	   	// no .txt
  		$Filename = $dir->GetFilename();
       	$FileExtension = strrpos($Filename, ".", 1) + 1;
       	$extension = strtolower(substr($Filename, $FileExtension, strlen($Filename) - $FileExtension));

		if( $file != "configuracion.php" && $extension == "php" ){
			require_once( $paquete . "/" . $file );
		}

		// Si el contenido del directorio tiene otro directorio dentro,
		// llama recursivamente a la función import
		if ( $file->isDir()){
			if($debug) {echo "es directorio $paquete/$file ";}
			import( "$paquete/$file" );
		}
	}
}

?>
