<?php

	$date = ( isset( $_GET['date'] ) && $_GET['date'] ? strtotime( $_GET['date'] ) : time( ) );
	$init = ( isset( $_GET['init'] ) && (int)$_GET['init'] > 0 ? (int)$_GET['init'] : 0 );
	$tFound = ( isset( $_GET['found'] ) && (int)$_GET['found'] > 0 ? (int)$_GET['found'] : 0 );
	$fAssigned = ( isset( $_GET['assigned'] ) && (int)$_GET['assigned'] > 0 ? (int)$_GET['assigned'] : 0 );

	$limit = 1000;

	$sql = "" .
		"SELECT COUNT(*) AS total
		FROM slot
		LEFT JOIN slot_chapter ON ( slot_chapter.slot = slot.id )
		WHERE slot.date > '" . date( "Y-m-01", $date ) . "'
		AND slot_chapter.slot IS NULL";

	$result = db_query( $sql );
	$row = db_fetch_array( $result );
?>
	<h3>Encontrados <?= $row['total'] ?> programas sin sinopsis para <?= date( "Y-m", $date ) ?></h3>
<?

	$sql = "" .
		"SELECT *
		FROM slot
		LEFT JOIN slot_chapter ON ( slot_chapter.slot = slot.id )
		WHERE slot.date > '" . date( "Y-m-01", $date ) . "'
		AND slot_chapter.slot IS NULL
		GROUP BY channel, LOWER(TRIM(title))
		LIMIT " . $limit . " OFFSET " . $init;

	$result = db_query( $sql );

	$slots = array();
	while( $row = db_fetch_array( $result ) ) {
		$slots[] = $row;
	}
?>
	<h5>Iniciando con <?= count( $slots ) ?> diferentes</h5>
<?

	$found = 0;
	$assigned = 0;

	foreach( $slots as $slot ) {
?>
<p style="text-align: left; font-size: 12px">
	<b><?= $slot['title'] ?></b>
	<br>
	Channel <?= $slot['channel'] ?>: Duration <?= $slot['duration'] ?>
<?
		$sql = "" .
			"SELECT slot_chapter.*
			FROM slot, slot_chapter
			WHERE slot_chapter.slot = slot.id
			AND slot.date > '" . date( "Y-01-01", $date ) . "'
			AND slot.channel = " . $slot['channel'] . "
			AND LOWER(TRIM(slot.title)) = LOWER(TRIM('" . mysql_real_escape_string( $slot['title'] ) . "'))
			LIMIT 1";
		$result = db_query( $sql );
		$row = db_fetch_array( $result );
		if( $row ) {
			$found ++;

			$sql = "INSERT INTO slot_chapter (_order,slot,chapter) (
				SELECT 0 AS _order, slot.id, " . $row['chapter'] . " AS chapter
				FROM slot
					LEFT JOIN slot_chapter ON ( slot_chapter.slot = slot.id )
				WHERE slot.date > '" . date( "Y-m-01", $date ) . "'
				AND slot_chapter.slot IS NULL
				AND slot.channel = " . $slot['channel'] . "
				AND LOWER(TRIM(slot.title)) = LOWER(TRIM('" . mysql_real_escape_string( $slot['title'] ) . "'))
			)";
			$result = db_query( $sql );

			$affected = mysql_affected_rows( );
?>
			<br>
			<b style="color:#090">Actualizados: <?= $affected ?></b>
<?
			$assigned += $affected;
		}
		else {
?>
			<br>
			<b style="color:#900">No es posible auto-asignar</b>
<?
		}
?>
</p>
<?
		flush( );
	}

?>
<a name="end"></a>
<div>
	<h3 style="color: #090">Encontrados <?= $found ?>, Asignados <?= $assigned ?></h3>
	<h5 style="color: #090">Total Encontrados <?= $tFound + $found ?>, Total Asignados <?= $fAssigned + $assigned ?></h5>
</div>
<div>
	<input type="button" value="Continuar" onclick="document.location.href = '?location=auto_sinopsis&prog=yes&init=<?= $init + $limit - $found ?>&found=<?= $tFound + $found ?>&assigned=<?= $fAssigned + $assigned ?>&date=<?= date( "Y-m-d", $date ) ?>'" />
</div>
<?
	if( count( $slots ) > 0 ) {
?>
		<script>
			document.location.href = '#end';
			setTimeout(function(){
				document.location.href = '?location=auto_sinopsis&prog=yes&init=<?= $init + $limit - $found ?>&found=<?= $tFound + $found ?>&assigned=<?= $fAssigned + $assigned ?>&date=<?= date( "Y-m-d", $date ) ?>';
			}, 1000)
		</script>
<?
	}
?>
