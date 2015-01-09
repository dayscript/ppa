<?
require_once( "ppa/config.php" );
  $numdays = date("d", mktime(0, 0, 0, $_POST['mes1']+1, 0, $_POST['ano1']) );
  $str = "( ";
  for( $i = 0 ; $i < count( $_POST['channels1'] ); $i++ ){
    if( ($i+1) == count( $_POST['channels1'] ) ){
      $str .= $_POST['channels1'][$i];
    }else{
      $str .= $_POST['channels1'][$i].", ";
    }
  }
  $str .= " )";
  $sql = "SELECT c.name, c1.id, c1.title, s.title FROM channel c, slot s, slot_chapter sc, chapter c1 WHERE c.id = s.channel AND s.id = sc.slot AND sc.chapter = c1.id AND s.date >= '".$_POST['ano1']."-".$_POST['mes1']."-01"."' AND s.date <= '".$_POST['ano1']."-".$_POST['mes1']."-".$numdays."' AND c.id IN ".$str." AND c1.serie = 0 GROUP BY s.title ORDER BY c.shortName";
  $query = db_query( $sql );
  /*
  for( $i = 0 ; $i < count( $_POST['channels1'] ); $i++ ){
    $channel =  new Channel( $_POST['channels1'][$i] );
    $channels[] = $channel;
    $chapters[] = $channel->getChaptersInYearMonth( $_POST['ano1'], $_POST['mes1'] );
  }
  */
  /*
  for( $i = 0; $i < count( $chapters ); $i++ ){
    for( $j = 0; $j < count( $chapters[$i] ); $j++ ){
      if( !in_array( $chapters[$i][$j]->getId(), $chapters_id ) ){
	$chapters_id[] = $chapters[$i][$j]->getId();
      }
    }
  }
  */
$sinop_array = array();
$sql2 = "select chapter from sinopsis where month = '".$_POST['mes1']."' and year = '".$_POST['ano1']."'";
$res2 = db_query( $sql2 );
while( $data2 = db_fetch_array( $res2 ) ){
  $sinop_array[] = $data2['chapter'];
}

?>
<script language="JavaScript">
function selectAll(){
	for (var i=0; i<document.forms['form1'].elements.length; i++){
                if (!document.forms['form1'].elements[i].checked){
					document.forms['form1'].elements[i].checked = true;
                }
        }
}
function deselectAll(){
	for (var i=0; i<document.forms['form1'].elements.length; i++){
                if (document.forms['form1'].elements[i].checked){
					document.forms['form1'].elements[i].checked = false;
                }
        }
}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="textos">
  <tr>
    <td><div align="center">
        <form name="form1" method="post" action="intermedio.php">
		<input type="hidden" name="mes1" value="<?=$_POST['mes1']?>">
		<input type="hidden" name="ano1" value="<?=$_POST['ano1']?>">
          Puede seleccionar el programa el cual quiere adicionar al listado de sinopsis:<br>
            <table width="400" border="1" cellspacing="0" cellpadding="0" >                    
			<?
                        $shown_channels = array();
			while( $row = db_fetch_array( $query ) ){
                        ?> 
                                <? if( !in_array( $row['name'], $shown_channels ) ){
				  $shown_channels[] = $row['name'];
                                ?>
					<tr>
					<td colspan="2" align="center">
				<strong><?=strtoupper($row['name'])?></strong>
				    </td>
					</tr>
                                <? }?>
                                 <tr>
                                    <? if( in_array( $row['id'], $sinop_array ) ){?>   
				  <td>
                                    <a href='#' style="text-decoration:none" onClick='window.open("ppa/info_chapter.php?id=<?=$row['id']?>", null, "height=150,width=300,status=yes,toolbar=no,menubar=no,location=no,scrollbars=yes" )'><font color="#FF9900"><strong><?=$row[3]?>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;<?=$row[2]?></strong></font></a>
                                    </td>
                                   <td>&nbsp;</td>
				    <?}else{?>
				  <td>
                                    <a href='#' style="text-decoration:none" onClick='window.open("ppa/info_chapter.php?id=<?=$row['id']?>", null, "height=150,width=300,status=yes,toolbar=no,menubar=no,location=no,scrollbars=yes" )'><?=$row[3]?>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;<?=$row[2]?></a>
                                    </td>
                                   <td>
								   <input type="checkbox" name="chapter_select[]" value="<?=$row['id']?>">
   								   <input type="hidden" name="slot_title_select[<?=$row['id']?>]" value="<?=$row[3]?>">
								   </td>
                                    <? } ?>
				</tr>                  
			<?
		   }
		?>
              </table>
          <br>
          <input type="button" name="Submit2" value="Seleccionar Todos" onClick="javascript:selectAll();">
          <input type="button" name="Submit2" value="Deseleccionar Todos" onClick="javascript:deselectAll();">
          <br>
          <input type="submit" name="Submit" value="Asignar">
        </form>
      </div></td>
  </tr>
</table>
