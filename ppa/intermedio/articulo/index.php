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
  $articulo = new Articulo( $_POST['id_obj'] );
  $contenido = $articulo->getContenido();
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

  if( $_POST['categoria'] != 'sinasignar' && $_POST['categoria'] != $articulo->getCategoria() ){
    $articulo->setCategoria( $_POST['categoria'] );
	?> <script>  window.opener.document.location.reload(); </script> <?
  }else{
    if( $_POST['categoria'] == 'sinasignar' ){
       $articulo->setCategoria( "" );
	?> <script>  window.opener.document.location.reload(); </script> <?	   
	}
  }	
  $articulo->setContenido($contenido);
  $articulo->commit();
  $contenido = $contenido->getTexto();
  $cambio = true;
}else{
   $sql = "select id from articulo where pagina = ".$id;
   $query = db_query( $sql );
   $row = db_fetch_array( $query );
   $articulo = new Articulo( $row['id'] );
   $contenido = $articulo->getContenido();
   $contenido = $contenido->getTexto();
}
if( trim( $contenido ) == "" ){
   $contenido = $articulo->getBase();  
}

?>
<html>
<head>
<title>Intermedio - ARTÍCULO</title>
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
<td valign="top"><img src="../images/cabezote_articulo.gif" width="400" height="40"></td>
</tr>
<tr>
<td>
<?
//echo $_SERVER['HTTP_USER_AGENT'];
if(strstr($_SERVER['HTTP_USER_AGENT'],"MSIE") && !strstr( $_SERVER['HTTP_USER_AGENT'],"Mac" )){
	if(strstr($_SERVER['HTTP_USER_AGENT'],"6.0")){
       include('../editorie/editor.php');
	   $tipo_editor = "ie";
	}
}else{
	if(strstr($_SERVER['HTTP_USER_AGENT'],"Mozilla/5")  && !strstr( $_SERVER['HTTP_USER_AGENT'],"Mac" ) ){
       include('../editormoz/editor1.php');
   	   $tipo_editor = "moz";
	}else{
	      echo "Articulo : <br>" . $articulo->contenido->getTexto();
	}
}
?>
</td>  
</tr>
<form action="index.php" method="post" name="principal">
<tr>
<td align="center">
<font size="3px">Categorías: </font>
<select name="categoria">
<? if( trim( $articulo->getCategoria() ) == "" ) {?>
<option value="sinasignar" selected>-- Sin Asignar --</option>
<? }else{ ?>
<option value="sinasignar">-- Sin asignar --</option>
<? } ?>
<? if( trim( $articulo->getCategoria() ) == "actualidad" ) {?>
<option value="actualidad" selected >Actualidad</option>
<? }else{ ?>
<option value="actualidad">Actualidad</option>
<? } ?>
<? if( trim( $articulo->getCategoria() ) == "alacarta" ) {?>
<option value="alacarta" selected >A la carta</option>
<? }else{ ?>
<option value="alacarta">A la carta</option>
<? } ?>
<? if( trim( $articulo->getCategoria() ) == "alfombraroja" ) {?>
<option value="alfombraroja" selected >Alfombra roja</option>
<? }else{ ?>
<option value="alfombraroja">Alfombra roja</option>
<? } ?>
<? if( trim( $articulo->getCategoria() ) == "alrededordelmundo" ) {?>
<option value="alrededordelmundo" selected >Alrededor del mundo</option>
<? }else{ ?>
<option value="alrededordelmundo">Alrededor del mundo</option>
<? } ?>
<? if( trim( $articulo->getCategoria() ) == "ambitos" ) {?>
<option value="ambitos" selected >Ámbitos</option>
<? }else{ ?>
<option value="ambitos">Ámbitos</option>
<? } ?>
<? if( trim( $articulo->getCategoria() ) == "articulos" ) {?>
<option value="articulos" selected >Artículos</option>
<? }else{ ?>
<option value="articulos">Artículos</option>
<? } ?>
<? if( trim( $articulo->getCategoria() ) == "atiempo" ) {?>
<option value="atiempo" selected >A tiempo</option>
<? }else{ ?>
<option value="atiempo">A tiempo</option>
<? } ?>
<? if( trim( $articulo->getCategoria() ) == "caprichos" ) {?>
<option value="caprichos" selected >Caprichos</option>
<? }else{ ?>
<option value="caprichos">Caprichos</option>
<? } ?>
<? if( trim( $articulo->getCategoria() ) == "cerosyunos" ) {?>
<option value="cerosyunos" selected >Ceros y unos</option>
<? }else{ ?>
<option value="cerosyunos">Ceros y unos</option>
<? } ?>
<? if( trim( $articulo->getCategoria() ) == "complice" ) {?>
<option value="complice" selected >Cómplice</option>
<? }else{ ?>
<option value="complice">Cómplice</option>
<? } ?>
<? if( trim( $articulo->getCategoria() ) == "desdeelvestier" ) {?>
<option value="desdeelvestier" selected >Desde el vestier</option>
<? }else{ ?>
<option value="desdeelvestier">Desde el vestier</option>
<? } ?>
<? if( trim( $articulo->getCategoria() ) == "desdelavina" ) {?>
<option value="desdelavina" selected >Desde la viña</option>
<? }else{ ?>
<option value="desdelavina">Desde la viña</option>
<? } ?>
<? if( trim( $articulo->getCategoria() ) == "fashionnights" ) {?>
<option value="fashionnights" selected >Fashion nights</option>
<? }else{ ?>
<option value="fashionnights">Fashion nights</option>
<? } ?>
<? if( trim( $articulo->getCategoria() ) == "latitudes" ) {?>
<option value="latitudes" selected >Latitudes</option>
<? }else{ ?>
<option value="latitudes">Latitudes</option>
<? } ?>
<? if( trim( $articulo->getCategoria() ) == "saludybelleza" ) {?>
<option value="saludybelleza" selected >Salud y belleza</option>
<? }else{ ?>
<option value="saludybelleza">Salud y belleza</option>
<? } ?>
<? if( trim( $articulo->getCategoria() ) == "sobreruedas" ) {?>
<option value="sobreruedas" selected >Sobre ruedas</option>
<? }else{ ?>
<option value="sobreruedas">Sobre ruedas</option>
<? } ?>
</select>
</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td align="center">
<font size="3px">Comentarios: </font>
<img src="../images/boton_agregar.gif" border="0" align="absmiddle" onClick="addComentario()">
<br>
</td>
</tr>
<tr>
    <td align="center"> <textarea name="comentario" id="comentario" cols="50" rows="10" readonly="readonly"><?=$articulo->contenido->getComentario()?></textarea>
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
<input type="hidden" name="id_obj" value="<?=$articulo->getId()?>">
<input type="hidden" name="HTMLContent">
<input type="hidden" name="user" value="<?=$user?>">
<input type="image" name="cambiar" value="cambiar" onClick="javascript:sampleSave('<?=( $tipo_editor == "ie" )?( "ie" ):( "moz" ) ?>')" src="../images/boton_cambiar.gif">
&nbsp;&nbsp;&nbsp;
<? if( $user == 'intercable' && $articulo->contenido->getFechaAprobacion() == "0000-00-00" ){ ?>
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
