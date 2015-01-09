<?
$manager = new GroupManager();
$groups = $manager->getAllGroups();
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
    <td bgcolor="#666666" class="subtitulos"><div align="center">ADMINISTRADOR DE PERMISOS</div></td>
  </tr>
  <tr>
  	<td align="center">
		<table width="403" border="1" cellpadding="6" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
                  <tr bgcolor="#666666">
                    <td colspan="1" class="subtitulos" align="center">Grupos</td>
                  </tr>
					<?
						for( $i = 0; $i < count( $groups ); $i++ ){
					?>
						<tr align="left">
							<td width="301" class="textos1" onClick="document.location='admin.php?agregarPermiso=1&id_group=<?= $groups[$i]['id_group'] ?>'" onMouseOver="changeTdBgColor( this, '#FFFFCC' )" onMouseOut="changeTdBgColor( this, '' )"><font color="#000000"><?= $groups[$i]['name'] ?></font></td>
						</tr>
					<?
					}
					?>
           </table>
	</td>
  </tr>
</table>
</body>
</html>
