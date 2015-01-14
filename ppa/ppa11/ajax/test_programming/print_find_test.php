<?php

	if(strpos( $_SERVER['HTTP_USER_AGENT'], "DayDevALL" ) !== false){
 		define(FDEBUG, true);
 	}else{
 		define(FDEBUG, false);
 	}

	//hace la consulta del reporte
	$request = $_GET;
	$result = array();

	if(  !( isset($request['client']) && isset($request['date']) )  ){
		return;
		exit();
	}



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
	$channels_ids = $channels = array();
	while( $row = $result_sql->fetch_array( MYSQLI_ASSOC ) ){
		$channels_ids[] = $row['channel'];
		$channels[] = $row;
	}


	$sql2 = "SELECT * FROM CHK_test_programming
			 WHERE Date_format( create_date, '%Y-%m-%d' ) = '".$request['date']."' AND id_channel IN( ".implode(',',$channels_ids)." )";

	$result_sql2 = $mysqli->query( $sql2 );

	$events_array = array();
	while( $row2 = $result_sql2->fetch_array( MYSQLI_ASSOC ) ){

		$events_array[ $row2['id_channel'] ][] = $row2;
	}

	$events_old = array();
	foreach ($channels_ids  as $key => $value) {
		if(  !isset( $events_array[$value] ) ){
			$events_old[] = $value;
		}
	}
?>
<div class="test_programming">
<table width="100%" class='reporting'>
	<?php foreach ($channels as $value): ?>
	<tr>
		<td><?php echo $value['number2'] ?></td>
		<td><?php echo $value['name'] ?></td>
		<td>
			<?php if( isset( $events_array[ $value['channel'] ] ) ): ?>
				<?php foreach ( $events_array[$value['channel']] as $key2 => $value2): ?>
					<div class='item_test'>
						<div class='check_title' style='width:80px; float:left;'>
						<?php if( $value2['check_test'] == 0 ): ?>
							<span class='err'><?php echo 'Incorrecto' ?></span>
						<?php elseif( $value2['check_test'] == 1 ): ?>
							<span class='ok'><?php echo 'Correcto' ?></span>
						<?php elseif( $value2['check_test'] == 2 ): ?>
							<span class='correct'><?php echo 'Corregido' ?></span>
						<?php endif; ?>
						</div>
						<div class='check_date'>
						<?php echo '('.$value2['create_date'].')' ?>
						</div>
						<?php if( $value2['comment'] ): ?>
							<?php echo "<br/>&nbsp;&nbsp;&nbsp;&nbsp;<em>".utf8_decode( $value2['comment'] )."</em>" ?>
						<?php endif; ?>
					</div><br />
				<?php endforeach; ?>
			<?php else: ?>
				<?php
					$sql3 = "SELECT Date_format( MAX(create_date), '%Y-%m-%d' ) as fecha
							FROM CHK_test_programming
							WHERE id_channel = ".$value['channel'].
							" AND Date_format( create_date, '%Y-%m-%d' ) < '".$request['date']."'";
					$result_sql3 = $mysqli->query( $sql3 );
					$row3 = $result_sql3->fetch_array( MYSQLI_ASSOC );
					if( $row3['fecha'] != '' ){
						$sql4 = "SELECT *
								 FROM CHK_test_programming
			 					 WHERE Date_format( create_date, '%Y-%m-%d' ) = '".$row3['fecha']."'
			 					 	   AND id_channel = ".$value['channel'];
			 			$result_sql4 = $mysqli->query( $sql4 );
			 			while( $row4 = $result_sql4->fetch_array( MYSQLI_ASSOC ) ){
			 	?>
							<div class='item_test_old'>
								<div class='check_title' style='width:80px; float:left;'>
								<?php if( $row4['check_test'] == 0 ): ?>
									<span class='err'><?php echo 'Incorrecto' ?></span>
								<?php elseif( $row4['check_test'] == 1 ): ?>
									<span class='ok'><?php echo 'Correcto' ?></span>
								<?php elseif( $row4['check_test'] == 2 ): ?>
									<span class='correct'><?php echo 'Corregido' ?></span>
								<?php endif; ?>
								</div>
								<div class='check_date'>
									<?php echo '('.$row4['create_date'].')' ?>
								</div>
								<?php if( $row4['comment'] ): ?>
									<?php echo "<br/>&nbsp;&nbsp;&nbsp;&nbsp;<em>".utf8_decode( $row4['comment'] )."</em>" ?>
								<?php endif; ?>
							</div><br />
				<?php	}
					}else{
						echo "<div class='no_data'>No hay datos</div>";
					}
				?>
			<?php endif; ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
</div>
