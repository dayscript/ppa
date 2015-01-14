<?
session_start();
//$path = stripslashes(substr($_SERVER['PATH_TRANSLATED'],0,strpos($_SERVER['PATH_TRANSLATED'],addslashes(str_replace("/","\\",$_SERVER['SCRIPT_NAME']))))) . "\\";
//$path .= "PPA\\";
//$URL_PPA = "190.27.201.2";
//$URL_PPA = "200.71.33.251";
//$URL_PPA = "200.75.105.77";
$URL_PPA = "200.75.113.177";
$path = "ppa/";
/*sorry, a f*k*n* patch*/
if( $_SESSION['user'] == "admin" || $_SESSION['user'] == "dbernal" || $_SESSION['user'] == "borjuela" )
	$link = mysql_pconnect("localhost", "root", "krxo4578") or mysql_die();
else if( $_GET['location'] == "del_synopsis" )
	$link = mysql_pconnect("localhost", "root", "krxo4578") or mysql_die();
else
	$link = mysql_pconnect("localhost", "ppa", "kfc3*9mn") or mysql_die();
$db="ppa";
@mysql_select_db("$db") or die(  "Unable to select database");
//require_once($path . "include/db.inc.php");
?>