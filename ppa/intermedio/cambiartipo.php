<?
require_once("../include/db.inc.php");
require_once( '../class/Application.class.php' );
session_start();
$tipos = $_SESSION['app']->getTipos();
if(isset($_GET['id']))$id = $_GET['id'];
else if(isset($_POST['id']))$id = $_POST['id'];

$pag = new Pagina($id);
$t = $pag->tipo->getId();
if(isset($_POST['tipo'])){
	if($_POST['tipo'] == 0)
		$pag->setTipo(new Tipo());
	else 
		$pag->setTipo(new Tipo($_POST['tipo']));
	$pag->commit();
//	$pag->_print();
	$t = $pag->tipo->getId();
	?>
	<script language="JavaScript">
		window.opener.location.reload();
		window.close();
	</script>
	<?
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Cambiar Tipo de P&aacute;gina</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action="">
  <table width="100%" border="0" cellspacing="1" cellpadding="1">
    <tr> 
      <td><img src="images/cabezote_cambiartipo.gif" width="400" height="38"></td>
    </tr>
    <tr> 
      <td><p>Estos son los tipos de p&aacute;gina disponibles, escoge un de ellos 
          para asignarlo a la p&aacute;gina escogida. </p>
        <p><strong>Advertencia</strong>: Cualquier informaci&oacute;n creada en 
          el tipo anterior, ser&aacute; eliminada y no habr&aacute; forma de recuperarla.<br>
        </p></td>
    </tr>
    <tr> 
      <td><div align="center">Tipos : 
          <select name="tipo">
		  	<option value="0">--Sin Asignar--</option>
            <? for($i=0; $i<count($tipos);$i++){
				?>
            <option value="<?=$tipos[$i]->getId()?>" <?=($tipos[$i]->getId()==$t?"selected":"")?>> 
            <?=ucwords($tipos[$i]->getNombre())?>
            </option>
            <?
			}
			?>
          </select>
          <br>
          <br>
        </div></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC"><div align="center">
	  		<input type="hidden" name="id" value="<?=$id?>">
          <input name="asignar" type="image" id="asignar" src="images/boton_asignar.gif" border="0">
        </div></td>
    </tr>
  </table>
</form>
</body>
</html>