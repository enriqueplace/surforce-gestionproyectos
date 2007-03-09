<?php
mysql_connect("localhost","root","root");


function quote($var){
    if (get_magic_quotes_gpc()) {
        $var = stripslashes($var);
    }
    $var = mysql_real_escape_string($var);
    return $var;
}

// $nombre = "Devil's House";

//$nombre = quote( $_GET['id'] );
$par = array(
	//"par1" => $_GET['id']
	"PAR1" => "1"
);
$sql = "SELECT * FROM usuarios WHERE id = PAR1";

echo controlar( $sql, $par );

function controlar($sql, $par){
	foreach( $par as $key => $value ){

		$sql = ereg_replace( $key,$value, $sql);
	}
	return $sql;
}

?>

