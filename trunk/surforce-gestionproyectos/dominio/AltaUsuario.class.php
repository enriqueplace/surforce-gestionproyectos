<?php
require_once("configuracion.php");

class AltaUsuario{
	private $nombre;
	private $apellido;
	private $pais;
	private $ciudad;
	private $gmail;
	private $skype;
	private $profesion;
	private $empresa;
	private $expectativas;
	private $foto;
	private $clave;
	private $programacion;
	private $objetos;
	private $sql;
	private $uml;
	private $php;
	private $html;
	private $css;
	private $js;
	private $lenguajes;
	private $patrones;
	private $frameworks;
	private $puesto;
	private $enteraron;

	private $mensaje;
	private $retorno = array(); // [error][mensaje]

	public function __construct(){}

	/*
	 * Setter / Getter
	 */
    public function getNombre(){return $this->nombre;}
    public function setNombre($var){$this->nombre = $var;}

    public function getApellido(){return $this->apellido;}
    public function setApellido($var){$this->apellido = $var;}

    public function getPais(){return $this->pais;}
    public function setPais($var){$this->pais = $var;}

    public function getCiudad(){return $this->ciudad;}
    public function setCiudad($var){$this->ciudad = $var;}

    public function getGmail(){return $this->gmail;}
    public function setGmail($var){$this->gmail = $var;}

    public function getSkype(){return $this->skype;}
    public function setSkype($var){$this->skype = $var;}

    public function getProfesion(){return $this->profesion;}
    public function setProfesion($var){$this->profesion = $var;}

    public function getEmpresa(){return $this->empresa;}
    public function setEmpresa($var){$this->empresa = $var;}

    public function getExpectativas(){return $this->expectativas;}
    public function setExpectativas($var){$this->expectativas = $var;}

    public function getFoto(){return $this->foto;}
    public function setFoto($var){$this->foto = $var;}

    public function getUsuario(){return $this->usuario;}
    public function setUsuario($var){$this->usuario = $var;}

    public function getClave(){return $this->clave;}
    public function setClave($var){$this->clave = $var;}

    public function getProgramacion(){return $this->programacion;}
    public function setProgramacion($var){$this->programacion = $var;}

    public function getObjetos(){return $this->objetos;}
    public function setObjetos($var){$this->objetos = $var;}

    public function getSql(){return $this->sql;}
    public function setSql($var){$this->sql = $var;}

    public function getUml(){return $this->uml;}
    public function setUml($var){$this->uml = $var;}

    public function getPhp(){return $this->php;}
    public function setPhp($var){$this->php = $var;}

    public function getHtml(){return $this->html;}
    public function setHtml($var){$this->html = $var;}

    public function getCss(){return $this->css;}
    public function setCss($var){$this->css = $var;}

    public function getJs(){return $this->js;}
    public function setJs($var){$this->js = $var;}

    public function getLenguajes(){return $this->lenguajes;}
    public function setLenguajes($var){$this->lenguajes = $var;}

    public function getPatrones(){return $this->patrones;}
    public function setPatrones($var){$this->patrones = $var;}

    public function getFrameworks(){return $this->frameworks;}
    public function setFrameworks($var){$this->frameworks = $var;}

    public function getPuesto(){return $this->puesto;}
    public function setPuesto($var){$this->puesto = $var;}

    public function getEnteraron(){return $this->enteraron;}
    public function setEnteraron($var){$this->enteraron = $var;}

