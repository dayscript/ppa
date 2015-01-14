<?
require_once("config1.php");

$sql = "select * from slot where date >= '2004-02-01' order by channel, date, time";
$query = db_query( $sql, true );
$slot_array = array();
while( $row = db_fetch_array( $query ) ){
  $slot_array[$row['id']] = $row['date']." ".$row['time'];
}
$keys = array_keys( $slot_array );

for( $i = 0 ; $i < count( $slot_array ); $i++ ){
  $time = strtotime( $slot_array[$keys[$i]] );
  $time1 = strtotime( $slot_array[$keys[$i+1]]);
  $duration = ($time1 - $time)/60;
  $sql = "update slot set duration = ".$duration." where id = ".$keys[$i];
  db_query( $sql );
}

echo "<strong>Fin</strong>";
?>