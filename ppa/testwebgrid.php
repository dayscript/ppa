<?
error_reporting(E_ERROR);
include('ppa/include/config.inc.php');
include('ppawebgrid/class/ChannelGrid.class.php');
include('ppawebgrid/class/SlotChapters.class.php');
include('include/db.inc.php');
$appDur=time();

define("DAY", 60*60*24);
define("CELLTIME", 30);
define("TELMEX", 2);
define("UNE", 3);
define("ZONAWOW", 4);
define("INTER", 5);

$PROVIDER  = (isset( $_GET['provider'] ) && $_GET['provider'] > 0 ) ? $_GET['provider'] : TELMEX;
$print_type = isset($_GET["print_type"])? $_GET["print_type"] : "screen";
$print_mode = isset($_GET["print_mode"])? $_GET["print_mode"] : "date";

if($PROVIDER == INTER)
{
	define("GRID", 6);
	define("GMT", -4.5);
}
else
{
	define("GRID", 4);
	define("GMT", -5);
}

if( isset( $_GET['header'] ) )
	$ID_CLIENT = $_GET['header'];
else if($PROVIDER == UNE)
	$ID_CLIENT = 78;
else if($PROVIDER == ZONAWOW)
	$ID_CLIENT = 65;
else if($PROVIDER == INTER)
	$ID_CLIENT = 26;
else
	$ID_CLIENT = 78;
	
$CITY_NAME = ucfirst( $_GET["cityname"] );

$time  = isset( $_GET['start_date'] ) ? customStrToTime( $_GET['start_date'] ) : (time()+((GMT+5)*60*60)); //+5 because COT is the reference time in PPA
$time += ( $_GET['offset']*60*60*GRID/2 );

$etime  = isset( $_GET['end_date'] ) ? customStrToTime( $_GET['end_date'] ): $time+(DAY*30*6);

/*helper functions*/
function xmlentities($str)
{
	$str = str_replace("&", "&amp;", $str);
	return $str;
}

function fixHour( $str )
{
	$time 	= explode(":", $str);
	$minute	= sprintf("%d", ( $time[1] / 30 )) * 30;
	
	return $time[0] .":". sprintf("%02d", $minute);
}

function to12H( $str )
{
	$str   = explode(":", $str);
	$hours = $str[0] % 12 == 0 ? 12 : $str[0] % 12;
	return $hours .":". 
		(!isset($str[1])?"00":$str[1]) ." ". 
		($str[0]==12 && $str[1]==0 ? "m" : ( $str[0] / 12 >= 1 ? "pm" : "am" ) );
}

function customStrToTime($str)
{
	$a = explode(" ", $str);
	$d = explode("/",$a[0]);
	$t = explode(":",$a[1]);
//	$t[0] = eregi("p.*m.*", $a[2]) ? $t[0]+12 : $t[0];
	$t[0] = eregi("p.*m.*", $a[2]) ? ($t[0]==12?$t[0]:$t[0]+12) : ($t[0]==12?0:$t[0]);
	
	return mktime($t[0], $t[1], 0, $d[1], $d[0], $d[2]);
}
function fixGmtTime($strDate, $gmt)
{
	$secs = ($gmt+5)*60*60; //+5 because COT is the reference time in PPA
	$time = strtotime($strDate) + $secs;
	$r["date"] = date("Y-m-d", $time);
	$r["time"] = date("H:i:00", $time);
	return $r;
}

function hourToMinutes($str)
{
	$tmp = explode(":",$str);
	return ($tmp[0] * 60) +	$tmp[1];
}
/*helper functions*/


/*
 * router
 */
