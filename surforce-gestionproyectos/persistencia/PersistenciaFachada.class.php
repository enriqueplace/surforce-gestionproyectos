<?php
/*
 * Aquí se resolverá todo el trabajo de la Persistencia (guardar y recuperar datos)
 * y desde fuera del paquete solo tratará con la fachada.
 *
 * PersistenciaFachada::traerUsuarios();
 *
 * Nota: PHP5 para diferenciar los contexto usar :: en sustitución a ->, pero
 * el sentido es el mismo.
 * */

require_once 'configuracion.php';
include( FWK . DIRECTORY_SEPARATOR . "BaseDeDatos.class.php" );

abstract class PersistenciaFachada {

	/**
	 * Fachada para la ejecución contra la base de datos, retorna integer
	 */
	private function ejecutarSentenciaSQL2FilasAfectadas( $sql, $par="" ){
		$miBD = new BaseDeDatos( HOME_APLICA . DIRECTORY_SEPARATOR . "gestionproyectos.bd.txt");
		$miBD->conectar();
		$miBD->ejecutarSQL($sql, $par);
		$resultado = $miBD->filasAfectadas();
		$miBD->desconectar();
		return $resultado;
	}
	/**
	 * Fachada para la ejecución contra la base de datos, retorna Array
	 */
	private function ejecutarSentenciaSQL2Registros( $sql, $par="" ){
		$miBD = new BaseDeDatos(HOME_APLICA . DIRECTORY_SEPARATOR . "gestionproyectos.bd.txt");
		$miBD->conectar();
		$miBD->ejecutarSQL($sql, $par);
		$resultado = $miBD->traerTodo();
		$miBD->desconectar();
		return $resultado;
	}
	private function ejecutarSentenciaSQL2Tupla( $sql, $par="" ){
		$miBD = new BaseDeDatos(HOME_APLICA . DIRECTORY_SEPARATOR . "gestionproyectos.bd.txt");
		$miBD->conectar();
		$miBD->ejecutarSQL($sql, $par);
		$resultado = $miBD->traerTodo();
		$miBD->desconectar();
		return $resultado[0];
	}

	public function habilitarUsuario( $id ){
		$sql = 	" UPDATE `usuarios` " .
				" SET `habilitado` = '1', `fecha_aceptado_usuario` = NOW() " .
				" WHERE `usuarios`.`id_usuario` = '". self::quote( $id ) . "' LIMIT 1; ";
		return self::ejecutarSentenciaSQL2FilasAfectadas( $sql );
	}
	public function deshabilitarUsuario($id){
		$sql = 	" UPDATE `usuarios` " .
				" SET `habilitado` = '0' " .
				" WHERE `usuarios`.`id_usuario` = '" . self::quote($id) . "' LIMIT 1;";
		return self::ejecutarSentenciaSQL2FilasAfectadas( $sql );
	}
	public function asignarForoUsuario( $id ){
		$par = array( "PAR1" => $id );
		$sql = 	" UPDATE usuarios SET foro_asignado = '1' WHERE id_usuario = 'PAR1' LIMIT 1; ";
		return self::ejecutarSentenciaSQL2FilasAfectadas( $sql, $par );
	}
	public function desasignarForoUsuario( $id ){
		$par = array( "PAR1" => $id );
		$sql = 	" UPDATE usuarios SET foro_asignado = '0' WHERE id_usuario = 'PAR1' LIMIT 1; ";
		return self::ejecutarSentenciaSQL2FilasAfectadas( $sql, $par );
	}
	public function traerUsuarioPorUsuario( $usuario ){
		$sql = " SELECT * FROM usuarios WHERE usuario_usuario = '" . self::quote($usuario) . "';";
		return self::ejecutarSentenciaSQL2Registros( $sql );
	}
	public function altaRespuestaCuestionario( $idPregunta, $respuesta ){
		try{

			$respuesta = self::quote($respuesta);

			$sql = 'INSERT INTO `cuest_respuestas` (' .
					' `id_respuesta`,' .
					' `id_pregunta`,' .
					' `respuesta`' .
					') VALUES (' .
					' NULL,' .
					' \'' . self::quote( $idPregunta ) . '\',' .
					' \'' . self::quote( $respuesta ) . '\'' .
					' );';

			return self::ejecutarSentenciaSQL2FilasAfectadas( $sql );
		} catch( Exception $e ){
			echo $e;
		}
	}
	public function ultimoIdPreguntaCuestionario(){
		return self::ejecutarSentenciaSQL2Registros( "SELECT MAX( id_pregunta ) AS 'id' FROM cuest_preguntas" );
	}
	public function altaPreguntaCuestionario( $idPregunta, $idCuestionario, $pregunta ){
		try{
			$sql = 'INSERT INTO `cuest_preguntas` (' .
					' `id_pregunta`,' .
					' `id_cuestionario`,' .
					' `pregunta`' .
					') VALUES (' .
					' \''. self::quote( $idPregunta ).'\',' .
					' \''. self::quote( $idCuestionario ).'\',' .
					' \''. self::quote( $pregunta ).'\'' .
					' );';
			return self::ejecutarSentenciaSQL2FilasAfectadas( $sql );
		} catch( Exception $e ){
			echo $e;
		}
	}
	public function ultimoIdCuestionario(){
		return self::ejecutarSentenciaSQL2Registros( "SELECT max( id_cuestionario ) AS 'id' FROM cuestionarios" );
	}

