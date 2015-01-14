<?
require_once("include/util.inc.php");
$error = true;
$found = false;
$title = ereg_replace( "%u[0-9]{4}", "_", $_GET['title'] );
$title = ereg_replace( "%2B", "+", $title );

$titleGroup = ( isset( $_GET['titleGroup'] ) && is_array( $_GET['titleGroup'] ) ? $_GET['titleGroup'] : array( ) );


$where = " TRIM(title) LIKE '" . addslashes( trim( $title ) ) . "' ";
if( !empty( $titleGroup ) ) {
	$where = "";
	$whereAr = array( );
	foreach( $titleGroup as $title )  {
		$title = ereg_replace( "%u[0-9]{4}", "_", $title );
		$title = ereg_replace( "%2B", "+", $title );
		$whereAr[] = " TRIM(title) LIKE '" . addslashes( trim( $title ) ) . "' ";
	}

	$where = " ( " . implode( " OR ", $whereAr ) . " ) ";
}


$sql = "SELECT id " .
	"FROM slot " .
	"WHERE " . $where . " " .
	"AND channel = '" . $_GET['channel'] . "' " .
	"AND date like '" . $_GET['date'] . "%'";
	
$result = db_query( $sql );

$in_id = "";
while( $row = db_fetch_array( $result ) )
{
	$error = false;
	$sql = "INSERT INTO slot_chapter " .
		"(slot, chapter ) VALUES (" .
		$row['id'] . ", " . $_GET['chapter'] . ")";
	db_query( $sql );
}

if( !$error ) 
  die( "OK" );
else
  die( "ERROR en " . $sql . "\n[" . $QUERY_STRING . "]" );