if( isset( $_GET['action'] ) && $_GET['action'] == "highlights"  )
{
	include( "ppawebgrid/highlights.php" );
	exit;
}
if( isset( $_GET['action'] ) && $_GET['action'] == "xsearch" )
{
//	if( $print_type=="printer" || (isset( $_GET['channel'] ) && (isset( $_GET['title'] ) || isset( $_GET['actor'] ) ) ) )
	if( $print_type=="printer" || count(explode(",",$_GET['channel']))==2 || ( ( isset( $_GET['title'] ) || isset( $_GET['actor'] ) ) ) )
	{
		include( "ppawebgrid/xsearch_develop.php" );
		exit;
	}
}
if( isset( $_GET['action'] ) && $_GET['action'] == "xsearch_dev" )
{
	if( isset( $_GET['channel'] ) || isset( $_GET['title'] ) || isset( $_GET['actor'] ) )
	{
		include( "ppawebgrid/xsearch_develop.php" );
		exit;
	}
}
if( isset( $_GET['action'] ) && $_GET['action'] == "channel_info" )
{
	include( "ppawebgrid/channel_info.php" );
	exit;
}
if( isset( $_GET['action'] ) && $_GET['action'] == "filters"  )
{
	include( "ppawebgrid/filters.php" );
	exit;
}
else if( isset( $_GET['action'] ) && $_GET['action'] == "channel" )
{
	include( "ppawebgrid/channel.php" );
	exit;
}
else if( isset( $_GET['action'] ) && $_GET['action'] == "actor" )
{
	include( "ppawebgrid/actor.php" );
	exit;
}
else if( isset( $_GET['action'] ) && $_GET['action'] == "title" )
{
	include( "ppawebgrid/title.php" );
	exit;
}
else if( isset( $_GET['action'] ) && $_GET['action'] == "synopsis" && $_GET['slot'] != ""  )
{
	include( "ppawebgrid/synopsis_dev.php" );
	exit;
}
else if( isset( $_GET['action'] ) && $_GET['action'] == "chn_list" )
{
	include( "ppawebgrid/channel_list.php" );
	exit;
}

//$_GET['category'] = $_GET['category'] != "" ? $_GET['category'] : "nacionales";
$yesterday = date("Y-m-d", $time - 86400);
$today = date("Y-m-d", $time);
$tomorrow  = date("Y-m-d", $time + 86400);

if( isset( $_GET['channel'] ) && $_GET['channel'] != "" )
	$categoryStr = "AND client_channel.channel in ( " . substr($_GET['channel'],0,-1) . " ) ";
else
	$categoryStr = ( $_GET['category'] != "" ) ? ( "AND client_channel._group like '" . $_GET['category'] . "' " ) : "";

$sql = "SELECT client.name head, client_channel.number, ifnull(client_channel.custom_shortname, channel.shortname) shortname, channel.name, channel.logo, channel.id ".
       "FROM channel, client, client_channel ".
       "WHERE client.id = client_channel.client ". 
       "AND channel.id=client_channel.channel ".
       "AND client.id = ". $ID_CLIENT ." ".
       $categoryStr . 
       "ORDER BY client_channel.number";

$channels = array();
$result = db_query($sql);
while( $row = db_fetch_array($result) )
{
	$channel = new ChannelGrid();
	$channel->setNumber( $row['number'] );
	$channel->setName( $row['name'] );
	$channel->setShortName( $row['shortname'] );
	$channel->setLogo( $row['logo'] );
	$channel->setIdChannel( $row['id'] );
	$channels[] = $channel;
}

$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.name, slot.time, slot.title, slot.date, slot.id ".
       "FROM channel, client, client_channel, slot ".
       "WHERE client.id = client_channel.client ". 
       "AND channel.id=client_channel.channel ".
       "AND client.id = ". $ID_CLIENT ." ".
       "AND slot.channel = channel.id ".
       "AND slot.date BETWEEN '". $yesterday ."' and '". $tomorrow ."' ".
       $categoryStr . 
       "ORDER BY client_channel.number, slot.date, slot.time ";

$result = db_query($sql);

$row    = db_fetch_array($result);

if( empty( $channels ) ) die("<div></div>");

reset($channels);

$arr_channels = array();
$slots        = array();

while( current($channels) )
{
  $channel = current($channels);
  for(; $row['number'] == $channel->getNumber(); $row = db_fetch_array($result))
  {
  	$fixedTime = fixGmtTime($row["date"] . " " . $row["time"],GMT);
  	$channel->appendProgram( $fixedTime['date'], substr($fixedTime['time'], 0,5), ucwords(strtolower( $row['title']) ), $row['id'] );
  	$slots[] = $row['id'];
  }
   $arr_channels[] = $channel;
   next($channels);
}

$chapters = new SlotChapters( $slots );
$search_string = "<b>Fecha y hora:</b> " . date("d/m/Y h:i a", $time) . " hasta " . date("d/m/Y h:i a", $etime) .
	($category==""?"":" / <b>Género:</b> ".$category." ");

$startHour = fixHour( date("H:i", $time ) );
$startHourMinutes = fixHour( $startHour );

