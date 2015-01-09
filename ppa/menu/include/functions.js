// Functions.js

function popup ( page , name, width, height, features, posx, posy )	{
	if (posx == "center")
		posx = (screen.width - width) / 2;
	else if (posx == "right")
		posx = (screen.width - width - 30);
	else if (posx < 0)
		posx = screen.width - width + posx;
	else 
		posx = 20;

	if (posy == "middle")
		posy = (screen.height - height) / 2;
	else if (posy == "bottom")
		posy = (screen.height - height - 60);
	else if (posy < 0)
		posx = screen.height - height - posy - 30;
	else 
		posy = 20;

	if (features != '' && features[0] != ',')
		features = ',' + features;

	newWindow = open ( page, name, 'width=' + width + ',height=' + height + ', screenX=' + posx + ',left=' + posx + ',screenY=' + posy + ',top=' + posy + features );
}

/* END Pop-up Windows Script */

/**********************************/
/* BEGIN General Functions Script */
/**********************************/

function delNew( id_new ){
	if( confirm( "Desea eliminar esta noticia" ) ){
		realizarFuncion( "verNoticias&delNew=" + id_new );
	}
}

function delPack( id_client, id_pack ) {
	if( confirm( "Relmente desea eliminar este combo?" ) ){
		realizarFuncion( "generalCliente&subfunction=catalog&id_client=" + id_client + "&delPack=" + id_pack );
	}
}

function delClient( id_client ){
	if( confirm( "Realmente desea eliminar este cliente?" ) ){
		realizarFuncion( "adminClientes&delClient=" + id_client );
	}
}

function delUser( id_user ){
		if( confirm( "Realmente desea eliminar este usuario?" ) ){
			realizarFuncion( "adminUsuarios&delUser=" + id_user );
		}
}

function delGroup( id_group ){
		if( confirm( "Realmente desea eliminar este grupo?" ) ){
				realizarFuncion( "adminGrupos&delGroup=" + id_group );
		}
}

function delClientGroup( id_group ){
	if( confirm( "Realmente desea eliminar este grupo?" ) ){
		realizarFuncion( "edicionManual&subfunction=grupos&delGroup=" + id_group );
	}
}

function delTypeEmployee(){
	if( idTreeSelected != 0 ){
		if( confirm( "Desea eliminar este tipo de empleado?" ) )
			window.top.document.location = URLBaseLive + "index.php?function=edicionManual&subfunction=tipoEmpleado&delType=" + idTreeSelected;
	}else
		alert( "No es un tipo de empleado valido" );
}

function enviar( nombreFormulario ){
		document[nombreFormulario].submit();
}

function changeUser( selec ){
		document.location = "?changeClient=" + selec.options[ selec.selectedIndex ].value;
}

function delMission( id_mission, id_client ){
	if( confirm( "Ralmente desea eliminar esta mision?" ) ){
		realizarFuncion( "generalCliente&subfunction=adminMision&delMission=" + id_mission + "&id_client=" + id_client );
	}
}

function delCategory( id_category ){
	if( confirm( "Realmente desea eliminar esta categoria?" ) ){
		realizarFuncion( "catalogoGeneral&delCategory=" + id_category );
	}
}

function delPais( id_country ){
	if( confirm( "Desea eliminar este pais?" ) ){
		realizarFuncion( 'adminPaises&delCountry=' + id_country );
	}
}

function delEstadoCivil( id_state ){
	if( confirm( "Desea eliminar esta estado civil?" ) ){
		realizarFuncion( 'adminEstadoCivil&delCivilState=' + id_state );
	}
}

function delAgencia( id_agency ){
		if( confirm( "Desea eliminar esta agencia?" ) ){
				realizarFuncion( "edicionManual&subfunction=agencias&delAgency=" + id_agency );
		}
}

function delCiudad( id_city ){
	if( confirm( "Desea eliminar esta ciudad?" ) ){
		realizarFuncion( 'adminCiudades&delCity=' + id_city );
	}
}

function delEmployee( id_employee ){
	if( confirm( "Desea eliminar este empleado?" ) )
		realizarFuncion( 'edicionManual&subfunction=empleado&delEmployee=' + id_employee );
}

function validatePackForm(){
	if( document.getElementById('name').value != "" ){
		if( document.getElementById('points').value != "" ){
			if( document.getElementById('idType').selectedIndex != 0 ){
				document.getElementById('packForm').submit();
			}else
				alert( "Se debe seleccionar un tipo de empleado" );
		}else{
			alert( "Los puntos son obligatorios" );
		}
	}else{
		alert( "El nombre es obligatorio" );
	}
}

function delCanalVenta( id_channel ){
	if( confirm( "Desea eliminar este canal?" ) )
		realizarFuncion( "edicionManual&subfunction=canalesVentas&delCanalVenta=" + id_channel );
}

function mostrar( tr ){
	alert( tr.style.display );
}

