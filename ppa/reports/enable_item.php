<?php
	require("mysql.class.php");
	require("reports.func.php");

	//Archivo que elimina registros de expresiones
	if(isset($_GET['id']) && $_GET['id'] != ''){

		$id_item = $_GET['id'];
		$enable_item = check_enable_status($id_item); //verifica el estado actual, y establece el estado contrario
		//actualiza el estado del registro
		$enable_sql = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
		$res = $enable_sql->query("UPDATE REP_regexp SET enable='".$enable_item."' WHERE id_regexp='".$id_item."'");
		$enable_sql->__Close__();

		echo 'ok'; //respuesta al archivo que hace el llamado

	}else{
		echo 'Problemas al activar/desactivar';
	}
?>