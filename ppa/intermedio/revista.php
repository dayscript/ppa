<?
/*
require_once('include/db.inc.php');
require_once('class/Application.class.php');
session_start();
$_SESSION['app']->connect();
if( isset( $_GET['id'] ) ){
   $id = $_GET['id'];
}else{
   if( isset( $_POST['id'] ) ){
      $id = $_POST['id'];
   }
}
*/
/*
if( isset( $_GET['user'] ) ){
   $user = $_GET['user'];
}else{
   if( isset( $_POST['user'] ) ){
      $user = $_POST['user'];
   }else{
       $user = "dayscript";
   }
}
*/
session_start();
$user = $_SESSION['user'];
?>
<img src="intermedio/images/cabezote_listadopaginas.gif" width="400" height="39"> 
<table width="750" cellpadding="0" cellspacing="0" border="0" class="textos">
<tr bgcolor="#FFCC99">
    <td height="26" colspan="6" align="right"><div>Mostrar Páginas : 
	- <a href="<?=$_SERVER['PHP_SELF']?>?v=all">[Todas]</a>
    - <a href="<?=$_SERVER['PHP_SELF']?>?v=notype">[Sin Asignar Tipo]</a></div>
	</td>
  </tr>
<tr><td colspan="6">&nbsp;</td></tr>
<tr>
<?
$revista = new Revista($id);
$pags = $revista->getPaginas();
if( count( $pags ) == 0 ){
   for( $i = 0; $i < 135; $i++ ){
      $pagina = new Pagina();
	  $pagina->setNumero( $i-1 );
	  $pagina->commit();
	  $revista->addPagina( $pagina );
   }
   $pags = $revista->getPaginas();
}
//print_r($pags);
$align[0]="left";
$align[1]="right";
for ($i=0; $i<count($pags)-1; $i++){
	if($i==0){
		?><td align="center" valign="top"><img src="intermedio/images/null.gif">&nbsp;<br></td><?continue;
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
		?><a href="javascript:popup('intermedio/cambiartipo.php?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','cambiartipo',410,210,'no')"><img alt="Página <?=$pags[$i-1]->getNumero()?>" border="0" src="intermedio/images/<?=abs(($pags[$i-1]->getNumero()-1)%2)?>_icon_sinasignar.gif"></a>&nbsp;<br><?
	} else if ($state == 1){
		if($pags[$i-1]->tipo->getNombre() == "articulo" && $pags[$i-1]->objeto->categoria){
			?><a href="javascript:popup('intermedio/<?=$pags[$i-1]->tipo->getModulo()?>?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','editar',417,700,'yes')"><img alt="Página <?=$pags[$i-1]->getNumero()?>" border="0" src="intermedio/images/<?=abs(($pags[$i-1]->getNumero()-1)%2)?>_icon_<?=$pags[$i-1]->objeto->getCategoria()?>.gif"></a>&nbsp;<br><?
		}else if ($pags[$i-1]->getPreview() != ""){
			if($pags[$i-1]->getFechaAprobacion() > "0000-00-00"){
				$temp = "2";
			} else {
				$temp = "3";
			}
			?><a href="javascript:popup('intermedio/preview.php?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','preview',410,800,'no')"><img alt="Página <?=$pags[$i-1]->getNumero()?>" width="100" border="0" src="intermedio/images/<?=$pags[$i-1]->getId()?>_preview<?=$temp?>.jpg"></a>&nbsp;<br><?
		} else {
			?><a href="javascript:popup('intermedio/<?=$pags[$i-1]->tipo->getModulo()?>?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','editar',417,800,'yes')"><img alt="Página <?=$pags[$i-1]->getNumero()?>" border="0" src="intermedio/images/<?=abs(($pags[$i-1]->getNumero()-1)%2)?>_icon_<?=$pags[$i-1]->tipo->getNombre()?>.gif"></a>&nbsp;<br><?
		}
	} else if ($state == 2){
		if($pags[$i-1]->getPreview() != ""){
			if($pags[$i-1]->getFechaAprobacion() > "0000-00-00"){
				$temp = "2";
			} else {
				$temp = "3";
			}
			?><a href="javascript:popup('intermedio/preview.php?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','preview',410,700,'no')"><img alt="Página <?=$pags[$i-1]->getNumero()?>" width="100" border="0" src="intermedio/images/<?=$pags[$i-1]->getId()?>_preview<?=$temp?>.jpg"></a>&nbsp;<br><?
		} else {
			if($pags[$i-1]->tipo->getNombre() == "articulo" && $pags[$i-1]->objeto->categoria){
				?><a href="javascript:popup('intermedio/preview.php?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','preview',417,700,'yes')"><img alt="Página <?=$pags[$i-1]->getNumero()?>" border="0" src="intermedio/images/<?=abs(($pags[$i-1]->getNumero()-1)%2)?>_icon_<?=$pags[$i-1]->objeto->getCategoria()?>.gif"></a>&nbsp;<br><?
			}else{
				?><a href="javascript:popup('intermedio/preview.php?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','preview',417,700,'yes')"><img alt="Página <?=$pags[$i-1]->getNumero()?>" border="0" src="intermedio/images/<?=abs(($pags[$i-1]->getNumero()-1)%2)?>_icon_<?=$pags[$i-1]->tipo->getNombre()?>.gif"></a>&nbsp;<br><?
			}
		}
	}
	?><a href="javascript:popup('intermedio/cambiartipo.php?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','cambiartipo',410,210,'no')"><img src="intermedio/images/boton_<?=($state==0?"asignar":"cambiar")?>tipo.gif" border="0"></a><br><?
	if(class_exists(ucwords($pags[$i-1]->tipo->getNombre())) && $pags[$i-1]->tipo->getNombre() != "contenido"){
		?><a href="javascript:popup('intermedio/<?=$pags[$i-1]->tipo->getModulo()?>?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','editar',417,700,'yes')"><img src="intermedio/images/boton_editar.gif" border="0"></a><br><?
	} else {
		?><img src="intermedio/images/_boton_editar.gif" border="0"><br><?
	}
	if($state == 2 || (($state >0)&&!(class_exists(ucwords($pags[$i-1]->tipo->getNombre())) && $pags[$i-1]->tipo->getNombre() != "contenido"))){
		?><a href="javascript:popup('intermedio/preview.php?user=<?=$user?>&id=<?=$pags[$i-1]->getId()?>','preview',410,700,'no')"><img src="intermedio/images/boton_preview.gif" border="0"></a><br><?
	}else{
		?><img src="intermedio/images/_boton_preview.gif" border="0"><br><?
	}
	?>
	</td>
	<?	
}
$revista->commit();
?>
</tr>