$startMod = hourToMinutes($startHour);
$wraperWidth = 656;
$minuteWidth  = $wraperWidth/(GRID*CELLTIME);
?>
<div class="city_title"><div class="left city_name"><?=$CITY_NAME?></div><div class="right">Imprimir: <a href="#;" onclick="GridApp.forPrintGrid('date')">fecha</a> | <a href="#;" onclick="GridApp.forPrintGrid('channel')">canal</a></div><img class="right" src="http://<?=$URL_PPA?>/ppa/jsapps/inter/images/printer.jpg" /></div>
<div class="search_string"><b>Resultados para:</b><br/><?=$search_string?></div>
<div class="gc">
	<div class="schedule">
	<div class="chn"><h1>CANAL</h1></div>
	<div class="larrow" onclick="GridApp.goNext(-1)"></div>
<?
$arrowWidth = 15;
$lastModInPixles=$arrowWidth;
for($i=0;$i < GRID-1; $i++)
{
	$modInPixels=round(CELLTIME*($i+1)*$minuteWidth);
	$cellwidth=$modInPixels-$lastModInPixles-1;
	$lastModInPixles=$modInPixels;
?>
	<div style="width:<?=$cellwidth?>px;"><h1><?=to12H(fixHour( date("H:i", $time+(CELLTIME*$i*60)))) ?></h1></div>
<? }
	$modInPixels=round(CELLTIME*($i+1)*$minuteWidth);
	$cellwidth=$modInPixels-$lastModInPixles-1-$arrowWidth;
	$lastModInPixles=$modInPixels;
?>
	<div style="width:<?=$cellwidth?>px;"><h1><?=to12H(fixHour( date("H:i", $time+(CELLTIME*$i*60)))) ?></h1></div>
	<div class="rarrow" onclick="GridApp.goNext(1)"></div>
</div>
<?
foreach($arr_channels as $channel)
{
	$prgs = $channel->getProgramsBySchedule($today, $startHour, (GRID*CELLTIME));
	if(($timeLength = hourToMinutes( $prg["hour"] ) - $startHourMinutes)<0)
		$timeLength = hourToMinutes( $prg["hour"] );
	
	$count=count($prgs);
	$lastModInPixles=0;
?>
	<div class="gc_line">
	<div class="ppa_chn_info"><img src="<?=( $channel->getLogo() != "" ? "http://" . $URL_PPA . "/ppa/ppa/" . $channel->getLogo() : "http://" . $URL_PPA . "/ppa/images/empty.gif" )?>"/><div class="ppa_chn_name"><?= xmlentities($channel->getShortName()) ."<br/>No.<span>". $channel->getNumber() . "</span>"?></div></div>
<?
//print_r($prgs);
	if($count==0)
	{
?>
		<div style="width:<?=$wraperWidth-1 ?>px;"><h2>Programación <?=$channel->getName()?></h2><h3></h3></div>
<?		
	}
	else
	for($i=0; $i < $count; $i++)// $prgs as $prg)
	{
		$relMod=(isset($prgs[$i+1]["mod"]) ? $prgs[$i+1]["mod"]-$startMod : (GRID*CELLTIME));
		$modInPixels=round($relMod*$minuteWidth);
		$cellwidth=$modInPixels-$lastModInPixles-1;
		$duration=$cellwidth/$minuteWidth;
/*		<div style="width:<?=$cellwidth ?>px;"><h2><?=$prgs[$i]["title"]?></h2><h3>[<?=$cellwidth . " = " . $modInPixels . "->" . $lastModInPixles ?>]</h3></div>*/
?>
		<div title="[<?=to12H($prgs[$i]["hour"])?>-<?=to12H($prgs[$i]["end"])?>] <?=$prgs[$i]["title"]?>" onmouseover="GridApp.hover(this,'graybg')" onmouseout="GridApp.hout(this)" onmouseup="GridApp.synopsisPopUp('<?=$prgs[$i]["id_slot"]?>', event);" style="width:<?=$cellwidth ?>px;">
<? if($duration >= 15) { ?>
			<h2><?= $prgs[$i]["title"] ?></h2><h3>[<?=to12H($prgs[$i]["hour"])?>-<?=to12H($prgs[$i]["end"])?>]</h3></div>
<? } else { ?>
			<h2><img src="http://<?=$URL_PPA?>/ppa/jsapps/inter/images/icon_tv.jpg"/></h2></div>
<?
	}
		$lastModInPixles=$modInPixels;
//		echo $prgs[$i]["hour"] . " - " . $prgs[$i]["title"] . " - " . $prgs[$i]["mod"] . " <br> ";
	}
echo "</div>";
//	print_r($prgs);
}
?>
</div>
<?=exit;?>
<div>
<div class="city_title"><div class="left city_name"><?=$CITY_NAME?></div><div class="right">Imprimir: <a href="#;" onclick="GridApp.forPrintGrid('date')">fecha</a> | <a href="#;" onclick="GridApp.forPrintGrid('channel')">canal</a></div><img class="right" src="http://<?=$URL_PPA?>/ppa/jsapps/inter/images/printer.jpg" /></div>
<table class="cat_table" width="100%" border="0" cellpadding="0" cellspacing="1">
	<tr class="cat_td_line_1"><td colspan="<?=GRID+1?>" style="text-align:center"><b>Resultados para:</b><br/><?=$search_string?></td></tr>
  <tr class="cat_table_title">
    <th class="cat_table_title"><div class="chn_title">CANAL</div></th>
