<?
require_once("class/Channel.class.php");
require_once("include/functions.inc.php");

$_GET['date'] = $_GET['date'] != "" ? $_GET['date'] : date("Y-m-d H:i");

$yesterday = date("Y-m-d", strtotime( $_GET['date'] ) - 86400);
$today     = date("Y-m-d", strtotime( $_GET['date'] ) );
$tomorrow  = date("Y-m-d", strtotime( $_GET['date'] ) + 86400);

$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.name, channel.id ".
       "FROM channel, client, client_channel ".
       "WHERE client.id = client_channel.client ". 
       "AND channel.id=client_channel.channel ".
       "AND client.id = ". CLIENT ." ".
       "ORDER BY client_channel.number";

$channels = array();
$result = db_query($sql );
while( $row = db_fetch_array($result) )
{
	$channel = new Channel();
	$channel->setId( $row['id'] );
	$channel->setNumber( $row['number'] );
	$channel->setName( $row['name'] );
	$channel->setShortName( $row['shortname'] );
	$channels[] = $channel;
}

$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.name, slot.time, slot.title, slot.date ".
       "FROM channel, client, client_channel, slot ".
       "WHERE client.id = client_channel.client ". 
       "AND channel.id=client_channel.channel ".
       "AND client.id = ". CLIENT ." ".
       "AND slot.channel = channel.id ".
       "AND slot.date BETWEEN '". $yesterday ."' and '". $tomorrow ."' ".
       "ORDER BY client_channel.number, slot.date, slot.time ";

$result = db_query($sql);
$row    = db_fetch_array($result);

if( empty( $channels ) ) exit;

reset($channels);
$arr_channels = array();

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
?>
	<TABLE class="cat_table">
      <TR>
        <TD class="cat_td_line_1" align="right"><div align="left"><a href="<?=$_GET['url']?>?date=<?=$_GET['date']?>&offset=<?=($_GET['offset']-4)?>" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('anterior1','','images/anterior_over.jpg',1)"><img src="images/anterior.jpg" name="anterior1" border="0"> </a></div></TD>
<? for($i=0; $i<GRID-1; $i++) { ?>
        <TD></TD>
<? } ?>
        <TD class="cat_td_line_1" align="left"><div align="right"><a href="<?=$_GET['url']?>?date=<?=$_GET['date']?>&offset=<?=($_GET['offset']+4)?>" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('siguiente1','','images/siguiente_over.jpg',1)"><img src="images/siguiente.jpg" name="siguiente1" border="0"></a></div></TD>
      </TR>
      <TR class="cat_table_title">
        <TD width="<?=100/(GRID+1) ."%"?>" background="images/TipoProg.jpg" align="center" ><?=date("Y-m-d", strtotime( $_GET['date'] ) )?></TD>
        <?
for($i=0; $i<GRID; $i++)
{
	$fh[$i] = fixHour( date("H:i", strtotime( $_GET['date'] ) + ( 1800 * ( $i + $_GET['offset'] ) ) ) );
?>
        <TD background="images/Hora.jpg" width="<?=100/(GRID+1) ."%"?>" ><?=to12H( $fh[$i] )?></TD>
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
        <TD class="<?= ($line%2) == 0 ? "cat_td_title_1" : "cat_td_title_2" ?>" ><?=$channel->getNumber() ."<br>". $channel->getName()?></TD>
        <?
	$colspan = 1;
	for($i=0; $i<GRID; $i++)
	{
		$title = $channel->getProgramBySchedule( $today, $fh[$i] );
		if(  $title == $channel->getProgramBySchedule($today, $fh[$i+1] ) && ($i+1) < GRID)
		{
			$colspan++;
		}
		else
		{
			$class = ($line%2) == 0 ? "cat_td_line_1" : "cat_td_line_2";
?>
        <TD <?=$colspan > 1 ? "colspan=\"". $colspan ."\"" : ""?> class="cat_td_border" ><a href="#" onClick="PopUp('<?=str_replace("'", "\'", $title)?>')" class="<?=$class?>"><?=$colspan > 1 ? $title : substr($title, 0, 48)?></a></TD>
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
        <TD class="cat_td_line_1" align="right"><div align="left"><a href="<?=$_GET['url']?>?date=<?=$_GET['date']?>&offset=<?=($_GET['offset']-4)?>" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('anterior2','','images/anterior_over.jpg',1)"><img src="images/anterior.jpg" name="anterior2" border="0"></a></div></TD>
<? for($i=0; $i<GRID-1; $i++) { ?>
        <TD></TD>
<? } ?>
        <TD class="cat_td_line_1" align="left"><div align="right"><a href="<?=$_GET['url']?>?date=<?=$_GET['date']?>&offset=<?=($_GET['offset']+4)?>" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('siguiente2','','images/siguiente_over.jpg',1)"><img src="images/siguiente.jpg" name="siguiente2" border="0"></a></div></TD>
      </TR>
    </TABLE>
