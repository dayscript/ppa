<?php
	
	if(strpos( $_SERVER['HTTP_USER_AGENT'], "DayDevALL" ) !== false){
 		define(FDEBUG, true);
 	}else{
 		define(FDEBUG, false);
 	}

	//hace la consulta del reporte
	$request = $_GET;
	$result = array();
	
	$mysqli = new mysqli('localhost', 'ppa', 'kfc3*9mn', 'ppa');
 	if ($mysqli->connect_error) {
    die('Error de Conexión (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
	}

	/*$sql = "SELECT * FROM CHK_test_programming as tp
			INNER JOIN channel as c ON tp.id_channel = c.id 
			WHERE Date_format( tp.create_date, '%Y-%m-%d' ) = '".$request['date']."'";*/


	$sql = "SELECT GROUP_CONCAT( cc.number SEPARATOR ', ') as number2, cc.channel, cc.number, c.name 
			FROM client_channel as cc
			INNER JOIN channel as c ON cc.channel = c.id
			WHERE cc.client = ".$request['client'].
			" AND  cc.number > 0 
			GROUP BY c.id".
			" ORDER BY cc.number ASC";			

	$result_sql = $mysqli->query( $sql );
	$channels = array();
	while( $row = $result_sql->fetch_array( MYSQLI_ASSOC ) ){
		if( !isset( $result[ $row['number'] ] ) ){
			$result[ $row['number'] ]['info'] = array( 
				'number2' => $row['number2'],
				'channel' => $row['channel'],
				'number' => $row['number'],
				'name' => $row['name']
			);
			$result[ $row['number'] ]['test'] = array();
			$channels[] = $row['number'];
		}
	}	

	$sql2 = "SELECT * FROM CHK_test_programming 
			 WHERE Date_format( create_date, '%Y-%m-%d' ) = '".$request['date']."' AND id_channel IN( ".implode(',',$channels)." )";
	
	$result_sql2 = $mysqli->query( $sql2 );
	while( $row2 = $result_sql2->fetch_array( MYSQLI_ASSOC ) ){
		$result[ $row2['id_channel'] ]['test'][] = array(
			'check_test' => $row2['check_test'],
			'comment' => $row2['comment'],
			'create_date' => $row2['create_date']
		);
	}
	echo json_encode( $result );
	
?>