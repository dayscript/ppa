<?
$manager = new MenuManager();
$option = new Options();

if( isset( $_GET['id_option'] ) ){
   $option->load( $_GET['id_option'] );
}

if( isset( $_POST['agregarMenu'] ) ){
    $option = $manager->createMenu( $_POST );
}

if( isset( $_POST['actualizarMenu'] ) ){
    $option = $manager->updateMenu( $_POST );
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Administrador</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.borrar{
 color: #009900;
}
body,table {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #FFFFFF;
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
</head>
<body>
<form action="admin.php" method="post" name="menuForm" id="menuForm"  >
<table border="1" align="center" cellpadding="6" cellspacing="0" bordercolor="#CCCCCC">
  <tr>
    <td bgcolor="#666666" class="subtitulos"><div align="center"><?= ( !$option->getIdOption() ? "AGREGAR" : "EDITAR" ) ?> MENU</div></td>
  </tr>
  <tr valign="top" bgcolor="#CCCCCC">
    <td>
          <table width="2" border="1" cellpadding="4" cellspacing="0" bordercolor="#CCCCCC">
      </table>
          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="63%" valign="top">
                <table width="403" border="0" cellpadding="6" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#FFFFFF" align="center">
                  <tr bgcolor="#666666">
                    <td width="120" colspan="3" class="style5"><div align="left"><span class="subtitulos">MENU</span></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="textos1"><span class="textos2"><font color="#000000">NOMBRE</font></span><br>     
                       <input name="description" value="<?= $option->getDescription() ?>" id="description" class="textos1" type="text" size="30">
                     </td>
                  </tr>
                   <tr>
                    <td colspan="3" class="textos1"><span class="textos2"><font color="#000000">FUNCION</font></span><br>     
                       <input name="_function" value="<?= $option->getFunction() ?>" id="_function" class="textos1" type="text" size="30">
                     </td>
                  </tr>
				   <tr>
                    <td colspan="3" class="textos1"><span class="textos2"><font color="#000000">POSICION</font></span><br>     
                       <input name="position" value="<?= $option->getPosition() ?>" id="position" class="textos1" type="text" size="30">
                     </td>
                  </tr>
				   <tr>
                    <td colspan="3" class="textos1" align="center">
                       <input name="Aceptar" class="textos1" value="Aceptar" onClick="validateMenu();" type="button" size="30">
					   <input name="Cancelar" class="textos1" value="Cancelar" onClick="document.location='admin.php?menus=1'" type="button" size="30">
					</td>
                  </tr> 
                </table>
                </div></td>
            </tr>
          </table></td></tr>
</table>
<input type="hidden" name="id_parent" value="<?= ( isset( $_GET['id_parent'] )? $_GET['id_parent'] : "" ) ?>" >
<input type="hidden" name="id_option" value="<?= $option->getIdOption() ?>" >
<input type="hidden" name="<?= ( !$option->getIdOption() ? "agregarMenu" : "actualizarMenu" ) ?>">
</form>
</body>
</html>
