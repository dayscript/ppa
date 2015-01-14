<?
require_once( 'ppa/include/functions_ver1.php' );
require_once( 'ppa/include/functions_ver2.php' );
require_once( 'ppa/include/functions_ver3.php' );
require_once( 'ppa/include/functions_ver4.php' );
require_once( 'ppa/include/functions_ver5.php' );
require_once("ppa/config.php");
session_start();
session_register( 'arry0' );
session_register( 'arry1' );
session_register( 'arry2' );
session_register( 'slots' );
if( !isset( $_SESSION['id_session'] ) ){
  $_SESSION['id_session'] = md5( date( "h:i s" ) );
  session_register( 'id_session' );
}
set_time_limit( -1 );

/* Si existe un slot en el array de slots tal que dado una fecha , hora y programa  tenga la misma fecha, hora y que el levenshtein del programa sea menor que tres, retorna ese slot, sino retorna falso*/
function slotfromDateHourProgram( $slots, $date, $hour, $program ){
  for( $i = 0 ; $i < count( $slots ); $i++ ){
    $slot_date = $slots[$i]->getDate();
    $slot_time = $slots[$i]->getTime();
    $chapters = $slots[$i]->getChapters();
    $chapters = $chapters->getChapters();
	$chapter = $chapters[0];
    $chapter_title = $chapter->getTitle();
	/*
    if( trim( $slot_date ) == trim( $date ) && trim( $hour ) == trim( $slot_time ) && levenshtein( strtolower( $program ), strtolower( $chapter_title) ) < 3   ){
      return $slots[$i];
    }
	*/
	/*
	echo "Date: ".$date."<br>";
	echo "Slot Date: ".$slot_date."<br>";	
	echo "Hour: ".$hour."<br>";
	echo "Slot Hour: ".$slot_time."<br>";	
	echo "Program: ".$program."<br>";
	echo "Program1: ".$chapter_title."<br>";
    echo "--------------------------------<br>";
	*/
	if( strstr( strtolower( $program ), strtolower( $chapter_title) ) ){
      $slot = new Slot();
  	  $chapter = new Chapter( $chapter->getId());
	  $slot->setDate( $date );
	  $slot->setTime( $hour );
	  $slot->addChapter( $chapter );
	  $slot->setChannel( $slots[$i]->getChannel() );
	  $slot->setDuration( $slots[$i]->getDuration() );	   
	  return $slot;	  
	}
	/*
    if( trim( $slot_date ) == trim( $date ) && trim( $hour ) == trim( $slot_time ) && strstr( strtolower( $program ), strtolower( $chapter_title) ) ){
	  echo "ENTRE!!";
      return $slots[$i];
    }
*/
  }
  return false;	
}

function find_array( $needle, $haystack ){
  for( $i = 0 ; $i < count( $haystack ); $i++ ){
       if( strtotime( $haystack[$i] ) == strtotime( $needle ) ){
	 return true;
       }
  }
  return false;
}

$ppa = new PPA(1);
if( isset( $_POST['show_program'] ) ){
  $_GET['show_program'] = $_POST['show_program'];
}
if( isset( $_POST['asign_program'] ) ){
  $_GET['asign_program'] = $_POST['asign_program'];
}
if( isset( $_POST['asign_programnew'] ) ){
  $_GET['asign_programnew'] = $_POST['asign_programnew'];
}
if( isset( $_POST['paso'] ) ){
  $_GET['paso'] = $_POST['paso'];
}
if( isset( $_POST['tipo_archivo'] ) ){
  $_GET['tipo_archivo'] = $_POST['tipo_archivo'];
}
if( isset( $_POST['dia'] ) ){
  $_GET['dia'] = $_POST['dia'];
}
if( isset( $_POST['mes'] ) ){
  $_GET['mes'] = $_POST['mes'];
}
if( isset( $_POST['ano'] ) ){
  $_GET['ano'] = $_POST['ano'];
}
if( isset( $_POST['id'] ) ){
  $_GET['id'] = $_POST['id'];
}
if( isset( $_GET['send_channel'] ) ){
  $_POST['send_channel'] = $_GET['send_channel'];
}
if( isset( $_GET['name'] ) ){
  $_POST['name'] = $_GET['name'];
}
if( isset( $_GET['shortName'] ) ){
  $_POST['shortName'] = $_GET['shortName'];
}
if( isset( $_GET['logo'] ) ){
  $_POST['logo'] = $_GET['logo'];
}
if( isset( $_GET['description'] ) ){
  $_POST['description'] = $_GET['description'];
}
if( isset( $_GET['ppa'] ) ){
  $_POST['ppa'] = $_GET['ppa'];
}

if(isset($_POST['send_channel'])){
	$m = new Channel($_GET['id'], $_POST['name'], $_POST['shortName'], $_POST['logo'],
	               $_POST['description'], false, $_POST['ppa']);
	$m->commit();
	$change_channel = true;
	die( "<script>window.close()</script>" );
} else if (isset($_GET['delete']) && $_GET['delete']){
	$sql = "select * from client_channel where channel = " . $_GET['id'];
	$results = db_query($sql);
	if(db_numrows($results) <= 0){
		$m = new Channel($_GET['id']);
		$m->erase();
		unset($m);
	} 
	$delete_channel = true;
}
	
