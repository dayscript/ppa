<?
session_start();
require_once("ppa/config.php");
//require_once("ppa/header.php");
set_time_limit( -1 );
if( !isset( $_SESSION['programs_found'] ) ){
  session_register( 'programs_found' );
}
$programs_found = $_SESSION['programs_found'];
function find_chapter( $aTitle, $aChapters, $aChapters1 ){
  $chapter_array = array();	
  $keys = array_keys( $aChapters );
  $aTitle = trim( $aTitle );
  $index = strtoupper($aTitle[0]);
  $keys = array_keys( $aChapters[$index] );
  for( $i = 0; $i < count( $aChapters[$index] ); $i++ ){
  	if( trim( strtoupper( $aChapters[$index][$keys[$i]] ) ) == trim( strtoupper( $aTitle ) )  ){
	  $chapter_array[] = $keys[$i];
	}
  }
  $keys1 = array_keys( $aChapters1[$index] );
  for( $i = 0; $i < count( $aChapters1[$index] ); $i++ ){
  	if( trim( strtoupper( $aChapters1[$index][$keys1[$i]] ) ) == trim( strtoupper( $aTitle ) ) && !in_array( $keys1[$i], $chapter_array ) ){
	  $chapter_array[] = $keys1[$i];
	}
  }
  /*
  for( $i = 0; $i < count( $aChapters ); $i++ ){
    $string_arr = explode( "|", $aChapters[$keys[$i]] );
    for( $j = 0 ; $j < count( $string_arr ); $j++ ){;
      if( trim( strtoupper( $string_arr[$j] ) ) == trim( strtoupper( $aTitle ) )  ){
	     $chapter_array[] = $keys[$i];
		 $j = count( $string_arr )+1;
      }
    }
  }
  */
  if( count( $chapter_array ) == 1 ){
  	return $chapter_array[0];
  }else{
  	return false;
  }
}

