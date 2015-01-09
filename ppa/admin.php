<?
error_reporting(E_ERROR);
require("header.php");
require_once( "ppa/include/config.inc.php" );
require_once("menu/classes/admin/AuthManager.class.php");
require_once("menu/classes/admin/GroupManager.class.php");
require_once("menu/classes/admin/MenuManager.class.php");
require_once("menu/classes/admin/PrivilegeManager.class.php");
require_once("menu/classes/ApplicationGroup.class.php");

if( isset( $_GET['usuarios'] ) || isset( $_GET['eliminarUsuario'] )){
	require("admin/usuarios.php");
}else{
	if( isset( $_GET['agregarUsuario'] ) || isset( $_POST['agregarUsuario'] ) || isset( $_GET['actualizarUsuario'] ) || isset( $_POST['actualizarUsuario'] )){
		require("admin/usuario.php");
	}else{
		if( isset( $_GET['grupos'] ) || isset( $_GET['eliminarGrupo'] ) ){
			require("admin/grupos.php");		
		}else{
			if( isset( $_GET['agregarGrupo'] ) || isset( $_POST['agregarGrupo'] ) || isset( $_GET['actualizarGrupo'] ) || isset( $_POST['actualizarGrupo'] )){
				require("admin/grupo.php");
			}else{
				if( isset( $_GET['menus'] ) || isset( $_GET['eliminarMenu'] ) ){
					require("admin/menus.php");		
				}else{
					if( isset( $_GET['agregarMenu'] ) || isset( $_POST['agregarMenu'] ) || isset( $_POST['actualizarMenu'] ) ){
						require("admin/menu.php");
					}else{
						if( isset( $_GET['permisos'] ) ){
							require("admin/permisos.php");
						}else{
							if( isset( $_GET['agregarPermiso'] ) ){
								require("admin/permiso.php");
							}
						}
					}
				}
			}		
		}
	}
}
require("footer.php");
?>