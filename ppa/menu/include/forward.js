// JavaScript Document

function showClient( id_client ){
	realizarFuncion( "generalCliente&id_client=" + id_client );
}

function showUser( id_user ){
		realizarFuncion( "agregarUsuario&id_user=" + id_user );
}

function showGroup( id_group ){
		realizarFuncion( "agregarGrupo&id_group=" + id_group );
}

function showEmployee( id_employee ){
	realizarFuncion( 'edicionManual&subfunction=empleado&level=agregarEmpleado&id_employee=' + id_employee );
}

function realizarFuncion( funcion ){
		document.location = "?function=" + funcion;
}

function showGroupPrivilegie( id_group ){
	realizarFuncion( "agregarPermiso&id_group=" + id_group );		
}