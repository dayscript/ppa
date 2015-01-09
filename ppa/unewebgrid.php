<?
error_reporting(E_ERROR);
include('ppa/include/config.inc.php');
include('include/db.inc.php');

define("DAY", 60*60*24);
define("TELMEX", 2);
define("UNE", 3);
define("ZONAWOW", 4);
define("INTER", 5);
$PROVIDER  = (isset( $_GET['provider'] ) && $_GET['provider'] > 0 ) ? $_GET['provider'] : TELMEX;

if($PROVIDER == INTER)
	define("GRID", 6);
else
	define("GRID", 4);

//$ID_CLIENT = (isset( $_GET['header'] ) && $_GET['header'] > 0 ) ? $_GET['header'] : 78 ;
if( isset( $_GET['header'] ) )
{
	$h = $_GET['header'];
	if(ereg("^[0-9]+$",$h))
		$ID_CLIENT = $h;
	else
	{
		$sql="SELECT id FROM client WHERE name like '" . $h . "'";
		$row = db_fetch_array(db_query($sql));
		if($row) $ID_CLIENT = $row["id"];
	}
}
else if($PROVIDER == UNE)
	$ID_CLIENT = 78;
else if($PROVIDER == ZONAWOW)
	$ID_CLIENT = 65;
else if($PROVIDER == INTER)
	$ID_CLIENT = 26;
else
	$ID_CLIENT = 78;

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
	return $hours .":". $str[1] ." ". ( $str[0] / 12 >= 1 ? "pm" : "am" );
}

