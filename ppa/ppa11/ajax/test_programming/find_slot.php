<?php 
 	

 	if(strpos( $_SERVER['HTTP_USER_AGENT'], "DayDevALL" ) !== false){
 		define(FDEBUG, true);
 	}else{
 		define(FDEBUG, false);
 	}
 	
 	$id_channel = $_GET['id_channel'];

 	$mysqli = new mysqli('localhost', 'ppa', 'kfc3*9mn', 'ppa');
 	if ($mysqli->connect_error) {
    die('Error de Conexion (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
	}

/*
	$sql1 = "SELECT * ,
	NOW( ) as hora_actual , 
	CONCAT(`date`,' ', `time`) as hora_inicio,
	DATE_ADD( CONCAT(`date`,' ', `time`) ,INTERVAL duration MINUTE) as hora_fin
	FROM slot
	WHERE DATE_ADD( CONCAT(`date`,' ', `time`) ,INTERVAL duration MINUTE) >= NOW()
	AND CONCAT(`date`,' ', `time`) < NOW()
	AND channel = ".$id_channel;
*/

	$sql = "
		SELECT *, CURTIME() AS hora_actual, `time` AS hora_inicio, SUBSTR( DATE_ADD(CONCAT(`date`,' ', `time`), INTERVAL duration MINUTE), 12 ) AS hora_fin, slot_chapter.chapter
		FROM slot
		LEFT JOIN slot_chapter ON ( slot_chapter.slot = slot.id )
		WHERE `date` = CURDATE()
		AND DATE_ADD(CONCAT(`date`,' ', `time`), INTERVAL duration MINUTE) >= NOW()
		AND CONCAT(`date`,' ', `time`) < NOW() AND channel = " . $id_channel . "
		LIMIT 1";
	$result_sql = $mysqli->query( $sql );

	$result = array(
		'channel' => $id_channel,
		'date' =>  "",
		'duration' =>  "",
		'hora_actual' =>  "",
		'hora_fin' =>  "No disponible",
		'hora_inicio' =>  "No disponible",
		'id' =>  "",
		'chapter' =>  "",
		'new_episode' =>  "",
		'time' =>  "",
		'title' =>  "No disponible",
		'exist' => 0,
		'images' => array( )
	);

	$row = $result_sql->fetch_array( MYSQLI_ASSOC );
	if ( $row ){
		$result['channel'] = $row['channel'];
		$result['date'] = $row['date'];
		$result['duration'] = $row['duration'];
		$result['hora_actual'] = $row['hora_actual'];
		$result['hora_fin'] = $row['hora_fin'];
		$result['hora_inicio'] = $row['hora_inicio'];
		$result['id'] = $row['id'];
		$result['chapter'] = $row['chapter'];
		$result['new_episode'] = $row['new_episode'];
		$result['time'] = $row['time'];
		$result['title'] = utf8_encode( $row['title'] );
		$result['exist'] = 1;

		// looking for images

		$sql = "
			SELECT *
			FROM chapter_images
			WHERE id_chapter IN (
				SELECT chapter
				FROM slot_chapter
				WHERE slot = " . $result['id'] . "
			)
			AND id_chapter_image_type = 2
			LIMIT 10";
		$result_sql = $mysqli->query( $sql );
		$images = array( );
		while( ( $row = $result_sql->fetch_array( MYSQLI_ASSOC ) ) != null )
			$images[] = $row;
		$result['images'] = $images;
	}
	$mysqli->close();

	echo json_encode( $result );

	