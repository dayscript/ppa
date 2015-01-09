// JavaScript Document
function validateAddClient(){
	if( document.getElementById( 'name' ).value != "" ){
		document.getElementById( 'clienteForm' ).submit();
	}else
		alert( "El cliente debe tener un nombre!" );
}

function validateCategory(){
	if( document.getElementById('name').value != "" ){
		document.getElementById('categoriaForm').submit();
	}else
		alert( "El nombre de la categoria es obligatorio" );
}

function validateProductForm(){
	if( document.getElementById('name').value != "" ){
		document.getElementById('productForm').submit();
	}else{
		alert( "El producto debe tener un nombre" );
	}
}

function validateMissionForm(){
	if( document.getElementById('name').value != "" ){
		document.getElementById('misionForm').submit();
	}else{
		alert( "La mision debe tener un nombre" );
	}
}

function validateTipoEmpleado(){
	if( document.getElementById( 'name' ).value != "" ){
		document.getElementById( 'tipoEmpleadoForm' ).submit();
	}else{
		alert( "Debe ingresar un nombre!" );
		document.getElementById( 'name' ).focus();
	}
}

function validatePais(){
	if( document.getElementById( 'name' ).value != "" ){
		document.getElementById( 'paisForm' ).submit();
	}else{
		alert( "Debe ingresar un nombre para el pais" );
		document.getElementById( 'name' ).focus();
	}
}

function validateEstadoCivil(){
	if( document.getElementById( 'name' ).value != "" ){
		document.getElementById( 'estadoCivilForm' ).submit();
	}else{
		alert( "Debe ingresar el nombre del estado civil" );
		document.getElementById( 'name' ).focus();
	}
}

function validateAgencia(){
		if( document.getElementById('name').value != "" ){
				if( document.getElementById('id_city').selectedIndex != 0 ){
						document.getElementById('agenciaForm').submit();
				}else{
						alert( "Seleccione la ciudad de la agencia" );
				}
		}else{
			alert( "La agencia debe tener un nombre" );
		}
}

function validateCiudad(){
	if( document.getElementById( 'name' ).value != "" ){
		if( document.getElementById( 'id_country' ).selectedIndex != 0 )
			document.getElementById( 'ciudadForm' ).submit();
		else{
			alert( "Debe seleccionar un pais!" );
			document.getElementById( 'ciudadForm' ).focus();
		}
	}else{
		alert( "Debe ingresar un nombre para la ciudad!" );
		document.getElementById( 'name' ).focus();
	}
}

function validateAddGroup(){
	if( document.getElementById( 'name' ).value != "" ){
		document.getElementById( 'addGroup' ).submit();
	}else{
		alert( "El grupo debe tener nombre" );
		document.getElementById( 'name' ).focus();
	}
}

function validateUpdateUser(){
	if( document.getElementById( 'id_group' ).selectedIndex == 0 ){
		alert( "Debe seleccionar un grupo para el usuario" );
		return;
	}
	
	if( document.getElementById( 'firstName' ).value != "" ){
		if( document.getElementById( 'lastName' ).value != "" ){
			if( document.getElementById( 'passwd' ).value == document.getElementById( 'passwd1' ).value ){
				document.getElementById( 'addUser' ).submit();
			}else{
				alert( "Las contraseñas no coinciden" );
				document.getElementById( 'passwd' ).select();
			}
		}else{
			alert( "Debe ingresar un apellido para el usuario" );
			document.getElementById( 'firstName' ).focus();
		}
	}else{
		alert( "Debe ingresar un nombre para el usuario" );
		document.getElementById( 'firstName' ).focus();
	}
}

function validateCanal(){
	if( document.getElementById( 'name' ).value != "" ){
		document.getElementById( 'canalForm' ).submit();
	}else{
		alert( "Debe ingresar un nombre para el canal!" );
		document.getElementById( 'name' ).focus();
	}
}

function validateEmployee(){
	if( document.getElementById( 'employeeCode' ).value == "" ){
		alert( "Debe ingresar un codigo de empleado" );
		return;
	}
	
	if( document.getElementById( 'firstName' ).value != "" ){
				if( document.getElementById( 'lastName' ).value != "" ){
						if( document.getElementById( 'identification' ).value != "" ){
							if( document.getElementById( 'id_agency' ).selectedIndex != 0 ){	
								if( document.getElementById( 'id_type' ).selectedIndex ){
										document.getElementById( 'empleadoForm' ).submit();
								}else{
									alert( "Debe seleccionar un tipo de empleado" );
									document.getElementById( 'id_type' ).focus();
								}
							}else{
								alert( "Debe seleccionar una agencia" );
								document.getElementById( 'id_agency' ).focus();
							}
						}else{
							alert( "Debe ingresar una identificacion" );
							document.getElementById( 'identification' ).focus();
						}
				}else{
					alert( "Debe ingresar un apellido para el usuario" );
					document.getElementById( 'firstName' ).focus();
				}
		}else{
			alert( "Debe ingresar un nombre para el usuario" );
			document.getElementById( 'firstName' ).focus();
		}
}

function validateAddUser(){
	if( document.getElementById( 'id_group' ).selectedIndex == 0 ){
		alert( "Debe seleccionar un grupo para el usuario" );
		return;
	}
		if( document.getElementById( 'firstName' ).value != "" ){
				if( document.getElementById( 'lastName' ).value != "" ){
						if( document.getElementById( 'user' ).value != "" ){
							if( document.getElementById( 'passwd' ).value != "" && document.getElementById( 'passwd1' ).value != "" ){	
								if( document.getElementById( 'passwd' ).value == document.getElementById( 'passwd1' ).value ){
										document.getElementById( 'addUser' ).submit();
								}else{
									alert( "Las contraseñas no coinciden" );
									document.getElementById( 'passwd' ).select();
								}
							}else{
								alert( "Debe ingresar una contraseña y reescribirla" );
								document.getElementById( 'passwd' ).focus();
							}
						}else{
							alert( "Debe ingresar un nombre de usuario" );
							document.getElementById( 'user' ).focus();
						}
				}else{
					alert( "Debe ingresar un apellido para el usuario" );
					document.getElementById( 'firstName' ).focus();
				}
		}else{
			alert( "Debe ingresar un nombre para el usuario" );
			document.getElementById( 'firstName' ).focus();
		}
}

function validateMenu(){
		if( document.getElementById('description').value != "" ){
				if( document.getElementById('_function').value != "" ){
					document.getElementById( 'menuForm' ).submit();
				}else{
					alert( "Debe ingresar la funcion del menu" );
					document.getElementById('function').focus();
				}
		}else{
			alert( "Debe ingresar el nombre del menu" );
			document.getElementById('description').focus();
		}
}

function validateNew(){
	if( document.getElementById('title').value != "" ){
		if( document.getElementById('date').value!= "" ){
			if( document.getElementById('content').value != "" )				
				document.getElementById('noticiaForm').submit();
			else
				alert( "Debe ingresar un contenido" );
		}else
			alert( "Debe ingresar una fecha" );
	}else{
		alert( "Debe ingresar un titulo" );
	}
}