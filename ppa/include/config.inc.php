<?
$path = stripslashes(substr($_SERVER['PATH_TRANSLATED'],0,strpos($_SERVER['PATH_TRANSLATED'],addslashes(str_replace("/","\\",$_SERVER['SCRIPT_NAME']))))) . "\\";
$link = mysql_connect("localhost", "ppa", "kfc3*9mn") or mysql_die();
$db="intermedio";
mysql_select_db($db) or die(  "Unable to select database");
require_once("db.inc.php");
?>