if( isset( $_GET['channel'] ) ){
	$_POST['channel'] = $_GET['channel'];
}
if( isset( $_GET['ano'] ) ){
	$_POST['ano'] = $_GET['ano'];
}
if( isset( $_GET['mes'] ) ){
	$_POST['mes'] = $_GET['mes'];
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PPA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.borrar{
 color: #009900;
}
.link1
{
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #444444;
	cursor: pointer;
	text-decoration: underline
}
.link1:hover
{
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #F3893A;	
	cursor: pointer;
	text-decoration: underline
 }
body,table {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #444444;
}
input, select, textarea, {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #444444;
	border: 1px solid #000000;
	background-color: #DDEEFF;
}
.large {
	width: 300;
}
-->
</style>
<script>
function spu(id,title,ttime, ddate, duration, channel, mes, ano, tipo ) //Search PopUp
{
	if( tipo )
		window.open( "ppa/chapter2.php?slot=" + id + "&program=" + title + "&time=" + ttime + "&date=" + ddate + "&duration=" + duration + "&channel=" + channel + "&mes=" + mes + "&ano=" + ano + "&tipo=" + tipo, "_blank", "resizable=yes,height=450,width=400,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes" )
	else
		window.open( "ppa/buscar_chapter2.php?slot=" + id + "&program=" + title + "&time=" + ttime + "&date=" + ddate + "&duration=" + duration + "&channel=" + channel + "&mes=" + mes + "&ano=" + ano, "_blank", "resizable=yes,height=250,width=600,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes" )
}
</script>
</head>

<body>
<?
if( isset( $_POST['paso'] ) ){
  $_GET['paso'] = $_POST['paso'];
}
if( !isset( $_GET['paso'] ) ){
	$_GET['paso'] = 1;
}
if( $_GET['paso']  == 1  ){
  $mes_array = array( "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" );
  $ppa = new PPA(1);
  $channels = $ppa->getChannels();
  $mes = date("n");
  $ano = date("Y");
?>
<br>
<form name="f1" id="f1" method="POST" action="ppa.php">
 <table width="600" border="0" cellspacing="1" cellpadding="1">
  <tr>
  <td align="center"> 
 <strong>Canal:</strong>
 <select name="channel">
<? for( $i = 0 ; $i < count( $channels ); $i++ ){ ?>
 <option value="<?=$channels[$i]->getId()?>"><?=$channels[$i]->getName()?></option>
<? }?>
 </select>
 </td>
 </tr>
 <tr>
 <td align="center"><strong>A�o: </strong>
 <select name="ano">
 <? for($i = ($ano-1) ; $i <= ( $ano + 4 ); $i++  ){ 
      if( $i == $ano  ){
 ?>
 <option value="<?=$i?>" selected><?=$i?></option>	
 <?    }else{?>
 <option value="<?=$i?>"><?=$i?></option>	
     <?}?>					  
 <? } ?>
 </select>
 </td>
 </tr>
 <tr>
 <td align="center"><strong>Mes :</strong>
 <select name="mes">
<? for( $i = 0; $i < count( $mes_array ); $i++ ){ 
     if( ( $i+1 ) == ($mes+1) ){
?>
<option selected value="<?=($i+1)?>"><?=$mes_array[$i]?></option>
<?   }else{ ?>
<option value="<?=($i+1)?>"><?=$mes_array[$i]?></option>
   <?}?>
<? } ?>
 </select>
 </td>
 </tr>
 <tr>
 <td align="center">
 <input type="hidden" name="asign_chapters" value="1">
 <input type="hidden" name="paso" value="2">
 <input type="submit" name="next" value="Siguiente">
 </td>
 </tr>
 </table>
</form>
<?
}else{
  if( $_GET['paso'] == 2 ){
    $channel = new Channel( $_POST['channel'] );
	?>
	  <table>
      <tr>
  	  <td>&nbsp;
	  
	  </td>
        <td align="center">
          <h3>
            <?=$channel->getName()?></br>
            <?=$channel->getDescription()?>
        </h3></td>
      </tr>
      <tr>
  	  <td>&nbsp;
	  
	  </td>	  
        <td align="center">
          <h3>A&ntilde;o:
              <?=$_POST['ano']?>
        </h3></td>
      </tr>
      <tr>
	    <td>&nbsp;
	  
	  </td>
        <td align="center">
          <h3>Mes:
              <?=$_POST['mes']?>
        </h3></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>	
	<?
  	$find_array = array();
    $slot_array = array();
    $slot_array_notfound = array();
    $chapters = array();
	$chaptersOr = array();
	$chaptersEs = array();
    $numdays = date("d",mktime(0,0,0,($_POST['mes']+1),0, $_POST['ano']));
    if( $_POST['mes'] <= 9 && !strstr( $_POST['mes'], "0" ) ){
      $_POST['mes'] = "0".$_POST['mes'];
    }
    $sql = "SELECT sc.slot FROM slot_chapter sc, slot s WHERE s.channel = ".$_POST['channel']." AND s.date >= '".$_POST['ano']."-".$_POST['mes']."-01"."' AND s.date <= '".$_POST['ano']."-".$_POST['mes']."-".$numdays."' AND s.id = sc.slot ";
    $query = db_query( $sql );
    $i = 0;
    $str = "";
    if( db_numrows( $query ) > 0 ){
      $str .= "(";
      while( $row = db_fetch_array( $query ) ){
		if( $i+1 == db_numrows( $query ) ){
	  	  $str .= $row['slot'];
		}else{
	  	  $str .= $row['slot'].",";
		}
		$i++; 
      }
      $str .= ")";
      $sql1 = "SELECT id, title FROM slot WHERE date >= '".$_POST['ano']."-".$_POST['mes']."-01"."' AND date <= '".$_POST['ano']."-".$_POST['mes']."-".$numdays."' AND channel = ".$_POST['channel']." AND id NOT IN ";
      $sql1 .= $str;
      $query1 = db_query( $sql1 );
      while( $row1 = db_fetch_array( $query1 ) ){
		$slot_array[$row1['id']] = $row1['title'];
      }
    }else{
      $sql1 = "SELECT id, title FROM slot WHERE date >= '".$_POST['ano']."-".$_POST['mes']."-01"."' AND date <= '".$_POST['ano']."-".$_POST['mes']."-".$numdays."' AND channel = ".$_POST['channel'];
      $sql1 .= $str;
      $query1 = db_query( $sql1 );
      while( $row1 = db_fetch_array( $query1 ) ){
	    $slot_array[$row1['id']] = $row1['title'];
      }
    }
    $sql = "SELECT id, title, spanishTitle FROM chapter";
    $query = db_query( $sql );
    while( $row = db_fetch_array( $query ) ){
      //$chapters[$row['id']] = $row['title']." | ".$row['spanishTitle'];
	  $title = trim( $row['title'] );
	  $spanishTitle = trim( $row['spanishTitle'] );
	  $chaptersOr[strtoupper($title[0])][$row['id']] = $row['title'];
	  $chaptersEs[strtoupper($spanishTitle[0])][$row['id']] = $row['spanishTitle'];
    }
    $keys = array_keys( $slot_array );
	if( count( $slot_array ) > 0 ){
    	$sql = "INSERT INTO slot_chapter( slot, chapter ) VALUES ";
    	for( $i = 0; $i < count( $keys ); $i++ ){
	  		if( !array_key_exists( $slot_array[$keys[$i]], $find_array ) ){
         		$chapter = find_chapter( $slot_array[$keys[$i]], $chaptersOr, $chaptersEs );
	  		}else{
         		$chapter = $find_array[$slot_array[$keys[$i]]];	
	  		}

			if( $chapter ){
			  $find_array[$slot_array[$keys[$i]]] = $chapter;
			  $sql .= "( ".$keys[$i].", ".$chapter." ), ";
			}else{
			  if( !$chapter ){
			    $slot_array_notfound[] = $keys[$i];
			  }
			}

    	}
	$sql = substr( $sql, 0, strrpos( $sql, "," ) );
		db_query( $sql );
	
		$str = "( ";
		for( $i = 0; $i < count( $slot_array_notfound ); $i++ ){
			if( ($i+1) == count( $slot_array_notfound ) ){
				$str .= $slot_array_notfound[$i];
			}else{
				$str .= $slot_array_notfound[$i].", ";
			}
		}
		$str .= ")";
		$sql = "SELECT id, time, date, duration, title FROM slot WHERE id IN ";
		$sql .= $str." ORDER BY date, time ";
		$query = db_query( $sql );
		$date = "";
	}
	?>
	<tr>
	<td>&nbsp;
	
	</td>
	<td align="center">	
	<strong><a href="ppa.php?exit_asign_chapter=1">Salir</a></strong>
	</td>
	</tr>
	<?
	$keys = @array_keys( $programs_found );
	$inserted_slot = false;
	while( $row = db_fetch_array( $query ) ){		
		for( $i = 0; $i < count( $programs_found); $i++ ){
			if( $programs_found[$keys[$i]] == $row['title'] ){
				$sql1 = "INSERT INTO slot_chapter(slot, chapter) VALUES ";
				$sql1 .= "( ".$row['id'].", ".$keys[$i]." )";
				echo $row['date']." - ".$row['time']." - ".$row['title']."<br>";
				db_query( $sql1 );
				$inserted_slot = true;
			}
		}
		$row['title'] = addslashes( $row['title'] );
		if( !$inserted_slot ){
			if( $date != $row['date'] ){
	?>
		<tr>
		 <td><b><font color="#42c472"><?=$row['date']?></font></b></td>
		 </tr>
	<?	
		 	}
	?>
	<tr>
	<td><b><font color="#217199"><?=$row['time']?></font></b><br></td>
	<td>
	<? echo $row['title']; $row['title'] = urlencode( $row['title'] );?>
	&nbsp;&nbsp;&nbsp;&nbsp;
	                        <a class="link1" onClick="spu( '<?=$row['id']?>', '<?=$row['title']?>', '<?=$row['time']?>', '<?=$row['date']?>', '<?=$row['duration']?>', '<?=$_POST['channel']?>', '<?=$_POST['mes']?>', '<?=$_POST['ano']?>' )">Buscar</a> / 
<? /*	                        <a class="link1" onClick="spu( '<?=$row['id']?>', '<?=$row['title']?>', '<?=$row['time']?>', '<?=$row['date']?>', '<?=$row['duration']?>', '<?=$_POST['channel']?>', '<?=$_POST['mes']?>', '<?=$_POST['ano']?>', 'ninguno' )">Nuevo</a> / */ ?>
	                        <a class="link1" onClick="spu( '<?=$row['id']?>', '<?=$row['title']?>', '<?=$row['time']?>', '<?=$row['date']?>', '<?=$row['duration']?>', '<?=$_POST['channel']?>', '<?=$_POST['mes']?>', '<?=$_POST['ano']?>', 'series' )">Nueva_Serie </a>/ 
	                        <a class="link1" onClick="spu( '<?=$row['id']?>', '<?=$row['title']?>', '<?=$row['time']?>', '<?=$row['date']?>', '<?=$row['duration']?>', '<?=$_POST['channel']?>', '<?=$_POST['mes']?>', '<?=$_POST['ano']?>', 'movie' )">Nueva_Pelicula</a> / 
	                        <a class="link1" onClick="spu( '<?=$row['id']?>', '<?=$row['title']?>', '<?=$row['time']?>', '<?=$row['date']?>', '<?=$row['duration']?>', '<?=$_POST['channel']?>', '<?=$_POST['mes']?>', '<?=$_POST['ano']?>', 'especiales_documentales' )">Nuevo_Especial/Documental</a>
	</td>
	</tr>
	<?
	  }
	  $inserted_slot = false;
		$date = $row['date'];
	}
	?>
	</table>
	<?
  }
?>
<? }?>
</body>
</html>