if( isset( $_GET['borrar_slot'] ) && isset( $_GET['id_slot'] ) ){
	$slot = new Slot( $_GET['id_slot'] );
	$slot->erase();
	$borrar_slot = true;
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PPA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.borrar{
 color: #009900;
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
</style></head>

<body>
<? if( isset( $change_channel ) || isset( $delete_channel ) ){?>
<script language="JavaScript">
document.location = "ppa.php?channels=1";
</script>
<? } ?>
<?
if( $borrar_slot ){
?>
<script language="JavaScript">
var url = document.URL;
var pos = url.lastIndexOf("/");
var str = url.substring(0, pos+1);
document.location = str + '<?="ppa.php?show_program=1&paso=2&ano=".$_GET['ano']."&mes=".$_GET['mes']."&dia=".$_GET['dia']."&id=".$_GET['id']?>';
</script>
<?
}
?>
<?
//require_once("ppa/header.php"); 
?>
  <form name="f1" action="ppa.php" method="post" enctype="multipart/form-data">
    <table width="700" border="0" cellspacing="1" cellpadding="1">
      <?
if( (isset($_GET['add']) && $_GET['add']) || (isset($_GET['edit']) && $_GET['edit']) ){
	if(isset($_GET['edit']) && $_GET['edit']){
		$ch = new Channel($_GET['id']);
	} else {
		$ch = new Channel();
	}
	$name = $ch->getName();
	$shortName = $ch->getShortName();
	$logo = $ch->getLogo();
	$description = $ch->getDescription();
?>
      <tr>
        <td width="146"><div align="right"><strong>Nombre</strong></div></td>
        <td width="15">&nbsp;</td>
        <td width="439">
          <div align="left">
            <input class="large" type="text" name="name" value="<?=$name?>">
        </div></td>
      </tr>
      <tr>
        <td width="146"><div align="right"><strong>Nombre Corto</strong></div></td>
        <td width="15">&nbsp;</td>
        <td width="439">
          <div align="left"><input class="large" type="text" name="shortName" value="<?=$shortName?>">
        </div></td>
      </tr>
      <tr>
        <td width="146"><div align="right"><strong>Logo</strong></div></td>
        <td width="15">&nbsp;</td>
        <td width="439">
          <div align="left">
            <input class="large" type="text" name="logo" value="<?=$logo?>">
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Descripción</strong></div></td>
        <td>&nbsp;</td>
        <td>
          <div align="left">
            <textarea name="description" class="large" rows="3"><?=$description?></textarea>
        </div></td>
      </tr>
      <input type="hidden" name="ppa" value="<?=$ppa->getId()?>">
      <input type="hidden" name="id" value="<?=($_GET['add']?"0":$_GET['id'])?>">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="39" colspan="3" align="center"><input type="submit" name="send_channel" value="<?=($_GET['add']?"Crear":"Actualizar")?>" class="large">
            <br>
            <input type="button" name="send2" value="Regresar" class="large" onClick="window.close()">
        </td>
      </tr>
      <?
}else{
  if( ( isset( $_GET['asign_program'] ) || isset( $_GET['asign_programnew'] ) )  && isset( $_GET['paso'] ) && isset( $_GET['id'] )){
  	if( $_GET['paso'] == 1 ){
   $channel = new Channel( $_GET['id'] );
 ?>
      <tr>
        <td colspan="2" align="center"><strong><font size="4"><?=$channel->getName()?></font></strong></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Mes</strong></div></td>
        <td>
          <select name="mes">
            <option value="01">Enero</option>
            <option selected value="02">Febrero</option>
            <option value="03">Marzo</option>
            <option value="04">Abril</option>
            <option value="05">Mayo</option>
            <option value="06">Junio</option>
            <option value="07">Julio</option>
            <option value="8">Agosto</option>
            <option value="9">Septiembre</option>
            <option value="10" selected>Octubre</option>
            <option value="11">Noviembre</option>
            <option value="12">Diciembre</option>
          </select>
        </td>
      </tr>
      <tr>
        <td><div align="right"><strong>Año</strong></div></td>
        <td>
          <select name="ano">
            <option value="2003">2003</option>
            <option value="2004">2004</option>
            <option selected value="2005">2005</option>
            <option value="2006">2006</option>
            <option value="2007">2007</option>
            <option value="2008">2008</option>
            <option value="2009">2009</option>
            <option value="2010">2010</option>
          </select>
        </td>
      </tr>
      <tr>
        <td><div align="right"><strong>Tipo de archivo</strong></div></td>
        <td>
          <select name="tipo_archivo">
            <option value="1">Tipo 1</option>
            <option value="2">Tipo 2</option>
            <option value="3">Tipo 3</option>
            <option value="4">Tipo 4</option>
            <option value="5">Tipo 5</option>
          </select>
        </td>
      </tr>
      <tr>
        <td><div align="right"><strong>Archivo</strong></div></td>
        <td>
          <input name="archivo" type="file">
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center">
          <input type="hidden" name="id" value="<?=$_GET['id']?>">
	 <? if( isset( $_GET['asign_program'] ) ){ ?>
          <input type="hidden" name="asign_program" value="<?=$_GET['asign_program']?>">
	 <? }else{
             if( isset( $_GET['asign_programnew'] ) ){
          ?>
             <input type="hidden" name="asign_programnew" value="<?=$_GET['asign_programnew']?>">
         <?
	     }
	 }
	  ?>
          <input type="hidden" name="paso" value="2">
          <input type="submit" name="next" value="Siguiente" >
          <br>
          <input type="button" name="send2" value="Regresar" onClick="javascript:window.document.location.href='channels.php'">
        </td>
      </tr>
      <? } ?>
      <? if( $_GET['paso'] == 2 && isset( $_GET['mes'] ) && isset( $_GET['ano'] ) && isset( $_GET['tipo_archivo'] ) ){
   $channel = new Channel( $_GET['id'] );
  ?>
      <tr>
        <td align="center">
          <h3>
            <?=$channel->getName()?>
        </h3></td>
      </tr>
      <tr>
        <td align="center">
          <h3>A&ntilde;o:
              <?=$_GET['ano']?>
        </h3></td>
      </tr>
      <tr>
        <td align="center">
          <h3>Mes:
              <?=$_GET['mes']?>
        </h3></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <? 
   if( $_GET['mes'] == 1 ){
     $mes = 12;
     $ano = $_GET['ano']-1;
   }else{
     $mes = $_GET['mes']-1;
     $ano = $_GET['ano'];
   }
   /*
   if( !isset($_GET['dia'] ) ){
     $arry0 = $channel->createSlotsfromMonthYear( $mes, $ano, $_GET['mes'], $_GET['ano'] );     
   }else{
     $arry0 = array();
   }
   */
   if( $_GET['bd'] == 1 ){
     $arry1 = array();
     $arry2 = array();
     $link = mysql_pconnect("localhost", "intercable", "inter379");
     mysql_select_db("tvcable",$link);
	 if( isset( $_GET['dia'] ) ){
        $fecha = $_GET['ano']."-".$_GET['mes']."-".$_GET['dia'];	 
	 }else{ 
       $fecha = $_GET['ano']."-".$_GET['mes'];
	 }
	 $sql1 = "select id from p_channel where original = '".$channel->getName()."'";
	 $data1 = mysql_db_query("tvcable",$sql1,$link);
	 $row1 = mysql_fetch_array( $data1 );
	 $channel_id = $row1['id'];
	 $sql = "select * from p_program where date like '%".$fecha."%' and channel='".$channel_id."' order by date, starthour";
	 $data = mysql_db_query("tvcable",$sql,$link);
	 while( $row = mysql_fetch_array( $data ) ){
	   $starthour = date("H:i", strtotime($row['starthour']));
	   $arry1[$row['date']] .= $starthour."\t";
	   $title = $row['originalTitle'];
	   if( trim( $title ) == "" ){
    	   $title = $row['title'];   
	   }
       $arry2[$row['date']] .= $title."\t";
     }
   }else{
     if( trim( $_GET['tipo_archivo'] ) == 1 ){
       /*
     if( is_uploaded_file ( $_FILES['archivo']['tmp_name'] ) ){
       if( copy( $_FILES['archivo']['tmp_name'], 'tipo1.txt' ) ){
	 $copiar = true;
	 $file = file( 'tipo1.txt' );
	 $date_array = array();
	 for( $i = 0 ; $i < count( $file ); $i++ ){
	   $date = getDatefromRow( $file[$i] );
	   if( !in_array( $date, $date_array ) ){
	     $date_array[] = $date;
	   }
	 }
	 $arry1 = array();
	 $arry2 = array();
	 for( $i = 0 ; $i < count( $date_array ); $i++ ){
	   $date = $date_array[$i];
	   $arr = getHoursProgramsfromDate( $date, $file );
	   $arry1[$date] = $arr[0];
	   $arry2[$date] = $arr[1];
	 }			
       }else{
	 $copiar = false;
       }	
     }
       */
     }else{
       if( trim( $_GET['tipo_archivo'] ) == 2 ){
	 $file = "tipo2".$_SESSION['id_session'].".txt";
	 if( is_uploaded_file ( $_FILES['archivo']['tmp_name'] ) ){
	   if( copy($_FILES['archivo']['tmp_name'], $file ) ){
	     $copiar = true;
	     $mat = convertToMatrix( $file, $_GET['ano'], $_GET['mes'] );
	     $arr = array();
	     $arry1 = array();
	     $arry2 = array();
	     $keys = array_keys( $mat );
	     for( $i = 0; $i < count( $mat ); $i++ ){
	       $arr = getHoursProgramsfromMatrix( $mat, $keys[$i] );
	       $arry1[ $keys[$i] ] = $arr[0];
	       $arry2[ $keys[$i] ] = $arr[1];
	     } 
	   }else{
	     $copiar = false;
	   }
	 }else{
	   if( isset( $_GET['dia'] ) && trim( $_GET['dia'] ) != "" && is_file( $file )){
	     $mat = convertToMatrix( $file, $_GET['ano'], $_GET['mes'] );
	     $arr = array();
	     $arry1 = array();
	     $arry2 = array();
	     $arr = getHoursProgramsfromMatrix( $mat, $_GET['ano']."-".$_GET['mes']."-".$_GET['dia'] );
	     $arry1[ $_GET['ano']."-".$_GET['mes']."-".$_GET['dia'] ] = $arr[0];
	     $arry2[ $_GET['ano']."-".$_GET['mes']."-".$_GET['dia'] ] = $arr[1];
	   }else{
	     $mat = convertToMatrix( $file, $_GET['ano'], $_GET['mes'] );
	     $arr = array();
	     $arry1 = array();
	     $arry2 = array();
	     $keys = array_keys( $mat );
	     for( $i = 0; $i < count( $mat ); $i++ ){
	       $arr = getHoursProgramsfromMatrix( $mat, $keys[$i] );
	       $arry1[ $keys[$i] ] = $arr[0];
	       $arry2[ $keys[$i] ] = $arr[1];
	     } 		 
	   }
	 }
       }else{
	 if( trim( $_GET['tipo_archivo'] ) == 3 ){
	   $file = "tipo3".$_SESSION['id_session'].".txt";
	   if( is_uploaded_file ( $_FILES['archivo']['tmp_name'] ) ){
	     if( copy($_FILES['archivo']['tmp_name'], $file ) ){
	       $copiar = true;
	       $mat = convertToMatrix1( $file );
	       $arr = array();
	       $arry1 = array();
	       $arry2 = array();
	       $keys = array_keys( $mat );
	       for( $i = 0; $i < count( $mat ); $i++ ){
	         $arr = getHoursProgramsfromMatrix( $mat, $keys[$i] );
	         $arry1[ $keys[$i] ] = $arr[0];
	         $arry2[ $keys[$i] ] = $arr[1];
	       } 
	     }else{
	       $copiar = false;
	     }
	   }else{
	     if( isset( $_GET['dia'] ) && trim( $_GET['dia'] ) != "" && is_file( $file )){
	       $mat = convertToMatrix1( $file );
	       $arr = array();
	       $arry1 = array();
	       $arry2 = array();
	       $arr = getHoursProgramsfromMatrix( $mat, $_GET['ano']."-".$_GET['mes']."-".$_GET['dia'] );
	       $arry1[ $_GET['ano']."-".$_GET['mes']."-".$_GET['dia'] ] = $arr[0];
	       $arry2[ $_GET['ano']."-".$_GET['mes']."-".$_GET['dia'] ] = $arr[1];
	     }else{
	       $mat = convertToMatrix1( $file );
	       $arr = array();
	       $arry1 = array();
	       $arry2 = array();
	       $keys = array_keys( $mat );
	       for( $i = 0; $i < count( $mat ); $i++ ){
		 $arr = getHoursProgramsfromMatrix( $mat, $keys[$i] );
		 $arry1[ $keys[$i] ] = $arr[0];
		 $arry2[ $keys[$i] ] = $arr[1];
	       } 		 
	     }
	   }
	 }else{
	   if( trim( $_GET['tipo_archivo'] ) == 4 ){
	     $file = "tipo4".$_SESSION['id_session'].".txt";
	     if( is_uploaded_file ( $_FILES['archivo']['tmp_name'] ) ){
	       if( copy($_FILES['archivo']['tmp_name'], $file ) ){
	         $copiar = true;
		 $date['mes'] = $_GET['mes'];
		 $date['ano'] = $_GET['ano'];			 
	         $mat = loadFileDiscovery( $file, $date );
	         $arr = array();
	         $arry1 = array();
	         $arry2 = array();
	         $keys = array_keys( $mat );
	         for( $i = 0; $i < count( $mat ); $i++ ){
	           $arr = getHoursProgramsfromMatrix( $mat, $keys[$i] );
	           $arry1[ $keys[$i] ] = $arr[0];
	           $arry2[ $keys[$i] ] = $arr[1];
	         }   
	       }else{
	         $copiar = false;
	       }
	     }else{
	       if( isset( $_GET['dia'] ) && trim( $_GET['dia'] ) != "" && is_file( $file )){
		 $date['mes'] = $_GET['mes'];
		 $date['ano'] = $_GET['ano'];			 
		 $mat = loadFileDiscovery( $file, $date );
		 $arr = array();
		 $arry1 = array();
		 $arry2 = array();
		 $arr = getHoursProgramsfromMatrix( $mat, $_GET['ano']."-".$_GET['mes']."-".$_GET['dia'] );
		 $arry1[ $_GET['ano']."-".$_GET['mes']."-".$_GET['dia'] ] = $arr[0];
		 $arry2[ $_GET['ano']."-".$_GET['mes']."-".$_GET['dia'] ] = $arr[1];
	       }else{
		 $date['mes'] = $_GET['mes'];
		 $date['ano'] = $_GET['ano'];			 
		 $mat = loadFileDiscovery( $file, $date );
		 $arr = array();
		 $arry1 = array();
		 $arry2 = array();
		 $keys = array_keys( $mat );
		 for( $i = 0; $i < count( $mat ); $i++ ){
		   $arr = getHoursProgramsfromMatrix( $mat, $keys[$i] );
		   $arry1[ $keys[$i] ] = $arr[0];
		   $arry2[ $keys[$i] ] = $arr[1];
		 }  		 
	       }
	     }
	   }else{//if 4
	     if( trim( $_GET['tipo_archivo'] ) == 5 ){
	       $file = "tipo5".$_SESSION['id_session'].".txt";
	       if( is_uploaded_file ( $_FILES['archivo']['tmp_name'] ) ){
		 if( copy($_FILES['archivo']['tmp_name'], $file ) ){
		   $copiar = true;
		   $date['mes'] = $_GET['mes'];
		   $date['ano'] = $_GET['ano'];			 
		   $mat = loadFileTipo5( $file, $date, -6 );
		   $arr = array();
		   $arry1 = array();
		   $arry2 = array();
		   $keys = array_keys( $mat );
		   for( $i = 0; $i < count( $mat ); $i++ ){
		     $arr = getHoursProgramsfromMatrix( $mat, $keys[$i] );
		     $arry1[ $keys[$i] ] = $arr[0];
		     $arry2[ $keys[$i] ] = $arr[1];
		   }   
		 }else{
		   $copiar = false;
		 }
	       }else{
		 if( isset( $_GET['dia'] ) && trim( $_GET['dia'] ) != "" && is_file( $file )){
		   $date['mes'] = $_GET['mes'];
		   $date['ano'] = $_GET['ano'];			 
		   $mat = loadFileTipo5( $file, $date, -6 );
		   $arr = array();
		   $arry1 = array();
		   $arry2 = array();
		   $arr = getHoursProgramsfromMatrix( $mat, $_GET['ano']."-".$_GET['mes']."-".$_GET['dia'] );
		   $arry1[ $_GET['ano']."-".$_GET['mes']."-".$_GET['dia'] ] = $arr[0];
		   $arry2[ $_GET['ano']."-".$_GET['mes']."-".$_GET['dia'] ] = $arr[1];
		 }else{
		   $date['mes'] = $_GET['mes'];
		   $date['ano'] = $_GET['ano'];			 
		   $mat = loadFileTipo5( $file, $date );
		   $arr = array();
		   $arry1 = array();
		   $arry2 = array();
		   $keys = array_keys( $mat );
		   for( $i = 0; $i < count( $mat ); $i++ ){
		     $arr = getHoursProgramsfromMatrix( $mat, $keys[$i] );
		     $arry1[ $keys[$i] ] = $arr[0];
		     $arry2[ $keys[$i] ] = $arr[1];
		   }  		 
		 }
	       }
	     }//if 5
	   }
	 }
       }
     }
   }

   if( isset( $_GET['asign_program'] ) ){
     $keys1 = array_keys( $arry1 );
     $keys2 = array_keys( $arry2 );
     if( isset( $_GET['dia'] ) && trim( $_GET['dia'] ) != "" && $_GET['bd'] == 1 ){
       $channel_slots = $channel->getSlots($_GET['ano']."-".$_GET['mes']."-".$_GET['dia']);
     }else{
       if( $_GET['mes'] != 12 ){
	 $channel_slots0 = $channel->getSlots($_GET['ano']."-".$_GET['mes']."-".$_GET['dia']);
	 if( ( $_GET['mes']+1 ) <= 9 ){
	   $new_mes = "0".($_GET['mes']+1);
	 }else{
	   $new_mes = $_GET['mes']+1;
	 }
	 $channel_slots1 = $channel->getSlots($_GET['ano']."-".$new_mes."-".$_GET['dia']);
	 $channel_slots = array_merge( $channel_slots0, $channel_slots1 );
       }else{
	 if( $_GET['mes'] == 12 ){
	   $channel_slots0 = $channel->getSlots($_GET['ano']."-".$_GET['mes']."-".$_GET['dia']);
	   $channel_slots1 = $channel->getSlots(($_GET['ano']+1)."-01"."-".$_GET['dia']);
	   $channel_slots = array_merge( $channel_slots0, $channel_slots1 );
	 }
       }
     }
     $channels_slots_time = array();
     for( $i = 0; $i < count( $channel_slots ); $i++ ){
       $channels_slots_time[$channel_slots[$i]->getDate()][] = $channel_slots[$i]->getTime();
     }
     $slots = array();
     for( $i = 0 ; $i < count( $arry1 ); $i++ ){
       $hour_array = explode( "\t", $arry1[$keys1[$i]] );
       for( $j = 0; $j < count( $hour_array ); $j++ ){
	 /*
	    echo "Hora: ".$hour_array[$j]."<br>";
	    echo "Dia: ".$keys1[$i]."<br>";		
	    print_r( $channels_slots_time[$keys1[$i]]);
		echo "<br>";
	 */
	 if( !find_array( trim( $hour_array[$j] ), $channels_slots_time[$keys1[$i]] ) ){
	   $hourinf = strtotime( $hour_array[$j] );
	   $hoursup = strtotime( $hour_array[$j+1] );
	   $dif = $hoursup - $hourinf;
	   $mins = date( "i", $dif-1 );
	   $mins += 1;
	   if( trim( $hour_array[$j] ) != "" ){
	     $slots[] = new Slot( false, $hour_array[$j], $keys1[$i], $mins, $_GET['id'] );
	   }
	 }
       }
     }
     
     if( true ){
       $slots3 = array();
       for( $i = 0 ; $i < count( $slots ); $i++ ){
	 $fecha1 = $slots[$i]->getDate();
	 $hora = $slots[$i]->getTime();
	 $duration = $slots[$i]->getDuration();   
	 for( $j = 0; $j < count( $arry1[$fecha1] ); $j++ ){
	   $times = explode("\t",$arry1[$fecha1]);
	   for( $k = 0 ; $k < count( $times ); $k++ ){
	     if( trim( $times[$k] ) == trim( $hora ) ){
	       $pos = $k;
	     }
	   }
	 }
	 $programs = explode("\t", $arry2[$fecha1] );
	 $program = ucwords($programs[$pos]);
	 $program2 = $program;
	 if( strstr( strtolower( $program ), "(r)" ) || strstr( strtolower( $program ), "(d)" ) || strstr( strtolower( $program ), "(p)" ) ){
	   $program = substr( $program, 0, strpos( $program, '(' ) );
	 }
	 $program1 = explode(" ", $program );
	 $temp = explode( " ", $program );
	 $program = "";
	 for( $j = 0 ; $j < count( $temp ); $j++ ){
	   if( trim( $temp[$j] ) != "" ){
	     $program .= trim( $temp[$j] )." ";
	   }
	 }
	 if( !strstr( $program, "'" ) ){
	   $sql = "select id, title from chapter where title like '".trim($program)."' or spanishTitle like '".trim($program)."'";
	 }else{
	   $sql = 'select id, title from chapter where title like "'.trim($program).'" or spanishTitle like "'.trim($program).'"';
	 }
	 $query = db_query( $sql );
	 if( !strstr( $program2, "'" ) ){
	   $sql1 = "select id, title from chapter where title like '".trim($program2)."' or spanishTitle like '".trim($program2)."'";
	 }else{
	   $sql1 = 'select id, title from chapter where title like "'.trim($program2).'" or spanishTitle like "'.trim($program2).'"';
	 }
	 $query1 = db_query( $sql1 );
	 if( db_numrows( $query ) == 1 ){
	   $row = db_fetch_array( $query );
	   echo $row['title']." - ".$fecha1." - ".$hora."<br>";
	   $channel = new Channel( $_GET['id'] );
	   $chapter = new Chapter( $row['id']);
	   $slot = new Slot();
	   $slot->addChapter( $chapter );
	   $slot->setChannel( $channel->getId() );
	   $slot->setDate( $fecha1 );
	   $slot->setTime( $hora );
	   $slot->setDuration( $duration );	   
	   $slot->commit();
	   $channel->addSlot( $slot );
	   $channel->commit();
	   $slots[$i] = "null"; 
	 }else{
	   if( db_numrows( $query1 ) == 1 ){
	     $row = db_fetch_array( $query1 );
	     echo "BD: ".$row['title']." - ".$fecha1." - ".$hora."<br>";
	     $channel = new Channel( $_GET['id'] );
	     $chapter = new Chapter( $row['id']);
	     $slot = new Slot();
	     $slot->addChapter( $chapter );
	     $slot->setChannel( $channel->getId() );
	     $slot->setDate( $fecha1 );
	     $slot->setTime( $hora );
	     $slot->setDuration( $duration );	   
	     $slot->commit();
	     $channel->addSlot( $slot );
	     $channel->commit();
	     $slots[$i] = "null"; 
	   }  
	 }	   
       }
     }
     /*Para asignar slots del mes anterior*/
     /*   
   for( $i = 0 ; $i < count( $arry0 ); $i++ ){
     $arry0[$i]->commit();
   }
     */ 

     $_SESSION['arry0'] = $arry0;
     $_SESSION['arry1'] = $arry1;
     $_SESSION['arry2'] = $arry2;
     $_SESSION['slots'] = $slots;
   }else{
     if( isset( $_GET['asign_programnew'] ) ){
       $keys = array_keys( $mat );
       $update_array = array();
       for( $i = 0 ; $i < count( $keys ); $i++ )
       {
   		 $keys1 = array_keys( $mat[$keys[$i]] );
		 for( $j = 0 ; $j < count( $keys1 ); $j++ ){
		 	$duration = 30;
			$title = $mat[$keys[$i]][$keys1[$j]];	
			if( $title != "" ){
	   			 $sql = "INSERT INTO slot( date, time, duration, channel, title ) VALUES( '".$keys[$i]."', '".$keys1[$j]."', ".$duration.", ".$channel->getId().", ";
	     		if( !strstr( $mat[$keys[$i]][$keys1[$j]], "'" )  ){
	       			$sql .= "'".$mat[$keys[$i]][$keys1[$j]]."' )";
	     		}else{
	       			$sql .= '"'.$mat[$keys[$i]][$keys1[$j]].'" )';
	     		}
	     		$query = db_query( $sql );
	     		if( !$query ){
	       			$update_array[$keys[$i]][$keys1[$j]] = $mat[$keys[$i]][$keys1[$j]];
	       			$sql1 = "UPDATE slot ";
	       			if( !strstr( $title, "'" )  ){
		 				$sql1 .= "SET title = '".$mat[$keys[$i]][$keys1[$j]]."'";
	       			}else{
		 				$sql1 .= 'SET title = "'.$mat[$keys[$i]][$keys1[$j]].'"';
	       			}
	       			$sql1 .= " WHERE date = '".$keys[$i]."' AND time = '".$keys1[$j]."' AND channel = ".$channel->getId();
	       			db_query( $sql1 );
	     		}
		  	}
		  }
	   	}
		$sql = "SELECT id, date, time FROM slot WHERE date >= '".$_GET['ano']."-".$_GET['mes']."-01"."' ORDER BY channel, date, time";
		$query = db_query( $sql );
		$slot_array = array();
		while( $row = db_fetch_array( $query ) ){
  			$slot_array[$row['id']] = $row['date']." ".$row['time'];
		}
		$keys = array_keys( $slot_array );
		for( $i = 0 ; $i < count( $slot_array ); $i++ ){
		  $time = strtotime( $slot_array[$keys[$i]] );
		  if(isset($keys[$i+1]))
		  {
			$time1 = strtotime( $slot_array[$keys[$i+1]]);
			$duration = ($time1 - $time)/60;
		}
		  else
		  {
		  	$duration = 30;
		  }
		  $sql = "UPDATE slot SET duration = ".$duration." WHERE id = ".$keys[$i];
		  db_query( $sql );
		}
	   /*
       for( $i = 0 ; $i < count( $keys ); $i++ ){
	 $keys1 = array_keys( $mat[$keys[$i]] );
	 for( $j = 0 ; $j < count( $keys1 ); $j++ ){
	   $title = $mat[$keys[$i]][$keys1[$j]];
	   if( isset( $keys1[$j+1]  ) ){
	     for( $k = $j+1; $k < count( $keys1 ); $k++ ){
	       if( $mat[$keys[$i]][$keys1[$k]] != ""  ){
		 $duration = ( strtotime( $keys[$i]." ".$keys1[$k] ) - strtotime(  $keys[$i]." ".$keys1[$j] ) )/60;
		 $k = count( $keys1 )+1;
	       }
	     }
	   }else{
	     if( isset( $keys[$i+1] ) ){
	       $keys_temp = array_keys( $mat[$keys[$i+1]] );
	       $duration = ( strtotime( $keys[$i+1]." ".$keys_temp[0] ) - strtotime( $keys[$i]." ".$keys1[$j] ) )/60;
	       for( $k = $j+1; $k < count( $keys_temp ); $k++ ){
		 if( $mat[$keys[$i+1]][$keys_temp[$k]] != ""  ){
		   $duration = ( strtotime( $keys[$i+1]." ".$keys_temp[$k] ) - strtotime(  $keys[$i]." ".$keys1[$j] ) )/60;
		   $k = count( $keys1 )+1;
		 }
	       }
	     }
	   }
	   */
	   /*
	   if( $title != "" ){
	   	 $sql = "INSERT INTO slot( date, time, duration, channel, title ) VALUES( '".$keys[$i]."', '".$keys1[$j]."', ".$duration.", ".$channel->getId().", ";
	     if( !strstr( $mat[$keys[$i]][$keys1[$j]], "'" )  ){
	       $sql .= "'".$mat[$keys[$i]][$keys1[$j]]."' )";
	     }else{
	       $sql .= '"'.$mat[$keys[$i]][$keys1[$j]].'" )';
	     }
		 echo $sql."<br>";
	     //$query = db_query( $sql );
	     if( !$query ){
	       $update_array[$keys[$i]][$keys1[$j]] = $mat[$keys[$i]][$keys1[$j]];
	       $sql1 = "UPDATE slot ";
	       if( !strstr( $title, "'" )  ){
		 $sql1 .= "SET title = '".$mat[$keys[$i]][$keys1[$j]]."'";
	       }else{
		 $sql1 .= 'SET title = "'.$mat[$keys[$i]][$keys1[$j]].'"';
	       }
	       $sql1 .= " WHERE date = '".$keys[$i]."' AND time = '".$keys1[$j]."' AND channel = ".$channel->getId();
	       //db_query( $sql1 );
	     }
	   }
	   
	 }
       }
	   */
       echo "FIN"; 
     }
   }

 ?>
      <?
  $fecha_ant = "";					      
  for( $i = 0 ; $i < count( $slots ); $i++ ){
    if( get_class( $slots[$i] ) == "slot" ){
      $fecha = $slots[$i]->getDate();
      $hora = $slots[$i]->getTime();
      $duration = $slots[$i]->getDuration();
	
?>
      <tr>
        <td> <b> <font color="#42c472">
          <?
    if( $fecha != $fecha_ant ){
      $fecha_ant = $fecha;
      echo $fecha;
    }
    ?>
        </font></b> </td>
      </tr>
      <tr>
        <td> <b><font color="#217199">
          <?=$hora?>
        </font></b>&nbsp;&nbsp;&nbsp;&nbsp; <a href="#" onClick='window.open( "ppa/mes_anterior.php", null, "height=600,width=400,status=yes,toolbar=no,menubar=no,location=no,scrollbars=yes" )'>Mes anterior</a> / <a href="#" onClick='window.open( "ppa/buscar_chapter.php?time=<?=trim($hora)?>&date=<?=$fecha?>&duration=<?=$duration?>&channel=<?=$channel->getId()?>&mes=<?=$_GET["mes"]?>&ano=<?=$_GET["ano"]?>&tipo_archivo=<?=$_GET["tipo_archivo"]?>&bd=<?=$_GET["bd"]?>&dia=<?=$_GET["dia"]?>", "_blank", "resizable=yes,height=450,width=400,status=no,toolbar=no,menubar=no,location=no,scrollbars=yes" )'>Buscar</a> / <a href="#" onClick='window.open( "ppa/chapter.php?time=<?=trim($hora)?>&date=<?=$fecha?>&duration=<?=$duration?>&channel=<?=$channel->getId()?>&mes=<?=$_GET["mes"]?>&ano=<?=$_GET["ano"]?>&bd=<?=$_GET["bd"]?>&dia=<?=$_GET["dia"]?>&tipo_archivo=<?=$_GET["tipo_archivo"]?>", null, "height=100,width=300,status=yes,toolbar=no,menubar=no,location=no,scrollbars=yes" )'>Nuevo</a> / <a href="#" onClick='window.open( "ppa/chapter.php?time=<?=trim($hora)?>&date=<?=$fecha?>&duration=<?=$duration?>&channel=<?=$channel->getId()?>&mes=<?=$_GET["mes"]?>&ano=<?=$_GET["ano"]?>&bd=<?=$_GET["bd"]?>&dia=<?=$_GET["dia"]?>&tipo_archivo=<?=$_GET["tipo_archivo"]?>&tipo=series", null, "height=100,width=300,status=yes,toolbar=no,menubar=no,location=no,scrollbars=yes" )'>Nueva_Serie </a>/ <a href="#" onClick='window.open( "ppa/chapter.php?time=<?=trim($hora)?>&date=<?=$fecha?>&duration=<?=$duration?>&channel=<?=$channel->getId()?>&mes=<?=$_GET["mes"]?>&ano=<?=$_GET["ano"]?>&bd=<?=$_GET["bd"]?>&dia=<?=$_GET["dia"]?>&tipo_archivo=<?=$_GET["tipo_archivo"]?>&tipo=movie", null, "height=100,width=300,status=yes,toolbar=no,menubar=no,location=no,scrollbars=yes" )'>Nueva_Pelicula</a> / <a href="#" onClick='window.open( "ppa/chapter.php?time=<?=trim($hora)?>&date=<?=$fecha?>&duration=<?=$duration?>&channel=<?=$channel->getId()?>&mes=<?=$_GET["mes"]?>&ano=<?=$_GET["ano"]?>&bd=<?=$_GET["bd"]?>&dia=<?=$_GET["dia"]?>&tipo_archivo=<?=$_GET["tipo_archivo"]?>&tipo=especiales_documentales", null, "height=100,width=300,status=yes,toolbar=no,menubar=no,location=no,scrollbars=yes" )'>Nuevo_Especial/Documental</a></td>
      </tr>
      <?
       }
  }
?>
      <? 

	 }
	?>
      <? 	
  }else{ 
  if( isset( $_GET['show_program'] ) && isset( $_GET['paso'] ) && isset( $_GET['id'] )){
    if( $_GET['paso'] == 1 ){
		$ch = new Channel($_GET['id']);
?>
      <tr>
        <td colspan="2" align="center">
          <h3>
            <?=$ch->getName()?> ( <?=$ch->getId()?> )<br>
            <?=$ch->getDescription()?>
        </h3></td>
      </tr>
      <tr>
        <td width="250"><div align="right"><strong>Mes</strong></div></td>
        <td>
          <select name="mes">
<? 
$month = Array( "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ); 
foreach( $month as $value => $name )
{
?>
            <option value="<?=$value+1?>" <?=($value+1)==date("m") ? "selected" : "" ?> ><?=$name?></option>
<? } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td><div align="right"><strong>Año</strong></div></td>
        <td>
          <select name="ano">
<? 
for( $i=(date("Y")-1); $i<=(date("Y")+1); $i++)
{
?>
            <option value="<?=$i?>" <?=$i==date("Y")? "selected" : ""?> ><?=$i?></option>
<? } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td align="right"><strong>Dia</strong></td>
        <td>
          <select name="dia">
            <option value="">--</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
          </select>
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center">
          <input type="hidden" name="id" value="<?=$_GET['id']?>">
          <input type="hidden" name="show_program" value="<?=$_GET['show_program']?>">
          <input type="hidden" name="paso" value="2">
          <input type="submit" name="next" value="Siguiente" >
          <br>
          <input type="button" name="send2" value="Regresar" onClick="document.location='ppa.php?channels=1'">
        </td>
      </tr>
      <?	
	}
?>
      <?
  if( $_GET['paso'] == 2 && isset( $_GET['mes'] ) && isset( $_GET['ano'] )   ){
      $channel = new Channel( $_GET['id'] );
?>
      <tr>
        <td align="center">
          <h3>
            <?=$channel->getId()?>. <?=$channel->getName()?><br>
            <?=$channel->getDescription()?>
        </h3></td>
      </tr>
      <tr>
        <td align="center">
          <h3>A&ntilde;o:
              <?=$_GET['ano']?>
        </h3></td>
      </tr>
      <tr>
        <td align="center">
          <h3>Mes:
              <?=$_GET['mes']?>
        </h3></td>
      </tr>
      <? if( $_GET['dia'] != "" ){ ?>
      <tr>
        <td align="center">
          <h3>Dia:
              <?=$_GET['dia']?>
        </h3></td>
      </tr>
      <? } ?>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <?
	  $slot_array = array();
	  $slot_array_chapter = array();
	  if( $_GET['dia'] != "" ){
	  	$sql = "SELECT  s.id, s.date, s.time, s.duration, c.title, c.spanishTitle, c.id FROM slot s, slot_chapter sc, chapter c WHERE s.channel = ".$channel->getId()." AND s.date =  '".$_GET['ano']."-".$_GET['mes']."-".$_GET['dia']."' AND s.id = sc.slot AND sc.chapter = c.id ORDER BY s.date, s.time";
		$query = db_query( $sql );
		while( $row = db_fetch_array( $query ) ){
			$slot_array_chapter[$row[0]] = $row[6]." (|) ".$row[4]. " (|) ".$row[5];
		}		
		$sql1 = "SELECT id, title, date, time, duration  FROM slot WHERE date =  '".$_GET['ano']."-".$_GET['mes']."-".$_GET['dia']."' AND channel = ".$channel->getId()." ORDER BY date, time";
	  }else{
	  	$numdays = date("d", mktime(0, 0, 0, $_GET['dia']+1, 0, $_GET['ano'] ) );
	  	$sql = "SELECT  s.id, s.date, s.time, s.duration, c.title, c.spanishTitle, c.id FROM slot s, slot_chapter sc, chapter c WHERE s.channel = ".$channel->getId()." AND s.date >= '".$_GET['ano']."-".$_GET['mes']."-01"."' AND s.date <= '".$_GET['ano']."-".$_GET['mes']."-".$numdays."' AND s.id = sc.slot AND sc.chapter = c.id ORDER BY s.date, s.time";	  
		$query = db_query( $sql );
		while( $row = db_fetch_array( $query ) ){
			$slot_array_chapter[$row[0]] = $row[6]." (|) ".$row[4]. " (|) ".$row[5];
		}		
		$sql1 = "SELECT id, title, date, time, duration FROM slot WHERE date >= '".$_GET['ano']."-".$_GET['mes']."-01"."' AND date <= '".$_GET['ano']."-".$_GET['mes']."-".$numdays."' AND channel = ".$channel->getId()." ORDER BY date, time";
	  }
      $fecha_ant = "";		
	  $query1 = db_query( $sql1 );
	  while( $row1 = db_fetch_array( $query1 ) ){
	  	$slot_id = $row1[0];
	  	$fecha = $row1['date'];
		$hora = $row1['time'];
		$duration = $row1['duration'];
		$slot_title = $row1['title'];
		if( array_key_exists( $slot_id, $slot_array_chapter ) ){
			$temp = explode("(|)", $slot_array_chapter[$slot_id] );
			for( $i = 0 ; $i < count( $temp ); $i++ ){
				$chapterid = $temp[0];
				$chapter_ortitle = $temp[1];
				$chapter_estitle = $temp[2];
			}
		}else{
			$chapterid = "-1";
			$chapter_ortitle = "";
			$chapter_estitle = "";
		}

?>
	      <tr>
        <td align="center"> <b> <font size="+1" color="#42c472">
          <?
    if( $fecha != $fecha_ant ){
      $fecha_ant = $fecha;
      echo "<br>".$fecha;
    }
    ?>
        </font></b> </td>
      </tr>
      <tr>
        <td > <b>
		<? if( $chapterid == -1 ){?>
		<font color="#00FF00">
          <?=$hora?>
	    </font> 
		  <? }else{ ?>
		<font color="#217199">
          <?=$hora?>
	    </font> 		  
		  <? } ?>
          </font></b>&nbsp;&nbsp;&nbsp;&nbsp;
		  <? if( $chapterid != -1 ){ ?>
		   <a href='#' style="text-decoration:none" onClick='window.open("ppa/info_chapter.php?id=<?=$chapterid?>", null, "height=150,width=300,status=yes,toolbar=no,menubar=no,location=no,scrollbars=yes" )'>
		   <? }?>
          <strong><?=$slot_title?></strong> &nbsp;&nbsp;/&nbsp;&nbsp; <strong><font color="#FF9933"><?=$chapter_ortitle?></font></strong> &nbsp;&nbsp;/&nbsp;&nbsp; <strong><font color="#CC0000"><?=$chapter_estitle?></font></strong>
        </a>&nbsp;&nbsp;&nbsp;<a href='#' onClick='window.open("ppa/change_title.php?id_slot=<?=$slot_id?>", null, "height=100,width=300,status=yes,toolbar=no,menubar=no,location=no,scrollbars=yes" )'>Cambiar de Título</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="ppa.php?borrar_slot=1&id_slot=<?=$slot_id?>&show_program=1&paso=2&ano=<?=$_GET['ano']?>&mes=<?=$_GET['mes']?>&id=<?=$_GET['id']?>&dia=<?=$_GET['dia']?>" class="borrar">Borrar</a></td>
      </tr>
      <?	  
     }
   }	
  }else{
?>
      <tr>
        <td colspan="4" align="center" bgcolor="#FFFFCC"><a href="ppa.php?add=1">Agregar Canal</a></td>
      </tr>
      <tr>
        <th scope="col" bgcolor="#F0F0F0" colspan="4">Canales Existentes </th>
      </tr>
	  <tr bgcolor="#FFFFCC">
	  <td>
	  <strong>Mes Act</strong>
	  </td>
	  <td>
	  <strong>Mes Sig</strong>
	  </td>
	  <td>&nbsp;
	  
	  </td>
	  <td>&nbsp;
	  
	  </td>
	  </tr>
      <?	
	  $channels = $ppa->getChannels();
	  $this_monthchannels = array();
	  $this_monthchannelsasigned = array();
	  $next_monthchannels = array();
  	  $next_monthchannelsasigned = array();
	  $thismonth_slots_channel = array();
	  $thismonth_slots_asigned_channel = array();
	  $nextmonth_slots_channel = array();
	  $nextmonth_slots_asigned_channel = array();

	  $this_month = date("m");
	  $this_year = date("Y");
	  if( $this_month != 12 ){
  	  	$next_month = $this_month+1;
		$next_year = $this_year; 
	  }else{
  	  	$next_month = "1";	 
		$next_year = $this_year+1; 
	  }
	  if( $next_month <= 9 ){
	  	$next_month = "0".$next_month;
	  }
	  /*
	  $str = "(";
	  for( $i = 0; $i < count( $channels ); $i++ ){
	  	if( $i+1 == count( $channels ) ){
	    	$str .= $channels[$i]->getId();
	  	}else{
			$str .= $channels[$i]->getId().",";
		}
	  }
	  $str .= ")";
	  */
  	  $numdays = date("d", mktime(0, 0, 0, $this_month+1, 0, $this_year ) );
	  $sql = "SELECT c.id FROM channel c, slot s WHERE c.id = s.channel AND s.date = '".$this_year."-".$this_month."-10"."'";
	  $query = db_query( $sql );
	  while( $row = db_fetch_array( $query ) ){
		$this_monthchannels[] = $row['id'];	
	  }
	  $sql = "SELECT channel, count( * ) FROM slot WHERE date >= '".$this_year."-".$this_month."-01"."' AND date <= '".$this_year."-".$this_month."-".$numdays."' GROUP BY channel";
	  $query = db_query( $sql );
	  while( $row = db_fetch_array( $query ) ){
		$thismonth_slots_channels[$row['channel']] = $row[1];	
	  }
	  $sql = "SELECT s.channel, count( * ) FROM slot s, slot_chapter sc WHERE s.date >= '".$this_year."-".$this_month."-01"."' AND s.date <= '".$this_year."-".$this_month."-".$numdays.".' AND sc.slot = s.id GROUP BY s.channel";
	  $query = db_query( $sql );
	  while( $row = db_fetch_array( $query ) ){
		$thismonth_slots_asigned_channels[$row['channel']] = $row[1];	
	  }
  	  $keys = array_keys( $thismonth_slots_channels );
	  for($i=0; $i<count($keys); $i++){
	  	if( $thismonth_slots_channels[$keys[$i]] <= $thismonth_slots_asigned_channels[$keys[$i]] ){
		  $this_monthchannelsasigned[] = $keys[$i];
		}
	  }
  	  $numdays = date("d", mktime(0, 0, 0, $next_month+1, 0, $next_year ) );	  	
  	  $sql = "SELECT c.id FROM channel c, slot s WHERE c.id = s.channel AND s.date = '".$next_year."-".$next_month."-10"."'";
	  $query = db_query( $sql );
	  while( $row = db_fetch_array( $query ) ){
		$next_monthchannels[] = $row['id'];  	
	  }
	  $sql = "SELECT channel, count( * ) FROM slot WHERE date >= '".$next_year."-".$next_month."-01"."' AND date <= '".$next_year."-".$next_month."-".$numdays."' GROUP BY channel";
	  $query = db_query( $sql );
	  while( $row = db_fetch_array( $query ) ){
		$nextmonth_slots_channels[$row['channel']] = $row[1];	
	  }
	  $sql = "SELECT s.channel, count( * ) FROM slot s, slot_chapter sc WHERE s.date >= '".$next_year."-".$next_month."-01"."' AND s.date <= '".$next_year."-".$next_month."-".$numdays.".' AND sc.slot = s.id GROUP BY s.channel";
	  $query = db_query( $sql );
	  while( $row = db_fetch_array( $query ) ){
		$nextmonth_slots_asigned_channels[$row['channel']] = $row[1];	
	  }
  	  $keys = array_keys( $nextmonth_slots_channels );
	  for($i=0; $i<count($keys); $i++){
	  	if( $nextmonth_slots_channels[$keys[$i]] <= $nextmonth_slots_asigned_channels[$keys[$i]] ){
		  $next_monthchannelsasigned[] = $keys[$i];
		}
	  }	  
	  for($i=0; $i<count($channels); $i++){
	?>
      <tr>
	  <? if( in_array( $channels[$i]->getId(), $this_monthchannels ) ){?>
	     <td align="center">
		 <table width="100%">
	     <? if( !in_array( $channels[$i]->getId(), $this_monthchannelsasigned ) ){?>
		 <tr>
		 <td bgcolor="#00FF99">
		 <strong>SI</strong>
		 </td>
		 </tr>
		 <tr>
		 <td bgcolor="#D2446B">
		 <strong>NO</strong>
		 </td>
		 </tr>
		 <? }else{ ?>
		 <tr>
		 <td bgcolor="#00FF99">
		 <strong>SI</strong>
		 </td>
		 </tr>
		 <tr>
		 <td bgcolor="#00FF99">
		 <strong>SI</strong>
		 </td>
		 </tr>
		 <? } ?>
		 </table>
		 </td>		 
	  <? }else{ ?>
	     <td align="center">
		 <table width="100%">
		 <tr>
		 <td bgcolor="#D2446B">
		 <strong>NO</strong>
		 </td>
		 </tr>
		 <tr>
		 <td bgcolor="#D2446B">
		 <strong>NO</strong>
		 </td>
		 </tr>
		 </table>
		 </td>		 
	  <? } ?>
  	  <? if( in_array( $channels[$i]->getId(), $next_monthchannels ) ){?>
	     <td align="center">
		 <table width="100%">
	     <? if( !in_array( $channels[$i]->getId(), $next_monthchannelsasigned ) ){?>
		 <tr>
		 <td bgcolor="#00FF99">
		 <strong>SI</strong>
		 </td>
		 </tr>
		 <tr>
		 <td bgcolor="#D2446B">
		 <strong>NO</strong>
		 </td>
		 </tr>
		 <? }else{ ?>
		 <tr>
		 <td bgcolor="#00FF99">
		 <strong>SI</strong>
		 </td>
		 </tr>
		 <tr>
		 <td bgcolor="#00FF99">
		 <strong>SI</strong>
		 </td>
		 </tr>
		 <? } ?>
		 </table>
		 </td>		 
	  <? }else{ ?>
	     <td align="center">
		 <table width="100%">
		 <tr>
		 <td bgcolor="#D2446B">
		 <strong>NO</strong>
		 </td>
		 </tr>
		 <tr>
		 <td bgcolor="#D2446B">
		 <strong>NO</strong>
		 </td>
		 </tr>
		 </table>
		 </td>		 
	  <? } ?>
        <td bgcolor="#DDEEFF"> <strong>
          <?=$channels[$i]->getName()?><br>
          <div class="description">(<?=$channels[$i]->getDescription()?>)<div>
        </strong> </td>
        <td align="center" bgcolor="#DDEEFF"><a target="_blank" href="ppa.php?asign_programnew=1&paso=1&id=<?=$channels[$i]->getId()?>">Asignar Programación</a><!--&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;<a href="ppa.php?asign_program=1&paso=1&id=<?=$channels[$i]->getId()?>">Asignar Programación</a>-->&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;<a target="_blank" href="ppa.php?show_program=1&paso=1&id=<?=$channels[$i]->getId()?>">Ver Programación</a>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;<a target="_blank" href="ppa.php?edit=1&id=<?=$channels[$i]->getId()?>">Editar</a>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;<br><a target="_blank" href="ppa.php?delete_programs=1&id=<?=$channels[$i]->getId()?>">Eliminar Programación</a>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;<a target="_blank" href="ppa.php?dup_programs=1&id=<?=$channels[$i]->getId()?>">Duplicar Programación</a></td>
      </tr>
      <?	}
?>
      <tr>
        <td colspan="4" align="center" bgcolor="#FFFFCC"><a href="ppa.php?add=1">Agregar Canal</a></td>
      </tr>
      <?
	}  
  }
} 
?>
    </table>
  </form>
</body>
</html>
