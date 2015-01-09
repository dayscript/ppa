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
<?
$manager = new MenuManager();

if( isset( $_GET['eliminarMenu'] ) ){
	if( !isset( $_GET['confirm'] ) ){
?>
<script language="javascript1.2">
if( confirm( "Realmente desea eliminar este menu?" ) ){
	document.location = 'admin.php?eliminarMenu=1&delMenu=<?=$_GET['delMenu'] ?>&confirm=1';
}
</script>
<?
	}else{
		if( isset( $_GET['delMenu'] ) ){
			$manager->deleteMenu( $_GET['delMenu'] );
		}
	}
}	


?>
<table border="1" align="center" cellpadding="6" cellspacing="0" bordercolor="#CCCCCC">
  <tr>
    <td bgcolor="#666666" class="subtitulos"><div align="center">MENU</div></td>
  </tr>
  <tr valign="top" bgcolor="#CCCCCC">
    <td>
          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="63%" valign="top">
                <table width="403" border="0" cellpadding="6" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#FFFFFF" align="center">
                  <tr bgcolor="#666666">
                    <td width="120" class="style5"><div align="left"><span class="subtitulos">ARBOL</span></div></td>
                  </tr>
				  <tr>
				  	<td height="400" width="100%">
						<iframe name="arbol" frameborder="0" width="100%" height="100%" marginheight="0" marginwidth="0" src="menu/menuFrame.php"></iframe>
					</td>
				  </tr>
				 </table>
				</td>
			 </tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
