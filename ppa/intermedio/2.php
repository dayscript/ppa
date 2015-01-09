<?
require_once( "ppa/config.php" );
?>
<?
  if( $_POST['mes'] <= 9 ){
    $_POST['mes'] = "0".$_POST['mes'];
  }
  $numdays = date("d", mktime(0, 0, 0, $_POST['mes']+1, 0, $_POST['ano']) );
 // $sql = "SELECT  c.id, c.name FROM channel c, slot s WHERE c.id = s.channel AND s.date =  '".$_POST['ano']."-".$_POST['mes']."-".$numdays."' AND s.title != '' GROUP  BY c.id";
  $sql = "SELECT  c.id, c.name, c.description FROM channel c, slot s WHERE c.id = s.channel AND s.date =  '".$_POST['ano']."-".$_POST['mes']."-27' AND s.title != '' GROUP  BY c.id ORDER BY c.name";
  $query = db_query( $sql );


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
		<input type="hidden" name="mes" value="<?=$_POST['mes']?>">
		<input type="hidden" name="ano" value="<?=$_POST['ano']?>">
          Escoja los canales:<br>
          <table width="400" border="1" cellspacing="0" cellpadding="0" class="textos">
            <tr>
			<?
                        $i = 0;
			while( $row = db_fetch_array( $query ) ){
			  ?>
			  <td><input type="checkbox" checked name="channels[]" value="<?=$row['id']?>"><?=$row['name']?><br><font color="#999999"><?=$row['description']?></font></td>
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
