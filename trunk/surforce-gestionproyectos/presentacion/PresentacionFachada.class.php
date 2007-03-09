<?php
require_once( 'configuracion.php' );
require_once( 'TemplateSmarty.class.php' );
require_once( DOM . DIRECTORY_SEPARATOR . 'DominioFachada.class.php');

abstract class PresentacionFachada {
	public function mostrarUsuario( $usuario, $valores_encuesta ){
		$miSmarty = new TemplateSmarty();
		$miSmarty->assign("usuario", $usuario);
		$miSmarty->assign("FOTOS", FOTOS);
		$miSmarty->assign("sitio_contenido", "contenido_ficha_usuario.tpl.html");
		$miSmarty->assign("valores_encuesta", $valores_encuesta);
		$miSmarty->assign("menu", "sitio_menu_admin.tpl.html");
		$miSmarty->display("sitio_estructura_usuarios.tpl.html");
	}
	public function mostrarLoginAdmin(){
		$miSmarty = new TemplateSmarty();
		$miSmarty->assign("sitio_contenido", "contenido_loginadmin.tpl.html");
		$miSmarty->display("sitio_estructura_index.tpl.html");
	}
	public function autorizarUsuario( $sesion ){
		$miSmarty = new TemplateSmarty();
		$miSmarty->assign("titulo", "Autenticación de Usuario");
		$miSmarty->assign("usuarios", $usuarios);
		$miSmarty->assign("sitio_contenido", "contenido_autenticar_usuario.tpl.html");
		$miSmarty->display("sitio_estructura_estandar.tpl.html");
	}
	public function listarUsuarios($usuarios){
		$miSmarty = new TemplateSmarty();
		$miSmarty->assign("titulo", "Listado de usuarios");
		$miSmarty->assign("usuarios", $usuarios);
		$miSmarty->assign("sitio_contenido", "contenido_listado_usuarios.tpl.html");
		$miSmarty->assign("menu", "sitio_menu_admin.tpl.html");
		$miSmarty->display("sitio_estructura_usuarios.tpl.html");
	}

	public function mostartIndexAdmin(){
		$miSmarty = new TemplateSmarty();
		$miSmarty->assign("sitio_contenido", "contenido_index_admin.tpl.html");
		$miSmarty->assign("menu", "sitio_menu_admin.tpl.html");
		$miSmarty->display("sitio_estructura_usuarios.tpl.html");
	}

	public function altaCuestionario($alta = ""){
		$miSmarty = new TemplateSmarty();
		$miSmarty->register_modifier('sslash', 'stripslashes');
		$miSmarty->assign("titulo", "Alta de Cuestionario");
		$miSmarty->assign("alta", $alta);
		$miSmarty->assign("sitio_contenido", "contenido_alta_cuestionario.tpl.html");
		$miSmarty->display("sitio_estructura_estandar.tpl.html");
	}

	public function listarCuestionarios($cuestionarios){
		$miSmarty = new TemplateSmarty();
		$miSmarty->assign("titulo", "Listado de Cuestionarios");
		$miSmarty->assign("cuestionarios", $cuestionarios);
		$miSmarty->assign("sitio_contenido", "contenido_listado_cuestionarios.tpl.html");
		$miSmarty->display("sitio_estructura_usuarios.tpl.html");
	}

