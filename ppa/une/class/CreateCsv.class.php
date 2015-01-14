<?
require_once "ppa11/class/ProgramGrid.class.php";
require_once "une/translator.php";
set_time_limit ( 120 );

class CreateCsv
{
	var $DAY;
	var $INTERACTIVE_MODE;
	var $WEB_MODE;
	var $PPV_MODE;

	var $idClient;
	var $mode;
	var $startDate;
	var $endDate;
	var $fileName;
	var $grid;
	var $channels;
	var $fileCounter;
	var $linesCounter;
	var $hd;

	function CreateCsv( $fileName )
	{
		$this->DAY              = 60*60*24;
		$this->INTERACTIVE_MODE = 0;
		$this->WEB_MODE         = 1;
		$this->PPV_MODE         = 2;

		$this->mode      = "";
		$this->idClient  = 0;
		$this->fileName = $fileName;
		$this->grid      = null;
		$this->fileCounter = 0;
		$this->linesCounter = 0;
	}
	
	function setIdclient( $id )
	{
		$this->idClient = $id;
	}
	
	function setMode( $mode )
	{
		$this->mode = $mode;
	}
	
	function setStartDate( $sd )
	{
		$this->startDate = $sd;	
	}
	
	function setEndDate( $ed )
	{
		$this->endDate = $ed;
	}
	
	function createFile($count)
	{
		$this->hd = fopen("/tmp/" . $this->fileName . "_" . sprintf("%03d", $count) . ".csv", "w+" );
		fwrite( $this->hd, "canal,fecha,horainicio,horafin,descripcion,tienedes,programa\r\n" );
	}

	function closeFile()
	{
		fclose( $this->hd );
	}
	
	function setChannels( )
	{
		switch( $this->mode )
		{
			case $this->WEB_MODE:
			case $this->INTERACTIVE_MODE:
				$sql = "SELECT channel.id, channel.name, client_channel.number, ifnull(client_channel.custom_shortname, channel.shortname) shortname " .
				  "FROM channel, client_channel " .
				  "WHERE " .
				  "channel.id = client_channel.channel AND " .
				  "client_channel.client = " . $this->idClient . " " .
				  "ORDER BY client_channel.channel";
			break;
			case $this->PPV_MODE:
				$sql = "SELECT channel.id, channel.name, client_channel.number, ifnull(client_channel.custom_shortname, channel.shortname) shortname " .
				  "FROM channel, client_channel " .
				  "WHERE " .
				  "channel.id = client_channel.channel AND " .
				  "client_channel.client = " . $this->idClient . " AND " .
				  "client_channel._group = 'PPV' " .
				  "ORDER BY client_channel.channel";
			break;
			default:
				die( "Modo desconocido" );
			break;
		}
		
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
		$this->createFile($this->fileCounter);
		$sql = "SELECT * FROM slot " .
		  "WHERE " .
		  "slot.channel IN (" . implode( ",", array_keys($this->channels) ) .") AND " .
		  "slot.date BETWEEN '" . $this->startDate . "' AND '" . $this->endDate . "' " .
		  "ORDER BY channel, date, time";

		$result           = db_query( $sql );
		$actualChannel    = "";
		reset( $this->channels );
		
		$row = db_fetch_array( $result );
//		while( $row && true )
		while( $row )
		{
			list( $actualChannel, $ch ) = each( $this->channels );
			unset($grid);
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
					while( $actualDate == $row['date'] && $actualChannel == $row['channel'] )
					{
						$dayBranch->createTime( $row['time'], $row['duration'], $row['title'], true );
						$row = db_fetch_array( $result );
					}
				}
			}
//			$this->fillGrid( &$grid );
			$this->writeWebFiles( &$grid );
			$grid->destroy();
		}
		$this->closeFile();
		mysql_free_result($result);
	}
	
	function writeWebFiles( &$grid )
	{
		$day = &$grid->firstChild;
		while( $day )
		{
			$time = &$day->firstChild;
			while( $time )
			{
				$tmpDuration = $time->getAttribute("duration");
				$tmpTitle    = ereg_replace("[\*|/|-|,]", "", $time->getAttribute("title") );
				$tmpTitle    = ereg_replace("ñ", "n", $tmpTitle );
				$tmpTitle    = ereg_replace("Ñ", "N", $tmpTitle );
				$tmpStart    = $time->getAttribute("start");
				$tmpTime     = $time->getAttribute("time");
				$tmpChannel  = $grid->getAttribute("channel");
				
				fwrite( $this->hd, translate( $this->channels[$tmpChannel]["name"] ) . "," );
//				fwrite( $this->hd, $this->channels[$tmpChannel]["name"] . "," );
				fwrite( $this->hd, str_replace( "-", "/", $day->getAttribute("date") ) . "," );
				fwrite( $this->hd, $this->timeToHour( $tmpStart ) . "," );
				fwrite( $this->hd, $this->timeToHour( $tmpStart+$tmpDuration ) . "," );
				fwrite( $this->hd, ".," );
				fwrite( $this->hd, "no" . "," );
				fwrite( $this->hd, strtoupper( $tmpTitle ) . "\r\n" );
		
				$time = &$time->nextSibling;
				$this->linesCounter++;
				if( $this->linesCounter >= 700 )
				{
					$this->linesCounter = 0;
					$this->fileCounter++;
					$this->closeFile();
					$this->createFile($this->fileCounter);
				}
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
	
	function compress()
	{
		$command = "zip -j /tmp/" . $this->fileName . ".zip /tmp/" . $this->fileName . "_*";
		$result = `$command`;
	}
	
	function save()
	{
		header('Content-type: application/zip');
		header('Content-Disposition: attachment; filename="' . $this->fileName . '"');
		readfile("/tmp/" . $this->fileName . ".zip");
	}
	
	function go()
	{
		if( $this->mode === "" ) die( "debe seleccionar un modo" );
		$this->setChannels();
		$this->createChannelsGrid();
	}
	
	function __destruct()
	{
		$command = "rm -f /tmp/" . $this->fileName . "*";
		$result = `$command`;
	}
}
?>