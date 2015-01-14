<? 
require_once("include/functions.inc.php");
$today = date("Y-m-d");
?>
	<TABLE class="cat_table" width="100%">
      <TR class="cat_table_title">
        <TD background="images/TipoProg.jpg" width="100" >Canal</TD>
        <TD background="images/Hora.jpg" align="center">Programa</TD>
        <TD background="images/Hora.jpg" align="center">Fecha</TD>
      </TR>
<?

if( $_GET['title'] != "" ) 
{
	$sql = "SELECT client_channel.number, channel.name, slot.time, slot.title, slot.date ".
	       "FROM channel, client, client_channel, slot ".
	       "WHERE client.id = client_channel.client ". 
	       "AND channel.id=client_channel.channel ".
	       "AND client.id = ". CLIENT ." ".
	       "AND slot.channel = channel.id ".
	       "AND slot.date BETWEEN '". date("Y-m-1") ."' and '". date("Y-m-31") ."' ".
	       "AND slot.title like '%" . $_GET['title'] . "%' ".
	       "ORDER BY client_channel.number, slot.date, slot.time ";
	
	$result = db_query( $sql );
	
	while( $row = db_fetch_array($result) )
	{
?>
      <TR height="20">
        <TD align="center" class="cat_td_title_1" ><?=$row['number'] . "<br>" . $row['name']?></TD>
        <TD align="center" class="cat_td_border cat_td_line_1" ><?=$row['title']?></TD>
        <TD align="center" class="cat_td_border cat_td_line_1" ><?=$row['date'] ."<br>". to12H($row['time'])?></TD>
      </TR>
<? 
	}
} 
?>
</table>
