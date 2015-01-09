<? 
require_once( "ppa/config.php" );
 ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="textos">
  <tr>
    <td><div align="center">
        <form name="form1" method="post" action="intermedio.php">
		<input type="hidden" name="mes" value="<?=$_POST['mes']?>">
		<input type="hidden" name="ano" value="<?=$_POST['ano']?>">
          Puede especificar cambios de hora para cada uno de los canales:<br>
          <table width="400" border="1" cellspacing="0" cellpadding="0" class="textos">
			<?
 $channels = $_POST['channels'];
 $str = "(";
 for( $i = 0; $i < count( $channels ); $i++ ){
   if( $i+1 == count( $channels ) ){
     $str .= $channels[$i];
   }else{
     $str .= $channels[$i].", ";
   }
 } 
 $str .= ")";
   
 $sql = "SELECT id, name FROM channel WHERE id IN ".$str ."ORDER BY name";
 $query = db_query( $sql );
 while( $row = db_fetch_array( $query ) ){
   $name = strtoupper( $row['name'] );
 ?><tr>
				  <td><?=$name?></td>
				  <td><input type="text" name="channels_time[<?=$row['id']?>]" value="1" size="2" maxlength="2">
				  	<input type="hidden" name="channels[]" value="<?=$row['id']?>">
				  </td>
				</tr><?
			}

			?>
          </table>
          <br>
          <input type="submit" name="Submit" value="Siguiente"><br>
	  <input type="submit" name="Descargar RTF" value="Descargar RTF">
        </form>
      </div></td>
  </tr>
</table>
