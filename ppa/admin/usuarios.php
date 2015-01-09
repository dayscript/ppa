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
$manager = new AuthManager();

if( isset( $_GET['eliminarUsuario'] ) ){
	if( !isset( $_GET['confirm'] ) ){
?>
<script language="javascript1.2">
if( confirm( "Realmente desea eliminar este cliente?" ) ){
	document.location = 'admin.php?eliminarUsuario=1&delUser=<?=$_GET['delUser'] ?>&confirm=1';
}
</script>
<?
	}else{
     $appUser = new ApplicationUsers( $_GET['delUser'] );
     if( $manager->delAuth( $appUser->getIdUser(), NULL ) ){
           $appUser->erase();
     	}else{
           alertHtml( $manager->getLastError() );
     	}
	}
}	
$users = $manager->getAllUsers();
?>
<table  border="1"  cellpadding="6" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#CCCCCC" >
  <tr>
    <td bgcolor="#666666" class="subtitulos"><div align="center">ADMINISTRADOR DE USUARIOS</div></td>
  </tr> 
  <tr>
        <td align="center">
                <table width="403" border="1" cellpadding="6" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
                  <tr bgcolor="#666666">
                    <td colspan="2" class="subtitulos" align="center">Usuarios aplicacion</td>
                  </tr>
                                        <?
                                                for( $i = 0; $i < count( $users['appUser'] ); $i++ ){
                                        ?>
                                                <tr align="left">
                                                        <td width="301" class="textos1" onClick="document.location='admin.php?agregarUsuario=1&id_user=<?= $users['appUser'][$i]['id_user'] ?>'" onMouseOver="changeTdBgColor( this, '#FFFFCC' )" onMouseOut="changeTdBgColor( this, '' )"><font color="#000000"><?= $users['appUser'][$i]['full_name'] ?></font></td>
                                                        <td width="40"><input class="textos1" type="button" onClick="document.location='admin.php?eliminarUsuario=1&delUser=<?= $users['appUser'][$i]['id_user'] ?>'" value="eliminar"></td>
                                                </tr>
                                        <?
                                        }
                                        ?>
                  <tr>
                    <td colspan="2" class="textos1" align="center">
                     <input type="button" name="agregarU" value="Agregar Usuario" onClick="document.location='admin.php?agregarUsuario=1'">
                     </td>
                  </tr>
           </table>
        </td>
  </tr>
</table>
</body>
</html>
