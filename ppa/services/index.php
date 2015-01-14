<?
require_once( "../ppa11/class/FamilyTree.class.php" );
require_once( "../include/db.inc.php" );
define( "ID_CLIENT", "65");

$link = mysql_pconnect("localhost", "dayscript", "kfc3*9mn") or mysql_die();
$db ="program";
mysql_select_db($db) or die("Unable to select database");

	switch( $_GET['type'] )
	{
		case "cat":
			include("category.php");
		break;
		default:
			echo "Error grave";
		break;
	}
?>