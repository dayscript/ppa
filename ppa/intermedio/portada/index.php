<?
require_once('../../include/db.inc.php');
require_once('../../class/Application.class.php');
session_start();
if(isset($_POST['user']))$_GET['user'] = $_POST['user'];

if(isset($_GET['id']))$id = $_GET['id'];
else if(isset($_POST['id']))$id = $_POST['id'];
$_SESSION['app']->connect();

$page = new Pagina($id);

if(isset($_POST['cambiar_x'])){
	$page->objeto->setTema($_POST['tema']);
    if( isset( $_POST['comentario'] ) ){
      $page->objeto->setComentario( $_POST['comentario'] );
    }
	$page->commit();
	?>
	<script language="JavaScript">
		window.opener.location.reload();
	</script>
	<?
}


?>
<html>
<head>
<title>Intermedio - PORTADA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<script>
  function addComentario(){
      nuevo_comentario = '<? echo ucwords( $_GET['user'] )." ( ".date("Y-m-d H:i:s")." ) "?>: ' + window.prompt( 'Escriba el comentario' ) + '. ' + '\n';
      comentarios =  nuevo_comentario + document.getElementById('comentario').value;
      document.getElementById('comentario').value = comentarios;
  }
</script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<form name="f1" method="post" action="<?=$_SERVER['PHP_SELF']?>">
<input name="id" value="<?=$id?>" type="hidden">
<input name="user" value="<?=$_GET['user']?>" type="hidden">
<table border="0" align="left" cellpadding="0" cellspacing="0">
<tr>
<td align="center" valign="top"><div align="left"><img src="../images/cabezote_portada.gif" width="400" height="39"></div></td>
</tr>
<tr>
	<td align="center" > <br>
	Tema: <input class="large" type="text" name="tema" value="<?=$page->objeto->getTema()?>">
	</td>
</tr>
<tr>
      <td>&nbsp; </td>
</tr>
<tr>
      <td> <div align="center"><font size="3px">Comentarios: </font> <img src="../images/boton_agregar.gif" border="0" align="absmiddle" onClick="addComentario()"> 
        </div></td>
</tr>
<tr>
<td align="center">
<textarea name="comentario" id="comentario" cols="50" rows="10" readonly="readonly"><?=$page->objeto->getComentario()?></textarea>
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
<input type="image" src="../images/boton_cambiar.gif" name="cambiar" border="0">&nbsp;&nbsp;&nbsp;
<a href="javascript:window.close()"><img src="../images/boton_cancelar.gif" width="103" height="24" border="0"></a></td>
</tr>
</table>
</form>
</body>
</html>
