<? 
include( "ppa/include/db.inc.php" );
include( "ppa/include/config.inc.php" );
set_time_limit( 0 );
/*
$file = file( "chapter.sql" );

foreach( $file as $ln )
{
	$sql = $ln;
	if(!db_query( $sql ) )
	{
		echo $sql . "\n";
		echo mysql_error($link) . "\n";
	}
}
$j = 1;
/*for( $i=0; $i<=count( $file ); $i++)
{
//	if( db_query( utf8_decode( $file[$i] ) ) ) echo "OK: " . $file[$i];
//	else echo "ERROR: " . $file[$i];

	ereg( "VALUES \(([0-9]*),", $file[$i], $regs );
	$id = $regs[1];
	
	$sql = "SELECT title, spanishtitle, description FROM chapter where id = " . $id;
	$row = db_fetch_array( db_query( $sql ) );
	
	$str   = $row['title'] . " " . $row['spanishtitle'] . " " . $row['description'];

	$arr[] = "ASCII";
	$arr[] = "UTF-8";
	$arr[] = "ISO-8859-1";

	$charset = mb_detect_encoding( $str, $arr );
	if( $charset == "UTF-8" )
	{
		$sql = "DELETE FROM chapter WHERE id = " . $id . " LIMIT 1";
		if( db_query( $sql ) )
		{
			$sql = utf8_decode( $file[$i] );
			if( db_query( $sql ) )
			{
				echo "OK: " . $sql . "<br>";	
			}
			else
			{
				echo "error 2: " . $sql . "<br>";	
			}
		}
		else
		{
			echo "error 1: " . $sql . "<br>";		
		}
	}
}*/

$arr[] = "ASCII";
$arr[] = "UTF-8";
$arr[] = "ISO-8859-1";

$field[] = "id";
$field[] = "title";
$field[] = "spanishtitle";
$field[] = "description";

$sql = "SELECT id, title, spanishtitle, description FROM chapter";
$result = db_query( $sql );
while( $row = db_fetch_array( $result ) )
{
	$cel = false;
	for( $i=1; $i<4; $i++ )
	{	
		$charset = mb_detect_encoding( $row[$i] . "a", $arr );
		if( $charset == "UTF-8" )
		{
			$dec = utf8_decode( $row[$i] );
			$sql = "UPDATE chapter SET " . $field[$i] . " = '" . addslashes( trim($dec) ) .  "' WHERE id = " . $row['id'];
/*			if( !db_query( $sql ) )
				echo "error: " . $sql . "<br>";
			else*/
				echo "OK: " . $sql . "<br>";
			$cel = true;
		}
	}
//	if( $cel ) echo "<br>";
}

?>
