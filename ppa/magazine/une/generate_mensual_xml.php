<?
require_once "ppa11/class/ProgramGrid.class.php";
require_once "Spreadsheet/Excel/Writer.php";

function timeFormat( $time )
{
	$hour     = substr( $time, 0, 2);
	$minute   = substr( $time, 3, 2);
	$meridian = ( $hour / 12 ) >=1 ? "pm" : "am";

	$hour       = $hour % 12;
	$hour       = $hour == 0 ? "12" : $hour;
	
	return sprintf("%02d", $hour ) . ":" . sprintf("%02d", $minute ) . " " . $meridian;
}

$sql         = "SELECT name FROM channel WHERE id=" . $_GET['id'];
$row         = db_fetch_array( db_query( $sql ) );
$channelName = str_replace( " ", "_", $row['name'] );
$time_format = (isset($_GET['time_format']) ? $_GET['time_format'] : "24H");

header( 'Content-Disposition: attachment; filename="' . $channelName . '_' . $_GET['date'] . '.xml"; Content-type: text/xml' );
//header( 'Content-type: text/xml' );

/*$daynames[0] = "DOMINGO";
$daynames[1] = "LUNES";
$daynames[2] = "MARTES";
$daynames[3] = "MIERCOLES";
$daynames[4] = "JUEVES";
$daynames[5] = "VIERNES";
$daynames[6] = "SABADO";*/

$daynames[0] = "Domingo";
$daynames[1] = "Lunes";
$daynames[2] = "Martes";
$daynames[3] = "Miércoles";
$daynames[4] = "Jueves";
$daynames[5] = "Viernes";
$daynames[6] = "Sábado";

$date = isset( $_GET['date'] ) ? $_GET['date'] : date("Y-m");

$start_date = $_GET['date']. "-01";
$end_date = $_GET['date']. "-31";


$sql = "SELECT slot.id id, slot.date, slot.time, slot.duration, slot.title, ifnull(slot_chapter.chapter, slot.title) chapter FROM slot " .
  "LEFT JOIN  slot_chapter " .
  "ON slot.id = slot_chapter.slot " .
  "LEFT JOIN chapter " .
  "ON chapter.id = slot_chapter.chapter " .
  "WHERE " .
  "slot.channel = " . $_GET['id'] ." AND " .
  "slot.date BETWEEN '" . $start_date . "' AND '" . $end_date . "' " .
  "ORDER BY date, time";

$sql = "SELECT title, time, date FROM slot " .
  "WHERE " .
  "channel = " . $_GET['id'] ." AND " .
  "date BETWEEN '" . $start_date . "' AND '" . $end_date . "' " .
  "ORDER BY date, time";

$result = db_query( $sql );

echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
/*echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';*/
echo "<Root>";

$i      = 1;
$day    = "";
while( ( $row = db_fetch_array( $result ) ) )
{
	if($time_format == "12H")
		$time = timeFormat( $row['time'] );
	else
		$time = substr( $row['time'], 0, 5 );

	if( $day == "" )
	{
		$fixDay  = sprintf("%02d", $i);
		$dayname = $daynames[ date("w", strtotime($row['date'])) ] . " " . $i;
		echo "<day" . $fixDay . ">";
		echo "<dayName" . $fixDay .">" . $dayname . "\n</dayName" . $fixDay . ">";
		echo "<grid" . $fixDay . ">";
		$day = $row['date'];
	}
	else if( $day != $row['date'] )
	{
		echo "</grid" . $fixDay . ">";
		echo "</day" . sprintf("%02d", $i) . ">\n";
		$i++;
		$fixDay  = sprintf("%02d", $i);
		$dayname = $daynames[ date("w", strtotime($row['date'])) ] . " " . $i;
		echo "<day" . $fixDay . ">";
		echo "<dayName" . $fixDay .">" . $dayname . "\n</dayName" . $fixDay . ">";
		echo "<grid" . $fixDay . ">";
		$day = $row['date'];
	}

//	echo substr( $row['time'], 0, 5 ) . "\t" . utf8_decode ( $row['title'] ) . "\n";
	echo $time . "\t" . htmlspecialchars( $row['title'] ) . "\n";
}
echo "</grid" . $fixDay . ">";
echo "</day" . $i . ">\n";
echo "</Root>";
?>