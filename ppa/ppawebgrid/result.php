<?
$REQUEST = array_merge($_POST, $_GET);

	$sql_select = "select client.* , client_channel.* , channel.* , slot.* , chapter.* ";
	$sql_from="FROM channel, client, client_channel, slot, chapter, slot_chapter ";
	$sql_where="".
		   "WHERE client.id = client_channel.client ". 
		   "AND channel.id=client_channel.channel ".
		   "AND chapter.id = slot_chapter.chapter ".
		   "AND slot.id = slot_chapter.slot ".
		   "AND client.id = '65' ".
		   "AND slot.channel = channel.id ";
	$sql_order=" ORDER BY client_channel.number, slot.date, slot.time ";



if($REQUEST["id_categorias"]!="0"){
    $sql_where.=" AND client_channel._group like '" . $REQUEST['id_categorias'] . "' ";
}


if($REQUEST["id_canales"]!="0"){
	$sql_where.= "AND channel.id = ". $REQUEST["id_canales"] ." ";
}

if($REQUEST["actor"]!="Por Actor"&&$REQUES["actor"]!=""){

	$sql = "" .
		"SELECT slot_chapter.slot, movie.actors FROM movie, chapter, slot_chapter ". 
		"WHERE ".
		"movie.id = chapter.movie ".
		"AND slot_chapter.chapter = chapter.id ".
		"AND movie.actors like '%" . $REQUEST['actor'] . "%' ";
	
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
		"AND serie.starring like '%" . $REQUEST['actor'] . "%' ";
	
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
		"AND special.starring like '%" . $REQUEST['actor'] . "%' ";
	
	$result = db_query( $sql );
	
	while( $row = db_fetch_array($result) )
	{
		$slot_id[]            = $row['slot'];
		$actors[$row['slot']] = $row['starring'];
	}
	
	if( empty( $slot_id ) )
	{
		$ERROR= "No se encuentran coincidencias. ";
	}
	else
	{
		$sql_where.= " AND slot.id IN (" . implode(",", $slot_id ) . ") ";
	}
}
//echo "<br>";print_r($_GET);echo "<br>";
if($REQUEST["director"]!="Por"&&$REQUEST["director"]!=""){

	$sql = "" .
		"SELECT slot_chapter.slot, movie.actors FROM movie, chapter, slot_chapter ". 
		"WHERE ".
		"movie.id = chapter.movie ".
		"AND slot_chapter.chapter = chapter.id ".
		"AND movie.director like '%" . $REQUEST['director'] . "%' ";
	
	$result = db_query( $sql );
	$slot_id = array();
	while( $row = db_fetch_array($result) )
	{
		$slot_id[]            = $row['slot'];
		$actors[$row['slot']] = $row['director'];
	}
	
	if( empty( $slot_id ) )
	{
		$ERROR= "No se encuentran coincidencias. ";
	}
	else
	{

		$sql_where.= " AND slot.id IN (" . implode(",", $slot_id ) . ") ";

	}
}

if($REQUEST["hoy"]!="Fecha"&&$REQUEST["hoy"]!=""){
	$sql_where.= " AND slot.date like '". $REQUEST["hoy"] ."' ";
}
else
{
	$sql_where.= "AND slot.date = '" . date("Y-m-d") ."' ";
}

	$sql=$sql_select.$sql_from.$sql_where.$sql_order;
	$result = db_query($sql);
	$canal="";
	?>



<table cellpadding="0" cellspacing="0" border="0" class="tvgrid_bus" width="100%" align="center">

	<tr valign="middle">
		<td class="supizq"></td>
		<td class="backsup"><div align="center" style="font-weight:bold; font-family:Tahoma; color:#333333; font-size:110%; padding-top:8px;">&nbsp;CANAL&nbsp;</div></td>
		<td class="supmedio"><div align="center"></div></td>
		<td class="backsup"><div align="center" style="font-weight:bold; font-family:Tahoma; color:#333333; font-size:110%; vertical-align:middle; padding-top:8px;">&nbsp;HORA&nbsp;</div></td>
		<td class="supmedio"><div align="center"></div></td>
		<td class="backsup"><div align="center" style="font-weight:bold; font-family:Tahoma; color:#333333; font-size:110%; vertical-align:middle; padding-top:8px;">&nbsp;FECHA&nbsp;</div></td>
		<td class="supmedio"><div align="center"></div></td>
		<td class="backsup"><div align="center" style="font-weight:bold; font-family:Tahoma; color:#333333; font-size:110%; vertical-align:middle; padding-top:8px;">&nbsp;PROGRAMA&nbsp;</div></td>
		<td class="supmedio"><div align="center"></div></td>
		<td class="backsup"><div align="center" style="font-weight:bold; font-family:Tahoma; color:#333333; font-size:110%; vertical-align:middle; padding-top:8px;">&nbsp;GENERO&nbsp;</div></td>
		<td class="supder"></td>
	</tr>
	<?	
		$canal="";
		$i=0;
		while( $row = db_fetch_array($result) )
		{
			if($canal==$row["name"]){
				$nombre="";
			}		
			if($i==0){
				$nombre=$row["name"];
				$canal=$row["name"];
				$i++;
			}		
			if($canal!=$row["name"]){
				$nombre=$row["name"];
				$canal=$row["name"];
			}		
	?>
	<tr>
		<td class="lat" ></td>
		<td <? if($nombre!=""){?> style="border-top:#B4B4B4 thin solid;"<? }?>>
		<div style="font-family:Tahoma; font-size:11px;"><?
			echo $nombre;
		?>
		</div>
		</td>
		<td class="med"></td>
		<td class="linea"><div align="center" style="font-family:Tahoma; color:#333333; font-size:85%; vertical-align:middle"><?=$row["time"];?></div></td>
		<td class="linea1"><div align="center"></div></td>
		<td class="linea"><div align="center" style="font-family:Tahoma; color:#333333; font-size:85%; vertical-align:middle"><?=$row["date"];?></div></td>
		<td class="linea1"><div align="center"></div></td>
		<td class="linea"><div align="left" style=" margin-left:50px;font-family:Tahoma; color:#333333; font-size:85%; vertical-align:middle"><?=$row["title"];?></div></td>
		<td class="linea1"><div align="center"></div></td>
		<td class="linea"><div align="center" style="font-family:Tahoma; color:#333333; font-size:85%; vertical-align:middle"><?=$row["_group"];?></div></td>
		<td class="der"></td>
	</tr>
<? }?>
	<tr>
		<td class="infizq"></td>
		<td class="backinf"></td>
		<td class="infmedio"></td>
		<td class="backinf"></td>
		<td class="backinf"></td>
		<td class="backinf"></td>
		<td class="backinf"></td>
		<td class="backinf"></td>
		<td class="backinf"></td>
		<td class="backinf"></td>
		<td class="infder"></td>
	</tr>
</table>

