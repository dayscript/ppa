<?
$manager = new GroupManager();
$group = new ApplicationGroups();

if( isset( $_POST['agregarGrupo'] ) ){
    $group = $manager->addGroup( $_POST );
    if( !$group ){
       alertHtml( $manager->getLastError() );
       $group = new ApplicationGroups();
     }
}

if( isset( $_POST['actualizarGrupo'] ) ){
	$group = $manager->updateGroup( $_POST );
   if( !$group ){
   	alertHtml( $manager->getLastError() );
      $group = new ApplicationGroups();
   }
}

if( isset( $_GET['id_group'] ) ){
	$group->load( $_GET['id_group'] );
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
<form action="admin.php" method="post" name="addGroup" id="addGroup"  >
<table border="1" align="center" cellpadding="6" cellspacing="0" bordercolor="#CCCCCC">
  <tr>
    <td bgcolor="#666666" class="subtitulos"><div align="center"><?= ( !$group->getIdGroup() ? "AGREGAR" : "EDITAR" ) ?> GRUPO</div></td>
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
                    <td width="120" colspan="3" class="style5"><div align="left"><span class="subtitulos">GRUPO</span></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="textos1"><span class="textos2"><font color="#000000">NOMBRE</font></span><br>     
                       <input name="name" value="<?= $group->getName() ?>" id="name" class="textos1" type="text" size="30">
                     </td>
                  </tr>
                   <tr>
                    <td colspan="3" class="textos1"><span class="textos2"><font color="#000000">DESCRIPCION<font color="#000000"></span><br>     
                       <div align="center"><textarea name="description" cols="50" rows="7" class="textos1" ><?= $group->getDescription() ?></textarea></div>
                     </td>
                  </tr>
				   <tr>
                    <td colspan="3" class="textos1" align="center">
                       <input name="Aceptar" class="textos1" value="Aceptar" onClick="validateAddGroup();" type="button" size="30">
					   <input name="Cancelar" class="textos1" value="Cancelar" onClick="document.location='admin.php?grupos=1'" type="button" size="30">
					 </td>
                  </tr> 
                </table>
                </div></td>
            </tr>
          </table></td></tr>
</table>
<input type="hidden" name="id_group" value="<?= $group->getIdGroup() ?>" >
<input type="hidden" name="<?= ( !$group->getIdGroup() ? "agregarGrupo" : "actualizarGrupo" ) ?>">
</form>
</body>
</html>
