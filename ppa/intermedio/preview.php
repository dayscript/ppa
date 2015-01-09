<?
require_once("../include/db.inc.php");
require_once( '../class/Application.class.php' );
session_start();
if(isset($_POST['user']))$_GET['user'] = $_POST['user'];
function thumbs($source, $dest, $dest2){
	$image = imagecreatefrompng($source);
	$image2 = imagecreate(100,130);
	$image3 = imagecreate(100,130);
	imagecopyresized($image2,$image,0,0,0,0,100,130,600,780);
	imagecopyresized($image3,$image,0,0,0,0,100,130,600,780);
	$total = ImageColorsTotal($image3);
	if($total<=0){
		imagepng($image,$source);
		$image = imagecreatefrompng($source);
		imagecopyresized($image2,$image,0,0,0,0,100,130,600,780);
		imagecopyresized($image3,$image,0,0,0,0,100,130,600,780);
	}
	for( $i=0; $i<$total; $i++){ 
		$old = ImageColorsForIndex($image3, $i); 
		$commongrey = (int)(($old[red] + $old[green] + $old[blue]) / 3); 
		ImageColorSet($image3, $i, $commongrey, $commongrey, $commongrey); 
	}
	imagejpeg($image2, $dest, 80);
	imagejpeg($image3, $dest2, 80);
}


if(isset($_GET['id']))$id = $_GET['id'];
else if(isset($_POST['id']))$id = $_POST['id'];
$_SESSION['app']->connect();

$page = new Pagina($id);
if( isset( $_POST['cambiar_x'] ) || isset( $_POST['aprobar_x'] ) ){
  if( isset( $_POST['comentario'] ) ){
    $page->setComentario( $_POST['comentario'] );
  }
  $page->commit();
}
if(isset($_POST['aprobar_x'])){
	$page->setFechaAprobacion(date("Y-m-d"));
	$page->commit();
	?>
	<script language="JavaScript">
		window.opener.location.reload();
	</script>
	<?
}

//		$page->_print();
if(isset($_FILES['file'])){
	echo "Archivo subido...";
	if(move_uploaded_file($_FILES['file']['tmp_name'],"images/" . $id . "_preview.png")){
		$resource = "images/" . $id . "_preview.png";
		thumbs($resource, "images/" . $id . "_preview2.jpg",  "images/" . $id . "_preview3.jpg");
		$page->setPreview("images/" . $id . "_preview.png");
		$page->commit();
//		$page->_print();
		?>
		<script language="JavaScript">
			window.opener.location.reload();
		</script>
		<?
	}else{
		echo "Error al copiar el archivo.";
	}

}

?>
<html>
<head>
<title>Intermedio - PREVIEW</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function addComentario(){
      nuevo_comentario = '<? echo ucwords( $user )." ( ".date("Y-m-d H:i:s")." ) "?>: ' + window.prompt( 'Escriba el comentario' ) + '. ' + '\n';
      comentarios =  nuevo_comentario + document.getElementById('comentario').value;
      document.getElementById('comentario').value = comentarios;
  }
</script>
</head>
<link href="estilos.css" rel="stylesheet" type="text/css">
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<form name="f1" method="post" enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
<input name="id" value="<?=$id?>" type="hidden">
<input name="user" value="<?=$_GET['user']?>" type="hidden">
<table border="0" align="left" cellpadding="0" cellspacing="0">
<tr>
<td align="center" valign="top"><img src="images/cabezote_preview.gif" width="400" height="39"></td>
</tr>
<tr>
      <td align="center" height="400"> <br>
	  <? 
	  if($page->getPreview() != "" && file_exists("images/" . $page->getId() . "_preview.png")){
		  ?><a href="images/<?=$page->getId()?>_preview.png" target="_blank"><img src="images/<?=$page->getId()?>_preview.png"  height="380" border="0" align="middle"></a><?
	  } else {
		  ?><img src="images/nopreview.jpg" height="380" border="0" align="middle"><?
	  }
	  ?>
        <br><br>
		<? if($_GET['user']=="dayscript"){
			?><input class="large" type="file" name="file"><?
		} ?>
			 </td>
</tr>
<tr>
      <td>&nbsp; </td>
</tr>
<tr>
      <td> <div align="center">
<font size="3px">Comentarios: </font>
 <img src="images/boton_agregar.gif" border="0" align="absmiddle" onClick="addComentario()"> 
        </div></td>
</tr>
<tr>
<td align="center">
<textarea name="comentario" id="comentario" cols="50" rows="10" readonly="readonly"><?=$page->getComentario()?></textarea>
</td>
</tr>
<tr>
      <td align="center">&nbsp; </td>
</tr>
<tr>
<td>

<td>
</tr>
<tr>
<td align="center" background="images/cabezote_botones.gif">
<input type="image" name="cambiar" value="cambiar" src="images/boton_cambiar.gif">&nbsp;&nbsp;&nbsp;
<?
if ($_GET['user'] == "intercable" && $page->getFechaAprobacion() == "0000-00-00"){
	?><a href="javascript:document.forms[0].submit()"><input type="image" src="images/boton_aprobar.gif" name="aprobar"></a>&nbsp;&nbsp;&nbsp;<?
}
?>
<a href="javascript:window.close()"><img src="images/boton_cancelar.gif" width="103" height="24" border="0"></a></td>
</tr>
</table>
</form>
</body>
</html>
