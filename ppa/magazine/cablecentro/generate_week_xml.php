<?
require_once "ppa11/class/ProgramGrid.class.php";
require_once "Spreadsheet/Excel/Writer.php";

$day_names = array("monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday" );

/**
 * funciones
 */
function thirdWeek( $timestamp )
{
	$first_monthday = date( "w", $timestamp );
	$days_array = array ( "1" => 14,
	                      "2" => 13,
	                      "3" => 12,
	                      "4" => 11,
	                      "5" => 10,
	                      "6" => 9,
	                      "0" => 15 );
	                      
	return $timestamp + ( $days_array[ $first_monthday ] *60*60*24 );
}

function timeToHour( $time )
{
		$minute = $time % 60;
		$hour   = ( ( $time - $minute ) / 60 ) % 12;
		$hour   = $hour == 0 ? "12" : $hour;
		
		return $hour . ":" . sprintf("%02d", $minute );
}

function timeFormat( $time )
{
		$hour     = substr( $time, 0, 2);
		$minute   = substr( $time, 3, 2);
		$meridian = ( $hour / 12 ) >=1 ? "pm" : "am";

		$hour       = $hour % 12;
		$hour       = $hour == 0 ? "12" : $hour;
		
		return sprintf("%02d", $hour ) . ":" . sprintf("%02d", $minute ) . " " . $meridian;
}

function dateFormat( $date )
{
	$day_names = array("", "LUNES", "MARTES", "MIÉRCOLES", "JUEVES", "VIERNES", "SÁBADO", "DOMINGO" );
	$timestamp = strtotime($date);
	
	return $day_names[date("N", $timestamp)] . " " . date("j", $timestamp);
}

/**
 * Generate grid
 */

$monday = thirdWeek( strtotime( $_GET['date'] . "-01" ) );

$sql = "SELECT slot.id id, slot.date, slot.time, slot.duration, slot.title, ifnull(slot_chapter.chapter, slot.title) chapter FROM slot " .
  "LEFT JOIN  slot_chapter " .
  "ON slot.id = slot_chapter.slot " .
  "LEFT JOIN chapter " .
  "ON chapter.id = slot_chapter.chapter " .
  "WHERE " .
  "slot.channel = " . $_GET['id'] ." AND " .
  "slot.date BETWEEN " . date( "'Y-m-d'", $monday ) . " AND " . date( "'Y-m-d'", ( $monday + 6*60*60*24 ) ) . " " .
  "ORDER BY date, time";

$result = db_query( $sql );
$date   = "";
$day    = 0;
$grid   = new programGrid();
$grid->setName("main");

while( ( $row = db_fetch_array( $result ) ) )
{
	if( ereg( ":00:|:30:", $row['time'] ) ) // just o'clock and half programs
	{
		if( $date != $row['date'] )
		{ 
			$day++; 
			$date = $row['date'];
			unset( $dayBranch );
			$dayBranch = &$grid->createDay();
		}
		unset( $timeBranch );
		$timeBranch = &$dayBranch->createTime( $row['time'], $row['duration'], $row['title'] );
	} 
}

if( $grid->firstChild == null ) die( "no hay programación para el mes" );

$grid->fillFirstProgram();
$grid->groupByDay();

$day = &$grid->firstChild;

$sql = "SELECT name FROM channel WHERE id=" . $_GET['id'];
$row = db_fetch_array( db_query( $sql ) );

header( "Content-type: text/xml" );
header( "Content-Disposition: attachment; filename=\"" . str_replace(" ", "_", $row['name'] ) . "_weekgrid_" . date( "Y-m-d", $monday )  . ".xml\"" );

echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
echo "<Root>";
echo "<grid>";
while( $day )
{
	echo "<" . $day_names[$day->item] . ">";
	$time = &$day->firstChild;
	while( $time )
	{
		$tmpTitle    = $time->getAttribute("title");
		$tmpTime     = $time->getAttribute("time");
		
		echo timeFormat($tmpTime) . "\t" . htmlspecialchars($tmpTitle) . "\n";
		$time = &$time->nextSibling;
	}
	echo "</" . $day_names[$day->item] . ">";
	$day = &$day->nextSibling;
}
echo "</grid>";
echo "<specials>";

$i=0;
$last_time = 0;

/**
 * Generate specials
 */
if( is_array( $_POST['specials'] ) ) 
{
	$specials = implode(",", $_POST['specials'] );
	$sql        = "SELECT date,time,title FROM slot WHERE id IN (" . $specials . ") ORDER BY date, time";
	$result     = db_query( $sql );
	$dateBuffer = "";
	while( $row = db_fetch_array( $result ) )
	{
		if($dateBuffer != $row["date"])
		{
			echo dateFormat( $row["date"] ) . "\n";
			$dateBuffer = $row["date"];
		}
		echo timeFormat( $row['time'] ) . "\t" .htmlspecialchars($row['title']) . "\n";
	}
}	

echo "</specials>";
echo "</Root>";
?>