<?
require_once( 'ppa/include/functions_ver1.php' );
require_once( 'ppa/include/functions_ver2.php' );
require_once("ppa/config.php");

$ppa = new PPA(1);
if( isset( $_POST['id'] ) ){
  $_GET['id'] = $_POST['id'];
}
if( isset( $_GET['send_client'] ) ){
  $_POST['send_client'] = $_GET['send_client'];
}
if( isset( $_GET['name'] ) ){
  $_POST['name'] = $_GET['name'];
}
if( isset( $_GET['timezone'] ) ){
  $_POST['timezone'] = $_GET['timezone'];
}
if( isset( $_GET['ip'] ) ){
  $_POST['ip'] = $_GET['ip'];
}
if( isset( $_GET['ppa'] ) ){
  $_POST['ppa'] = $_GET['ppa'];
}
if( isset( $_GET['channel_edit'] ) ){
  $_POST['channel_edit'] = $_GET['channel_edit'];
}
if( isset( $_GET['id_channel'] ) ){
  $_POST['id_channel'] = $_GET['id_channel'];
}
if( isset( $_GET['channel_num'] ) ){
  $_POST['channel_num'] = $_GET['channel_num'];
}
if( isset( $_GET['group_name'] ) ){
  $_POST['group_name'] = $_GET['group_name'];
}
if(isset($_POST['send_client'])){
	$m = new Client($_GET['id']);
	$m->setName( $_POST['name'] );
	$m->setTimezone( $_POST['timezone'] );
    $m->setIp( $_POST['ip'] );
	$m->setPPA( $_POST['ppa'] );	
	$m->commit();
	$added_client = true;
}else{
    if( isset($_GET['delete_client']) ){
	   $sql = "select * from client where id = " . $_GET['id'];
	   $results = db_query($sql);
	   if(db_numrows($results) >= 0){
		  $m = new Client($_GET['id']);
		  $m->erase();
		  unset($m);
	   } 
	   $deleted_client = true;
   }else{
     if( isset( $_POST['asign_channel'] ) ){
	   $m = new Client( $_GET['id'] );
	   $channels = $m->getChannels();
	   $c_channel = $_POST['c_channel'];
	   for( $i = 0 ; $i < count( $c_channel ); $i++ ){
	      $channel = new Channel( $c_channel[$i] );
	      $channels->addChannel( $channel );	   
	   }
	   $m->setChannels( $channels );
	   $m->commit();
	   $asigned_channel = true;
     }else{
       if( isset( $_GET['delete_channel'] ) ){
	 $m = new Client( $_GET['id'] );
	 $channels = $m->getChannels();
	 $channels_array = $channels->getChannels();
	 $number_channels = $channels->getNumbers();
	 $group_channels = $channels->getGroups();
	 for( $i = 0 ;$i < count( $channels_array ); $i++ ){
	   if( $channels_array[$i]->getId() == $_GET['id_channel'] ){
	     $temp = array_slice( $channels_array, 0, $i );
	     $temp1 = array_slice( $channels_array, $i+1 );
	     $channels_array = array_merge( $temp, $temp1 );

	     $temp = array_slice( $number_channels, 0, $i );
	     $temp1 = array_slice( $number_channels, $i+1 );
	     $number_channels = array_merge( $temp, $temp1 );

	     $temp = array_slice( $group_channels, 0, $i );
	     $temp1 = array_slice( $group_channels, $i+1 );
	     $group_channels = array_merge( $temp, $temp1 );
	     
	     $i = count( $channels_array )+1;
	   }
	 } 

	 $channels->setChannels( $channels_array );
	 $channels->setNumbers( $number_channels );
	 $channels->setGroups( $group_channels );
	 $m->setChannels( $channels );
	 $m->commit();
	 $deleted_channel = true;
       }else{
	 if( isset( $_POST['channel_edit'] ) ){
	   $m = new Client( $_GET['id'] );
	   $channels = $m->getChannels();
	   $channel_array = $channels->getChannels();
	   $number_array = $channels->getNumbers();
	   $group_array = $channels->getGroups();
	   for( $i = 0 ; $i < count( $channel_array ); $i++ ){
	     if( $channel_array[$i]->getId() == $_POST['id_channel'] ){
	       $number_array[$i] = $_POST['channel_num'];
	       $group_array[$i] = $_POST['group_name'];
	       $i = count( $channel_array )+1;
	     }
	   }
	   $channels->setNumbers( $number_array );
	   $channels->setGroups( $group_array );
	   $m->setChannels( $channels );
	   $m->commit();
	   $channel_edited = true;
	 }
       }
     }
   }	 
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
<? if($added_client || $deleted_client){?>
<script language="JavaScript">
document.location = "ppa.php?clients=1";
</script>
<? }else{
  if( $deleted_channel || $channel_edited){
    ?>
<script language="JavaScript">
 //document.location = "ppa.php?edit_client=1&id=<?=$_GET['id']?>";
</script>
Gracias!!
<?  exit();
    }else{
      if( $asigned_channel ){
?>
<script language="JavaScript">
document.location = "ppa.php?edit_client=1&id=<?=$_GET['id']?>";
</script>
<?

      }
    }
?>
<? } ?>
<?
//require_once("ppa/header.php"); 
?>
  <form name="f1" action="ppa.php" method="post" enctype="multipart/form-data">
    <table width="600" border="0" cellspacing="1" cellpadding="1">
      <?
if( (isset($_GET['add_client']) && $_GET['add_client']) || (isset($_GET['edit_client']) && $_GET['edit_client']) ){
	if(isset($_GET['edit_client']) && $_GET['edit_client']){
		$cl = new Client($_GET['id']);
	} else {
		$cl = new Client();
	}
	$name     = $cl->getName();
	$timezone = $cl->getTimezone();	
	$channels = $cl->getChannels();
    $ip       = $cl->getIp(); 
	$channels_array = $channels->getChannels();
	$channels_number = $channels->getNumbers();	
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
        <td width="146"><div align="right"><strong>Zona Horaria</strong></div></td>
        <td width="15">&nbsp;</td>
        <td width="439">
          <div align="left">
            <input name="timezone" type="text" value="<?=$timezone?>" size="7">
        </div></td>
      </tr>
      <tr>
        <td width="146"><div align="right"><strong>Dirección IP</strong></div></td>
        <td width="15">&nbsp;</td>
        <td width="439">
          <div align="left">
            <input name="ip" type="text" value="<?=$ip?>" size="20">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp; </td>
      </tr>
      <tr>
        <td>
          <table width="100%">
            <? for( $i = 0 ; $i < count($channels_array); $i++  ){?>
            <tr>
              <td bgcolor="#42c472"><strong>
                <?=$channels_number[$i]?>
              </strong></td>
              <td bgcolor="#DDEEFF"><?=$channels_array[$i]->getName() . "<br><div class=\"description\">" .$channels_array[$i]->getDescription() ."</div>" ?></td>
              <td bgcolor="#DDEEFF"><a href="ppa.php?edit_channel=1&id_channel=<?=$channels_array[$i]->getId()?>&id=<?=$_GET['id']?>">Editar</a>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;<a href="ppa.php?delete_channel=1&id_channel=<?=$channels_array[$i]->getId()?>&id=<?=$_GET['id']?>">Borrar</a></td>
            </tr>
            <? } ?>
        </table></td>
      </tr>
      <? if(!$_GET['add_client']){?>
      <tr>
        <td>
        <td>     
      </tr>
      <tr>
        <td align="right"> <a href="ppa.php?show_channels=1&id=<?=$_GET['id']?>">Agregar Canal</a> </td>
      </tr>
      <? } ?>
      <input type="hidden" name="ppa" value="<?=$ppa->getId()?>">
      <input type="hidden" name="id" value="<?=($_GET['add_client']?"0":$_GET['id'])?>">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="39" colspan="3" align="center"><input type="submit" name="send_client" value="<?=($_GET['add_client']?"Crear":"Actualizar")?>" class="large">
            <br>
            <input type="button" name="send2_client" value="Regresar" class="large" onClick='document.location="ppa.php?clients=1"'>
        </td>
      </tr>
      <?
}else{
   if( isset( $_GET['show_channels'] ) ){
     $client = new Client( $_GET['id'] );
     $client_channels = $client->getChannels();
     $client_channels_array = $client_channels->getChannels();
     $channels = $ppa->getChannels();
     for( $i = 0 ; $i < count( $client_channels_array ); $i++ ){
       for( $j = 0; $j < count( $channels ); $j++ ){
	 if( $channels[$j]->getId() == $client_channels_array[$i]->getId() ){
	   $temp = array_slice( $channels, 0, $j );
	   $temp1 = array_slice( $channels, $j+1 );
	   $channels = array_merge( $temp, $temp1 );
	 }
       }
     }
 ?>
      <tr>
        <td align="center">
          <table>
            <?
	for($i=0; $i<count($channels); $i++){
	?>
            <tr>
              <td bgcolor="#DDEEFF"> <strong>
                <?=$channels[$i]->getName() . "<br><div class=\"description\">" .$channels[$i]->getDescription() ."</div>"?>
              </strong> </td>
              <td bgcolor="#DDEEFF">
                <input type="checkbox" name="c_channel[]" value="<?=$channels[$i]->getId()?>">
              </td>
            </tr>
            <?	}
?>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp; </td>
      </tr>
      <tr>
        <td align="center">
          <input type="hidden" name="id" value="<?=$_GET['id']?>">
          <input type="button" name="regresar" value="Regresar" onClick='document.location="ppa.php?edit_client=1&id=<?=$_GET['id']?>"'>
          <input type="submit" name="asign_channel" value="Asignar">
        </td>
      </tr>
      <?   }else{
    if( isset( $_GET['edit_channel'] ) ){
      $client = new Client( $_GET['id'] );
      $channels = $client->getChannels();
      $channels_array = $channels->getChannels();
      $channels_numbers = $channels->getNumbers();
      $channels_groups = $channels->getGroups();

      for( $i = 0; $i < count( $channels_array ); $i++ ){
	if( $channels_array[$i]->getId() == $_GET['id_channel'] ){
	  $number = $channels_numbers[$i];
	  $group  = $channels_groups[$i];
	  $name   = $channels_array[$i]->getName(); 
	  $i = count( $channels_array )+1;
	}
      }
?>
      <tr>
        <td align="center" colspan="2" style="font-size:15;"><strong><?=$name?></strong></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Número</strong></div></td>
        <td><div align="left"><input id="number" type="text" name="channel_num" size="7" value="<?=$number?>"></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Grupo</strong></div></td>
        <td><div align="left">
            <input type="text" name="group_name" size="10" value="<?=$group?>">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp; </td>
      </tr>
      <tr>
        <td height="39" colspan="3" align="center">
          <input type="hidden" name="id" value="<?=$_GET['id']?>">
          <input type="hidden" name="id_channel" value="<?=$_GET['id_channel']?>">
          <input type="submit" name="channel_edit" value="Cambiar" class="large">
          <br>
          <input name="button" type="button" class="large" onClick='document.location="ppa.php?edit_client=1&id=<?=$_GET['id']?>"' value="Regresar">
        </td>
      </tr>
      <?
    }else{
?>
      <?	
    $clients = $ppa->getClients();
	for($i=0; $i<count($clients); $i++){
	?>
      <tr>
        <td bgcolor="#DDEEFF"> <strong>
          <?=$clients[$i]->getName()?>
        </strong> </td>
        <td align="center" bgcolor="#DDEEFF"><a href="ppa.php?edit_client=1&id=<?=$clients[$i]->getId()?>">Editar</a></td>
      </tr>
      <?	 }
?>
      <tr>
        <td colspan="3" align="center" bgcolor="#FFFFCC"><a href="ppa.php?add_client=1">Agregar Cabecera</a></td>
      </tr>
      <?
       }
  }
 }  
?>
    </table>
  </form>
<script>
window.onload = function( ){ document.getElementById( "number" ).focus(); }
document.getElementById( "number" ).focus();
</script>
</body>
</html>