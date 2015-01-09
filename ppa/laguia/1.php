<?
   $mes = date("n");
   $ano = date("Y");
   $mes_array = array( "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" );
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="textos">
  <tr>
    <td><div align="center">
        <form name="form1" method="post" action="laguia.php">
          Mes : 
          <select name="mes" id="mes">
<? for( $i = 0; $i < count( $mes_array ); $i++ ){ 
     if( ( $i+1 ) == ($mes+1) ){
?>
<option selected value="<?=($i+1)?>"><?=$mes_array[$i]?></option>
<?   }else{ ?>
<option value="<?=($i+1)?>"><?=$mes_array[$i]?></option>
   <?}?>
<? } ?>
          </select>
          / 
          <select name="ano" id="ano">
<? for($i = ($ano-1) ; $i <= ( $ano + 4 ); $i++  ){ 
      if( $i == $ano  ){
 ?>
 <option value="<?=$i?>" selected><?=$i?></option>	
 <?    }else{?>
 <option value="<?=$i?>"><?=$i?></option>	
     <?}?>					  
 <? } ?>
          </select>
          <br>
          <input type="submit" name="Submit" value="Siguiente">
        </form>
      </div></td>
  </tr>
</table>
