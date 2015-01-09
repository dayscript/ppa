<?
include('ppa/include/config.inc.php');
include('include/db.inc.php');


function limpiar_acentos($s)
{
	$s = ereg_replace("[áàâãª]","a",$s);
	$s = ereg_replace("[ÁÀÂÃ]","A",$s);
	$s = ereg_replace("[ÍÌÎ]","I",$s);
	$s = ereg_replace("[íìî]","i",$s);
	$s = ereg_replace("[éèê]","e",$s);
	$s = ereg_replace("[ÉÈÊ]","E",$s);
	$s = ereg_replace("[óòôõº]","o",$s);
	$s = ereg_replace("[ÓÒÔÕ]","O",$s);
	$s = ereg_replace("[úùû]","u",$s);
	$s = ereg_replace("[ÚÙÛ]","U",$s);
	$s = str_replace("ç","c",$s);
	$s = str_replace("Ç","C",$s);
	$s = str_replace("[ñ]","n",$s);
	$s = str_replace("[Ñ]","N",$s);
	return $s;
}

if(isset($_GET['view']) && $_GET['view']=="short"){
	define("GRID", 3);
} else {
	define("GRID", 3);
}
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
if( isset( $_GET['action'] ) && $_GET['action'] == "result"  )
{
	include( "webgrid/result.php" );
	exit;
}
if( isset( $_GET['action'] ) && $_GET['action'] == "filterschannel"  )
{
	include( "webgrid/filterschannel.php" );
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
		return "0|||Programación Sin Confirmar|||-";
	}

	/***********************
	*  
	***********************/

	function appendProgram( $date, $hour, $title, $slot, $description )
	{
		$datos = explode(";",$description);
		if($datos[1]>0){
			$sql = "select * from serie where id=" . $datos[1];
			$res = db_query($sql);
			if($r = db_fetch_array($res)){
				$description = $r['gender'];
			} else {
				$description = "-";
			}
		} else if($datos[0]>0){
			$sql = "select * from movie where id=" . $datos[1];
			$res = db_query($sql);
			if($r = db_fetch_array($res)){
				$description = "Pelicula " . $r['gender'];
			} else {
				$description = "-";
			}
		} else {
			$sql = "select * from special where id=" . $datos[1];
			$res = db_query($sql);
			if($r = db_fetch_array($res)){
				$description = $r['gender'];
			} else {
				$description = "-";
			}
		}
		$this->program[$date][$hour] = $slot . "|||" . $title . "|||" . $description;
	}
}


$_GET['category'] = $_GET['category'] != "" ? $_GET['category'] : "nacionales";

$time  = isset( $_GET['date'] ) ? strtotime( $_GET['date'] ): time();
$time += ( $_GET['offset']*60*60*GRID/2 );

$yesterday = date("Y-m-d", $time - 86400);
$today = date("Y-m-d", $time);
$tomorrow  = date("Y-m-d", $time + 86400);

