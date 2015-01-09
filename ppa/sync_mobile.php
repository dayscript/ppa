<?php

error_reporting( -1 );
ini_set( 'display_errors', 1 );

define( "DSYNK", 5000 );

function countTable( $table, $link )	{
	$sql = "SELECT COUNT(*) AS total FROM `" . $table . "`";
	$result = mysql_query( $sql, $link );
	$row = mysql_fetch_assoc( $result );
	
	return $row['total'];
}

function unsyncByID( $table, $id )	{
	global $remoteLink;
	
	$sql = "DELETE 
			FROM `" . $table . "`
			ORDER BY `" . $id . "` DESC
			LIMIT " . DSYNK . " 
			";
	$result = mysql_query( $sql, $remoteLink );
	echo "\n\tDe-Sincronizando por ID: `" . DSYNK . "` registros en `" . $table . "`";
}

function syncByID( $table, $id )	{
	global $localLink;
	global $remoteLink;
	
	$sql = "SELECT `" . $id . "` AS ID
			FROM `" . $table . "`
			ORDER BY `" . $id . "` DESC
			LIMIT 1
			";
	$result = mysql_query( $sql, $remoteLink );
	$row = mysql_fetch_assoc( $result );
	
	$sql = "SELECT *
			FROM `" . $table . "`
			WHERE `" . $id . "` > '" . $row['ID'] . "'
			";
	$result = mysql_query( $sql, $localLink );
	
	$cont = 0;
	while( ( $row = mysql_fetch_assoc( $result ) ) != null )	{
		$sql = "REPLACE INTO `" . $table . "` ( ";
		$values = " ) VALUES ( ";
		foreach( $row as $key => $value )	{
			$sql .= $key . ",";
			$values .= "'" . mysql_real_escape_string( $value, $remoteLink ) . "',";
		}
		
		$sql = trim( $sql, ',' );
		$sql .= trim( $values, ',' ) . ");\n\n";
		
		mysql_query( $sql, $remoteLink );
		$cont ++;
		if( $cont % 20 == 0 )
			echo '.';
	}
	
	echo "\n\tSincronizados por ID: `" . $cont . "` registros en `" . $table . "`";
	
}

function unsyncByCount( $table, $remoteTotal )	{
	global $remoteLink;
	
	$sql = "DELETE 
			FROM `" . $table . "` 
			LIMIT " . DSYNK . " OFFSET " . ( $remoteTotal - DSYNK ) . "
			";
	$result = mysql_query( $sql, $remoteLink );
	echo "\n\tDe-Sincronizando por CONTEO: `" . DSYNK . "` registros en `" . $table . "`";
}	

function syncByCount( $table, $local, $remote )	{
	global $localLink;
	global $remoteLink;
	
	if( $local <= $remote )	{
		echo "\n\tNO es posible sincronizar `" . ( $local - $remote ) . "` registros en `" . $table . "`";
		return;
	}
	
	$sql = "SELECT *
			FROM `" . $table . "`
			LIMIT " . ( $local - $remote ) . " OFFSET " . $remote;
	$result = mysql_query( $sql, $localLink );
	
	$cont = 0;
	while( ( $row = mysql_fetch_assoc( $result ) ) != null )	{
		$sql = "REPLACE INTO `" . $table . "` ( ";
		$values = " ) VALUES ( ";
		foreach( $row as $key => $value )	{
			$sql .= $key . ",";
			$values .= "'" . mysql_real_escape_string( $value, $remoteLink ) . "',";
		}
		
		$sql = trim( $sql, ',' );
		$sql .= trim( $values, ',' ) . ");\n\n";
		
		mysql_query( $sql, $remoteLink );
		$cont ++;
		if( $cont % 20 == 0 )
			echo '.';
	}
	
	echo "\n\tSincronizados por CONTEO: `" . $cont . "` registros en `" . $table . "`";
	
}


echo "\nConectando a equipo remoto.";
$remoteLink = mysql_connect( '50.23.114.66', 'ppa', 'ppa0487', true );
mysql_select_db( 'ppa_ws', $remoteLink );

echo "\nConectando a equipo local.";
$localLink = mysql_connect( 'localhost', 'root', 'krxo4578', true );
mysql_select_db( 'ppa', $localLink );

$tableSync = array(
	'chapter' => 'id',
	'client_channel' => null,
	'sinopsis' => null,
	'movie' => 'id',
	'serie' => 'id',
	'slot' => 'id',
	'slot_chapter' => 'slot',
	'special' => 'id'
);

foreach ( $tableSync as $table => $id )	{
	$remoteTotal = countTable( $table , $remoteLink );
	$localTotal = countTable( $table , $localLink );
	
	if( $remoteTotal != $localTotal )	{
		echo "\n\nNecesario sincronizar por " . ( $id ? "ID" : "CONTEO" )  . ":  `" . $table . "` " . $remoteTotal . "/" . $localTotal;
		
		if( $id )	{
			syncByID( $table, $id );
		}
		else	{
			syncByCount( $table, $localTotal, $remoteTotal );
		}
		
		$remoteTotal = countTable( $table , $remoteLink );
		$localTotal = countTable( $table , $localLink );
		if( $remoteTotal != $localTotal )	{
			echo "\n\tERROR al sincronizar: `" . $table . "` " . $remoteTotal . "/" . $localTotal;
			
			if( $id )	{
				unsyncByID( $table, $id );
				echo "\n\nNecesario sincronizar: `" . $table . "` ";
				syncByID( $table, $id );
			}
			else	{
				unsyncByCount( $table, $remoteTotal );
				echo "\n\nNecesario sincronizar: `" . $table . "` ";
				syncByCount( $table, $localTotal, $remoteTotal );
			}
			
			$remoteTotal = countTable( $table , $remoteLink );
			$localTotal = countTable( $table , $localLink );
			if( $remoteTotal < $localTotal )
				echo "\n\tERROR al forzar sincronizar: `" . $table . "` " . $remoteTotal . "/" . $localTotal;
		}
	}
	else	{
		echo "\n\nNada que hacer para: `" . $table . "`";
	}
}


echo "\n\n\n\n";


mysql_close( $remoteLink );
mysql_close( $localLink );