if( isset( $_GET['action'] ) && $_GET['action'] == "highlights"  )
{
	include( "ppawebgrid/highlights.php" );
	exit;
}
if( isset( $_GET['action'] ) && $_GET['action'] == "xsearch" )
{
	if( isset( $_GET['channel'] ) || isset( $_GET['title'] ) || isset( $_GET['actor'] ) )
	{
		include( "ppawebgrid/xsearch.php" );
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
if( isset( $_GET['action'] ) && $_GET['action'] == "channel_info"  )
{
	include( "ppawebgrid/channel_info.php" );
	exit;
}
if( isset( $_GET['action'] ) && $_GET['action'] == "filters"  )
{
	include( "ppawebgrid/filters.php" );
	exit;
}
else if( isset( $_GET['action'] ) && $_GET['action'] == "channel"  )
{
	include( "ppawebgrid/channel.php" );
	exit;
}
else if( isset( $_GET['action'] ) && $_GET['action'] == "actor"  )
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
	include( "ppawebgrid/synopsis.php" );
	exit;
}
else if( isset( $_GET['action'] ) && $_GET['action'] == "chn_list" )
{
	include( "ppawebgrid/channel_list.php" );
	exit;
}
class ChannelGrid
{
	var $name;
	var $shortname;
	var $number;
	var $program;
	var $logo;
	var $idChannel;
	
	function Channel()
	{
		$this->name      = "";
		$this->shortname = "";
		$this->logo      = "";
		$this->idChannel = 0;
		$this->program   = Array();		
	}
	
	/***********************
	*  SETS
	***********************/
	
	function setName( $str )
	{
		$this->name = $str;
	}

	function setNumber( $str )
	{
		$this->number = $str;
	}

	function setShortName( $str )
	{
		$this->shortname = $str;
	}

	function setLogo( $str )
	{
		$this->logo = $str;
	}

	function setIdChannel( $id )
	{
		$this->idChannel = $id;
	}

	/***********************
	*  GETS
	***********************/

	function getName()
	{
		return $this->name;
	}

	function getNumber()
	{
		return $this->number;
	}

	function getShortName()
	{
		return $this->shortname;
	}

	function getLogo()
	{
		return $this->logo;
	}
	
	function getIdChannel()
	{
		return $this->idChannel;
	}
	
	function getProgramBySchedule( $search_date, $search_hour )
	{
		$find = -1;
		if(is_array($this->program[$search_date]))
		{
			foreach($this->program[$search_date] as $hour => $title)
			{
				if( $hour > $search_hour )
					if($find == -1)
						return $this->getProgramBySchedule(date("Y-m-d", strtotime($search_date, -86400)), "23:59");
					else
						return $find;
				else
					$find = $title;
			}
			return $find;
		}
		return "0|||Programación Sin Confirmar";
	}

	/***********************
	*  
	***********************/

	function appendProgram( $date, $hour, $title, $slot )
	{
		$this->program[$date][$hour] = $slot . "|||" . $title;
	}
}

class SlotChapters
{
	var $slots;
	var $classNames;

	function SlotChapters( $slots )
	{
		$this->slots = array();
		if(!empty( $slots ) )
			$this->load( $slots );
			
		$this->classNames['Infantil'] = "children";
		$this->classNames['Deportes'] = "sports";
		$this->classNames['Movie']    = "movie";
	}
	
	function load( $slots )
	{
		$inStr = implode( ",", $slots );
		
		$sql = "SELECT slot_chapter.slot, serie.gender ".
		       "FROM chapter, slot_chapter, serie ".
		       "WHERE chapter.id = slot_chapter.chapter ". 
		       "AND serie.id = chapter.serie ".
		       "AND chapter.serie > 0 ".
		       "AND slot_chapter.slot in ( ". $inStr ." ) ".
		       "ORDER BY slot";

		$result = db_query($sql );
		
		while( $row = db_fetch_array( $result ) )
		{
			$this->slots[$row['slot']] = $row['gender'];
		}

		$sql = "SELECT slot_chapter.slot ".
		       "FROM chapter, slot_chapter ".
		       "WHERE chapter.id = slot_chapter.chapter ". 
		       "AND chapter.movie > 0 ".
		       "AND slot_chapter.slot in ( ". $inStr ." ) ".
		       "ORDER BY slot";

		$result = db_query($sql );
		
		while( $row = db_fetch_array( $result ) )
		{
			$this->slots[$row['slot']] = "Movie";
		}
		
		asort( $this->slots );
	}
	
	function getClassName($slot)
	{
		$gender = $this->slots[$slot];
		return $this->classNames[ $gender ];
	}

	function getGender($slot)
	{
		$gender = $this->slots[$slot];
		return $gender;
	}
}

//$_GET['category'] = $_GET['category'] != "" ? $_GET['category'] : "nacionales";

$time  = isset( $_GET['start_date'] ) ? strtotime( $_GET['start_date'] ): time();
$time += ( $_GET['offset']*60*60*GRID/2 );

$yesterday = date("Y-m-d", $time - 86400);
$today = date("Y-m-d", $time);
$tomorrow  = date("Y-m-d", $time + 86400);

$categoryStr = ( $_GET['category'] != "" ) ? ( "AND client_channel._group like '" . $_GET['category'] . "' " ) : "";

$sql = "SELECT client.name head, client_channel.number, ifnull(client_channel.custom_shortname, channel.shortname) shortname, channel.name, channel.logo, channel.id ".
       "FROM channel, client, client_channel ".
       "WHERE client.id = client_channel.client ". 
       "AND channel.id=client_channel.channel ".
       "AND client.id = ". $ID_CLIENT ." ".
       $categoryStr . 
       "ORDER BY client_channel.number";

$channels = array();
$result = db_query($sql );
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

if( empty( $channels ) ) exit;

reset($channels);

$arr_channels = array();
$slots        = array();
while( $row )
{
  $channel = current($channels);
  for(; $row['number'] == $channel->getNumber(); $row = db_fetch_array($result))
  {
  	$channel->appendProgram( $row['date'], substr($row['time'], 0,5), ucwords(strtolower( $row['title']) ), $row['id'] );
  	$slots[] = $row['id'];
  }
   $arr_channels[] = $channel;
   next($channels);
}

$chapters = new SlotChapters( $slots );
$search_string = "<b>Fecha y hora:</b> " . $_GET["start_date"] . 
	($category==""?"":" / <b>Género:</b> ".$category." ");
?>
<table class="cat_table" width="100%" border="0" cellpadding="0" cellspacing="1">
	<tr class="cat_td_line_1"><td colspan="<?=GRID+1?>" style="text-align:center"><?=$search_string?></td></tr>
  <TR class="cat_table_title">
    <th class="cat_table_title"><div class="chn_title">CANAL</div></th>
<?
for($i=0; $i<GRID; $i++)
{
	$gobackstr = "";
	$gonextstr = "";
	if( $i==0 ){ $gobackstr = "<img class=\"goback\" onclick=\"GridApp.goNext(-1)\" src=\"http://" . $URL_PPA . "/ppa/ppawebgrid/images/goback.gif\" />"; }
	if( $i==GRID-1 ){ $gonextstr = "<img class=\"gonext\" onclick=\"GridApp.goNext(1)\" src=\"http://" . $URL_PPA . "/ppa/ppawebgrid/images/gonext.gif\" />"; }

	$fh[$i] = fixHour( date("H:i", $time + ( 1800 * ( $i ) ) ) );
?>
    <th class="cat_table_title" width="<?=100/(GRID) ."%"?>" ><?=$gobackstr ?><div class="hour"><?=to12H( $fh[$i] )?></div><?=$gonextstr ?></th>
<?
}
?>
  </TR>
<?
$line = 1;
foreach($arr_channels as $channel)
{
$line++;
?>
  <TR>
  <TD onclick="document.getElementById('ppa_channel').value = <?=$channel->getIdChannel()?>;GridApp.consultGrid()" class="<?= ($line%2) == 0 ? "cat_td_title_1" : "cat_td_title_2" ?>">
  	<div class="ppa_chn_info"><img src="<?=( $channel->getLogo() != "" ? "http://" . $URL_PPA . "/ppa/ppa/" . $channel->getLogo() : "http://" . $URL_PPA . "/ppa/images/empty.gif" )?>"/><div class="ppa_chn_name"><?= xmlentities($channel->getShortName()) ."<br/>". $channel->getNumber() ?></div></div></TD>
<?
	$colspan = 1;
	for($i=0; $i<GRID; $i++)
	{
		$title = $channel->getProgramBySchedule( $today, $fh[$i] );
		if(  $title == $channel->getProgramBySchedule($today, $fh[$i+1] ) && ($i+1)<GRID)
		{
			$colspan++;
		}
		else
		{
			$tmp = explode( "|||", $title );
?>			
    <TD onmouseover="GridApp.hover(this)" onmouseout="GridApp.hout(this)" onmouseup="GridApp.synopsisPopUp('<?=$tmp[0]?>', event);" <?=$colspan > 1 ? "colspan=\"". $colspan ."\"" : ""?> class="cat_td_line_1<?=" " . $chapters->getClassName($tmp[0])?>" >
    	<div class="program"><?=xmlentities($tmp[1])?></div><div class="gender"><?=xmlentities($chapters->getGender($tmp[0]))?></div></TD>
<?
			$colspan = 1;
		}
	}
?>
  </TR>
<?
}
$line++;
?>
</table>
