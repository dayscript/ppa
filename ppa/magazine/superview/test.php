<?php
class programBlock
{
	var $id;
	var $day;
	var $start;
	var $duration;
	var $days;
	var $title;
	var $previous;
	var $next;
	
	function programBlock( )
	{
		$this->id       = 0;
		$this->day      = 0;
		$this->start    = 0;
		$this->duration = 0;
		$this->days     = 1;
		$this->title    = $id;
	}
	
	function getId()      { return $this->id; }
	function getDay()     { return $this->day; }
	function getStart()   { return $this->start; }
	function getDuration(){ return $this->duration; }
	function getDays()    { return $this->days; }
	function getTitle()   { return $this->title; }
	function getPrevious(){ return $this->previous; }
	function getNext()    { return $this->next; }
	function setId($id)            { $this->id       = $id; }
	function setDay($day)          { $this->day      = $day; }
	function setStart($start)      { $this->start    = $start; }
	function setDuration($duration){ $this->duration = $duration; }
	function setDays($days)        { $this->days     = $days; }
	function setTitle($title)      { $this->title    = $title; }
	function setPrevious(&$prev)   { $this->previous = &$prev; return $this; }
	function setNext(&$next)       { $this->next     = &$next;  return $this; } //www.unpaltda.com rocio molat
}

class programGrid
{
	var $grid;
	var $tree;
	
	function programGrid()
	{
		$this->grid        = array();
	}
	
	function append($day, $time, $title)
	{
		$tmp        = explode(":", $time);
		$time       = $tmp[0] * 60 + $tmp[1];
		
		$this->grid[$day][$time] = $title;
	}
	
	function makeTree()
	{
		for( $i=1; $i<=7; $i++ )
		{
			$first      = null;
			$current    = &$first;
			$next       = null;
			
			$current = new programBlock( );
			foreach( $this->grid[$i] as $time => $title )
			{
				if( $title != $current->previous->title )
				{
					for( $j=1; $j<=7-$i; $j++ )
					{
						if( $title == $this->grid[$i+$j][$time] && $title !== null)
						{
							$this->grid[$i+$j][$time] = null;
							$current->days++;
						}
						else
							break;
					}
	
					$current->setTitle( $title ); 
					$current->setDay( $i );
					$current->setStart( $time );
					
					if( $current->previous  ) $current->previous->setDuration( $time - $current->previous->getStart() );
					$next = new programBlock( );
					
					$current->setNext( $next );
					$next->setPrevious($current);

					unset( $current );
					$current = &$next;
					unset( $next );
				}
				else
				{
					for( $j=1; $j<=7-$i; $j++ )
					{
						if( $title == $this->grid[$i+$j][$time]  && $title !== null )
						{
							$this->grid[$i+$j][$time] = null;
						}
						else
							break;
					}					
				}
			}
			$end = &$current->previous;
			$end->setDuration( 1440 - $end->getStart() );
			$current = null;
			$this->tree[$i] = $first;
			unset( $first );
			unset( $end );
		}
	}
	
	function getProgram( $day, $time )
	{
		if( $this->grid[$day][$time] ) return $this->grid[$day][$time];
		else "";
	}
}


function thirdWeek( $timestamp )
{
	$first_monthday = date( "w", $timestamp );
	$days_array = array ( "1" => 14,
	                      "2" => 13,
	                      "3" => 12,
	                      "4" => 11,
	                      "5" => 10,
	                      "6" => 9,
	                      "0" => 8 );
	                      
	return $timestamp + ( $days_array[ $first_monthday ] *60*60*24 );
}

function timeToHour( $time )
{
		$minute = $time % 60;
		$hour   = ( ( $time - $minute ) / 60 ) % 12;
		$hour   = $hour == 0 ? "12" : $hour;
		
		return sprintf("%02d", $hour ) . ":" . sprintf("%02d", $minute );
}

$monday = thirdWeek( strtotime( "2006-08-01" ) );
$sql    = "SELECT * FROM slot WHERE channel = 141 AND date BETWEEN " . date( "'Y-m-d'", $monday ) . " AND " . date( "'Y-m-d'", ( $monday + 6*60*60*24 ) );

$result = db_query( $sql );
$grid   = new programGrid();
$date   = "";
$day    = 0;

while( $row = db_fetch_array( $result ) )
{
	if( $date != $row['date'] ) { $day++; $date = $row['date']; }
	$grid->append( $day, $row['time'], $row['title'] );
//	echo $row['date'] . " " . $row['time'] . " " . $row['title'] . "<br>\n";
}
$grid->makeTree();
/*print_r( $grid->grid );
print_r( $grid->tree ); exit;*/

require_once 'Spreadsheet/Excel/Writer.php';

$COL_OFFSET = 1;
$ROW_OFFSET = 1;

$workbook = new Spreadsheet_Excel_Writer();
$worksheet =& $workbook->addWorksheet();
$workbook->setVersion(8);

$worksheet->setColumn($COL_OFFSET,$COL_OFFSET,10);
$worksheet->setColumn($COL_OFFSET+8,$COL_OFFSET+8,10);
$worksheet->setColumn(1,8,22.33);

