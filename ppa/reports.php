<?php
error_reporting(E_ERROR);
require("header.php");

if(isset($_GET['action'])){
	$action = $_GET['action']; // action contiene el parámetro para cargar un archivo específico
	if($action == 'futbol_list'){
		$include_file = "reports/report_futbol_list.php";
	}elseif( $action == 'test_programing' ){
		$include_file = "reports/report_test_programing.php";
	}
}else{ // si no viene un action definido se carga un archivo por defecto
	$include_file = "reports/report_futbol_list.php";
}

require($include_file);

require("footer.php");
?>