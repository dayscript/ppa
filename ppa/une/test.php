<?
require_once "ppa11/class/ProgramGrid.class.php";
require_once "Spreadsheet/Excel/Writer.php";
set_time_limit ( 120 );

class GenerateInteactiveSheet
{
	var $id_client;
	var $startDate;
	var $endDate;
	var $sheetName;
	var $grid;
	var $channels;
	var $sheetRow;
	var $workbook;
	var $workSheet;
	var $DAY;

	function GenerateInteactiveSheet( $sheetName )
	{
		$this->DAY       = 60*60*24;
		$this->rowOffset = 0;
		$this->colOffset = 0;
		$this->idClient  = 0;
		$this->sheetName = $sheetName;
		$this->grid      = new ProgramGrid();

		$this->workbook  = new Spreadsheet_Excel_Writer();
		$this->worksheet =& $this->workbook->addWorksheet( $sheetName );
		$this->workbook->setVersion(8);
	}
	
	function setIdclient( $id )
	{
		$this->idClient = $id;
	}
	
	function setStartDate( $sd )
	{
		$this->startDate = $sd;	
	}
	
	function setEndDate( $ed )
	{
		$this->endDate = $ed;
	}
	
	function setColOffset( $co )
	{
		$this->colOffset = $co;
	}

	function setRowOffset( $ro )
	{
		$this->RolOffset = $ro;
	}

	function setChannels( )
	{
		$sql = "SELECT channel.id, channel.name, client_channel.number, ifnull(client_channel.custom_shortname, channel.shortname) shortname " .
		  "FROM channel, client_channel " .
		  "WHERE " .
		  "channel.id = client_channel.channel AND " .
		  "client_channel.client = " . ID_CLIENT . " " .
		  "ORDER BY client_channel.channel";
		
		$result = db_query( $sql );
		while( $row = db_fetch_array($result) )
		{
			$this->channels[$row['id']]['shortname'] = $row['shortname'];
			$this->channels[$row['id']]['name']      = $row['name'];
			$this->channels[$row['id']]['number']    = $row['number'];
		}		
	}
	
	function fillGrid( &$grid )
	{
		$tmpChannel = $grid->getAttribute("channel");
		if( !$grid->lastChild )
		{
			unset( $dayBranch );
			$dayBranch = &$grid->createDay();
			$dayBranch->setAttribute( "date", $this->startDate );			
			$dayBranch->createTime( "00:00", "1440", "Programación " . $this->channels[$tmpChannel]['name'], true );
		}
		while( $grid->lastChild->getAttribute("date") < $this->endDate )
		{
			$time = strtotime( $grid->lastChild->getAttribute("date") );
			unset( $dayBranch );
			$dayBranch = &$grid->createDay();
			$dayBranch->setAttribute( "date", date("Y-m-d", $time + $this->DAY ) );			
			$dayBranch->createTime( "00:00", "1440", "Programación " . $this->channels[$tmpChannel]['name'], true );
		}
	}
	
	function createChannelsGrid()
	{
		$sql = "SELECT * FROM slot " .
		  "WHERE " .
		  "slot.channel IN (" . implode( ",", array_keys($this->channels) ) .") AND " .
		  "slot.date BETWEEN '" . $this->startDate . "' AND '" . $this->endDate . "' " .
		  "ORDER BY channel, date, time";
		
		$result           = db_query( $sql );
		$actualChannel    = "";
		reset( $this->channels );
		
		$row = $row = db_fetch_array( $result );
		while( $row )
		{
			list( $actualChannel, $ch ) = each( $this->channels );
			$grid   = new programGrid();
			$grid->setName("main");
			$grid->setAttribute( "channel", $actualChannel );
			
			if( $actualChannel == $row['channel'] )
			{				
				while( $actualChannel == $row['channel'] )
				{
					$actualDate = $row['date'];
					unset( $dayBranch );
					$dayBranch = &$grid->createDay();
					$dayBranch->setAttribute( "date", $actualDate );
					while( $actualDate == $row['date'] )
					{
						$dayBranch->createTime( $row['time'], $row['duration'], $row['title'], true );
						$row = db_fetch_array( $result );
					}
				}
			}
			$this->fillGrid( &$grid );
			$this->writeSheet( &$grid );
		}
	}
	
	function writeSheet( &$grid )
	{		
		$day = &$grid->firstChild;
		while( $day )
		{
			$time = &$day->firstChild;
			while( $time )
			{
				$tmpDuration = $time->getAttribute("duration");
				$tmpTitle    = $time->getAttribute("title");
				$tmpStart    = $time->getAttribute("start");
				$tmpTime     = $time->getAttribute("time");
				$tmpChannel  = $grid->getAttribute("channel");
				
				$col = $this->colOffset;
				$this->worksheet->write($this->sheetRow, $col++, "EPM", $textFormat );
				$this->worksheet->write($this->sheetRow, $col++, "MDE01", $textFormat );
				$this->worksheet->write($this->sheetRow, $col++, str_replace( "-", "/", $day->getAttribute("date") ), $textFormat );
				$this->worksheet->write($this->sheetRow, $col++, $this->timeToHour( $tmpStart ), $textFormat );
				$this->worksheet->write($this->sheetRow, $col++, $this->channels[$tmpChannel]["shortname"], $textFormat );
				$this->worksheet->write($this->sheetRow, $col++, $this->channels[$tmpChannel]["name"], $textFormat );
				$this->worksheet->write($this->sheetRow, $col++, strtoupper( $tmpTitle ), $textFormat );
				$this->worksheet->write($this->sheetRow, $col, $tmpDuration, $textFormat );

				$this->sheetRow++;
		
				$time = &$time->nextSibling;
			}
			$day = &$day->nextSibling;
		}		
	}
	
	function timeToHour( $time )
	{
		$hour   = floor( $time / 60 ) % 24;
		$minute = $time % 60;
	
		return sprintf("%02d", $hour) . ":" . sprintf("%02d", $minute ) . ":00";
	}
	
	function go()
	{
		$this->sheetRow = $this->rowOffset;
		$this->setChannels();
		$this->createChannelsGrid();
		$this->workbook->send( $this->sheetName );
		$this->workbook->close();
	}
}

$test = new GenerateInteactiveSheet( 'GridTest' );
$test->setIdClient( 78 );
$test->setStartDate( '2006-11-28' );
$test->setEndDate( '2006-12-06' );
$test->setColOffset( 3 );
$test->go();
?>