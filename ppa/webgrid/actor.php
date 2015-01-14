<? 
$today = date("Y-m-d");
?>
	<TABLE class="cat_table" width="100%">
      <TR class="cat_table_title">
        <TD background="images/TipoProg.jpg" width="100" >Canal</TD>
        <TD background="images/Hora.jpg" align="center">Programa</TD>
        <TD background="images/Hora.jpg" align="center">Fecha</TD>
        <TD background="images/Hora.jpg" align="center">Actores</TD>
      </TR>
<?

if( strlen( $_GET['actor'] ) <= 3 ) 
{
		echo '<tr><td colspan="4" class="cat_td_title_1">El valor buscado debe tener m&aacute;s de 3 caracteres</td></tr>';	
}
else
	{
	$sql = "" .
		"SELECT slot_chapter.slot, movie.actors FROM movie, chapter, slot_chapter ". 
		"WHERE ".
		"movie.id = chapter.movie ".
		"AND slot_chapter.chapter = chapter.id ".
		"AND movie.actors like '%" . $_GET['actor'] . "%' ";
	
	$result = db_query( $sql );
	$slot_id = array();
	while( $row = db_fetch_array($result) )
	{
		$slot_id[]            = $row['slot'];
		$actors[$row['slot']] = $row['actors'];
	}
	
	$sql = "" .
		"SELECT slot_chapter.slot, serie.starring FROM serie, chapter, slot_chapter ". 
		"WHERE ".
		"serie.id = chapter.serie ".
		"AND slot_chapter.chapter = chapter.id ".
		"AND serie.starring like '%" . $_GET['actor'] . "%' ";
	
	$result = db_query( $sql );
	
	while( $row = db_fetch_array($result) )
	{
		$slot_id[]            = $row['slot'];
		$actors[$row['slot']] = $row['starring'];
	}
	
	$sql = "" .
		"SELECT slot_chapter.slot, special.starring FROM special, chapter, slot_chapter ". 
		"WHERE ".
		"special.id = chapter.special ".
		"AND slot_chapter.chapter = chapter.id ".
		"AND special.starring like '%" . $_GET['actor'] . "%' ";
	
	$result = db_query( $sql );
	
	while( $row = db_fetch_array($result) )
	{
		$slot_id[]            = $row['slot'];
		$actors[$row['slot']] = $row['starring'];
	}
	
	
	$sql = "" .
		"SELECT * FROM slot, slot_channel ".
		"WHERE id IN (" . implode(",", $slot_id ) . ") AND ".
		"slot.date BETWEEN '" . date("Y-m-01") . "' AND '" . date("Y-m-31") . "' ";
	
	if( empty( $slot_id ) )
	{
		echo '<tr><td colspan="4" class="cat_td_title_1">Ning&uacute;n Resultado para: '	. $_GET["actor"] . '</td></tr>';
	}
	else
	{
		$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.name, slot.id, slot.time, slot.title, slot.date ".
		       "FROM channel, client, client_channel, slot ".
		       "WHERE client.id = client_channel.client ". 
		       "AND channel.id=client_channel.channel ".
		       "AND client.id = ". ID_CLIENT ." ".
		       "AND slot.channel = channel.id ".
		       "AND slot.id IN (" . implode(",", $slot_id ) . ") ".
			   "AND slot.date BETWEEN '" . date("Y-m-01") . "' AND '" . date("Y-m-31") . "' ".
		       "ORDER BY client_channel.number, slot.date, slot.time ";
		
		$result = db_query( $sql );
		
		while( $row = db_fetch_array($result) )
		{
?>
      <TR height="20">
        <TD align="center" class="cat_td_title_1" ><?=$row['number'] . "<br>" . $row['name']?></TD>
        <TD align="center" class="cat_td_line_1" ><a href="#;" onmouseup="GridApp.synopsisPopUp('<?=$row['id']?>', event);" class="link"><?=$row['title']?></a></TD>
        <TD align="center" class="cat_td_line_1" ><?=$row['date'] ." ". $row['time']?></TD>
        <TD align="center" class="cat_td_line_1" ><?=$actors[$row['id']]?></TD>
      </TR>
<? } } } ?>
</table>
