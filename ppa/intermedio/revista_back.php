<?
$path = stripslashes(substr($_SERVER['PATH_TRANSLATED'],0,strpos($_SERVER['PATH_TRANSLATED'],addslashes(str_replace("/","\\",$_SERVER['SCRIPT_NAME']))))) . "\\";
require_once($path . "include/db.inc.php");
require_once( 'class/Application.class.php' );
session_start();
if(isset($_GET['user']))$user = $_GET['user'];
else $user = "dayscript";
?>
<html>
<head>
<title>Intercable</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
<!--
function popup(pagina,nombre,ancho,alto,scrollbar){
	newWindow=window.open(pagina,nombre,'resizable=no,status=no,scrollbars='+scrollbar+',menubar=no,width='+ancho+',height='+alto);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<img src="images/cabezote_listadopaginas.gif" width="400" height="39"> 
<table width="750" cellpadding="0" cellspacing="0" border="0">
<tr bgcolor="#FFCC99">
    <td height="26" colspan="6" align="right"><div>Mostrar Páginas : 
	- <a href="<?=$_SERVER['PHP_SELF']?>?v=all">[Todas]</a>
    - <a href="<?=$_SERVER['PHP_SELF']?>?v=notype">[Sin Asignar Tipo]</a></div>
	</td>
  </tr>
<tr><td colspan="6">&nbsp;</td></tr>
<tr>
<?
if(!session_is_registered("app")){
	$app = new Application("include/config.php");
	$app->connect();
	session_register("app");
	$_SESSION["app"] = $app;
} else {
	if(!isset($app) && isset($_SESSION['app']))
		$app = $_SESSION['app'];
}
$revistas = $app->getRevistas("2003","");
$revista = $revistas[0];
$pags = $revista->getPaginas();
$align[0]="left";
$align[1]="right";
for ($i=0; $i<count($pags)-1; $i++){
	if($i==0){
		?><td align="center" valign="top"><img src="images/null.gif">&nbsp;<br></td><?continue;
	}
	if ((($i) % 6) == 0){
		?></tr><tr><?
	}
	if(isset($_GET['v']) && $_GET['v']=="notype" && $pags[$i-1]->tipo->getNombre() != "")continue;
	?>
	<td valign="top" align="<?=$align[abs(($pags[$i-1]->getNumero()-1)%2)]?>">
	<?
	$state = $pags[$i-1]->getState();
	if($state == 0){
		?><a href="javascript:popup('cambiartipo.php?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','cambiartipo',410,210,'no')"><img alt="Página <?=$pags[$i-1]->getNumero()?>" border="0" src="images/<?=abs(($pags[$i-1]->getNumero()-1)%2)?>_icon_sinasignar.gif"></a>&nbsp;<br><?
	} else if ($state == 1){
		if($pags[$i-1]->tipo->getNombre() == "articulo" && $pags[$i-1]->objeto->categoria){
			?><a href="javascript:popup('<?=$pags[$i-1]->tipo->getModulo()?>?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','editar',417,700,'yes')"><img alt="Página <?=$pags[$i-1]->getNumero()?>" border="0" src="images/<?=abs(($pags[$i-1]->getNumero()-1)%2)?>_icon_<?=$pags[$i-1]->objeto->getCategoria()?>.gif"></a>&nbsp;<br><?
		}else if ($pags[$i-1]->getPreview() != ""){
			if($pags[$i-1]->getFechaAprobacion() > "0000-00-00"){
				$temp = "2";
			} else {
				$temp = "3";
			}
			?><a href="javascript:popup('preview.php?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','preview',410,700,'no')"><img alt="Página <?=$pags[$i-1]->getNumero()?>" width="100" border="0" src="images/<?=$pags[$i-1]->getId()?>_preview<?=$temp?>.jpg"></a>&nbsp;<br><?
		} else {
			?><a href="javascript:popup('<?=$pags[$i-1]->tipo->getModulo()?>?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','editar',417,700,'yes')"><img alt="Página <?=$pags[$i-1]->getNumero()?>" border="0" src="images/<?=abs(($pags[$i-1]->getNumero()-1)%2)?>_icon_<?=$pags[$i-1]->tipo->getNombre()?>.gif"></a>&nbsp;<br><?
		}
	} else if ($state == 2){
		if($pags[$i-1]->getPreview() != ""){
			if($pags[$i-1]->getFechaAprobacion() > "0000-00-00"){
				$temp = "2";
			} else {
				$temp = "3";
			}
			?><a href="javascript:popup('preview.php?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','preview',410,700,'no')"><img alt="Página <?=$pags[$i-1]->getNumero()?>" width="100" border="0" src="images/<?=$pags[$i-1]->getId()?>_preview<?=$temp?>.jpg"></a>&nbsp;<br><?
		} else {
			if($pags[$i-1]->tipo->getNombre() == "articulo" && $pags[$i-1]->objeto->categoria){
				?><a href="javascript:popup('preview.php?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','preview',417,700,'yes')"><img alt="Página <?=$pags[$i-1]->getNumero()?>" border="0" src="images/<?=abs(($pags[$i-1]->getNumero()-1)%2)?>_icon_<?=$pags[$i-1]->objeto->getCategoria()?>.gif"></a>&nbsp;<br><?
			}else{
				?><a href="javascript:popup('preview.php?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','preview',417,700,'yes')"><img alt="Página <?=$pags[$i-1]->getNumero()?>" border="0" src="images/<?=abs(($pags[$i-1]->getNumero()-1)%2)?>_icon_<?=$pags[$i-1]->tipo->getNombre()?>.gif"></a>&nbsp;<br><?
			}
		}
	}
	?><a href="javascript:popup('cambiartipo.php?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','cambiartipo',410,210,'no')"><img src="images/boton_<?=($state==0?"asignar":"cambiar")?>tipo.gif" border="0"></a><br><?
	if(class_exists(ucwords($pags[$i-1]->tipo->getNombre())) && $pags[$i-1]->tipo->getNombre() != "contenido"){
		?><a href="javascript:popup('<?=$pags[$i-1]->tipo->getModulo()?>?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','editar',417,700,'yes')"><img src="images/boton_editar.gif" border="0"></a><br><?
	} else {
		?><img src="images/_boton_editar.gif" border="0"><br><?
	}
	if($state == 2 || (($state >0)&&!(class_exists(ucwords($pags[$i-1]->tipo->getNombre())) && $pags[$i-1]->tipo->getNombre() != "contenido"))){
		?><a href="javascript:popup('preview.php?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','preview',410,700,'no')"><img src="images/boton_preview.gif" border="0"></a><br><?
	}else{
		?><img src="images/_boton_preview.gif" border="0"><br><?
	}
	?>
	</td>
	<?	
}
$revista->commit();
?>
</tr>
</table>
</body>
</html>
