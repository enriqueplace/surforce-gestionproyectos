<?php
require_once( "configuracion.php" );
require_once( FWK . "/Usuario.class.php" );

class UsuarioTaller extends Usuario{

	private $gmail;
	private $skype;
	private $profesion;
	private $empresa;
	private $expectativas;
	private $foto;

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

    public function __construct(){}

}
?>