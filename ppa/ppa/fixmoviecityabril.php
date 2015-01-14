<?
require_once("config1.php");
$sql = 'SELECT  sc.chapter FROM slot s, slot_chapter sc WHERE s.channel =132 AND s.date >=  "2004-04-01" AND s.date <=  "2004-04-30" AND s.id = sc.slot';
$query = db_query( $sql );
while( $row = db_fetch_array( $query ) ){
	$sql1 = "delete from sinopsis where chapter = ".$row['chapter']." and month = '04' and year = '2004' ";
	db_query( $sql1 ); 
}
?>