$format_center =& $workbook->addFormat();
$format_center->setAlign('center');
$format_center->setAlign('vcenter');
$format_center->setTextWrap(1);
$format_center->setBorder(1);
$format_center->setBorderColor("black");
$format_center->setFontFamily("Futura Book");
$format_center->setSize(18);

foreach( $grid->tree as $day )
{
	$i=0;
	while( $day && $i<100 )
	{
		$i++;
		$row = $day->getStart()/30 + $ROW_OFFSET + 1;
		$col = $day->getDay() + $COL_OFFSET;
		
		$time_arr[ $day->getStart() ] = timeToHour( $day->getStart() );
		
		$worksheet->write($row, $col, strtoupper( $day->getTitle() ), $format_center );	
		$worksheet->setRow($row,26);
		if( $day->getTitle() !== null )
		{
			$rows = $day->getDuration() / 30;
			if( $day->getDays() > 1  )
			{
				$worksheet->setMerge( $row, $col, $row+$rows-1, $col+$day->getDays()-1 );
				for( $i=1; $i< $day->getDays(); $i++ )
					$worksheet->write($row, $col+$i, "", $format_center );
				for( $i=0; $i< $rows; $i++ )
					$worksheet->write($row+$i, $col+$day->getDays()-1, "", $format_center );
				for( $i=1; $i< $rows; $i++ )
					$worksheet->write($row+$i, $col, "", $format_center );
			}
			else if( $day->getDuration() / 30 > 1 )
			{
				$worksheet->setMerge( $row, $col, $row+($day->getDuration()/30)-1, $col );			
				for( $i=1; $i< $rows; $i++ )
					$worksheet->write($row+$i, $col, "", $format_center );
			}
		}
		$day = $day->next;
	}
}



$format_title =& $workbook->addFormat();
$format_title->setAlign("center");
$format_title->setSize(18);
$format_title->setBorder(1);
$format_title->setBorderColor("black");
$format_title->setColor("white");
$format_title->setFgColor("red");
$format_title->setBold( );
$format_title->setFontFamily("Futura Book");

$i=0;
$worksheet->write($ROW_OFFSET+0, $COL_OFFSET+$i++, "HORA", $format_title );
$worksheet->write($ROW_OFFSET+0, $COL_OFFSET+$i++, "LUNES", $format_title );
$worksheet->write($ROW_OFFSET+0, $COL_OFFSET+$i++, "MARTES", $format_title );
$worksheet->write($ROW_OFFSET+0, $COL_OFFSET+$i++, "MIÉRCOLES", $format_title );
$worksheet->write($ROW_OFFSET+0, $COL_OFFSET+$i++, "JUEVES", $format_title );
$worksheet->write($ROW_OFFSET+0, $COL_OFFSET+$i++, "VIERNES", $format_title );
$worksheet->write($ROW_OFFSET+0, $COL_OFFSET+$i++, "SABADO", $format_title );
$worksheet->write($ROW_OFFSET+0, $COL_OFFSET+$i++, "DOMINGO", $format_title );
$worksheet->write($ROW_OFFSET+0, $COL_OFFSET+$i++, "HORA", $format_title );

$format_title->setNumFormat("hh:mm");
$last_time = 0;

ksort( $time_arr );
foreach( $time_arr as $start => $time )
{
	for($i=$last_time+30; $i< $start; $i+=30 )
	{
		$worksheet->write(1+($i/30)+$ROW_OFFSET, 0+$COL_OFFSET, timeToHour( $i ), $format_title );
		$worksheet->write(1+($i/30)+$ROW_OFFSET, 8+$COL_OFFSET, timeToHour( $i ), $format_title );
	}

	$worksheet->write(1+($start/30)+$ROW_OFFSET, 0+$COL_OFFSET, $time, $format_title );	
	$worksheet->write(1+($start/30)+$ROW_OFFSET, 8+$COL_OFFSET, $time, $format_title );	

	$last_time = $start;
}
for($i=$last_time+30; $i< 1440; $i+=30 )
{
		$worksheet->write(1+($i/30)+$ROW_OFFSET, 0+$COL_OFFSET, timeToHour( $i ), $format_title );
		$worksheet->write(1+($i/30)+$ROW_OFFSET, 8+$COL_OFFSET, timeToHour( $i ), $format_title );
}

$workbook->send('align.xls');
$workbook->close();

/*
foreach( $grid->tree as $day )
{
	$i=0;
	while( $day->next && $i<100 )
	{
		$i++;
		$row = $day->getStart()/30;
		$col = $day->getDay();
		
		echo $col . " " . $row . " " . $day->getTitle();
		
		if( $day->getDays() > 1 )
		{
			echo " merge " . $row . " " . $col . " " . ($row+($day->getDuration()/30)-1 ). " " . ($col+$day->getDays()-1);
		}
		else if( ( $day->getDuration() / 30 > 1 ) && ( $day->getTitle() !== null ) )
		{
			echo " merge " . $row . " " . $col . " " . ($row+($day->getDuration()/30)-1 ). " " . $col;
		}
		echo "<br>";
		$day = $day->next;
	}
}*/
?>