function agregarProducto(){
	if( document.getElementById( 'id_producto' ).selectedIndex != -1 ){
		productValue = document.getElementById( 'id_producto' ).options[ document.getElementById( 'id_producto' ).selectedIndex ].value;
		productText = document.getElementById( 'id_producto' ).options[ document.getElementById( 'id_producto' ).selectedIndex ].text;
		
		childs = document.getElementById( 'products' ).childNodes;
		for( i = 0; i < childs.length; i++ ){
			if( childs.item( i ).value == productValue ){
				alert( "El producto ya se encuentra en la lista" );
				return;
			}
		}
		
		option = document.createElement( "option" );
		option.setAttribute( "value", productValue );
			option.appendChild( document.createTextNode( productText ) );
		
		if( document.getElementById( 'products' ).firstChild.value == 0 )
			document.getElementById('products').removeChild( document.getElementById( 'products' ).firstChild );
			
		document.getElementById( 'products' ).appendChild( option );
	}else{
		alert( "Debe seleccionar un producto!" );
	}
}

function agregarTipoEmpleado(){
	if( document.getElementById( 'id_type' ).selectedIndex != -1 ){
		typeValue = document.getElementById( 'id_type' ).options[ document.getElementById( 'id_type' ).selectedIndex ].value;
		typeText = document.getElementById( 'id_type' ).options[ document.getElementById( 'id_type' ).selectedIndex ].text;
		
		childs = document.getElementById( 'employeeType' ).childNodes;
		for( i = 0; i < childs.length; i++ ){
			if( childs.item( i ).value == typeValue ){
				alert( "El tipo de empleado ya se encuentra en la lista" );
				return;
			}
		}
		
		option = document.createElement( "option" );
		option.setAttribute( "value", typeValue );
			option.appendChild( document.createTextNode( typeText ) );
		
		if( document.getElementById( 'employeeType' ).firstChild.value == 0 )
			document.getElementById('employeeType').removeChild( document.getElementById( 'employeeType' ).firstChild );
			
		document.getElementById( 'employeeType' ).appendChild( option );
	}else{	
		alert( "Seleccione un tipo de empleado" );
	}
}

function agregarAgencia(){
	if( document.getElementById( 'id_agency' ).selectedIndex != -1 ){
		agencyValue = document.getElementById( 'id_agency' ).options[ document.getElementById( 'id_agency' ).selectedIndex ].value;
		agencyText = document.getElementById( 'id_agency' ).options[ document.getElementById( 'id_agency' ).selectedIndex ].text;
		
		childs = document.getElementById( 'agency' ).childNodes;
		for( i = 0; i < childs.length; i++ ){
			if( childs.item( i ).value == agencyValue ){
				alert( "La agencia ya se encuentra en la lista" );
				return;
			}
		}
		
		option = document.createElement( "option" );
		option.setAttribute( "value", agencyValue );
			option.appendChild( document.createTextNode( agencyText ) );
		
		if( document.getElementById( 'agency' ).firstChild.value == 0 )
			document.getElementById('agency').removeChild( document.getElementById( 'agency' ).firstChild );
			
		document.getElementById( 'agency' ).appendChild( option );
	}else{
		alert("Debe seleccionar una agencia!");
	}
}

function agregarCanal(){
	if( document.getElementById( 'id_channel' ).selectedIndex != -1 ){
		canalValue = document.getElementById( 'id_channel' ).options[ document.getElementById( 'id_channel' ).selectedIndex ].value;
		canalText = document.getElementById( 'id_channel' ).options[ document.getElementById( 'id_channel' ).selectedIndex ].text;
		
		childs = document.getElementById( 'channel' ).childNodes;
		for( i = 0; i < childs.length; i++ ){
			if( childs.item( i ).value == canalValue ){
				alert( "El canal ya se encuentra en la lista" );
				return;
			}
		}
		
		option = document.createElement( "option" );
		option.setAttribute( "value", canalValue );
			option.appendChild( document.createTextNode( canalText ) );
			
		if( document.getElementById( 'channel' ).firstChild.value == 0 )
			document.getElementById('channel').removeChild( document.getElementById( 'channel' ).firstChild );
			
		document.getElementById( 'channel' ).appendChild( option );
	}else{
		alert("Debe seleccionar un canal!");
	}
	//
}

function agregarGrupo(){
	if( document.getElementById( 'id_group' ).selectedIndex != -1 ){
		groupValue = document.getElementById( 'id_group' ).options[ document.getElementById( 'id_group' ).selectedIndex ].value;
		groupText = document.getElementById( 'id_group' ).options[ document.getElementById( 'id_group' ).selectedIndex ].text;
		
		childs = document.getElementById( 'group' ).childNodes;
		for( i = 0; i < childs.length; i++ ){
			if( childs.item( i ).value == groupValue ){
				alert( "El grupo ya se encuentra en la lista" );
				return;
			}
		}
		
		option = document.createElement( "option" );
		option.setAttribute( "value", groupValue );
			option.appendChild( document.createTextNode( groupText ) ); 
			
		if( document.getElementById( 'group' ).firstChild.value == 0 )
			document.getElementById('group').removeChild( document.getElementById( 'group' ).firstChild );
			
		document.getElementById( 'group' ).appendChild( option );
	}else{
		alert("Debe seleccionar un grupo!");
	}
		//
}

