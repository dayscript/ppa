<?
        $appUser = new ApplicationUsers();
        $aManager = new AuthManager();
        $gManager = new GroupManager();

        if( isset( $_POST['agregarUsuario'] ) ){
                $appUser = new ApplicationUsers();
                $appUser->setFirstName( $_POST['firstName'] );
                $appUser->setLastName( $_POST['lastName'] );
                $appUser->setPhone( ( $_POST['phone'] != "" )? $_POST['phone'] : NULL );
                $appUser->setEmail( ( $_POST['email'] != "" )? $_POST['email'] : NULL );
                $appUser->setMovilPhone( ( $_POST['movilPhone'] != "" )? $_POST['movilPhone'] : NULL );
                $appUser->setAddress( ( $_POST['address'] != "" )? $_POST['address'] : NULL  );
                $appUser->setLogin( $_POST['user'] );
                $appUser->commit();

                if( $id_auth = $aManager->addAuth( $appUser->getIdUser(), $_POST['user'], $_POST['passwd'] ) ){
                        if( $_POST['id_group'] != 0 )
                                $aManager->addGroupAuth( $id_auth, $_POST['id_group'] );
                }else{
                        alertHtml( $aManager->getLastError() );
                }
        }

        if( isset( $_POST['actualizarUsuario'] ) ){
                $appUser = new ApplicationUsers( $_POST['id_user'] );
                $appUser->setFirstName( $_POST['firstName'] );
                $appUser->setLastName( $_POST['lastName'] );
                $appUser->setPhone( ( $_POST['phone'] != "" )? $_POST['phone'] : NULL );
                $appUser->setEmail( ( $_POST['email'] != "" )? $_POST['email'] : NULL );
                $appUser->setMovilPhone( ( $_POST['movilPhone'] != "" )? $_POST['movilPhone'] : NULL );
                $appUser->setAddress( ( $_POST['address'] != "" )? $_POST['address'] : NULL  );
                $appUser->commit();
					                 $idAuth = $aManager->getAuthFromUserId( $appUser->getIdUser() );
                if( $idAuth && $_POST['id_group'] != 0 )
                                $aManager->addGroupAuth( $idAuth, $_POST['id_group'] );

                if( $_POST['user'] != "" && $_POST['passwd'] != "" ){
                        if( !$aManager->updateAuth( $idAuth, $_POST['user'], $_POST['passwd'] ) ){
                                alertHtml( $aManager->getLastError() );
                        }
                }
        }

        if( isset( $_GET['id_user'] ) ){
                $appUser->load( $_GET['id_user'] );
        }

        $groups = $gManager->getAllGroups();
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
<form action="admin.php" method="post" name="addUser" id="addUser"  >
<table border="1" align="center" cellpadding="6" cellspacing="0" bordercolor="#CCCCCC">
  <tr>
    <td bgcolor="#666666" class="subtitulos"><div align="center"><?= ( !$appUser->getIdUser() ? "AGREGAR" : "EDITAR" ) ?> USUARIO</div></td>
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
                    <td width="120" colspan="3" class="style5"><div align="left"><span class="subtitulos">USUARIO</span></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="textos1"><span class="textos2"><font color="#000000">NOMBRE</font></span><br>     
                       <input name="firstName" value="<?= $appUser->getFirstName() ?>" id="firstName" class="textos1" type="text" size="30">
                     </td>
                  </tr>
                   <tr>
                    <td colspan="3" class="textos1"><span class="textos2"><font color="#000000">APELLIDO</font></span><br>     
                       <input name="lastName" value="<?= $appUser->getLastName() ?>" id="lastName" class="textos1" type="text" size="30">
                     </td>
                  </tr>
				  <tr>
                    <td colspan="3" class="textos1"><span class="textos2"><font color="#000000">TELEFONO</font></span><br>     
                       <input name="phone" value="<?= $appUser->getPhone() ?>" class="textos1"  type="text" size="30">
                     </td>
                  </tr>
				  <tr>
                    <td colspan="3" class="textos1"><span class="textos2"><font color="#000000">TELEFONO MOVIL</font></span><br>     
                       <input name="movilPhone" value="<?= $appUser->getMovilPhone() ?>" class="textos1" type="text" size="30">
                     </td>
                  </tr>
				  <tr>
                    <td colspan="3" class="textos1"><span class="textos2"><font color="#000000">CORREO ELECTRONICO</font></span><br>     
                       <input name="email" value="<?= $appUser->getEmail() ?>" class="textos1" type="text" size="30">
                     </td>
                  </tr>
				  <tr>
                    <td colspan="3" class="textos1"><span class="textos2"><font color="#000000">DIRRECCION</font></span><br>     
                       <input name="address" value="<?= $appUser->getAddress() ?>" class="textos1" type="text" size="30">
                     </td>
                  </tr>
				  <tr>
                    <td colspan="3" class="textos1"><span class="textos2"><font color="#000000">GRUPO AL QUE PERTENECE</font></span><br>     
					<select name="id_group" id="id_group" class="textos1" style="width: 200px;" >
						<option value="0">Seleccion un grupo</option>
					<? for( $i = 0; $i < count( $groups ); $i++ ){ ?>
                       <option value="<?= $groups[$i]['id_group'] ?>" <?= ( $groups[$i]['id_group'] == $appUser->getGroup() ? "SELECTED" : "" ) ?> ><?= $groups[$i]['name'] ?></option>
					<? } ?>
					</select>
                     </td>
                  </tr>
				  <tr>
				  	<td align="center">
						<table width="50%" cellpadding="0" cellspacing="0" border="0">
							<tr>	
                    			<td colspan="3" class="textos1"><span class="textos2"><font color="#000000">USUARIO</font></span><br>     
                       			<input name="user" id="user" value="<?= $appUser->getLogin() ?>" class="textos1" type="text" size="30">
                     			</td>
                  			</tr>
				  			<tr>
                    			<td colspan="3" class="textos1"><span class="textos2"><font color="#000000">CONTRASEÑA</font></span><br>     
                       			<input name="passwd" id="passwd" class="textos1" type="password" size="30">
                     		</td>
                  			</tr>
				  			<tr>
                    			<td colspan="3" class="textos1"><span class="textos2"><font color="#000000">REPETIR CONTRASEÑA</font></span><br>     
                       			<input name="passwd1" id="passwd1" class="textos1" type="password" size="30">
                     			</td>
                  			</tr>
					  </table>
					</td>
				</tr>
				  <tr>
                    <td colspan="3" class="textos1" align="center">
                       <input name="Aceptar" class="textos1" value="Aceptar" onClick="<?= ( !$appUser->getIdUser() ? "validateAddUser();" : "validateUpdateUser();" ) ?>" type="button" size="30">
					   <input name="Cancelar" class="textos1" value="Cancelar" onClick="document.location='admin.php?usuarios=1'" type="button" size="30">
					</td>
                  </tr>
				  
                </table>
                </div></td>
            </tr>
          </table></td></tr>
</table>
<input type="hidden" name="id_user" value="<?= $appUser->getIdUser() ?>" >
<input type="hidden" name="<?= ( !$appUser->getIdUser() ? "agregarUsuario" : "actualizarUsuario" ) ?>" >
</form>
</body>
</html>
