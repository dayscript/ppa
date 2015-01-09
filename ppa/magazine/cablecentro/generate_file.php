<?
$sql = "SELECT name FROM channel WHERE id=" . $_GET['id'];
$row = db_fetch_array( db_query( $sql ) );

header( "Content-type: text/plain" );
header( "Content-Disposition: attachment; filename=\"" . $row['name'] . "_" . $_GET['date'] . ".txt\"" );
require_once "ppa11/class/ProgramGrid.class.php";

$days = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo" );
class textFile
{
	var $hd;
	var $filename;
	function textFile( $name )
	{
		$this->filename = "/tmp/" . $name . rand(0,99999);
//		$this->filename = $name;
		$this->hd = fopen( $this->filename, "w+");
	}
	
	function getFilename()
	{
		return $this->filename;
	}
	
	function write($str)
	{
		fwrite($this->hd, $str );
	}

	function writeLn($str)
	{
		$this->write($str . "\n");
	}
	
	function close()
	{
		fclose($this->hd);
	}
}

$sql    = "SELECT title, wd, date, time, sum " .
	"FROM ( " .
	"SELECT concat( WEEKDAY( date ) , time, left(title,8 )) cat, WEEKDAY( date ) wd, title, date, time, count( * ) sum " .
	"FROM `slot` " .
	"WHERE channel =" . $_GET['id'] ." AND " .
  "date BETWEEN '" . $_GET['date'] . "-01' AND '" . $_GET['date'] . "-31' " .
	"GROUP BY 1 " .
	"ORDER BY cat, date " .
	")t " .
	"ORDER BY wd, time, sum, date";

$grid    = new textFile( "grid" );
$special = new textFile( "special" );

$result = db_query( $sql );
$row = db_fetch_array( $result );
$weekday = "";
while( true )
{
	if( $row["wd"] != $weekday )
	{
		$grid->writeLn($days[$row["wd"]]);
		$special->writeLn($days[$row["wd"]]);
		$weekday = $row["wd"];
	}
	if( $row["sum"] > 1 )
	{
		$grid->write($row["time"] . "\t");
		$grid->writeLn($row["title"]);
		$row = db_fetch_array( $result );
	}
	else
	{
		do
		{
			$buffer_time = $row["time"];
			$special->write($row["date"] . "\t");
			$special->write($row["time"] . "\t");
			$special->writeLn($row["title"]);
			$row = db_fetch_array( $result );
		}while($buffer_time == $row["time"] );
		$special->writeLn("");
	}
	if( !$row ) break;
}
$grid->close();
$special->close();
echo "======== GRILLA SEMANAL ==========\n";
readfile( $grid->getFileName() );
echo "\n\n\n======== PROGRAMAS ESPECIALES ==========\n";
readfile( $special->getFileName() );
unlink( $grid->getFileName() );
unlink( $special->getFileName() );
?>