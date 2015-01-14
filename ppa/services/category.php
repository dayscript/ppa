<?
require_once "../ppa11/class/ProgramGrid.class.php";

$date     = isset( $_GET['date'] ) ? $_GET['date'] : date("Y-m-d");
$category = isset( $_GET['category'] ) ? $_GET['category'] : "premium";

$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.name, channel.id ".
       "FROM channel, client, client_channel ".
       "WHERE client.id = client_channel.client ". 
       "AND channel.id=client_channel.channel ".
       "AND client.id = ". ID_CLIENT ." ".
       "AND client_channel._group like '" . $category . "' ".
       "ORDER BY client_channel.number";
       
$result = db_query( $sql );
while( ( $row = db_fetch_array( $result ) ) )
{
	$channels[$row['id']] = $row['name'];
}

$sql = "SELECT slot.id id, slot.date, slot.time, slot.duration, slot.title, ifnull(slot_chapter.chapter, slot.title) chapter, slot.channel FROM slot " .
  "LEFT JOIN  slot_chapter " .
  "ON slot.id = slot_chapter.slot " .
  "LEFT JOIN chapter " .
  "ON chapter.id = slot_chapter.chapter " .
  "WHERE " .
  "slot.duration >= 10 AND " .
//  "slot.channel IN ( " . implode( ",", array_keys( $channels ) ) ." ) AND " .
  "slot.channel IN ( 193,140 ) AND " .
  "slot.date = '" . $date . "' " .
  "ORDER BY channel, date, time";

$result = db_query( $sql,1 );

$date   = "";
$day    = 0;
$grid   = array();

while( ( $row = db_fetch_array( $result ) ) )
{
	if( !isset( $grid[ $row['channel'] ] ) ) 
	{
		echo $row['channel'] . "<br>\n";
		$grid[ $row['channel'] ] = new programGrid();
		$grid[ $row['channel'] ]->setName("main");
	}

	if( $date != $row['date'] )
	{ 
		$day++; 
		$date = $row['date'];
		unset( $dayBranch );
		$dayBranch = &$grid[ $row['channel'] ]->createDay();
		$dayBranch->setAttribute( "date", $row['date'] );
	}
	unset( $timeBranch );
	$timeBranch = &$dayBranch->createTime( $row['time'], $row['duration'], $row['title'] );
	$timeBranch->setAttribute( "date", $row['date'] );
	$timeBranch->setAttribute( "chapter", $row['chapter'] );
}
print_r( $grid[193] ); exit;
foreach( $channels as $key => $value )
{
	echo $key . " " . $value;
	if( isset( $grid[$key] ) )
	{
		$day  = &$grid[$key]->firstChild;
		echo $day . "<br>";
	}
}



?>