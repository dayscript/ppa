<? 
$today = date("Y-m-d");
?>
	<TABLE class="cat_table" width="100%">
      <TR class="cat_table_title">
        <TD background="images/TipoProg.jpg" width="100" >Canal</TD>
        <TD background="images/Hora.jpg" align="center">Programa</TD>
        <TD background="images/Hora.jpg" align="center">Fecha</TD>
      </TR>
<?

if( strlen( $_GET['title'] ) <= 3 ) 
{
		echo '<tr><td colspan="3" class="cat_td_title_1">El valor buscado debe tener m&aacute;s de 3 caracteres</td></tr>';	
}
else
{
	
	$sql = "SELECT client_channel.number, channel.name, channel.id ".
	       "FROM channel, client, client_channel ".
	       "WHERE client.id = client_channel.client ". 
	       "AND channel.id=client_channel.channel ".
	       "AND client.id = ". ID_CLIENT ." ".
	       "ORDER BY client_channel.number ";
	
	$result = db_query( $sql );
	
	while( $row = db_fetch_array($result) )
	{
		$channels[$row['id']]['name']   = $row['name'];
		$channels[$row['id']]['number'] = $row['number'];
	}
	
	$sql = "SELECT slot.time, slot.title, slot.date, slot.channel, slot.id ".
	       "FROM slot ".
	       "WHERE ". 
	       "slot.channel IN (" . implode( ",", array_keys( $channels ) ) . ") ".
	       "AND slot.date BETWEEN '". date("Y-m-01") ."' and '". date("Y-m-31") ."' ".
	       "AND slot.title like '%" . $_GET['title'] . "%' ".
	       "ORDER BY slot.date, slot.time ";
	
	$result = db_query( $sql );

	if( db_numrows( $result ) == 0 )
	{
		echo '<tr><td colspan="3" class="cat_td_title_1">Ning&uacute;n Resultado para: '	. $_GET["title"] . '</td></tr>';
	}
	else
	while( $row = db_fetch_array($result) )
	{
?>
      <TR height="20">
        <TD align="center" class="cat_td_title_1" ><?=$channels[$row['channel']]['number'] . "<br>" . $channels[$row['channel']]['name']?></TD>
        <TD align="center" class="cat_td_border cat_td_line_1" ><a href="#;" class="link" onmouseup="GridApp.synopsisPopUp(<?=$row['id']?>, event);"><?=$row['title']?></a></TD>
        <TD align="center" class="cat_td_border cat_td_line_1" ><?=$row['date'] ."<br>". to12H($row['time'])?></TD>
      </TR>
<? 
	}
}
?>
</table>
