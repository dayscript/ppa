<?

require_once("config1.php");
$sql = "SELECT id, time, date, title FROM slot WHERE channel = 155 AND date like '2004-11%'";
$query = db_query( $sql );
while( $row = db_fetch_array( $query ) )
{
	$nuevo_ts = strtotime( $row['date']." ".$row['time'] ) + 1 * (60*60);

	$newtime = date( "H:i:s", $nuevo_ts );
	$newdate = date( "Y-m-d", $nuevo_ts );
	echo "$row[date] $row[time] --> $newdate $newtime $row[title]<br>";

	$sql = "update slot set time = '".$newtime."', date = '".$newdate."' where id = ".$row['id']." ";
//	echo $sql ."<br>";
//	db_query( $sql );
	
}
echo "<strong>Fin</strong>";

?>