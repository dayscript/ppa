<?
require_once( "ppa/config.php" );
  if( isset( $_POST['intermedio'] ) ){
    $intermedio = $_POST['intermedio'];
    $keys_intermedio = array_keys( $intermedio );
    for( $i = 0; $i < count( $intermedio ); $i++ ){
      $sql3 = "update sinopsis set intermedio = ".$intermedio[$keys_intermedio[$i]]." where chapter = ".$keys_intermedio[$i];
      db_query( $sql3 );
    }
  }
  if( isset($_GET['eliminar_sinop']) ){
    $sql4 = "delete from sinopsis where chapter=".$_GET['eliminar_sinop'];
    db_query( $sql4 );
  }
  if( isset( $_POST['ano1'] )  ){
    $ano1 = $_POST['ano1'];
  }else{
    if( isset( $_GET['ano1'] ) ){
      $ano1 = $_GET['ano1'];
    }
  }
  if( isset( $_POST['mes1'] )  ){
    $mes1 = $_POST['mes1'];
  }else{
    if( isset( $_GET['mes1'] ) ){
      $mes1 = $_GET['mes1'];
    }
  }
  $temp =  trim( $mes1 );
  if( $mes1 < 10 && $temp[0] != '0' ){
    $mes1 = "0".$mes1;
  }
  $sinop_array = array();
  $title_array = array();
  $sql2 = "SELECT s.title, s.chapter, s.intermedio FROM sinopsis s, chapter c where year = '".$ano1."' and month = '".$mes1."' and s.chapter = c.id order by c.title";
  $res2 = db_query( $sql2 );
  while( $data2 = db_fetch_array( $res2 ) ){
    $chapter = new Chapter( $data2['chapter'] );
    $sinop_array[$chapter->getId()] = $chapter->getTitle();
	$title_array[$chapter->getId()] = $data2['title'];
    $channels_array[ $chapter->getId()] = $chapter->getChannelsfromYearMonth( $ano1, $mes1 );
    $int_array[$chapter->getId()] = $data2['intermedio'];
  }
  $num_sinopsis = count( $sinop_array );
  $sinop_keys = array_keys( $sinop_array );
  $sql4 = "select * from sinopsis where intermedio = 1 and year = '".$ano1."' and month = '".$mes1."'";
  $query4 = db_query( $sql4 );
  $num_intermedio = db_numrows( $query4 );
?>
   <table width="60%" border="0" cellspacing="0" cellpadding="0" class="textos">
<tr>
<td>
Intermedio
</td>
</tr>
</table>
<script>
	function zeroFill()
	{
		var str = "";
		var el = document.forms["intermedio_sinopsis"].elements;
		for(var i=0; i<el.length; i++)
		{
			try{if(el[i].type == "text" && el[i].name.match(/^intermedio/) ) el[i].value="1";}catch(e){}
		}
	}
</script>
<form name="intermedio_sinopsis" action="intermedio.php" method="post">
   <table width="50%" border="1" cellspacing="0" cellpadding="0" class="textos">
   	<tr><td align="center"><a href="#;" onclick="zeroFill()">Llenar</a></td><td>&nbsp</td><td>&nbsp</td></tr>
			<?
for( $i = 0; $i < count( $sinop_array ); $i++ ){
  $channel_str = "";
  for( $i1 = 0; $i1 < count( $channels_array[$sinop_keys[$i]] ); $i1++ ){
    $channel_str .= $channels_array[$sinop_keys[$i]][$i1]->getName().", ";
  }
?>
 <tr>
<td>
<input type="text" size="3" name="intermedio[<?=$sinop_keys[$i]?>]" value="<?=$int_array[$sinop_keys[$i]]?>">
</td>
<td>
   <a href='#' style="text-decoration:none" onClick='window.open("ppa/info_chapter.php?id=<?=$sinop_keys[$i]?>", null, "height=150,width=300,status=yes,toolbar=no,menubar=no,location=no,scrollbars=yes" )'>
<? if( $int_array[$sinop_keys[$i]] == 1 ){?>
  <font color='red'><b><?=$title_array[$sinop_keys[$i]]?>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;<?=$sinop_array[$sinop_keys[$i]]?></b></font>,<b><?=$channel_str?></b>
<?}else{?>
	<?=$title_array[$sinop_keys[$i]]?>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;<?=$sinop_array[$sinop_keys[$i]]?>,<b><?=$channel_str?></b>
<?}?>
</a>
 </td>
  <td><a href="intermedio.php?eliminar_sinop=<?=$sinop_keys[$i]?>&ano1=<?=$ano1?>&mes1=<?=$mes1?>">Eliminar</a></td>
</tr>
<? } ?>
   </table>
<table width="60%" border="0" cellspacing="0" cellpadding="0" class="textos">
<tr>
<td align="center">
<input type="hidden" name="ano1" value="<?=$ano1?>">
<input type="hidden" name="mes1" value="<?=$mes1?>">
<input type="hidden" name="list_sinop" value="<?=$_POST['list_sinop']?>">
<input type="submit" name="asignar" value="Asignar">
</td>
</tr>
</table>
</form>
Se han asignado <?=$num_sinopsis?> programas<br>
Intermedio = <?=$num_intermedio?>
