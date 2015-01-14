<?
error_reporting(E_ERROR);
set_time_limit( 0 );

$link = mysql_pconnect("localhost", "root", "krxo4578") or mysql_die();
$db ="ppa";
$channels = "762,134,506,428,719,135,508,429,720,263,763,427,718,507,860,581,850,491,492,493,495,496";
mysql_select_db($db) or die("Unable to select database");
require_once("../include/db.inc.php");

/*$sql = "select * from( " .
"select * from slot left join slot_chapter on slot_chapter.slot = slot.id where slot.channel in (134,428,135,429,718,850) and date > '" . date("Y-m-d") . "' " .
") as s1 where isnull(slot);";
*/

$slots_q = "SELECT id FROM slot WHERE channel IN (" . $channels .") AND date > '" . date("Y-m-00") . "'";
$sql = "DELETE FROM slot_chapter WHERE slot in ( " . $slots_q . " )";
if(!db_query( $sql )){echo "Error!!\n";exit;}
echo $sql . "\n";

$slots_q = "SELECT id, title FROM slot WHERE channel IN (" . $channels . ") AND date > '" . date("Y-m-00") . "'";
$result = db_query( $slots_q );
echo $slots_q . "\n";

while($row=db_fetch_array($result))
{
//	$sql = "UPDATE slot set title = '" . trim(substr($row['title'], 0, 26)) . " (adulto)' WHERE id=" . $row['id'];
	$sql = "UPDATE slot set title = '" . trim(substr(str_replace(" (adulto)","",$row['title']), 0, 26)) . " (adulto)' WHERE id=" . $row['id'];
//	$sql = "UPDATE slot set title = '" . trim(substr(ereg_replace(" \(ad.*","",$row['title']), 0, 26)) . " (adulto)' WHERE id=" . $row['id'];
	db_query($sql);
	$sql = "INSERT INTO slot_chapter (slot,chapter) VALUES (" . $row["id"] . ",27652)";
	db_query($sql);
}
echo "\n\n\nGracias, vuelvan pronto!\n";
?>
