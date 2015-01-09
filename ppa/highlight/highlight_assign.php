<?
$highlights = $_POST['specials'];
$subtypes = $_POST['subtype'];
/*sorry, machete de afán*/

$sql = "DELETE FROM highlights WHERE id_slot IN(" .
	"SELECT slot.id FROM slot WHERE " .
	"slot.channel = " . $_GET['id'] . " AND " .
	"slot.date like '" . $_GET['date'] . "-%'" .
	") AND " . 
	"type='" . $_GET['type'] ."'";
	
db_query( $sql );

foreach( $highlights as $h )
{
	$subtype = $subtypes[$h]? "'$subtypes[$h]'": "NULL";
	if( $_POST['special_sname'][$h] == "" )
		$sql = "INSERT INTO highlights (id_slot, type, subtype) VALUES (" . $h . ", '" . $_GET['type'] . "', " . $subtype . ")";
	else
		$sql = "INSERT INTO highlights (id_slot, title, type, subtype) VALUES (" . $h . ", '" . $_POST['special_sname'][$h] . "', '" . $_GET['type'] . "', " . $subtype . ")";
	
	db_query( $sql );
}
?><script>window.close();</script>