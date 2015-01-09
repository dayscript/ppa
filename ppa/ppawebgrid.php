<?

exit();

error_reporting(E_ERROR);
include('ppa/include/config.inc.php');
include('include/db.inc.php');

define("GRID", 3);
define("DAY", 60*60*24);
define("TELMEX", 2);
define("UNE", 3);
$PROVIDER  = (isset( $_GET['provider'] ) && $_GET['provider'] > 0 ) ? $_GET['provider'] : TELMEX;
$ID_CLIENT = (isset( $_GET['header'] ) && $_GET['header'] > 0 ) ? $_GET['header'] : 25;

function fixHour( $str )
{
	$time 	= explode(":", $str);
	$minute	= sprintf("%d", ( $time[1] / 30 )) * 30;
	
	return $time[0] .":". sprintf("%02d", $minute);
}

function myDate()
{
	$days = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
	$months = array("", "Ene.", "Feb.", "Mar.", "Abr.", "May.", "Jun.", "Jul.", "Ago.", "Sep.", "Oct.", "Nov.", "Dic.");
	return $days[date("w")] . " " . date("d") . $months[date("n")];
}

function to12H( $str )
{
	$str   = explode(":", $str);
	$hours = $str[0] % 12 == 0 ? 12 : $str[0] % 12;
	return $hours .":". $str[1] ." ". ( $str[0] / 12 >= 1 ? "pm" : "am" );
}

/*echo "<!--";
print_r($_GET['title']);
echo "-->";*/

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

if (isset ($_REQUEST["nG"])) {
	$_GET['category'] = $_GET['category'] != "" ? $_GET['category'] : "Cine";
} else {
	$_GET['category'] = $_GET['category'] != "" ? $_GET['category'] : "Nacionales"; //nacionales
}

$time  = isset( $_GET['date'] ) ? strtotime( $_GET['date'] ): time();
$time += ( $_GET['offset']*60*60*GRID/2 );

$yesterday = date("Y-m-d", $time - 86400);
$today = date("Y-m-d", $time);
$tomorrow  = date("Y-m-d", $time + 86400);

if(isset($_GET['telmexhome']))
	$categoryStr = "AND client_channel.channel in (" . $_GET['telmexhome'] . ")";
else
	$categoryStr = ( $_GET['category'] != "Todas" && !empty ($_GET['category']) ) ? ( "AND client_channel._group = '" . $_GET['category'] . "' " ) : "";

$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.name, channel.logo, channel.id ".
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
	$channels[$row['id'] . "-" . $row['number']] = $channel;
}

if( empty( $channels ) ) {
?>
<table class="cat_table" width="100%" border="0" cellpadding="0" cellspacing="1">
	<TR class="cat_table_title">
		<td align="center" height="20">No se encontraron datos con los criterios utilizados</td>
	</TR>
</table>
<?
	exit;
}

$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.id AS channel, channel.name, slot.time, slot.title, slot.date, slot.id ".
       "FROM channel, client, client_channel, slot ".
       "WHERE client.id = client_channel.client ".
       "AND channel.id=client_channel.channel ".
       "AND client.id = ". $ID_CLIENT ." ".
       "AND slot.channel = channel.id ".
       "AND slot.date BETWEEN '". $yesterday ."' and '". $tomorrow ."' ".
       $categoryStr .
       "ORDER BY client_channel.number, slot.date, slot.time ";

$result = db_query($sql);

$slots        = array();
while( $row = db_fetch_array($result) )
{
	$channel = $channels[$row['channel'] . "-" . $row['number']];
	if( $channel )  {
		$channel->appendProgram( $row['date'], substr($row['time'], 0,5), ucwords(strtolower( $row['title']) ), $row['id'] );
		$slots[] = $row['id'];
	}
}

$chapters = new SlotChapters( $slots );
//print_r( $chapters );
?>
<table class="cat_table" width="100%" border="0" cellpadding="0" cellspacing="1">
  <TR class="cat_table_title">
    <th class="cat_table_title" width="<?=100/(GRID+1) ."%"?>" ><div class="chn_title"><?php echo isset($_GET["telmexhome"]) ? myDate() : "CANAL" ?></div></th>
<?
for($i=0; $i<GRID; $i++)
{
	$gobackstr = "";
	$gonextstr = "";
	if( $i==0 ){ $gobackstr = "<img class=\"goback\" onclick=\"ga.goNext(-1)\" src=\"images/goback.gif\" />"; }
	if( $i==GRID-1 ){ $gonextstr = "<img class=\"gonext\" onclick=\"ga.goNext(1)\" src=\"images/gonext.gif\" />"; }

	$fh[$i] = fixHour( date("H:i", $time + ( 1800 * ( $i ) ) ) );
?>
    <th class="cat_table_title" width="<?=100/(GRID+1) ."%"?>" ><?=$gobackstr ?><div class="hour"><?=to12H( $fh[$i] )?></div><?=$gonextstr ?></th>
<?
}
?>
  </TR>
</table>
<div style="height:600px;overflow:auto;">
<table class="cat_table" width="100%" border="0" cellpadding="0" cellspacing="1">
<?
for($i=0; $i<GRID; $i++)
{
	$gobackstr = "";
	$gonextstr = "";
	if( $i==0 ){ $gobackstr = "<img class=\"goback\" onclick=\"ga.goNext(-1)\" src=\"images/goback.gif\" />"; }
	if( $i==GRID-1 ){ $gonextstr = "<img class=\"gonext\" onclick=\"ga.goNext(1)\" src=\"images/gonext.gif\" />"; }

	$fh[$i] = fixHour( date("H:i", $time + ( 1800 * ( $i ) ) ) );
?>
	<col width="<?=100/(GRID+1) ."%"?>" />
<?
}

$line = 1;
foreach($channels as $channel)
{
$line++;
?>
  <TR>
  <TD onclick="ga.consultGrid('channel', <?=$channel->getIdChannel()?>, null, 0)" class="<?= ($line%2) == 0 ? "cat_td_title_1" : "cat_td_title_2" ?>">
  	<img src="<?=( $channel->getLogo() != "" ? "http://" . $URL_PPA . "/ppa/ppa/" . $channel->getLogo() : "http://" . $URL_PPA . "/ppa/ppa/channel_logos/default3.jpg" )?>"/><div><?= $channel->getName() . (isset($_GET['telmexhome']) ? "" : "<br>" . $channel->getNumber()) ?></div></TD>
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
			if(isset($_GET['telmexhome'])):
?>
    <TD onmouseover="GridApp.hover(this)" onmouseout="GridApp.hout(this)" <?=$colspan > 1 ? "colspan=\"". $colspan ."\"" : ""?> class="cat_td_line_1<?=" " . $chapters->getClassName($tmp[0])?>" >
<? else: ?>
    <TD onmouseover="GridApp.hover(this)" onmouseout="GridApp.hout(this)" onmouseup="GridApp.synopsisPopUp('<?=$tmp[0]?>', event);" <?=$colspan > 1 ? "colspan=\"". $colspan ."\"" : ""?> class="cat_td_line_1<?=" " . $chapters->getClassName($tmp[0])?>" >
<? endif; ?>
    	<div class="program"><?=$tmp[1]?></div><div class="gender"><?=$chapters->getGender($tmp[0])?></div></TD>
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
</div>
