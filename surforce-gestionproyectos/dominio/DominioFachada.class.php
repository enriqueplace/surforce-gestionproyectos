<?php
/*
 * Aquí se resolverá todo el trabajo del Dominio, y desde fuera del mismo
 * solo tratará con la fachada.
 * */
require_once 'configuracion.php';
require_once( PER . DIRECTORY_SEPARATOR . "PersistenciaFachada.class.php" );
require_once( FWK . DIRECTORY_SEPARATOR . "Foto.class.php" );
require_once( DOM . DIRECTORY_SEPARATOR . "AltaUsuario.class.php" );
require_once( FWK . DIRECTORY_SEPARATOR . "Sesion.class.php" );
require_once( DOM . DIRECTORY_SEPARATOR . "AltaCuestionario.class.php" );
require_once( FWK . DIRECTORY_SEPARATOR . "Usuario.class.php" );

abstract class DominioFachada {
	public function altaUsuario($datosAlta){
		foreach($datosAlta as $hash => $valor){
			$datosAlta[$hash] = trim($valor);
		}
		$usuario = new AltaUsuario();
		$usuario->setNombre($datosAlta['nombre']);
		$usuario->setApellido($datosAlta['apellido']);
		$usuario->setPais($datosAlta['pais']);
		$usuario->setCiudad($datosAlta['ciudad']);
		$usuario->setGmail($datosAlta['gmail']);
		$usuario->setSkype($datosAlta['skype']);
		$usuario->setProfesion($datosAlta['profesion']);
		$usuario->setEmpresa($datosAlta['empresa']);
		$usuario->setExpectativas($datosAlta['expectativas']);
		$usuario->setFoto($datosAlta['foto']);
		$usuario->setUsuario($datosAlta['usuario']);
		$usuario->setClave($datosAlta['clave']);
		$usuario->setProgramacion($datosAlta['programacion']);
		$usuario->setObjetos($datosAlta['objetos']);
		$usuario->setSql($datosAlta['sql']);
		$usuario->setUml($datosAlta['uml']);
		$usuario->setPhp($datosAlta['php']);
		$usuario->setHtml($datosAlta['html']);
		$usuario->setCss($datosAlta['css']);
		$usuario->setJs($datosAlta['js']);
		$usuario->setLenguajes($datosAlta['lenguajes']);
		$usuario->setPatrones($datosAlta['patrones']);
		$usuario->setFrameworks($datosAlta['frameworks']);
		$usuario->setPuesto($datosAlta['puesto']);
		$usuario->setEnteraron($datosAlta['enteraron']);

		$resultado = $usuario->realizarAlta();

		if(!$resultado['error']){
			Sesion::destruirSesion();
		}

		return $resultado;
	}

	public function loginAdministrador($usuario='', $clave=''){
		if( strtolower( trim($usuario) ) != "enriqueplace@gmail.com" ){
			header("Location: index.php");
			exit();
		}
		$usuario_persistido = self::traerUsuarioPorUsuario(strtolower( trim( $usuario ) ));
		if( $usuario_persistido['clave_usuario'] != $clave ){
			header("Location: index.php");
			exit();
		}
	}

