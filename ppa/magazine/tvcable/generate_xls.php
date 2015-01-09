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
	                      "0" => 15 );
	                      
	return $timestamp + ( $days_array[ $first_monthday ] *60*60*24 );
}

function timeToHour( $time )
{
		$minute = $time % 60;
		$hour   = ( ( $time - $minute ) / 60 ) % 12;
		$hour   = $hour == 0 ? "12" : $hour;
		
//		return sprintf("%02d", $hour ) . ":" . sprintf("%02d", $minute );
		return $hour . ":" . sprintf("%02d", $minute );
}

function timeFormat( $time )
{
		$hour     = substr( $time, 0, 2);
		$minute   = substr( $time, 3, 2);
		$meridian = ( $hour / 12 ) >=1 ? "PM" : "AM";

		$hour       = $hour % 12;
		$hour       = $hour == 0 ? "12" : $hour;
		
		return sprintf("%02d", $hour ) . ":" . sprintf("%02d", $minute ) . " " . $meridian;
}

function getIdealFontSize( $str, $rows, $cols )
{

	if( $str == "" ) return 18;
	$cols_weight = 1;
//	$rows_weight = ( $rows > 1 ) ? 1.5 : 1;
	$rows_weight = 1;
	$chars_num   = strlen( $str );
	
	$size        = round( $cols_weight * $rows_weight * $cols * $rows * 200 / $chars_num );

	if($size < 10) return 8;
	else if($size < 12) return 10;
	else if($size < 14) return 12;
	else if($size < 16) return 14;
	else if($size < 18) return 16;
	else return 18;
}

/**
 * Generate sheet1
 */

$COL_OFFSET = 1;
$ROW_OFFSET = 1;

$monday = thirdWeek( strtotime( $_GET['date'] . "-01" ) );

/*$sql    = "SELECT * FROM slot WHERE " .
  "channel = " . $_GET['id'] ." AND " .
  "date BETWEEN " . date( "'Y-m-d'", $monday ) . " AND " . date( "'Y-m-d'", ( $monday + 6*60*60*24 ) );
*/

//$sql = "SELECT slot.id id, slot.date, slot.time, slot.duration, ifnull(chapter.title, slot.title) title, ifnull(slot_chapter.chapter, slot.title) chapter FROM slot " .
$sql = "SELECT slot.id id, slot.date, slot.time, slot.duration, slot.title, ifnull(slot_chapter.chapter, slot.title) chapter FROM slot " .
  "LEFT JOIN  slot_chapter " .
  "ON slot.id = slot_chapter.slot " .
  "LEFT JOIN chapter " .
  "ON chapter.id = slot_chapter.chapter " .
  "WHERE " .
  "slot.channel = " . $_GET['id'] ." AND " .
  "slot.date BETWEEN " . date( "'Y-m-d'", $monday ) . " AND " . date( "'Y-m-d'", ( $monday + 6*60*60*24 ) ) . " " .
  "ORDER BY date, time";

$result = db_query( $sql );
$date   = "";
$day    = 0;
$grid   = new programGrid();
$grid->setName("main");

while( ( $row = db_fetch_array( $result ) ) )
{
	if( ereg( ":00:|:30:", $row['time'] ) ) // just o'clock and half programs
	{
		if( $date != $row['date'] )
		{ 
			$day++; 
			$date = $row['date'];
			unset( $dayBranch );
			$dayBranch = &$grid->createDay();
		}
		unset( $timeBranch );
		$timeBranch = &$dayBranch->createTime( $row['time'], $row['duration'], $row['title'] );
	} 
}

if( $grid->firstChild == null ) die( "no hay programación para el mes" );

$grid->fillFirstProgram();
$grid->groupByDay();

$workbook = new Spreadsheet_Excel_Writer();
$worksheet =& $workbook->addWorksheet("Grid " . $_GET['date'] );
$workbook->setVersion(8);

$worksheet->setColumn($COL_OFFSET,$COL_OFFSET,10);
$worksheet->setColumn($COL_OFFSET+8,$COL_OFFSET+8,10);
$worksheet->setColumn(1,8,22.33);