	public function mostrarIndex($titulo, $texto){
		$miSmarty = new TemplateSmarty();
		$miSmarty->assign("titulo", $titulo);
		$miSmarty->assign("texto", $texto);
		$miSmarty->assign("sitio_contenido", "contenido_index.tpl.html");
		$miSmarty->assign("usuarios", DominioFachada::traerUsuariosHabilitados());
		$miSmarty->assign("usuarios_todos", DominioFachada::getCantidadUsuarios());
		$miSmarty->assign("usuarios_habilitados", DominioFachada::getCantidadUsuariosHabilitados());
		$miSmarty->assign("usuarios_no_habilitados", DominioFachada::getCantidadUsuariosNoHabilitados());

		$miSmarty->display("sitio_estructura_index.tpl.html");
	}
	public function mostrarAltaUsuario($titulo, $texto, $accion){
		$miSmarty = new TemplateSmarty();
		switch($accion){
			case "datos_personales":
				$miSmarty->assign("datos", $_SESSION);
				$miSmarty->assign("titulo", $titulo." - Paso 1 - Datos Personales");
				$miSmarty->assign("sitio_contenido", "contenido_alta_usuario_datos_personales.tpl.html");
				$miSmarty->register_modifier('sslash', 'stripslashes');
				break;
			case "encuesta":
				$miSmarty->assign("datos", $_SESSION);
				$miSmarty->assign("titulo", $titulo .= " - Paso 2 - Encuesta");
				$miSmarty->assign("sitio_contenido", "contenido_alta_usuario_encuesta.tpl.html");

				// Chequeo si ya existe el usuario
				$resultado = DominioFachada::obtenerIdUsuarioPorGmail($_SESSION['gmail']);
				if($resultado){
					$miSmarty->assign("mensaje", "Ya existe un usuario con la dirección ".$_SESSION['gmail']);
					$miSmarty->assign("sitio_contenido", "contenido_error.tpl.html");
					break;
				}

				// Si subió una foto, la guardamos
				if($_FILES['foto']['tmp_name']){
					$resultado = DominioFachada::guardarFoto($_SESSION['gmail'], $_FILES['foto']['tmp_name']);
					if($resultado['error']){
						$miSmarty->assign("mensaje", $resultado['mensaje']);
						$miSmarty->assign("sitio_contenido", "contenido_error.tpl.html");
					}else{
						$_SESSION['foto'] = $resultado['mensaje'];
					}
					break;
				}
				$miSmarty->register_modifier('sslash', 'stripslashes');
				break;

			case "procesar":
				$datosAlta['nombre'] = $_SESSION['nombre'];
				$datosAlta['apellido'] = $_SESSION['apellido'];
				$datosAlta['pais'] = $_SESSION['pais'];
				$datosAlta['ciudad'] = $_SESSION['ciudad'];
				$datosAlta['gmail'] = $_SESSION['gmail'];
				$datosAlta['skype'] = $_SESSION['skype'];
				$datosAlta['profesion'] = $_SESSION['profesion'];
				$datosAlta['empresa'] = $_SESSION['empresa'];
				$datosAlta['expectativas'] = $_SESSION['expectativas'];
				$datosAlta['foto'] = $_SESSION['foto'];
				$datosAlta['usuario'] = $_SESSION['gmail']; // Vamos a usar el gmail como nombre de usuario
				$datosAlta['clave'] = $_SESSION['clave'];
				$datosAlta['programacion'] = $_SESSION['programacion'];
				$datosAlta['objetos'] = $_SESSION['objetos'];
				$datosAlta['sql'] = $_SESSION['sql'];
				$datosAlta['uml'] = $_SESSION['uml'];
				$datosAlta['php'] = $_SESSION['php'];
				$datosAlta['html'] = $_SESSION['html'];
				$datosAlta['css'] = $_SESSION['css'];
				$datosAlta['js'] = $_SESSION['js'];
				$datosAlta['lenguajes'] = $_SESSION['lenguajes'];
				$datosAlta['patrones'] = $_SESSION['patrones'];
				$datosAlta['frameworks'] = $_SESSION['frameworks'];
				$datosAlta['puesto'] = $_SESSION['puesto'];
				$datosAlta['enteraron'] = $_SESSION['enteraron'];
				$resultado = DominioFachada::altaUsuario($datosAlta);
				$miSmarty->assign("resultado", $resultado);
				$miSmarty->assign("titulo", $titulo .= " - Registro");
				$miSmarty->assign("sitio_contenido", "contenido_alta_usuario_procesar.tpl.html");
				$miSmarty->register_modifier('sslash', 'stripslashes');
				break;
			default:
				die("Acción Incorrecta");
				break;
		}

		$miSmarty->assign("texto", $texto);
		$miSmarty->display("sitio_estructura_estandar.tpl.html");
	}

/*---------------------------------------*/
/*
	public function mostrarTexto($titulo, $texto){
		require_once("TemplateSmarty.class.php");
		$miSmarty = new TemplateSmarty();

		$miSmarty->assign("titulo", $titulo);
		$miSmarty->assign("texto", $texto);
		$miSmarty->assign("sitio_contenido", "contenido_index.tpl.html");
		$miSmarty->display("sitio_estructura_estandar.tpl.html");
	}

	public function consultarUsuario(){
		require_once("TemplateSmarty.class.php");
		$miSmarty = new TemplateSmarty();
		$miSmarty->assign("titulo", "Consultar Usuarios");
		$miSmarty->assign("sitio_contenido", "contenido_consultar_usuario.tpl.html");
		$miSmarty->display("sitio_estructura_estandar.tpl.html");
	}

	public function mostrarUsuario($unUsuario){
		require_once("TemplateSmarty.class.php");
		$miSmarty = new TemplateSmarty();
		$miSmarty->assign("titulo", " Usuario");
		$miSmarty->assign("usuario",$unUsuario);

		$miSmarty->assign("sitio_contenido", "contenido_mostrar_usuario.tpl.html");
		$miSmarty->display("sitio_estructura_estandar.tpl.html");
	}
	*/
}
?>