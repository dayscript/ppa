<?
require_once( "ppa/config.php" );
set_time_limit( -1 );  
define( "MAX_CHAR_LENGTH", 25 );
  $channels = "(0";
  for($i=0; $i<count($_POST['channels']); $i++){
    $channels .= ", " . $_POST['channels'][$i];
  }
  $channels .= ")";
  $slots_array = array();
  $max = date( "j", mktime(0, 0, 0, $_POST['mes'] + 1, 1, $_POST['year']) - 1 );
  for( $k = 1; $k <= $max; $k++ ){
    if( $k < 10 ){
      $dia = "0".$k;
    }else{
      $dia = $k;
    }
    // $sql = "SELECT S.time, C.shortName ,CH.spanishTitle, C.id, S.id, S.date , C.id, CH.title FROM channel C, slot S, slot_chapter SC, chapter CH WHERE C.id IN $channels  AND S.channel = C.id AND S.date = '".$_POST['ano']."-".$_POST['mes']."-".$dia."' AND S.id = SC.slot AND SC.chapter = CH.id order by S.time, C.shortname ";	
    $sql = "SELECT S.time, C.shortName ,S.title, C.id, S.id, S.date  FROM channel C, slot S WHERE C.id IN $channels  AND S.channel = C.id AND S.date = '".$_POST['ano']."-".$_POST['mes']."-".$dia."' ORDER BY C.shortName, time";
    $results = db_query($sql);
    while( $row = db_fetch_array( $results ) ){
      $timediff = $_POST['channels_time'][$row[3]];
      $row[0] = date( "H:i", strtotime( $row[0] ) );
      $date = date( "Y-m-d", strtotime( $row[5]." ".$row[0] )+ ((60*60)*$timediff) );
      $time = date( "H:i", strtotime( $row[5]." ".$row[0] )+ ((60*60)*$timediff) );
      if( trim( $row[2] ) != "" ){
	$slots_array[$date][$time][$row[1]] = ucwords(strtolower($row[2]));
      }else{
	$slots_array[$date][$time][$row[1]] = ucwords(strtolower($row[7]));
      }
    }
  }
  $keys = array_keys( $slots_array );
  for( $i = 0; $i < count( $keys ); $i++ ){
    $keys1 = array_keys( $slots_array[$keys[$i]] );
    ksort($slots_array[$keys[$i]], SORT_STRING);
  }
  
 ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="textos">
  <tr>
    <td><div align="center">
	      <table width="400" border="0" cellspacing="0" cellpadding="0" class="textos">
		  <tr><td align="center" colspan="3"><?=$_POST['ano']?>-<?=$_POST['mes']?></td></tr>
		<?
		$dias[] = "DOMINGO";
		$dias[] = "LUNES";
		$dias[] = "MARTES";
		$dias[] = "MIERCOLES";
		$dias[] = "JUEVES";
		$dias[] = "VIERNES";
		$dias[] = "SABADO";
		$max = date( "j", mktime(0, 0, 0, $_POST['mes'] + 1, 1, $_POST['year']) - 1 );
		for($j=1;$j<=$max;$j++){
			if($j<10)$dia = "0" . $j;
			else $dia = $j;			
			$date = $_POST['ano']."-".$_POST['mes']."-".$dia;
			$am = 0;
			$pm = 0;
			  ?>
                        <tr>
                        <td colspan="3" align="center" bgcolor="#FF9900"><b><font color="#FFFFFF">'<?=$dias[date("w",strtotime($_POST['ano'] . "-" . $_POST['mes'] . "-" . $dia))]?> <?=$dia?></font></b></td>
			</tr>
                         <?
		        for( $k = 0; $k < count( $slots_array[$date] ); $k++ ){
                          $keys = array_keys( $slots_array[$date] );
                        ?>
                         <?
                          if( date( "A", strtotime( $keys[$k] ) ) == "AM" && $am == 0 ){
                         ?>
                        <tr>
                         <td colspan="3" align="center"><b><font color="#FF9900">AM</font></b></td>
          	        </tr>
                         <?
                            $am = 1;
                           }
						  ?>
                         <?
                          if( date( "A", strtotime( $keys[$k] ) ) == "PM" && $pm == 0 ){
                         ?>
                        <tr>
                         <td colspan="3" align="center"><b><font color="#FF9900">PM</font></b></td>
          	        </tr>
                         <?
                            $pm = 1;
                           }

			   $keys1 = array_keys( $slots_array[$date][$keys[$k]] );
			   for( $m = 0; $m < count( $slots_array[$date][$keys[$k]] ); $m++ ){
			     ?>
                   <tr>
				   <? if( $m == 0 ){?>
                    <td align="right">'<?=date("h:i", strtotime( $keys[$k] ))?>&nbsp;</td>
					<? }else{ ?>
                    <td align="right">'&nbsp;</td>
					<? } ?>
			     <td align="center">&nbsp;<b><?=$keys1[$m]?></b>&nbsp;</td>						      
				 <?
				 if( strlen( $slots_array[$date][$keys[$k]][$keys1[$m]] ) > MAX_CHAR_LENGTH ){
					$pos = strrpos(substr($slots_array[$date][$keys[$k]][$keys1[$m]],0,MAX_CHAR_LENGTH)," ");
					$t1 = substr($slots_array[$date][$keys[$k]][$keys1[$m]],0,$pos);
					$t2 = substr( substr($slots_array[$date][$keys[$k]][$keys1[$m]],$pos+1), 0, MAX_CHAR_LENGTH );
				?>
 			     <td align="left"><?=$t1?></td>
				 </tr>
				 <tr>
                  <td align="right">'&nbsp;</td><td align="center">&nbsp;&nbsp;</td><td align="left"><?=$t2?></td>
				 </tr>				 						      
				<?	
				 }else{
				?>
 			     <td align="left"><?=$slots_array[$date][$keys[$k]][$keys1[$m]]?></td>						      
 				 </tr>
				 <? } ?>
                   <?
			   }

			}			
		}
		?>
		</table>
      </div></td>
  </tr>
</table>
