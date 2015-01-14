<?php 
	
	$id_channel = $_GET['id_channel'];
	$check = $_GET['save_type'];
	$comment =  $_GET['comment'];


	if(strpos( $_SERVER['HTTP_USER_AGENT'], "DayDev" ) !== false){
		define('DEVELOPER', true);
		$comment = "pruebas__".$comment;
	}else{
		define('DEVELOPER', false);
	}

	$mysqli = new mysqli('localhost', 'ppa', 'kfc3*9mn', 'ppa');
 	if ($mysqli->connect_error) {
    die('Error de Conexión (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
	}

	$sql = "INSERT INTO CHK_test_programming ( id_channel , create_date , check_test , id_user, comment ) 
			VALUES ('".$id_channel."', NOW(), '".$check."', 1, '".utf8_encode($comment)."')";

	$result_sql = $mysqli->query( $sql );

	echo json_encode( array(
		'channel' => $id_channel,
		'result' => $result_sql
	) );