	public function habilitarUsuario($id){
		$resultado = PersistenciaFachada::habilitarUsuario($id);
		$usuario = self::traerUsuarioPorId($id);

		if($resultado){
			// Email - Usuario
			$asunto  = "TallerPHP5: ¡Tu registro ha sido aceptado!";
			$cuerpo  = "<b>¡Tu registro ha sido aceptado!</b><br><br>\n\n";
			$cuerpo .= "<b>".$usuario['nombre_usuario'].":</b>" .
					" Mmmm... algo debes haber hecho bien pues el administrador" .
					" aceptó tu solicitud de registro ;-)." .
					" Esperemos que no" .
					" desperdicies esta oportunidad, porque con la misma" .
					" rapidez puede darte de baja }:-)" .
					" <br><br>" .
					" Gracias por participar del taller piloto sobre PHP5 ;-)" .
					" <br><br>" .
					"Por cualquier consulta o queja: tallerphp5@surforce.com" .
					"<br><br>" .
					"-= SurForce Team =-";
			$origen  = MAIL_ADMIN;
			$resultado = self::enviarMail($usuario['gmail_usuario'],$asunto,$cuerpo,$origen);
		}
		return $resultado;
	}
	public function deshabilitarUsuario($id){
		$resultado = PersistenciaFachada::deshabilitarUsuario($id);
		$usuario = self::traerUsuarioPorId($id);

		if($resultado){
			// Email - Usuario
			$asunto  = "TallerPHP5: Usuario Deshabilitado :(";
			$cuerpo  = "<b>¡Tu usuario ha sido deshabilitado!</b><br><br>\n\n";
			$cuerpo .= "<b>".$usuario['nombre_usuario'].":</b>" .
					" Lamentablemente, y muy probablemente, por algo que tú has" .
					" hecho mal, tu usuario quedó en este momento deshabilitado." .
					" ¡Leru Leru! }:-)" .
					" <br><br>" .
					"Por cualquier consulta o queja: tallerphp5@surforce.com" .
					"<br><br>" .
					"-= SurForce Team =-";
			$origen  = MAIL_ADMIN;
			$resultado = self::enviarMail($usuario['gmail_usuario'],$asunto,$cuerpo,$origen);
		}

		return $resultado;
	}
	public function asignarForoUsuario( $id ){
		$resultado = PersistenciaFachada::asignarForoUsuario($id);
	}
	public function desasignarForoUsuario( $id ){
		$resultado = PersistenciaFachada::desasignarForoUsuario( $id );
	}
	public function altaCuestionario($datosAlta){

		foreach($datosAlta as $hash => $valor){
			$datosAlta[$hash] = trim($valor);
		}
		$cuestionario = new AltaCuestionario();
		$cuestionario->setNombre($datosAlta['nombre']);
		$cuestionario->setDescripcion($datosAlta['descripcion']);
		$cuestionario->setPreguntas($datosAlta['preguntas']);
		$resultado = $cuestionario->realizarAlta();
		return $resultado;
	}

	public function obtenerIdUsuarioPorGmail($gmail){
		return PersistenciaFachada::obtenerIdUsuarioPorGmail(trim($gmail));
	}
	public function guardarFoto($nombre, $archivo){
		$foto = new Foto();
		return $foto->guardarFoto($nombre, $archivo);
	}
	public function crearFotoMiniatura($nombre, $archivo, $ancho, $alto, $tipo=""){
		$foto = new Foto();
		return $foto->crearFotoMiniatura($archivo, $nombre, $ancho, $alto, $tipo="");
	}

	/*
	 * Enviar eMail
	 */
	public function enviarMail($destino, $asunto, $cuerpo, $origen){

		// Mercury no encara este :(
		$encabezados = 	'From: "Taller PHP5" <'.$origen.">\r\n" .

		// Compatible con Mercury :)
		//$encabezados = 	'From: '.$origen."\r\n" .

   			'Reply-To: ' . $origen . "\r\n" .
   			'X-Mailer: PHP/' . phpversion() . "\r\n" .
			'MIME-Version: 1.0' . "\r\n" .
			'Content-Type: text/html;charset=iso-8859-1' . "\r\n" .
			'Content-Transfer-Encoding: 7bit';

		if(mail($destino,$asunto,$cuerpo,$encabezados)){
	    	return true;
	    }else{
	    	return false;
	    }
	}

	public function traerUsuariosHabilitados(){
		return PersistenciaFachada::traerUsuariosHabilitados();
	}
	public function traerCuestionarios(){
		return PersistenciaFachada::traerCuestionarios();
	}

/*--------------------------------*/
	public function traerUsuarioPorUsuario($usuario){
		return PersistenciaFachada::traerUsuarioPorUsuario($usuario);
	}

	public function traerUsuarios( $orden ){
		return PersistenciaFachada::traerUsuarios( $orden );
	}

	/** Retorna un array con hash */
	public function traerUsuarioPorId($id){
		$arr = PersistenciaFachada::traerUsuarioPorId($id);
		foreach( $arr as $a){
			$ret = $a;
		}
		return $ret;
	}
	public function traerValoresEncuesta(){
		$arr = PersistenciaFachada::traerValoresEncuesta();
		foreach( $arr as $a){
			$ret = $a;
		}
		return $ret;
	}
	/** Retorna un objeto único de tipo Usuario */
	public function traerObjetoUsuarioPorId( $id ){
		$arr = PersistenciaFachada::traerUsuarioPorId( $id );
		$unUsuario = new Usuario( $arr['id'], $arr['nombre'], $arr['descripcion'], $arr['ingreso'] );
		return $unUsuario;
	}
	public function getCantidadUsuarios(){
		return PersistenciaFachada::getCantidadUsuarios();
	}
	public function getCantidadUsuariosHabilitados(){
		return PersistenciaFachada::getCantidadUsuariosHabilitados();
	}
	public function getCantidadUsuariosNoHabilitados(){
		return PersistenciaFachada::getCantidadUsuariosNoHabilitados();
	}
}
?>