	public function obtenerIdCuestionarioPorNombre($nombre){
		$sql = "SELECT * FROM cuestionarios WHERE nombre_cuestionario = '". self::quote( $nombre )."';";
		return self::ejecutarSentenciaSQL2Registros( $sql );
	}
	public function altaCuestionario($datos){
		try{
			$sql = 'INSERT INTO `cuestionarios` (' .
					' `id_cuestionario`,' .
					' `nombre_cuestionario`,' .
					' `descripcion_cuestionario`,' .
					' `creacion_cuestionario`' .
					') VALUES (' .
					' \'' . self::quote( $datos[id] ) . '\',' .
					' \'' . self::quote( $datos[nombre] ) . '\',' .
					' \'' . self::quote( $datos[descripcion] ) . '\',' .
					' NOW( )' .
					' );';
			return self::ejecutarSentenciaSQL2FilasAfectadas( $sql );
		} catch (Exception $e) {
			echo $e;
		}
	}
	public function registrarUsuario($datos){

		$sql = 'INSERT INTO `usuarios` (' .
				' `id_usuario`,' .
				' `nombre_usuario`,' .
				' `apellido_usuario`,' .
				' `pais_usuario`,' .
				' `ciudad_usuario`,' .
				' `gmail_usuario`,' .
				' `skype_usuario`,' .
				' `profesion_usuario`,' .
				' `empresa_usuario`,' .
				' `expectativas_usuario`,' .
				' `foto_usuario`,' .
				' `usuario_usuario`,' .
				' `clave_usuario`,' .
				' `fecha_ingreso_usuario`,' .
				' `ip_usuario`,' .
				' `ip_real_usuario`,' .
				' `habilitado`' .
				') VALUES (' .
				' NULL,' .
				' \''. self::quote( $datos[nombre] ).'\',' .
				' \''. self::quote( $datos[apellido] ).'\',' .
				' \''. self::quote( $datos[pais] ).'\',' .
				' \''. self::quote( $datos[ciudad] ).'\',' .
				' \''. self::quote( $datos[gmail] ).'\',' .
				' \''. self::quote( $datos[skype] ).'\',' .
				' \''. self::quote( $datos[profesion] ).'\',' .
				' \''. self::quote( $datos[empresa] ).'\',' .
				' \''. self::quote( $datos[expectativas] ).'\',' .
				' \''. self::quote( $datos[foto] ).'\',' .
				' \''. self::quote( $datos[usuario] ).'\',' .
				' \''. self::quote( $datos[clave] ).'\',' .
				' CURRENT_TIMESTAMP,' .
				' \''. self::quote( $datos[ip] ).'\',' .
				' \''. self::quote( $datos[ipReal] ).'\',' .
				' 0' .
				' );';
		return self::ejecutarSentenciaSQL2FilasAfectadas( $sql );
	}
	public function registrarEncuesta($datos){
		$sql = 'INSERT INTO `encuesta_conocimientos` (' .
				' `id_enc_con`,' .
				' `id_usuario`,' .
				' `programacion`,' .
				' `objetos`,' .
				' `sql`,' .
				' `uml`,' .
				' `php`,' .
				' `html`,' .
				' `css`,' .
				' `js`,' .
				' `lenguajes`,' .
				' `patrones`,' .
				' `frameworks`,' .
				' `puesto`,' .
				' `enteraron_por`' .
				') VALUES (' .
				' NULL,' .
				' \''. self::quote( $datos[idUsuario] ).'\',' .
				' \''. self::quote( $datos[programacion] ).'\',' .
				' \''. self::quote( $datos[objetos] ).'\',' .
				' \''. self::quote( $datos[sql] ).'\',' .
				' \''. self::quote( $datos[uml] ).'\',' .
				' \''. self::quote( $datos[php] ).'\',' .
				' \''. self::quote( $datos[html] ).'\',' .
				' \''. self::quote( $datos[css] ).'\',' .
				' \''. self::quote( $datos[js] ).'\',' .
				' \''. self::quote( $datos[lenguajes] ).'\',' .
				' \''. self::quote( $datos[patrones] ).'\',' .
				' \''. self::quote( $datos[frameworks] ).'\',' .
				' \''. self::quote( $datos[puesto] ).'\',' .
				' \''. self::quote( $datos[enteraron] ).'\'' .
				');';
		return self::ejecutarSentenciaSQL2FilasAfectadas( $sql );
	}
	public function obtenerIdUsuarioPorGmail($gmail){
		$sql = "SELECT id_usuario FROM usuarios WHERE gmail_usuario = '". self::quote( $gmail ) ."'";
		return self::ejecutarSentenciaSQL2Registros( $sql );

	}
	/**
	 * FIXME: debe ir contra una vista que construya toda la información del usuario,
	 * ocultando los datos más sencibles (que el sistema no necesita manejar directamente)
	 */
	public function traerUsuarios( $orden ){
		/*
		 * Esta forma de procesar el orden de las columnas protege contra "sql injection" al
		 * evitar pasar directamente lo que recibe por parámetros a la sentencia sql base
		 */
		if( !empty( $orden )){
			switch ($orden) {
				case "puesto":
					$orden = " ec.puesto ";
					break;
				case "pais":
					$orden = " u.pais_usuario ";
					break;
				case "foro":
					$orden = " u.foro_asignado ";
					break;
				case "nombre":
					$orden = " u.nombre_usuario, u.apellido_usuario ";
					break;
				case "usuario":
					$orden = " u.usuario_usuario";
					break;
				case "conocimientos":
					$orden = " conocimientos";
					break;
				default:
					$orden = " u.fecha_ingreso_usuario ";
					break;
			}
		}else{
			$orden = " u.fecha_ingreso_usuario ";
		}
		$par = array( "PAR1" => $orden );

		$sql = 	" select u.*, ec.*, ep.*, ec.programacion + ec.objetos + ec.sql + ec.uml + ec.php + ec.html + ec.css + ec.js AS conocimientos " .
				" from usuarios as u " .
				" INNER JOIN encuesta_conocimientos AS ec ON ec.id_usuario = u.id_usuario " .
				" INNER JOIN encuesta_puesto AS ep ON ep.id_puesto = ec.puesto " .
				" order by PAR1 DESC; ";

		return self::ejecutarSentenciaSQL2Registros( $sql, $par );
	}
	public function traerValoresEncuesta(){
		return self::ejecutarSentenciaSQL2Registros( "select * from encuesta_valores" );
	}
	public function traerUsuarioPorId( $id ){
		$par = array( "PAR1" => $id);

		$sql = 	" select u.*, ec.*, ep.*, ec.programacion + ec.objetos + ec.sql + ec.uml + ec.php + ec.html + ec.css + ec.js AS conocimientos " .
				" from usuarios as u " .
				" INNER JOIN encuesta_conocimientos AS ec ON ec.id_usuario = u.id_usuario " .
				" INNER JOIN encuesta_puesto AS ep ON ep.id_puesto = ec.puesto " .
				" WHERE u.id_usuario = PAR1; ";

		return self::ejecutarSentenciaSQL2Registros( $sql, $par );
	}
	public function traerUsuariosHabilitados(){
		$sql = "SELECT * FROM usuarios AS u WHERE u.habilitado ORDER BY u.fecha_aceptado_usuario DESC;";
		return self::ejecutarSentenciaSQL2Registros( $sql );
	}
	public function traerCuestionarios(){
		return self::ejecutarSentenciaSQL2Registros( "SELECT * FROM cuestionarios;" );
	}
	public function getCantidadUsuarios(){
		return self::ejecutarSentenciaSQL2Tupla( "SELECT COUNT(*) as cantidad FROM usuarios;" );
	}
	public function getCantidadUsuariosHabilitados(){
		$sql = "SELECT COUNT(*) as cantidad FROM usuarios WHERE habilitado;";
		return self::ejecutarSentenciaSQL2Tupla( $sql );
	}
	public function getCantidadUsuariosNoHabilitados(){
		$sql = "SELECT COUNT(*) as cantidad FROM usuarios WHERE NOT habilitado;";
		return self::ejecutarSentenciaSQL2Tupla( $sql );
	}
}
?>