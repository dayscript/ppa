<?
error_reporting(E_ERROR);
set_time_limit( 0 );

$link = mysql_pconnect("localhost", "ppa", "kfc3*9mn") or mysql_die();
$db ="ppa";
mysql_select_db($db) or die("Unable to select database");
require_once("../include/db.inc.php");
require_once("../include/util.inc.php");
require_once("../class/Ftp.class.php");
require_once("class/Properties.class.php");
require_once("class/WriteFile.class.php");
require_once("class/Schedule2.class.php");
require_once("class/Program.class.php");

define("OUT_PATH", "files/");

/*********************
* Funcion para convertir minutos a Horas y minutos (HH:MM)
*********************/
function toHM( $minutes )
{
	if( $minutes === "" ) return "";
	if( $minutes <= 0 ) return "0030";
//	if( $minutes > 300 ) echo "programa de mas de 5 horas\n";
	$m = $minutes % 60;
	$h = $minutes / 60;
	return sprintf( "%02d%02d", $h, $m );
}

/***************************/
if( !isset($argv[1]) || !ereg("[0-9]{4}-[0-9]{2}-[0-9]{2}", $argv[1] ) ) die( "\nSe debe especificar la fecha de inicio en el formato yyyy-mm-dd.\nej.create_files.php 2025-10-01\n\n");

$ID_CLIENT=67;
$MAX_LENGHT=3*60; //máxima duración de programa
$props  = new Properties( "properties/" . $ID_CLIENT .".properties" );
$GMT    = 5;
$DAY    = 60*60*24;
$PREFIX = "ppv_";
$start_ts=strtotime($argv[1]);

if( $PREFIX == "" ) die ( "No se ha definido el prefijo en el archivo de configuración\n\n" );

$start_date = date("Y-m-d", $start_ts - (7*$DAY));
$stop_date  = date("Y-m-d", $start_ts + (37*$DAY));

/*$stop_date_ppv  = date("Y-m-d", time() + (30 * 24 * 60 * 60) );
$start_date     = "2009-07-24";
$stop_date_ppv  = "2009-09-03";*/

	
/************************
* *
* Genera archivo de PPV (.csv)
* *
*************************/

println( date("[H:i:s] ") . "Generando archivo de PPV" );
println( date("[H:i:s] ") . "Iniciando $start_date y finalizando $stop_date" );
$file = new WriteFile( $PREFIX . date("md") .".csv" );

/************************
* Trae programacion PPV
*************************/

$sql = "SELECT ".
       "  slot.id sid, slot.title, slot.date, slot.time, slot.duration, ".
       "  channel.id id, channel.shortname, ".
       "  client_channel.number ".
       "FROM ppa.client, channel, client_channel, slot ".
       "WHERE ppa.client.id = client_channel.client ".
       "  AND channel.id = client_channel.channel ".
       "  AND ppa.client.id = " . $ID_CLIENT ."".
       "  AND channel.id = slot.channel ".
       "  AND client_channel._group like 'PPV' ".
       "  AND slot.date BETWEEN '". $start_date ."' AND '". $stop_date ."' ".
       "ORDER by client_channel.number, slot.date, slot.time ";


$result = db_query( $sql );

println( date("[H:i:s] ") . "Escribiendo archivo de PPV" );

$file->write( "Hora inicio", 100, "," );
$file->write( "Hora fin", 100, "," );
$file->write( "Título", 100, "," );
$file->write( "Fecha inicio", 100, "," );
$file->write( "Fecha fin", 100, "," );
$file->write( "Canal", 100 );
$file->writeLn( );

$row = db_fetch_array($result);
$old_shortname = $row['shortname'];
$old_timestamp = 0;
while($next_row = db_fetch_array($result))
{
	if( $row['duration'] > $MAX_LENGHT ) echo "Duración Excesiva: " . $row['duration'] . " " . $row['shortname'] . " " . $row['title'] . " " . $row['date'] . " " . $row['time'] . "\n";
	if( $next_row['shortname'] != $row['shortname'] )
	{
		$row = db_fetch_array($result);
//		$old_shortname = $row['shortname'];
		$file->writeLn();
		continue;
	}

	$date_time_ts = strtotime( $row['date'] ." ". $row['time'] );
	$date_time =  date("Y-m-d H:i:s", $date_time_ts + ($GMT * 60 * 60) );

	$next_date_time_ts = strtotime( $next_row['date'] ." ". $next_row['time'] );
	$next_date_time =  date("Y-m-d H:i:s", $next_date_time_ts + ($GMT * 60 * 60) );

	$file->write( substr( $date_time, 11, 8) , 10, "," );
	$file->write( substr( $next_date_time, 11, 8) , 10, "," );
//	$file->write( $row['time'], 500, "," );
	$file->write( $row['title'], 500, "," );
	$file->write( substr( $date_time, 0, 10), 10, "," );
	$file->write( substr( $next_date_time, 0, 10) , 10, "," );
//	$file->write( $row['date'], 500, "," );
	$file->write( $row['shortname'], 10, "," );
//	if(($date_time_ts-$old_timestamp)/60 > $MAX_LENGHT ) $file->write( ($date_time_ts-$old_timestamp)/(60*60), 10 );
	if(($next_date_time_ts-$date_time_ts)/60 > $MAX_LENGHT ) $file->write( ($next_date_time_ts-$date_time_ts)/(60*60), 10 );
		
	$file->writeLn( );
	
//	$old_shortname = $row['shortname'];
//	$old_timestamp = $date_time_ts;
	$row = $next_row;
}

$file->close();

?>  
