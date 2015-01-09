<?

include('ppa/include/config.inc.php');
include('include/db.inc.php');

define("GRID", 4);

class Channel
{
	var $name;
	var $shortname;
	var $number;
	var $program;
	
	function Channel()
	{
		$this->name = "";
		$this->shortname = "";
		$this->program = Array();		
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
	
	function getProgramBySchedule( $search_date, $search_hour )
	{
		$find = -1 ;
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
		return "Programación Sin Confirmar";
	}

	/***********************
	*  
	***********************/

	function appendProgram( $date, $hour, $title )
	{
		$this->program[$date][$hour] = $title;
	}
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

$_GET['category'] = $_GET['category'] != "" ? $_GET['category'] : "nacionales";

$yesterday = date("Y-m-d", time() - 86400);
$today     = date("Y-m-d");
$tomorrow  = date("Y-m-d", time() + 86400);

$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.name ".
       "FROM channel, client, client_channel ".
       "WHERE client.id = client_channel.client ". 
       "AND channel.id=client_channel.channel ".
       "AND client.id = ". 65 ." ".
       "AND client_channel._group like '" . $_GET['category'] . "' ".
       "ORDER BY client_channel.number";

$channels = array();
$result = db_query($sql );
while( $row = db_fetch_array($result) )
{
	$channel = new Channel();
	$channel->setNumber( $row['number'] );
	$channel->setName( $row['name'] );
	$channel->setShortName( $row['shortname'] );
	$channels[] = $channel;
}

$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.name, slot.time, slot.title, slot.date ".
       "FROM channel, client, client_channel, slot ".
       "WHERE client.id = client_channel.client ". 
       "AND channel.id=client_channel.channel ".
       "AND client.id = ". 65 ." ".
       "AND slot.channel = channel.id ".
       "AND slot.date BETWEEN '". $yesterday ."' and '". $tomorrow ."' ".
//       "AND slot.time BETWEEN '". date("H:i:s", time() - 14400) ."' AND '". date("H:i:s", time() + 7200) ."' ".
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
  	$channel->appendProgram( $row['date'], substr($row['time'], 0,5), ucwords(strtolower($row['title'])) );
  }
   $arr_channels[] = $channel;
   next($channels);
}
//print_r($arr_channels);
?>

<TABLE class="cat_table">
<tr><td class="cat_table_title" colspan="<?=GRID+1?>"><?=strtoupper($_GET['category'])?></td></tr>
  <TR class="cat_table_title">
    <TD width="<?=100/(GRID+1) ."%"?>" >Canal</TD>
<?
for($i=0; $i<GRID; $i++)
{
	$fh[$i] = fixHour( date("H:i", time() + ( 1800 * ( $i + $_GET['offset'] ) ) ) );
?>
    <TD width="<?=100/(GRID+1) ."%"?>" ><?=to12H( $fh[$i] )?></TD>
<?
}
?>
  </TR>
  <TR>
<?
$line = 1;
foreach($arr_channels as $channel)
{
$line++;
?>
  <TD class="<?= ($line%2) == 0 ? "cat_td_title_1" : "cat_td_title_2" ?>"><?=$channel->getNumber() ."<br>". $channel->getName()?></TD>
<?
//	echo $channel->getName() .": ";
	$colspan = 1;
	for($i=0; $i<GRID; $i++)
	{
//		echo $fh[$i] ." -- ";
		$title = $channel->getProgramBySchedule( $today, $fh[$i] );
		if(  $title == $channel->getProgramBySchedule($today, $fh[$i+1] ) && ($i+1)<GRID)
		{
			$colspan++;
		}
		else
		{
?>			

    <TD <?=$colspan > 1 ? "colspan=\"". $colspan ."\"" : ""?> class="<?= ($line%2) == 0 ? "cat_td_line_1" : "cat_td_line_2" ?>" ><?=$title?></TD>

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
<TR>
  <TD colspan="<?=(GRID-1)?>" class="cat_td_line_1"></TD>
  <TD colspan="2" class="cat_td_line_1" align="right"><a href="<?=$_GET['url']?>?category=<?=$_GET['category']?>&offset=<?=($_GET['offset']-4)?>" class="cat_link_1" ><< atrás</a> / <a href="<?=$_GET['url']?>?category=<?=$_GET['category']?>&offset=<?=($_GET['offset']+4)?>" class="cat_link_1" > adelante >></a></TD>
</TABLE>
