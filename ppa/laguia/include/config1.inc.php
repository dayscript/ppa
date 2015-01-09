<?
//$path = stripslashes(substr($_SERVER['PATH_TRANSLATED'],0,strpos($_SERVER['PATH_TRANSLATED'],addslashes(str_replace("/","\\",$_SERVER['SCRIPT_NAME']))))) . "\\";
//$path .= "PPA\\";
$path = "ppa/";
$link = mysql_connect("localhost", "intercable", "inter379") or mysql_die();
$db="program";
@mysql_select_db("$db") or die(  "Unable to select database");
require_once($path . "include/db.inc.php");
?>
