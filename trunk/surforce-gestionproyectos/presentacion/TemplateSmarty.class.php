<?PHP
require_once( 'configuracion.php' );
require_once( SMARTY . DIRECTORY_SEPARATOR . "libs/Smarty.class.php");

/*Heredamos de Smarty todos sus atributos y m�todos, pero en
 * la construcci�n del objeto definimos un comportamiento por defecto
 * que m�s se ajuste a nuestras necesidades particulares.
 * */
class TemplateSmarty extends Smarty{
	function __construct(){
		$this->template_dir = 'templates';
		$this->config_dir = 'config';
		$this->cache_dir = 'cache';
		$this->compile_dir = TMP_C;
		$this->left_delimiter = '<!--{';
		$this->right_delimiter = '}-->';
	}
}
?>