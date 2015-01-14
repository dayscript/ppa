<?
require_once "ppa11/class/ProgramGrid.class.php";
require_once "une/class/CreateSheets.class.php";
require_once "Spreadsheet/Excel/Writer.php";

define( "DAY", 60*60*24 );
set_time_limit ( 120 );

if( $_GET['days'] > 15 || $_GET['days'] < 0 ) $days = 15;
else $days = $_GET['days'];

if( $_GET['next_month'] == "part1" || $_GET['next_month'] == "part2" )
{
	if ( $_GET['next_month'] == "part1" ) 
	{
		$time = mktime(0,0,0,date("m")+1, 1, date("y") );
		$start_date = date("Y-m-d", $time );
		$time = mktime(0,0,0,date("m")+1, 15, date("y") );
		$end_date   = date("Y-m-d", $time );
	}
	else 
	{
		$time = mktime(0,0,0,date("m")+1, 16, date("y") );
		$start_date = date("Y-m-d", $time );
		$time = mktime(0,0,0,date("m")+2, 0, date("y") );
		$end_date   = date("Y-m-d", $time );
	}
	
}
else
{
	$start_date = date("Y-m-d");
	$end_date   = date("Y-m-d", time() + ($days*DAY));	
}

$cs = new CreateSheets( "interactivefile" . $days . "days" . $_GET['next_month'] . ".xls" );
$cs->setIdClient( 78 );
$cs->setMode( $cs->INTERACTIVE_MODE );
$cs->setStartDate( $start_date );
$cs->setEndDate( $end_date );
$cs->setColOffset( 3 );
$cs->go();
?>