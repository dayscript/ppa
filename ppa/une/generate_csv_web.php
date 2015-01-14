<?
require_once "ppa11/class/ProgramGrid.class.php";
require_once "une/class/CreateCsv.class.php";

define( "DAY", 60*60*24 );
set_time_limit ( 120 );

/*if( $_GET['days'] > 15 || $_GET['days'] < 0 ) $days = 15;
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
{*/

//error_reporting(E_ALL);
//$start_date = date("Y-m-01");
//$end_date   = date("Y-m-03");	
$date = isset($_GET['date']) ? $_GET['date'] : date("Y-m");
$start_date = $date . "-01";
$end_date   = $date . "-31";;
$fileName   = "test_web_" . substr($start_date,0,-3);

$cs = new CreateCsv( $fileName );
$cs->setIdClient( 78 );
$cs->setMode( $cs->WEB_MODE );
$cs->setStartDate( $start_date );
$cs->setEndDate( $end_date );
$cs->go();
$cs->compress();
$cs->save();
?>