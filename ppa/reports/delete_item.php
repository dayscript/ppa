<?php
	//Archivo que elimina registros de expresiones
	require("mysql.class.php");
	require("reports.func.php");
	$item_id = '';
	if(isset($_GET['id']) && $_GET['id'] != ''){
		$item_id = $_GET['id'];
	}
	//si no viene un id muestra un error y termina
	if($item_id != ''){
		$delete_sql = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
		$res = $delete_sql->query("DELETE FROM REP_regexp WHERE id_regexp = '".$item_id."'");
		$delete_sql->__Close__();
		echo 'ok'; //valor a retornar al archivo que hace el llamado
	}else{
		echo 'Problemas al borrar';
	}

?>