	/*
	 *Realiza el alta de usuario
	 */
	public function realizarAlta(){

		if(!$this->validarDatos()){
			$this->retorno = array("error" => true, "mensaje" =>$this->mensaje);
			return $this->retorno;
		}

		require_once (PER."/PersistenciaFachada.class.php");
		$datosUsuario = array();
		$datosUsuario['nombre'] = $this->nombre;
		$datosUsuario['apellido'] = $this->apellido;
		$datosUsuario['pais'] = $this->pais;
		$datosUsuario['ciudad'] = $this->ciudad;
		$datosUsuario['gmail'] = strtolower($this->gmail);
		$datosUsuario['skype'] = $this->skype;
		$datosUsuario['profesion'] = $this->profesion;
		$datosUsuario['empresa'] = $this->empresa;
		$datosUsuario['expectativas'] = $this->expectativas;
		$datosUsuario['foto'] = $this->foto;
		$datosUsuario['usuario'] = strtolower($this->usuario);
		$datosUsuario['clave'] = strtoupper(md5($this->clave));

		require_once( FWK . DIRECTORY_SEPARATOR . "Ip.class.php" );
		$datosUsuario['ip'] = Ip::obtenerIp();
		$datosUsuario['ipReal'] = Ip::obtenerIpReal();

		$datosUsuario['clave'] = strtoupper(md5($this->clave));
		$resultado = PersistenciaFachada::registrarUsuario($datosUsuario);
		if(!$resultado){
			$this->mensaje = "Error al persistir el usuario :(";
			$this->retorno = array("error" => true, "mensaje" =>$this->mensaje);
			return $this->retorno;
		}

		$idUsuario = PersistenciaFachada::obtenerIdUsuarioPorGmail(strtolower($this->gmail));
		if(!$idUsuario){
			$this->mensaje = "Error al intentar obtener el id del usuario :(";
			$this->retorno = array("error" => true, "mensaje" =>$this->mensaje);
			return $this->retorno;
		}

		$datosEncuesta = array();
		$datosEncuesta['idUsuario'] = $idUsuario;
		$datosEncuesta['programacion'] = $this->programacion;
		$datosEncuesta['objetos'] = $this->objetos;
		$datosEncuesta['sql'] = $this->sql;
		$datosEncuesta['uml'] = $this->uml;
		$datosEncuesta['php'] = strtolower($this->php);
		$datosEncuesta['html'] = $this->html;
		$datosEncuesta['css'] = $this->css;
		$datosEncuesta['js'] = $this->js;
		$datosEncuesta['lenguajes'] = $this->lenguajes;
		$datosEncuesta['patrones'] = $this->patrones;
		$datosEncuesta['frameworks'] = $this->frameworks;
		$datosEncuesta['puesto'] = $this->puesto;
		$datosEncuesta['enteraron'] = $this->enteraron;
		$resultado = PersistenciaFachada::registrarEncuesta($datosEncuesta);
		if(!$resultado){
			$this->mensaje = "Error al persistir la encuesta :(";
			$this->retorno = array("error" => true, "mensaje" =>$this->mensaje);
			return $this->retorno;
		}

		// Email - Administrador
		$asunto  = "Taller PHP5 On Line - Alta de usuario";
		$cuerpo  = "<b>Aviso de alta de usuario.</b><br><br>\n\n";
		$cuerpo .= "Nombre: ".$this->nombre."<br>\n";
		$cuerpo .= "Apellido: ".$this->apellido."<br>\n";
		$cuerpo .= "Pa&iacute;s: ".$this->pais."<br>\n";
		$cuerpo .= "Ciudad: ".$this->ciudad."<br>\n";
		$cuerpo .= "Gmail: ".$this->gmail."<br>\n";
		$origen  = MAIL_ADMIN;
		$resultado = DominioFachada::enviarMail(MAIL_ADMIN,$asunto,$cuerpo,$origen);
		if(!$resultado){
			$this->mensaje = "Ocurrió un error al enviar el email de notificación al administrador :(";
			$this->retorno = array("error" => true, "mensaje" =>$this->mensaje);
			return $this->retorno;
		}

		// Email - Usuario
		$asunto  = "Taller PHP5 On Line";
		$cuerpo  = "<b>Correo de confirmación.</b><br><br>\n\n";
		$cuerpo .= "Hola ".$this->nombre." ".$this->apellido.", este mail es para confirmar que sus datos han sido guardados correctamente, el administrador se contactará en breve con usted.<br>\n";
		$origen  = MAIL_ADMIN;
		$resultado = DominioFachada::enviarMail($this->gmail,$asunto,$cuerpo,$origen);
		if(!$resultado){
			$this->mensaje = "Ocurrió un error al enviar el email de confirmación al usuario :(";
			$this->retorno = array("error" => true, "mensaje" =>$this->mensaje);
			return $this->retorno;
		}

		$this->mensaje = "Todos sus datos han sido correctamente guardados. Se dará un aviso al administrador para que autorice su ingreso. Está demás decir que sus datos son confidenciales y no se venderán ni usarán para enviar publicidad no deseada ;-)";
		$this->retorno = array("error" => false, "mensaje" =>$this->mensaje);
		return $this->retorno;
	}

	/*
	 * Validar datos
	 */
	private function validarDatos(){

		// Nombre
		if(trim($this->nombre) == ""){
			$this->mensaje = "Sin nombre de usuario";
			return false;
		}

		// Apellido
		if(trim($this->apellido) == ""){
			$this->mensaje = "Sin apellido de usuario";
			return false;
		}

		// Gmail
		if(!$this->validarEmail($this->gmail)){
			$this->mensaje = "Dirección de gmail no válida.";
			return false;
		}
		$segmentos = spliti ("@", $this->gmail);
		if(strtolower(trim($segmentos[1])) != "gmail.com"){
			$this->mensaje = "La dirección de correo no es de gmail.";
			return false;
		}

		// Usuario
		require_once (PER."/PersistenciaFachada.class.php");
		$resultado = PersistenciaFachada::obtenerIdUsuarioPorGmail(strtolower($this->gmail));
		if($resultado){
			$this->mensaje = "Usuario ya registrado.";
			return false;
		}

	return true;
	}




	/*
	 *  Verifica si una direccion de e-mail es correcta
	 */
	private function validarEmail($email){
		$mail_correcto = 0;
		if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){
		   if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) {
			  if (substr_count($email,".")>= 1){
				 $term_dom = substr(strrchr ($email, '.'),1);
				 if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){
					$antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
					$caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
					if ($caracter_ult != "@" && $caracter_ult != "."){
					   $mail_correcto = 1;
					}
				 }
			  }
		   }
		}
		if ($mail_correcto)
		   return TRUE;
		else
		   return FALSE;
	}
}
?>
