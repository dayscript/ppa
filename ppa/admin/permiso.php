<?
$manager = new PrivilegeManager();

if( isset( $_GET['agregarMenupermiso'] ) ){
	$manager->addMenuToPrivelege( $_GET['agregarMenupermiso'], $_GET['id_group'] );
}

if( isset( $_GET['quitarMenupermiso'] ) ){
    $manager->removeMenuToPrivelege( $_GET['quitarMenupermiso'], $_GET['id_group'] );
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
<table border="1" align="center" cellpadding="6" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#CCCCCC" >
  <tr>
    <td bgcolor="#666666" class="subtitulos"><div align="center">EDITAR PERMISOS</div></div></td>
  </tr>
  <tr>
  	<td align="center">
		<table width="403" border="1" cellpadding="6" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
                  <tr bgcolor="#666666">
                    <td colspan="1" class="subtitulos" align="center">Menus disponibles</td>
                  </tr>
					
						<tr align="left">
							<td width="301" class="textos1" >
								<iframe name="arbol" frameborder="0" width="100%" height="150" marginheight="0" marginwidth="0" src="menu/framePermisos.php?id_group=<?= $_GET['id_group'] ?>"></iframe>
							</td>
						</tr>
					<tr>
						<td height="5"></td>
					</tr>
					<tr bgcolor="#666666">
                    <td colspan="1" class="subtitulos" align="center">Menus permitidos</td>
                  </tr>
					
						<tr align="left">
							<td width="301" class="textos1" >
								<iframe name="arbol" frameborder="0" width="100%" height="150" marginheight="0" marginwidth="0" src="menu/frameMenuAdmitido.php?id_group=<?= $_GET['id_group'] ?>"></iframe>
							</td>
						</tr>
					<tr>
						<td align="center"><input type="button" class="textos1" name="Atras" value="Atras" onClick="realizarFuncion( 'adminPermisos' )"></td>
					</tr>
           </table>
	 </td>
  </tr>
</table>
</body>
</html>
