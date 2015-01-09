<?
require_once('../../include/db.inc.php');
require_once('../../class/Application.class.php');
session_start();
$_SESSION['app']->connect();
if( isset( $_GET['id'] ) ){
   $id = $_GET['id'];
}else{
   if( isset( $_POST['id'] ) ){
      $id = $_POST['id'];
   }
}
if( isset( $_GET['user'] ) ){
   $user = $_GET['user'];
}else{
   if( isset( $_POST['user'] ) ){
      $user = $_POST['user'];
   }
}

if( isset( $_POST['cambiar_x'] ) || isset( $_POST['aprobar_x'] ) ){
  $nacionales = new Nacionales( $_POST['id_obj'] );
  $contenido = $nacionales->getContenido();
  if( $_POST['HTMLContent'] != "" ){
     $parte=strtok($_POST['HTMLContent'],"\n");
     if($parte){
       while($parte){
         $contenido_texto.=trim($parte)." ";
         $parte=strtok("\n");
       }
     } else {
	   $contenido_texto=$_POST['HTMLContent'];
     }
  $contenido->setTexto( stripslashes( $contenido_texto ) );
 }  
  if( isset( $_POST['aprobar_x'] ) ){
     $contenido->setFechaAprobacion( date("Y-m-d") );
  }
  if( isset( $_POST['comentario'] ) ){
     $contenido->setComentario( $_POST['comentario'] );
  }
  $nacionales->setContenido($contenido);
  $nacionales->commit();
  $contenido = $contenido->getTexto();
  $cambio = true;
}else{
   $sql = "select id from nacionales where pagina = ".$id;
   $query = db_query( $sql );
   $row = db_fetch_array( $query );
   $nacionales = new Nacionales( $row['id'] );
   $contenido = $nacionales->getContenido();
   $contenido = $contenido->getTexto();
}
if( trim( $contenido ) == "" ){
   $contenido = $nacionales->getBase();  
}

?>
<html>
<head>
<title>Intermedio - NACIONALES</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
  function sampleSave(editor){
    if( editor == "ie" ){
      document.forms['principal'].HTMLContent.value = cleanup(document.all.ewe.innerHTML);
	}else{
	   if( editor == "moz" ){
         document.forms['principal'].HTMLContent.value = document.getElementById('edit').contentWindow.document.body.innerHTML;   
	   }else{
	      if( editor == "" ){
		     document.forms['principal'].HTMLContent = "";
		  }
	   }
	}
    document.forms['principal'].submit();
  }
  
  function addComentario(){
      nuevo_comentario = '<? echo ucwords( $user )." ( ".date("Y-m-d H:i:s")." ) "?>: ' + window.prompt( 'Escriba el comentario' ) + '. ' + '\n';
      comentarios =  nuevo_comentario + document.getElementById('comentario').value;
      document.getElementById('comentario').value = comentarios;
  }
</script>  
</head>
<link href="../estilos.css" rel="stylesheet" type="text/css">
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<? if( db_numrows( $query ) > 0  || $cambio = true ){ ?>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
<tr>
<td valign="top"><img src="../images/cabezote_nacionales.gif" width="400" height="40"></td>
</tr>
<tr>
<td>
<?
if(strstr($_SERVER['HTTP_USER_AGENT'],"MSIE")){
	if(strstr($_SERVER['HTTP_USER_AGENT'],"6.0")){
       include('../editorie/editor.php');
	   $tipo_editor = "ie";
	}
}else{
	if(strstr($_SERVER['HTTP_USER_AGENT'],"Mozilla/5")  && !strstr( $_SERVER['HTTP_USER_AGENT'],"Macintosh" ) ){
       include('../editormoz/editor1.php');
   	   $tipo_editor = "moz";
	}else{
	   if( strstr( $_SERVER['HTTP_USER_AGENT'],"Macintosh" ) ){
	      echo $nacionales->contenido->getTexto();
	   }
	}
}
?>
</td>  
</tr>
<form action="index.php" method="post" name="principal">
<tr>
<td align="center">
<font size="3px">Comentarios: </font>
<img src="../images/boton_agregar.gif" border="0" align="absmiddle" onClick="addComentario()">
<br>
</td>
</tr>
<tr>
    <td align="center"> <textarea name="comentario" id="comentario" cols="50" rows="10" readonly="readonly"><?=$nacionales->contenido->getComentario()?></textarea>
	<br>
	</td>
</tr>
<tr>
<td>&nbsp;

</td>
</tr>
<tr>
<td>
<td>
</tr>
<tr>
<td align="center" background="../images/cabezote_botones.gif">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="id_obj" value="<?=$nacionales->getId()?>">
<input type="hidden" name="HTMLContent">
<input type="hidden" name="user" value="<?=$user?>">
<input type="image" name="cambiar" value="cambiar" onClick="javascript:sampleSave('<?=( $tipo_editor == "ie" )?( "ie" ):( "moz" ) ?>')" src="../images/boton_cambiar.gif">
&nbsp;&nbsp;&nbsp;
<? if( $user == 'intercable' && $nacionales->contenido->getFechaAprobacion() == "0000-00-00" ){ ?>
<input type="image" name="aprobar" value="aprobar" onClick="javascript:sampleSave('<?=( $tipo_editor == "ie" )?( "ie" ):( "moz" ) ?>')" src="../images/boton_aprobar.gif">
&nbsp;&nbsp;&nbsp;
<? } ?>
<img src="../images/boton_cancelar.gif" border="0" onClick="window.close()">
</td>
</tr>
</form>
</table>
<? } ?>
</body>
</html>
