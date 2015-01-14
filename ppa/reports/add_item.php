<?php
	//Archivo que agrega los registros de expresiones
	require("mysql.class.php");
	require("reports.func.php");
	//si no viene el registro a insertar muestra un error y termina allow y enable tienen valores predefinidos si estos no se ingresan
	if(isset($_GET['val']) && $_GET['val'] != ''){
		$expresion = $_GET['val'];
		$allow = ($_GET['allow'] != '') ? $_GET['allow'] : '0';
	}
	if($expresion != '' && $allow != ''){
		//inserta el registro, se abre una conexión se realiza el sql y se libera la conexión con mysql
		$add_sql = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
		$res = $add_sql->query("INSERT INTO REP_regexp (expresion, allow) VALUES('".mysql_real_escape_string($expresion)."', '".$allow."')");
		$id_insert = $add_sql->msql_id();
		$add_sql->__Close__();
		echo $id_insert; //valor retornado al archivo que hace el llamado, corresponde al id del nuevo registro
	}else{
		echo 'Problemas al agregar';
	}
?>
