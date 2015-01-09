<?
require_once "ppa11/class/ProgramGrid.class.php";
require_once "Spreadsheet/Excel/Writer.php";

/**
 * funciones
 */
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

function timeFormat( $time )
{
		$hour     = substr( $time, 0, 2);
		$minute   = substr( $time, 3, 2);
		$meridian = ( $hour / 12 ) >=1 ? "PM" : "AM";

		$hour       = $hour % 12;
		$hour       = $hour == 0 ? "12" : $hour;
		
//		return sprintf("%02d", $hour ) . ":" . sprintf("%02d", $minute ) . " " . $meridian;
		return $hour . ":" . sprintf("%02d", $minute );
}

function getIdealFontSize( $str, $cols )
{
	global $ROW_SIZE;
	if( $str == "" || $cols == 0 ) return 18;
	$chars_num   = strlen( $str );
	
	$size        = floor( $chars_num / $cols );
	
	if( $ROW_SIZE == 56 )
	{
		if( $size <= 12 ) return 18;
		else if($size <= 14) return 16;
		else if($size <= 21) return 14;
		else if($size <= 27) return 12;
		else if($size <= 44) return 10;
		else return 10;
	}
	else
	{
		if( $size <= 6 ) return 18;
		else if($size <= 7) return 16;
		else if($size <= 8) return 14;
		else if($size <= 18) return 12;
		else if($size <= 22) return 10;
		else return 10;
	}
}

/**
 * Generate sheet1
 */

$COL_OFFSET  = 1;
$ROW_OFFSET  = 1;
$GRID_CENTER = 13+$COL_OFFSET;
$DAY         = 60*60*24;
$ROW_SIZE    = 43;
$COL_SIZE    = 11.5;
$date        = explode( "-", $_GET['date'] );
$start_date  = mktime( 0, 0, 0, $date[1], 0, $date[0] );
$end_date    = mktime( 0, 0, 0, $date[1]+1, 1, $date[0] );

if( $_GET['group'] == 'culturales'){
  $id_channels = "95, 57, 505, 73, 98, 327";
}elseif( $_GET['group'] == 'deportes'){
  $id_channels = "780, 66, 77, 199, 461";
}elseif( $_GET['group'] == 'series'){
  $id_channels = "311, 103, 446, 30";
}elseif( $_GET['group'] == 'cine'){
  $id_channels = "116, 131, 108, 81, 110, 43";
}elseif( $_GET['group'] == 'internacionales'){
  $id_channels = "114, 198, 63";
}elseif( $_GET['group'] == 'ellosyellas'){
  $id_channels = "364, 586";
}elseif( $_GET['group'] == 'premium'){
  $id_channels = "123, 132, 133, 124, 125";
  $ROW_SIZE = 56;
}elseif( $_GET['group'] == 'musicales'){
  $id_channels = "302, 299, 83";
  $ROW_SIZE = 56;
}elseif( $_GET['group'] == 'infantiles'){
  $id_channels = "126, 60, 96, 76, 49";
  $ROW_SIZE = 56;
}elseif( $_GET['group'] == 'cyv'){
  $id_channels = "79, 59, 61, 141, 56, 331, 84, 447";
  $ROW_SIZE = 56;
}elseif( $_GET['group'] == 'hogar'){
  $id_channels = "31, 55, 50, 117, 312, 337";
  $ROW_SIZE = 56;
}elseif( $_GET['group'] == 'religiosos'){
  $id_channels = "70, 64";
  $ROW_SIZE = 56;
}

$sql = "SELECT channel.id, channel.name FROM channel, client, client_channel " .
	" WHERE " .
	" client.id = client_channel.client AND " .
	" channel.id = client_channel.channel AND " .
	" client.id = 86 AND " .
	" channel.id IN ( " . $id_channels . " ) " .
	"ORDER BY client_channel.number";

$result = db_query( $sql );
while( ( $row = db_fetch_array( $result ) ) )
{
	$channels[$row['id']] = $row['name'];
}

$sql = "SELECT slot.id id, slot.date, slot.time, slot.duration, slot.title, ifnull(slot_chapter.chapter, slot.title) chapter, slot.channel FROM slot " .
  "LEFT JOIN  slot_chapter " .
  "ON slot.id = slot_chapter.slot " .
  "LEFT JOIN chapter " .
  "ON chapter.id = slot_chapter.chapter " .
  "WHERE " .
  "slot.duration >= 10 AND " .
  "slot.channel IN ( " . $id_channels ." ) AND " .
  "slot.date BETWEEN " . date( "'Y-m-d'", $start_date ) . " AND " . date( "'Y-m-d'", $end_date ) . " " .
  "ORDER BY channel, date, time";

$result = db_query( $sql );
$date   = "";
$channel= "";
$day    = 0;
$grid   = array();

while( ( $row = db_fetch_array( $result ) ) )
{
	if( !isset( $grid[ $row['channel'] ] ) ) 
	{
		$grid[ $row['channel'] ] = new programGrid();
		$grid[ $row['channel'] ]->setName("main");
	}

	if( $date != $row['date'] )
	{ 
		$day++; 
		$date = $row['date'];
		unset( $dayBranch );
		$dayBranch = &$grid[ $row['channel'] ]->createDay();
		$dayBranch->setAttribute( "date", $row['date'] );
	}
	unset( $timeBranch );
	$timeBranch = &$dayBranch->createTime( $row['time'], $row['duration'], $row['title'] );
	$timeBranch->setAttribute( "date", $row['date'] );
	$timeBranch->setAttribute( "chapter", $row['chapter'] );
}