if(isset($_GET['view']) && $_GET['view']=="short"){
	$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.name, channel.logo, channel.id ".
		   "FROM channel, client, client_channel ".
		   "WHERE client.id = client_channel.client ". 
		   "AND channel.id=client_channel.channel ".
		   "AND client.id = ". ID_CLIENT ." ".
		   "AND client_channel._group like '" . $_GET['category'] . "' ".
		   "ORDER BY client_channel.number";
	$channels = array();
	$result = db_query($sql );
	$channels_text = "0";
	while( $row = db_fetch_array($result) )
	{
		$channel = new ChannelGrid();
		$channel->setNumber( $row['number'] );
		$channel->setName( $row['name'] );
		$channel->setShortName( $row['shortname'] );
		$channel->setLogo( $row['logo'] );
		$channels[] = $channel;
		$channels_text .= "," . $row['id'];
	}
/*	

lista de categorias
$sql = "SELECT distinct(client_channel._group) ".
		   "FROM channel, client, client_channel, slot ".
		   "WHERE client.id = client_channel.client ". 
		   "AND slot.channel = channel.id ".
		   "AND slot.date BETWEEN '". $yesterday ."' and '". $tomorrow ."' ".
		   "AND channel.id=client_channel.channel ".
		   "AND client.id = ". ID_CLIENT ." ".
		   "ORDER BY 1";
	$result = db_query($sql);
	while($c = db_fetch_array($result)){
		?><?=$c[0]?><br /><?
	}
*/	
	
	$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.name, slot.time, slot.title, slot.date, slot.id, concat(chapter.movie,';',chapter.serie,';',chapter.special) as description ".
		   "FROM channel, client, client_channel, slot, chapter, slot_chapter ".
		   "WHERE client.id = client_channel.client ". 
		   "AND channel.id=client_channel.channel ".
		   "AND chapter.id = slot_chapter.chapter ".
		   "AND slot.id = slot_chapter.slot ".
		   "AND channel.id in ($channels_text) ".
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
		$channel->appendProgram( $row['date'], substr($row['time'], 0,5), ucwords(strtolower( $row['title']) ), $row['id'], ucwords(strtolower( $row['description']) ) );
	  }
	   $arr_channels[] = $channel;
	   next($channels);
	}
	?>
	<table cellpadding="0" cellspacing="0" width="675" class="tvlist" border="0">
		<tr>
			<td width="72%" class="tvlist_chanellist"></td>
			<td class="tvlist_grid" colspan="6">
				<table cellpadding="0" cellspacing="0" width="100%" class="tvlist">
					<tr>
						<td class="tvlist_header_tl"></td>
						<td class="tvlist_header_t"></td>
						<td class="tvlist_header_tr"></td>
					</tr>
					<tr>
						<td class="tvlist_header_l"></td>
						<td class="tvlist_header_c">
						<?
									$sql    = "SELECT distinct( _group ) FROM client_channel WHERE client = 65 ORDER BY 1";
									$result = db_query( $sql );
									?>
									<select name="categorias" onChange="cambio_categoria(this.options[this.selectedIndex].value,<?=$offset;?>,1);">
									  <option value="0">Elija una categoria</option>
									<? 
									while( $row = db_fetch_array( $result ) )
									{
									?>
										<option value="<?=$row['_group']?>" <? if($_GET['category']==$row['_group']) echo "selected";?> ><?=ucfirst( $row['_group'] )?></option>
									<?	
									}
									?>
									</select>
						<? //=strtoupper($_GET['category'])?>
						<br/><?=$today?></td>
						<td class="tvlist_header_r"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="background:url(imagenes/ppa/grad_lu.gif);border-top:#B4B4B4 thin solid;border-left:#B4B4B4 thin solid;border-bottom:#B4B4B4 thin solid;height:30px;padding-left:5px;font-size:14px;font-weight:bold;color:#333333; text-align:center;vertical-align:middle;" colspan="2">&nbsp;CANAL</td>
			<td class="tvlist_timeback" onclick="cambiohora('<?=$category?>',<?=$offset?>,1);" onmouseover="javascript:this.className='tvlist_timeback2';" onmouseout="javascript:this.className='tvlist_timeback';">&nbsp;</td>
	<? 		for($i=0; $i<GRID; $i++) {
				$fh[$i] = fixHour( date("H:i", $time + ( 1800 * ( $i ) ) ) );
	?>			<td  style="width:80px;background:url(imagenes/ppa/grad_lu.gif);border-top:#B4B4B4 thin solid;border-left:#B4B4B4 thin solid;border-bottom:#B4B4B4 thin solid;height:30px;padding-left:5px;font-size:14px;font-weight:bold;color:#333333; text-align:center; vertical-align:middle"><?=to12H( $fh[$i] )?></td>
	<?		}
	?>		<td class="tvlist_timeforward" onclick="cambiohora('<?=$category?>',<?=$offset?>,2);" onmouseover="javascript:this.className='tvlist_timeforward2';" onmouseout="javascript:this.className='tvlist_timeforward';">&nbsp;</td>
		</tr>	
	<?	$line = 1;
		foreach($arr_channels as $channel) {
		print_r($channel);
			$line++;
	?>		<tr>
				<td class="tvlist_chanel"><img src="imagenes/<?=$channel->getLogo()?>" border="0" width="30" height="30"> | <?=$channel->getName()?></td>
				<td width="85" class="tvlist_header_l2"></td>
				<td class="tvlist_gridleft"></td>
	<?			$colspan = 1;
				for($i=0; $i<GRID; $i++) {
					$title = $channel->getProgramBySchedule( $today, $fh[$i] );
					if(  $title == $channel->getProgramBySchedule($today, $fh[$i+1] ) && ($i+1)<GRID) {
						$colspan++;
					} else {
						$tmp = explode( "|||", $title );
	?>					<td <?=$colspan > 1 ? "colspan=\"". $colspan ."\"" : ""?> class="tvlist_griddata"><div style="text-align:left; margin-left:10px;"><?=$tmp[1]?><br /><div style="color:#666666"><?=$tmp[2]?></div></div><div style=" vertical-align:top;position:relative;"><? if($img = @GetImageSize("http://208.66.69.236/infinitum/imagenes/categorias/".$s)){?><img src="imagenes/categorias/<?=$s?>.jpg" border="0" onclick="diagseccionestv.show();" style="cursor:pointer "/><? }else{?> <img src="imagenes/categorias/default.jpg" border="0" onclick="diagseccionestv.show();" style="cursor:pointer "/><? }?></div></td>
	<?					$colspan = 1;
					}
				}
	?>			<td class="tvlist_gridright"></td>
			</tr>
<?	}
		$line++;
	?></table>
	<?
} else {
	$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.name, channel.logo, channel.id ".
		   "FROM channel, client, client_channel ".
		   "WHERE client.id = client_channel.client ". 
		   "AND channel.id=client_channel.channel ".
		   "AND client.id = ". ID_CLIENT ." ".
		   "AND client_channel._group like '" . $_GET['category'] . "' ".
		   "ORDER BY client_channel.number";
	
	$channels = array();
	$result = db_query($sql );
	$channels_text = "0";
	while( $row = db_fetch_array($result) )
	{
		$channel = new ChannelGrid();
		$channel->setNumber( $row['number'] );
		$channel->setName( $row['name'] );
		$channel->setShortName( $row['shortname'] );
		$channel->setLogo( $row['logo'] );
		$channels[] = $channel;
		$channels_text .= "," . $row['id'];
	}
	$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.name, slot.time, slot.title, slot.date, slot.id, concat(chapter.movie,';',chapter.serie,';',chapter.special) as description ".
		   "FROM channel, client, client_channel, slot, chapter, slot_chapter ".
		   "WHERE client.id = client_channel.client ". 
		   "AND channel.id=client_channel.channel ".
		   "AND chapter.id = slot_chapter.chapter ".
		   "AND slot.id = slot_chapter.slot ".
		   "AND channel.id in ($channels_text) ".
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
		$channel->appendProgram( $row['date'], substr($row['time'], 0,5), ucwords(strtolower( $row['title']) ), $row['id'], ucwords(strtolower( $row['description']) ) );
	  }
	   $arr_channels[] = $channel;
	   next($channels);
	}
	?>
	<table width="675" height="129" border="0" cellpadding="0" cellspacing="0" class="tvlist">
		<tr>
			<td width="377" class="tvlist_chanellist"></td>
			<td class="tvlist_grid" colspan="6">
				<table cellpadding="0" cellspacing="0" width="100%" class="tvlist">
					<tr>
						<td class="tvlist_header_tl"></td>
						<td class="tvlist_header_t"></td>
						<td class="tvlist_header_tr"></td>
					</tr>
					<tr>
						<td class="tvlist_header_l"></td>
						<td class="tvlist_header_c">
						<?
									$sql    = "SELECT distinct( _group ) FROM client_channel WHERE client = 65 ORDER BY 1";
									$result = db_query( $sql );
									?>
									<select name="categorias" onChange="cambio_categoria(this.options[this.selectedIndex].value,<?=$offset;?>,1);">
									  <option value="0">Elija una categoria</option>
									<? 
									while( $row = db_fetch_array( $result ) )
									{
									?>
										<option value="<?=$row['_group']?>" <? if($_GET['category']==$row['_group']) echo "selected";?>><?=ucfirst( $row['_group'] )?></option>
									<?	
									}
									?>
									</select>
						<? //=strtoupper($_GET['category'])?>
						<br/><?=$today?></td>
						<td class="tvlist_header_r"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="background:url(imagenes/ppa/grad_lu.gif);border-top:#B4B4B4 thin solid;border-left:#B4B4B4 thin solid;border-bottom:#B4B4B4 thin solid;height:30px;padding-left:5px;font-size:14px;font-weight:bold;color:#333333;vertical-align:middle;" colspan="2" align="center">&nbsp;CANAL</td>
			<td class="tvlist_timeback" onclick="cambiohora('<?=$category?>',<?=$offset?>,1);" onmouseover="javascript:this.className='tvlist_timeback2';" onmouseout="javascript:this.className='tvlist_timeback';">&nbsp;</td>
	<? 		for($i=0; $i<GRID; $i++) {
				$fh[$i] = fixHour( date("H:i", $time + ( 1800 * ( $i ) ) ) );
	?>			<td style="width:180px;background:url(imagenes/ppa/grad_lu.gif);border-top:#B4B4B4 thin solid;border-left:#B4B4B4 thin solid;border-bottom:#B4B4B4 thin solid;height:30px;padding-left:5px;font-size:14px;font-weight:bold;color:#333333;vertical-align:middle;" align="center"><?=to12H( $fh[$i] )?></td>
	<?		}
	?>		<td class="tvlist_timeforward" onclick="cambiohora('<?=$category?>',<?=$offset?>,2);" onmouseover="javascript:this.className='tvlist_timeforward2';" onmouseout="javascript:this.className='tvlist_timeforward';">&nbsp;</td>
		</tr>	
	<?	$line = 1;
		foreach($arr_channels as $channel) {
			
			$line++;
	?>		<tr>
				<td class="tvlist_chanel"><? if($img = @GetImageSize("http://208.66.69.236/infinitum/imagenes/".$channel->getLogo())){?><img src="imagenes/<?=$channel->getLogo()?>" border="0" width="30" height="30"><? } else {?> <img src="imagenes/channel_logos/canal.gif" border="0" width="30" height="30"><? }?> | <?=$channel->getName()?></td>
				<td width="85" class="tvlist_header_l2"></td>
				<td class="tvlist_gridleft"></td>
	<?			$colspan = 1;
				for($i=0; $i<GRID; $i++) {
					$title = $channel->getProgramBySchedule( $today, $fh[$i] );
					if(  $title == $channel->getProgramBySchedule($today, $fh[$i+1] ) && ($i+1)<GRID) {
						$colspan++;
					} else {
						$tmp = explode( "|||", $title );
						$s=limpiar_acentos($tmp[2]);
	?>					<td <?=$colspan > 1 ? "colspan=\"". $colspan ."\"" : ""?> class="tvlist_griddata" align="right"  valign="middle"><div style="text-align:left; margin-left:10px;"><?=$tmp[1]?><br /><div style="color:#666666"><?=$tmp[2]?></div> <div style=" vertical-align:top; text-align:right; position:relative;"><? if($img = @GetImageSize("http://208.66.69.236/infinitum/imagenes/categorias/".$s)){?><img src="imagenes/categorias/<?=$s?>.jpg" border="0" onclick="diagseccionestv.show();" style="cursor:pointer "/><? }else{?> <img src="imagenes/categorias/default.jpg" border="0" onclick="diagseccionestv.show();" style="cursor:pointer "/><? }?></div></div></td>
	<?					$colspan = 1;
					}
				}
	?>			<td class="tvlist_gridright"></td>
			</tr>
<?	}
		$line++;
	?></table>
    <?
}
?>