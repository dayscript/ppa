<?
//$path = stripslashes(substr($_SERVER['PATH_TRANSLATED'],0,strpos($_SERVER['PATH_TRANSLATED'],addslashes(str_replace("/","\\",$_SERVER['SCRIPT_NAME']))))) . "\\";
//$path .= "PPA\\";
if( !isset( $path ) ){
  $path = "";
}
$link = mysql_pconnect("localhost", "ppa", "kfc3*9mn") or mysql_die();
$db="ppa";
@mysql_select_db("$db") or die(  "Unable to select database");
require_once($path . "include/db.inc.php");
?>