$workbook = new Spreadsheet_Excel_Writer();
$workbook->setVersion(8);

$format_array  = array( "HAlign" => "left", 
                        "VAlign" => "vcenter",
                        "TextWrap" => 1,
//                        "FgColor" => "white",
//                        "Border" => 1,
//                        "BorderColor" => "black",
                        "FontFamily" => "Futura Condensed" );

$format_center[18] =& $workbook->addFormat( $format_array );
$format_center[18]->setSize(18);

$format_center[16] =& $workbook->addFormat( $format_array );
$format_center[16]->setSize(16);

$format_center[14] =& $workbook->addFormat( $format_array );
$format_center[14]->setSize(14);

$format_center[12] =& $workbook->addFormat( $format_array );
$format_center[12]->setSize(12);

$format_center[10] =& $workbook->addFormat( $format_array );
$format_center[10]->setSize(10);

$format_center[8] =& $workbook->addFormat( $format_array );
$format_center[8]->setSize(8);

foreach( $channels as $key => $value )
{
	if( $grid[$key]->firstChild == null ) die( "Alguno de los canales no tiene programación" );
	$grid[$key]->fillFirstProgram();
	$grid[$key]->groupByHour();
}

$i=0;
unset($worksheet);
$worksheet = &$workbook->addWorksheet( $_GET['date'] );
$worksheet->setColumn($COL_OFFSET,26, $COL_SIZE);

for( $i_date = $start_date + $DAY; $i_date < $end_date; $i_date+=$DAY )
{
	$worksheet->write($ROW_OFFSET+$i, $COL_OFFSET, date("Y-m-d", $i_date), $format_center[18] );	
	for( $j=0; $j < 24; $j++)
	{
		$col = 1+$j;
		if( $j > 11 ) $col++;
		$worksheet->write($ROW_OFFSET+$i, $COL_OFFSET+$col, timeFormat($j . ":00"), $format_center[18] );	
	}

	foreach( $channels as $key => $value )
	{
		unset( $day );
		unset( $time );
		$day  = &$grid[$key]->getElementByDate( date("Y-m-d", $i_date) );
	
		$time = &$day->firstChild;
		$row  = $i+$ROW_OFFSET+1;
		$worksheet->write($row, $COL_OFFSET, $value, $format_center[18] );
		$worksheet->setRow($row,$ROW_SIZE);
	
		$i++;
		$title = array();
		while( $time )
		{
			$tmpDuration = $time->getAttribute("duration");
			$tmpStart    = $time->getAttribute("start");
			$tmpTime     = $time->getAttribute("time");
//			$tmpTitle    = timeFormat( $tmpTime ) . " " . ucwords( $time->getAttribute("title") );
			$tmpTitle    = timeFormat( $tmpTime ) . " " . $time->getAttribute("title");
			$tmpGrouped  = $time->getAttribute("grouped");
	
			if( $tmpGrouped !== "yes" )
			{		
				$col         = 1 + $COL_OFFSET + floor( $tmpStart / 60 );
				$col         = $col >= $GRID_CENTER ? ($col+1) : $col;
				$title[$col] = $title[$col] == "" ? $tmpTitle : $title[$col] . "\n" . $tmpTitle;
		
				$end_col   = 1 + $COL_OFFSET + floor( ( $tmpStart + $tmpDuration ) / 60 );
				$end_col   = $end_col > $GRID_CENTER ? ($end_col+1) : $end_col;
		
				if( $col < $GRID_CENTER && $end_col > $GRID_CENTER )
				{
					$font_size = getIdealFontSize( $title[$col], $GRID_CENTER-$col );
					if( $col != ( $GRID_CENTER - 1 ) )
					{
						for( $j=$col+1; $j <= ($GRID_CENTER-1); $j++ )
							$worksheet->writeBlank($row, $j, $format_center[10] );

						$worksheet->setMerge( $row, $col, $row, $GRID_CENTER-1 );
					}
					$worksheet->write($row, $col, $title[$col], $format_center[$font_size] );
					
					$font_size = getIdealFontSize( $title[$col] . " (cont)", $end_col-$GRID_CENTER-1 );
					if( ($end_col-1) != ( $GRID_CENTER+1 ) )
					{
						for( $j=$end_col; $j <= ($GRID_CENTER+1); $j++ )
							$worksheet->writeBlank($row, $j, $format_center[10] );

						$worksheet->setMerge( $row, $GRID_CENTER+1, $row, $end_col-1 );
					}
//					$worksheet->write($row, $GRID_CENTER+1, $title[$col] . " (cont)" , $format_center[$font_size] );
					$worksheet->write($row, $GRID_CENTER+1, $tmpTitle . " (cont)" , $format_center[$font_size] );
				}
				else
				{
					if( $time->getAttribute( "cont" ) )
						$title[$col] .= " (cont)";
	
					$font_size = getIdealFontSize( $title[$col], $end_col-$col );	
					if( ($end_col-1) > $col )
					{
						for( $j=$col+1; $j <= ($end_col-1); $j++ )
							$worksheet->writeBlank($row, $j, $format_center[10] );

						$worksheet->setMerge( $row, $col, $row, ($end_col-1) );
					}
	
					$worksheet->write($row, $col, $title[$col], $format_center[$font_size] );
				}
			}
			$time = &$time->nextSibling;
		}	
	}
	$i+=2;
}

$workbook->send( $_GET['group'] . "-" . $_GET['date'] . ".xls" );
$workbook->close();
?>