<?
//if($user == "dayscript"){
?>
<tr><td colspan="6">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="textos">
  <tr>
    <td height="59" bgcolor="#CCCCCC" valign="middle"><div align="center">
        <form name="form1" method="post" action="intermedio.php">
		<input type="hidden" name="mes" value="<?=$revista->getMes()?>">
		<input type="hidden" name="ano" value="<?=$revista->getAno()?>">
          Puede generar la programación para esta revista: 
                <input type="submit" name="Submit" value="Consultar Programación">
        </form>
      </div></td>
  </tr>
  <tr>
  <td bgcolor="#CCCCCC" align="center">
  Sinopsis:
  </td>
  </tr>
  <tr>
    <td height="59" bgcolor="#CCCCCC" valign="middle"><div align="center">
        <form name="form2" method="post" action="intermedio.php">
		<input type="hidden" name="mes1" value="<?=$revista->getMes()?>">
		<input type="hidden" name="ano1" value="<?=$revista->getAno()?>">
          
                <input type="submit" name="choose_sinop" value="Escoger Sinopsis">
                <input type="submit" name="list_sinop" value="Lista Sinopsis">
                <input type="submit" name="show_sinop_faltante" value="Ver Sinopsis Faltante">
                <input type="submit" name="show_sinop" value="Ver Sinopsis">
                <input type="submit" name="show_sinop1" value="Ver Sinopsis Intermedio">
        </form>
      </div></td>
  </tr>
</table>
</td></tr>

<?
//}
?>
</table>
