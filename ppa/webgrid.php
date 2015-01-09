<?
include('ppa/include/config.inc.php');
include('include/db.inc.php');

define("GRID", 4);
define("DAY", 60*60*24);
define("ID_CLIENT", 65);

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


if( isset( $_GET['action'] ) && $_GET['action'] == "filters"  )
{
	include( "webgrid/filters.php" );
	exit;
}
else if( isset( $_GET['action'] ) && $_GET['action'] == "channel"  )
{
	include( "webgrid/channel.php" );
	exit;
}
else if( isset( $_GET['action'] ) && $_GET['action'] == "actor"  )
{
	include( "webgrid/actor.php" );
	exit;
}
else if( isset( $_GET['action'] ) && $_GET['action'] == "title" )
{
	include( "webgrid/title.php" );
	exit;
}
else if( isset( $_GET['action'] ) && $_GET['action'] == "synopsis" && $_GET['slot'] != ""  )
{
	include( "webgrid/synopsis.php" );
	exit;
}
class ChannelGrid
{
	var $name;
	var $shortname;
	var $number;
	var $program;
	var $logo;
	
	function Channel()
	{
		$this->name      = "";
		$this->shortname = "";
		$this->logo      = "";
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

$_GET['category'] = $_GET['category'] != "" ? $_GET['category'] : "nacionales";

$time  = isset( $_GET['date'] ) ? strtotime( $_GET['date'] ): time();
$time += ( $_GET['offset']*60*60*GRID/2 );

$yesterday = date("Y-m-d", $time - 86400);
$today = date("Y-m-d", $time);
$tomorrow  = date("Y-m-d", $time + 86400);

$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.name, channel.logo ".
       "FROM channel, client, client_channel ".
       "WHERE client.id = client_channel.client ". 
       "AND channel.id=client_channel.channel ".
       "AND client.id = ". ID_CLIENT ." ".
       "AND client_channel._group like '" . $_GET['category'] . "' ".
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
	$channels[] = $channel;
}

$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.name, slot.time, slot.title, slot.date, slot.id ".
       "FROM channel, client, client_channel, slot ".
       "WHERE client.id = client_channel.client ". 
       "AND channel.id=client_channel.channel ".
       "AND client.id = ". ID_CLIENT ." ".
       "AND slot.channel = channel.id ".
       "AND slot.date BETWEEN '". $yesterday ."' and '". $tomorrow ."' ".
       "AND client_channel._group like '" . $_GET['category'] . "' ".
       "ORDER BY client_channel.number, slot.date, slot.time ";

$result = db_query($sql);
$row    = db_fetch_array($result);

if( empty( $channels ) ) exit;

reset($channels);

while( $row )
{
  $channel = current($channels);
  for(; $row['number'] == $channel->getNumber(); $row=db_fetch_array($result))
  {
  	$channel->appendProgram( $row['date'], substr($row['time'], 0,5), ucwords(strtolower( $row['title']) ), $row['id'] );
  }
   $arr_channels[] = $channel;
   next($channels);
}
?>
<table class="cat_table" width="100%">
<tr><td class="cat_table_title" colspan="<?=GRID+1?>"><?=strtoupper($_GET['category'])?><br/><?=$today?></td></tr>
  <TR class="cat_table_title">
    <TD class="cat_table_title" width="<?=100/(GRID+1) ."%"?>" >Canal</TD>
<?
for($i=0; $i<GRID; $i++)
{
	$fh[$i] = fixHour( date("H:i", $time + ( 1800 * ( $i ) ) ) );
?>
    <TD class="cat_table_title" width="<?=100/(GRID+1) ."%"?>" ><?=to12H( $fh[$i] )?></TD>
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
  <TD class="<?= ($line%2) == 0 ? "cat_td_title_1" : "cat_td_title_2" ?>" style="background: white url(<?=( $channel->getLogo() != "" ? "http://200.71.33.249/~ppa/ppa/" . $channel->getLogo() : "" )?>) no-repeat">
  	<?= "<span style=\"position:relative;left:47px\">" . $channel->getNumber() ."<br>". $channel->getName()  . "</span>" ?></TD>
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
    <TD <?=$colspan > 1 ? "colspan=\"". $colspan ."\"" : ""?> class="<?= ($line%2) == 0 ? "cat_td_line_1" : "cat_td_line_2" ?>" >
    	<a href="#;" class="link" onmouseup="GridApp.synopsisPopUp('<?=$tmp[0]?>', event);"><?=$tmp[1]?></a></TD>
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
</TABLE>
