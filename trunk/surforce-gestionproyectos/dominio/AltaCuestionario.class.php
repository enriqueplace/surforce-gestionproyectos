<?php
require_once("configuracion.php");

class AltaCuestionario{
	private $nombre;
	private $descripcion;
	private $preguntas;

	private $mensaje;
	private $retorno = array(); // [error(bool)][mensaje]

	public function __construct(){}

	/*
	 * Setter / Getter
	 * */
    public function getNombre(){return $this->nombre;}
    public function setNombre($var){$this->nombre = $var;}

    public function getDescripcion(){return $this->descripcion;}
    public function setDescripcion($var){$this->descripcion = $var;}

    public function getPreguntas(){return $this->preguntas;}
    public function setPreguntas($var){$this->preguntas = $var;}


	/*
	 *Realiza el alta de cuestionario
	 * */
	public function realizarAlta(){

		if(!$this->validarDatos()){
			$this->retorno = array("error" => true, "mensaje" =>$this->mensaje);
			return $this->retorno;
		}

		$datosPreguntas = $this->procesarPreguntas();

		if( ! $datosPreguntas ){
			$this->retorno = array("error" => true, "mensaje" =>$this->mensaje);
			return $this->retorno;
		}

		require_once ( PER . DIRECTORY_SEPARATOR . "PersistenciaFachada.class.php" );


		// Cuestionario

		$idCuestionario = PersistenciaFachada::ultimoIdCuestionario() + 1;

		if(!$idCuestionario){
			$this->mensaje = "Error al intentar obtener el id del cuestionario :(";
			$this->retorno = array("error" => true, "mensaje" =>$this->mensaje);
			return $this->retorno;
		}

		$datosCuestionario = array();
		$datosCuestionario['id'] = $idCuestionario;
		$datosCuestionario['nombre'] = $this->nombre;
		$datosCuestionario['descripcion'] = $this->descripcion;

		$resultado = PersistenciaFachada::altaCuestionario($datosCuestionario);

		if(!$resultado){
			$this->mensaje = "Error al persistir el cuestionario :(";
			$this->retorno = array("error" => true, "mensaje" =>$this->mensaje);
			return $this->retorno;
		}


		// Preguntas

		foreach($datosPreguntas as $pregunta){

			$idPregunta = PersistenciaFachada::ultimoIdPreguntaCuestionario() + 1;

			$resultado = PersistenciaFachada::altaPreguntaCuestionario($idPregunta, $idCuestionario, $pregunta['pregunta']);
			if(!$resultado){
				$this->mensaje = "Error al persistir la pregunta (".$pregunta['pregunta'].") :(";
				$this->retorno = array("error" => true, "mensaje" =>$this->mensaje);
				return $this->retorno;
			}


			// Respuestas

			foreach($pregunta['respuestas'] as $respuesta){
				$resultado = PersistenciaFachada::altaRespuestaCuestionario($idPregunta, $respuesta);
				if(!$resultado){
					$this->mensaje = "Error al persistir la respuesta ($respuesta) :(";
					$this->retorno = array("error" => true, "mensaje" =>$this->mensaje);
					return $this->retorno;
				}
			}

		}

		$this->mensaje = "Todos los datos se guardaron correctamente :)";
		$this->retorno = array("error" => false, "mensaje" =>$this->mensaje);
		return $this->retorno;
	}


	/*
	 * Validar datos
	 * */
	private function validarDatos(){

		// Nombre
		if(trim($this->nombre) == ""){
			$this->mensaje = "Sin nombre de usuario";
			return false;
		}

		require_once ( PER . DIRECTORY_SEPARATOR . "PersistenciaFachada.class.php" );
		$resultado = PersistenciaFachada::obtenerIdCuestionarioPorNombre($this->nombre);
		if($resultado){
			$this->mensaje = "Ya existe un cuestionario con el nombre: ".$this->nombre.".";
			return false;
		}

		// Descripcion
		if(trim($this->descripcion) == ""){
			$this->mensaje = "Sin descripción de cuestionario.";
			return false;
		}

		// Preguntas
		if(trim($this->preguntas) == ""){
			$this->mensaje = "Sin preguntas de cuestionario.";
			return false;
		}

		return true;
	}


	private function procesarPreguntas(){

		if(count($this->preguntas) == 0){
			$this->mensaje = "Sin preguntas definidas.";
			return false;
		}

		$p = explode("\n", $this->preguntas);

		foreach($p as $linea){

			$linea = trim($linea);

			// Si es una linea vacia, nos salteamos el bucle y pasamos a la siguiente
			if($linea == ""){
				continue;
			}

			/* **********************************************************
			 *	ATENCION: El orden del los procesos es importante
			 * **********************************************************/

			// Remuevo los espacios en blanco a la derecha de "|"
			while(substr_count($linea, "| ") > 0){
				$linea = str_replace("| ", "|", $linea);
			}

			// Remuevo los espacios a la izquierda de "|"
			while(substr_count($linea, " |") > 0){
				$linea = str_replace(" |", "|", $linea);
			}

			// Remuevo los "|" al principio
			while($linea{0} == "|"){
				$linea = substr($linea, 1);
			}

			// Remuevo los "|" al final
			while($linea{strlen($linea) -1} == "|"){
				$linea = substr($linea, 0, strlen($linea) -1);
			}

			// Remuevo los "|" duplicados
			while(substr_count($linea, "||") > 0){
				$linea = str_replace("||", "|", $linea);
			}

			if(substr_count($linea, "|") < 2){
				$this->mensaje = "Pregunta y/o respuestas incompletas.";
				return false;
			}

			$pregYResp = explode("|", $linea);

			$preguntas['pregunta'] = $pregYResp[0];

			$preguntas['respuestas'] = array();

			for( $i = 1; $i < count($pregYResp); $i++ ){
				$preguntas['respuestas'][] = $pregYResp[$i];
			}

			$retorno[] = $preguntas;

		}

		return $retorno;
	}

}
?>