<?
for($i=0; $i<GRID; $i++)
{
	$gobackstr = "";
	$gonextstr = "";
	if( $i==0 ){ $gobackstr = "<img class=\"goback\" onclick=\"GridApp.goNext(-1)\" src=\"http://" . $URL_PPA . "/ppa/jsapps/inter/images/goback.jpg\" />"; }
	if( $i==GRID-1 ){ $gonextstr = "<img class=\"gonext\" onclick=\"GridApp.goNext(1)\" src=\"http://" . $URL_PPA . "/ppa/jsapps/inter/images/gonext.jpg\" />"; }

	$fh[$i] = fixHour( date("H:i", $time + ( 1800 * ( $i ) ) ) );
?>
    <th class="cat_table_title" width="<?=100/(GRID) ."%"?>" ><?=$gobackstr ?><div class="hour"><?=to12H( $fh[$i] )?></div><?=$gonextstr ?></th>
<?
}
?>
  </tr>
<?
$line = 1;
//print_r($arr_channels);
foreach($arr_channels as $channel)
{
///	echo $channel->getShortName();
//echo $channel->getName();
$line++;
/*  <td onclick="document.getElementById('ppa_channel').value = '<?=$channel->getIdChannel()?>,';GridApp.consultGrid()" class="<?= ($line%2) == 0 ? "cat_td_title_1" : "cat_td_title_2" ?>">*/
?>
  <tr>
  <td onclick="#;" class="<?= ($line%2) == 0 ? "cat_td_title_1" : "cat_td_title_2" ?>"
  	<div class="ppa_chn_info"><img src="<?=( $channel->getLogo() != "" ? "http://" . $URL_PPA . "/ppa/ppa/" . $channel->getLogo() : "http://" . $URL_PPA . "/ppa/images/empty.gif" )?>"/><div class="ppa_chn_name"><?= xmlentities($channel->getShortName()) ."<br/>No.<span>". $channel->getNumber() . "</span>"?></div></div></td>
<?
	$colspan = 1;
	for($i=0; $i<GRID; $i++)
	{
//		$title = $channel->getProgramBySchedule( $today, $fh[$i] );
		$prog = $channel->getProgramsBySchedule( $today, $fh[$i] );
		$title = $prog[0];
		$prg_start = to12H($prog[1]);
		$prg_end = to12H($prog[2]);
		
		$next_title = $channel->getProgramsBySchedule($today, $fh[$i+1] );

//		if(  $title == $next_title && ($i+1)<GRID)
		if(  $title == $next_title[0] && ($i+1)<GRID)
		{
			$colspan++;
		}
		else
		{
			$tmp = explode( "|||", $title ); //$title == id|||title
?>			
    <td onmouseover="GridApp.hover(this)" onmouseout="GridApp.hout(this)" onmouseup="GridApp.synopsisPopUp('<?=$tmp[0]?>', event);" <?=$colspan > 1 ? "colspan=\"". $colspan ."\"" : ""?> class="cat_td_line_1<?=" " . $chapters->getClassName($tmp[0])?>" title="<?=$prg_start . " " . xmlentities($tmp[1])?>" >
    	<div class="program"><?=xmlentities($tmp[1])?></div><div class="gender" style="font-family:serif;font-size:xx-small;color:#ccc;">[<?=$prg_start ."-". $prg_end/*=xmlentities($chapters->getGender($tmp[0]))*/?>]</div></td>
<?
			$colspan = 1;
		}
	}
?>
  </tr>
<?
}
$line++;
echo ($appDur . " " . time());
echo "<br>" . ( time()-$appDur );
?>
</table>
</div>