function eliminarPatron(){
	if( document.getElementById('patterns').selectedIndex != -1 ){
		elementP = document.getElementById('patterns').options[ document.getElementById('patterns').selectedIndex ];
		parentP = elementP.parentNode;
		parentP.removeChild( elementP );
		
		if( parentP.childNodes.length == 0 ){
			option = document.createElement( "option" );
			option.setAttribute( "value", "0" );
			
			switch( parentP.id ){
				case "products":
					option.appendChild( document.createTextNode( "todos los productos" ) );
					break;
				case "employeeType":
					option.appendChild( document.createTextNode( "todos los tipos" ) );
					break;
				case "agency":
					option.appendChild( document.createTextNode( "todas las agencias" ) );
					break;
				case "channel":
					option.appendChild( document.createTextNode( "todos los canales" ) );
					break;
				case "group":
					option.appendChild( document.createTextNode( "todos los grupos" ) );
					break;
			}
			
			parentP.appendChild( option );
		}
	}else{
		alert( "Debe seleccionar un elemento" );
	}
}

function verReporte(){
	
	year = document.getElementById('ano').options[ document.getElementById('ano').selectedIndex ].value;
	month = document.getElementById('mes').options[ document.getElementById('mes').selectedIndex ].value;
	
	inputT = document.createElement( "INPUT" );
	inputT.setAttribute( "type", "hidden" );
	inputT.setAttribute( "name", "date" );
	inputT.setAttribute( "value", year + "-" + month );
	document.getElementById('filtroForm').appendChild( inputT );
	
	productosT = "";
	childs = document.getElementById('products').childNodes;
	for( i = 0; i < childs.length; i++ ){
		productosT += childs.item(i).value + ( ( i != childs.length - 1 )? "," : "" );
	}
	inputT = document.createElement( "INPUT" );
	inputT.setAttribute( "type", "hidden" );
	inputT.setAttribute( "name", "products" );
	inputT.setAttribute( "value", productosT );
	document.getElementById('filtroForm').appendChild( inputT );
	
	employeeTypeT = "";
	childs = document.getElementById('employeeType').childNodes;
	for( i = 0; i < childs.length; i++ ){
		employeeTypeT += childs.item(i).value + ( ( i != childs.length - 1 )? "," : "" );
	}
	inputT = document.createElement( "INPUT" );
	inputT.setAttribute( "type", "hidden" );
	inputT.setAttribute( "name", "employeeType" );
	inputT.setAttribute( "value", employeeTypeT );
	document.getElementById('filtroForm').appendChild( inputT );
	
	agencyT = "";
	childs = document.getElementById('agency').childNodes;
	for( i = 0; i < childs.length; i++ ){
		agencyT += childs.item(i).value + ( ( i != childs.length - 1 )? "," : "" );
	}
	inputT = document.createElement( "INPUT" );
	inputT.setAttribute( "type", "hidden" );
	inputT.setAttribute( "name", "agency" );
	inputT.setAttribute( "value", agencyT );
	document.getElementById('filtroForm').appendChild( inputT );
	
	channelT = "";
	childs = document.getElementById('channel').childNodes;
	for( i = 0; i < childs.length; i++ ){
		channelT += childs.item(i).value + ( ( i != childs.length - 1 )? "," : "" );
	}
	inputT = document.createElement( "INPUT" );
	inputT.setAttribute( "type", "hidden" );
	inputT.setAttribute( "name", "channel" );
	inputT.setAttribute( "value", channelT );
	document.getElementById('filtroForm').appendChild( inputT );
	
	groupT = "";
	childs = document.getElementById('group').childNodes;
	for( i = 0; i < childs.length; i++ ){
		groupT += childs.item(i).value + ( ( i != childs.length - 1 )? "," : "" );
	}
	inputT = document.createElement( "INPUT" );
	inputT.setAttribute( "type", "hidden" );
	inputT.setAttribute( "name", "group" );
	inputT.setAttribute( "value", groupT );
	document.getElementById('filtroForm').appendChild( inputT );
	
	window.open( "", "reporteDetallado", "width=600, height=600, scrollbars=yes, resizable" );
	document.getElementById('filtroForm').submit();
}

function openSalesRankingAgency( id_agencia ){
	window.open( 'dayTemplates/reportes/basicos/detalleVentaAgencia.php?id_agency=' + id_agencia, '', 'width=500, height=400, scrollbars=yes, resizable'  );
}
		
		