$format_array  = array( "HAlign" => "center", 
                        "VAlign" => "vcenter",
                        "TextWrap" => 1,
                        "Border" => 1,
                        "BorderColor" => "black",
                        "FontFamily" => "Futura Book" );

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
		$tmpDays     = $time->getAttribute("days");
		$tmpGrouped  = $time->getAttribute("grouped");

		$row = $tmpStart/30 + $ROW_OFFSET + 1;
		$col = $day->item + 1 + $COL_OFFSET;
		
		$time_arr[ $tmpStart ] = $tmpTime;

		$font_size = getIdealFontSize( $tmpTitle, $tmpDuration/30, $tmpDays );
		$worksheet->write($row, $col, strtoupper( $tmpTitle ), $format_center[$font_size] );
		$worksheet->setRow($row,26);
		if( $tmpGrouped !== "yes" )
		{
			$rows = $tmpDuration / 30;
			if( $tmpDays > 1  )
			{
				$worksheet->setMerge( $row, $col, $row+$rows-1, $col+$tmpDays-1 );
				for( $i=1; $i< $tmpDays; $i++ )
					$worksheet->write($row, $col+$i, "", $format_center[18] );
				for( $i=0; $i< $rows; $i++ )
					$worksheet->write($row+$i, $col+$tmpDays-1, "", $format_center[18] );
				for( $i=1; $i< $rows; $i++ )
					$worksheet->write($row+$i, $col, "", $format_center[18] );
			}
			else if( $tmpDuration / 30 > 1 )
			{
				$worksheet->setMerge( $row, $col, $row+($tmpDuration/30)-1, $col );			
				for( $i=1; $i< $rows; $i++ )
					$worksheet->write($row+$i, $col, "", $format_center[18] );
			}
		}
		$time = &$time->nextSibling;
	}
	$day = &$day->nextSibling;
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

	$worksheet->write(1+($start/30)+$ROW_OFFSET, 0+$COL_OFFSET, timeToHour( $start ), $format_title );	
	$worksheet->write(1+($start/30)+$ROW_OFFSET, 8+$COL_OFFSET, timeToHour( $start ), $format_title );	

	$last_time = $start;
}
for($i=$last_time+30; $i< 1440; $i+=30 )
{
		$worksheet->write(1+($i/30)+$ROW_OFFSET, 0+$COL_OFFSET, timeToHour( $i ), $format_title );
		$worksheet->write(1+($i/30)+$ROW_OFFSET, 8+$COL_OFFSET, timeToHour( $i ), $format_title );
}

/**
 * Generate sheet2
 */
if( is_array( $_POST['specials'] ) ) 
{
	$specials = implode(",", $_POST['specials'] );
	$sql        = "SELECT date,time,title FROM slot WHERE id IN (" . $specials . ") ORDER BY date, time";
	$result     = db_query( $sql );
	$worksheet2 = &$workbook->addWorksheet("Specials");
	$ss_row     = 0;
	$ss_col     = -4;
	
	$format_left =& $workbook->addFormat();
	$format_left->setBorder(1);
	$format_left->setBorderColor("black");
	$format_left->setFontFamily("Futura Book");
	$format_left->setSize(14);
	
	unset( $format_title );
	$format_title =& $workbook->addFormat();
	$format_title->setAlign("center");
	$format_title->setSize(14);
	$format_title->setBorder(1);
	$format_title->setBorderColor("black");
	$format_title->setColor("white");
	$format_title->setFgColor("red");
	$format_title->setBold( );
	$format_title->setFontFamily("Futura Book");
	
	while( $row = db_fetch_array( $result ) )
	{
		if( $ss_row % 10 == 0 )
		{
			$ss_row=0;
			$ss_col+=4;
			$worksheet2->setColumn($COL_OFFSET+$ss_col,$COL_OFFSET+$ss_col,4.25);
			$worksheet2->setColumn($COL_OFFSET+$ss_col+1,$COL_OFFSET+$ss_col+1,28.13);
			$worksheet2->setColumn($COL_OFFSET+$ss_col+2,$COL_OFFSET+$ss_col+2,10.38);
			$worksheet2->setColumn($COL_OFFSET+$ss_col+3,$COL_OFFSET+$ss_col+3,1);
	
			$worksheet2->write( $ROW_OFFSET+$ss_row, $COL_OFFSET+$ss_col, "DIA", $format_title );
			$worksheet2->write( $ROW_OFFSET+$ss_row, $COL_OFFSET+$ss_col+1, "ESPECIAL", $format_title );
			$worksheet2->write( $ROW_OFFSET+$ss_row, $COL_OFFSET+$ss_col+2, "HORA", $format_title );
			$ss_row++;
		}		
		$worksheet2->write( $ROW_OFFSET+$ss_row, $COL_OFFSET+$ss_col, substr($row['date'],-2,2), $format_title );
		$worksheet2->write( $ROW_OFFSET+$ss_row, $COL_OFFSET+$ss_col+1, $row['title'], $format_left );
		$worksheet2->write( $ROW_OFFSET+$ss_row, $COL_OFFSET+$ss_col+2, timeFormat( $row['time'] ), $format_title );
		$ss_row++;
	}
}	

$sql = "SELECT name FROM channel WHERE id=" . $_GET['id'];
$row = db_fetch_array( db_query( $sql ) );

$workbook->send( $row['name'] . "-" . date( "Y-m-d", $monday ) . ".xls" );
$workbook->close();
?>