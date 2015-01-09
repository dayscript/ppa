<?
require_once( "ppa/config.php" );
  if( isset( $_POST['chapter_select'] ) ){

    for( $i = 0 ; $i < count( $_POST['chapter_select'] ); $i++ ){
	 if( strstr( $_POST['slot_title_select'][$i], "'" ) ){
      	$sql1 = 'insert into sinopsis( chapter, year, month, title ) values( '.$_POST['chapter_select'][$i].', "'.$_POST['ano1'].'", "'.$_POST['mes1'].'", "'.stripslashes( $_POST['slot_title_select'][$_POST['chapter_select'][$i]] ).'")';
	  }else{
		$sql1 = "insert into sinopsis( chapter, year, month, title ) values( ".$_POST['chapter_select'][$i].", '".$_POST['ano1']."', '".$_POST['mes1']."', '".stripslashes( $_POST['slot_title_select'][$_POST['chapter_select'][$i]] )."')";	  
	  }
      db_query( $sql1 );
    }
  }
  if( $_POST['mes1'] <= 9 && $_POST['mes1'][0] != "0"){
    $_POST['mes1'] = "0".$_POST['mes1'];
  } 
  $numdays = date("d", mktime(0, 0, 0, $_POST['mes1']+1, 0, $_POST['ano1']) );
  $sql = "SELECT  c.id, c.name FROM channel c, slot s, slot_chapter sc WHERE c.id = s.channel AND s.date =  '".$_POST['ano1']."-".$_POST['mes1']."-".$numdays."' AND sc.slot = s.id AND s.title != '' GROUP  BY c.id";
  $results = db_query($sql);
  $sql2 = "select chapter from sinopsis where month='".$_POST['mes1']."' and year='".$_POST['ano1']."'";
  $results2 = db_query($sql2);
  $num_sinopsis = db_numrows( $results2 );
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
          Escoja los canales:<br>
          <table width="400" border="1" cellspacing="0" cellpadding="0" class="textos">
            <tr>
			<?
                        $i = 0;
			while( $row = db_fetch_array( $results ) ){			  
?>
			  <td><input type="checkbox" checked name="channels1[]" value="<?=$row['id']?>"><?=$row['name']?></td>
			     <?
			     $i++;
			  if($i%3 == 0){
                           ?></tr><tr><?
	                   }			  
			  
			}
			?>
            </tr>
          </table>
          <input type="button" name="Submit2" value="Seleccionar Todos" onClick="javascript:selectAll();">
          <input type="button" name="Submit2" value="Deseleccionar Todos" onClick="javascript:deselectAll();">
          <br>
          <input type="submit" name="Submit" value="Siguiente">
        </form>
      </div></td>
  </tr>
</table>
Se han asignado <?=$num_sinopsis?> programas
