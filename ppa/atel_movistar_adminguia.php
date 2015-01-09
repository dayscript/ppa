<?
error_reporting(E_ERROR);
$file = file("/home/intercable/public_html/WEB-INF/classes/logs/28/" . date("Y-m-d") . ".txt" );
$last_ip = null;
foreach($file as $ln)
{
	if(!ereg("INFORMACION:", $ln)) continue;
	$ln = trim(ereg_replace("^.*INFORMACION:","",$ln));
	echo "'" .  $ln . "'<br>";
	if(ereg("[0-9]{1,3}\.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}", $ln))
		$last_ip = $ln;
}
if(!$last_ip) exit;
header("Location: http://" . $last_ip . ":8080/adminguia/DayAdmin/index.jsp");
?>
