<?
ini_set('display_errors', 0);
error_reporting(E_ERROR);

session_start();
include( "util/common.php");
include( "util/html.php");
include( "util/slang.php" );
include( "util/config.php" );

define( "ID_CLIENT", 0 );
define( "GMT", 0 ); // ( GMT -4 ) - ( GMT -5 )= VEN - COL = 1
//define( "NOW", time() + ( GMT * 3600 ) );
define( "NOW", time() );

printHeader();
$location = isset($_GET['l']) ? $_GET['l'] : "home";
switch( $location )
{
	case $slang['program_form']:
		include( "program_form.php" );
	break;
	case $slang['synopsis']:
		include( "synopsis.php" );
	break;
	case "home":
	default:
		include( "menu.php" );
	break;
}
printFooter();
?>