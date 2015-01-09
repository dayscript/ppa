// JavaScript Document

var contextMenuArbolTimeOut = null;
var idTreeSelected = null;

function cambiarModo( imagen ){
	var TD = imagen.parentNode;
	var TR = TD.parentNode;
	var TABLE = TR.parentNode;
	var __TR = TABLE.lastChild;
			
	if( TR != __TR ){
		__TR.style.display = ( __TR.style.display == "none" )?"":"none";
		imagen.src = ( __TR.style.display == "none" )? URLBaseLive + "images/menu_corner_plus.gif" : URLBaseLive + "images/menu_corner_minus.gif";
	}
}
		
function cambiarModo2( carpeta ){
	var TD = carpeta.parentNode;
	var TR = TD.parentNode;
	var IMG = TR.firstChild.firstChild;
	var TABLE = TR.parentNode;
	var __TR = TABLE.lastChild;
			
	if( TR != __TR ){
		__TR.style.display = ( __TR.style.display == "none" )?"":"none";
		IMG.src = ( __TR.style.display == "none" )? URLBaseLive + "images/menu_corner_plus.gif" : URLBaseLive + "images/menu_corner_minus.gif";
	}
}
		
function closeMenu(){
	document.getElementById('menuDerecho').style.visibility = 'hidden';
	document.getElementById( idTreeSelected ).parentNode.parentNode.parentNode.style.background = "";
}
			
function mostrarMenu( evento ){
	if( evento ){
		if( evento.which == 3 || evento.which == 2 ){
			e = new DOM2Event( evento, window.event, this );
			if( e.target.id ){
				document.getElementById('menuDerecho').style.top = e.pageY;
				document.getElementById('menuDerecho').style.left = e.pageX;
				document.getElementById('menuDerecho').style.visibility = 'visible';
				if( idTreeSelected )
					document.getElementById( idTreeSelected ).parentNode.parentNode.parentNode.style.background = "";
							
				idTreeSelected = e.target.id;		
				document.getElementById( idTreeSelected ).parentNode.parentNode.parentNode.style.background = "#EEEEEE";
			}
			return false;
		}
	}else{
  		if(event && (event.button == 2 || event.button == 3)) {
  			e = new DOM2Event( evento, window.event, this );
			if( e.target.id ){
				document.getElementById('menuDerecho').style.top = e.pageY;
				document.getElementById('menuDerecho').style.left = e.pageX;
				document.getElementById('menuDerecho').style.visibility = 'visible';
				if( idTreeSelected )
					document.getElementById( idTreeSelected ).parentNode.parentNode.parentNode.style.background = "";
							
					idTreeSelected = e.target.id;
					document.getElementById( idTreeSelected ).parentNode.parentNode.parentNode.style.background = "#EEEEEE";
			}
    		return false;
  		}
	}
	return true;
}
		
function initTimeOutMenu(){
	if( contextMenuArbolTimeOut )
		clearTimeout( contextMenuArbolTimeOut );
		
	contextMenuArbolTimeOut = setTimeout( 'closeMenu()', 500 );
}
		
function clearTimeOutMenu(){
	if( contextMenuArbolTimeOut )
		clearTimeout( contextMenuArbolTimeOut );
				 
	contextMenuArbolTimeOut = null;
}
		
function vacio(){
	return false;
}
		
function initialize() {
	document.oncontextmenu = vacio;
	document.onmousedown = mostrarMenu;
  	if( document.layers ){
    	window.captureEvents(Event.MOUSEDOWN);
  	}
  	window.onmousedown = mostrarMenu;
}
		
function editMenu(){
	if( idTreeSelected != 0 )
		window.top.document.location = URLBaseLive + "admin.php?agregarMenu&id_option=" + idTreeSelected;
	else
		alert( "Este menu no es editable" );
}
		
function addMenu(){
	window.top.document.location = URLBaseLive + "admin.php?agregarMenu&id_parent=" + idTreeSelected;
}
		
function delMenu(){
	if( idTreeSelected != 0 ){
			window.top.document.location = URLBaseLive + "admin.php?eliminarMenu&delMenu=" + idTreeSelected;
	}else
			alert( "Este menu no se puede eliminar!" );
}
		
function quitarPermitidoMenu( id_grupo ){
	if( idTreeSelected != 0 ){
		hijos = getAllChildsIds( idTreeSelected, document.getElementById( idTreeSelected ) );
		window.top.document.location = URLBaseLive + "admin.php?agregarPermiso&id_group=" + id_grupo + "&quitarMenupermiso=" + hijos;
	}else{
		alert( "No se puede quitar este menu!" );
	}
}
		
function agregarMenu( id_grupo ){
	if( idTreeSelected != 0 ){
		padres = getAllParentsIds( idTreeSelected, document.getElementById( idTreeSelected ) );
		window.top.document.location = URLBaseLive + "admin.php?agregarPermiso&id_group=" + id_grupo + "&agregarMenupermiso=" + padres;
	}else{
		alert( "No se puede agregar la raiz!" );
	}
}
		
function getAllParentsIds( padres, node ){
	padre = node.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.firstChild.lastChild.firstChild.firstChild;
	idPadre = padre.id;
			
	if( idPadre != 0 ){
		padres = getAllParentsIds( padres+","+idPadre, padre );
	}
			
	return padres;
}
		
function getAllChildsIds( hijos, node ){
	hijo = node.parentNode.parentNode.parentNode.parentNode.lastChild.lastChild.firstChild;
	if( hijo.nodeName == "TABLE" ){
		hijo = hijo.firstChild.firstChild.lastChild.firstChild.firstChild;
		hijos = getAllChildsIds( hijos+","+hijo.id, hijo  );
	}
			
	return hijos;
}
		
function addClientGroup(){
	if( idTreeSelected == "0" ){
		window.top.document.location = URLBaseLive + "index.php?function=edicionManual&subfunction=grupos&level=agregarGrupo";
	}else
		alert( "El grupo debe ser agregado a la raiz del arbol" );
}
		
function editClientGroup(){
	idType = idTreeSelected.substring( idTreeSelected.indexOf('_') + 1, idTreeSelected.length );
	if( idType == "grupo" ){
		id_group = idTreeSelected.substring( 0, idTreeSelected.indexOf('_') );
		window.top.document.location = URLBaseLive + "index.php?function=edicionManual&subfunction=grupos&level=agregarGrupo&id_group=" + id_group;
	}else{
		alert( "La seleccion no es un grupo editable!" );
	}
}
		
function addUserGroup(){
	idType = idTreeSelected.substring( idTreeSelected.indexOf('_') + 1, idTreeSelected.length );
	if( idType == "grupo" ){
		id_group = idTreeSelected.substring( 0, idTreeSelected.indexOf('_') );
		window.top.open( URLBaseLive + 'dayTemplates/ventas/manual/grupo/addUserPopup.php?id_group='+id_group, '', 'width=450, height=400, scrollbars=yes' );
		//window.top.document.location = URLBaseLive + "index.php?function=edicionManual&subfunction=grupos&level=agregarGrupo&id_group=" + id_group;
	}else{
		alert( "El usuario debe ser agregado a un grupo" );
	}
}
		
function delUserGroup(){
	idType = idTreeSelected.substring( idTreeSelected.lastIndexOf('_') + 1, idTreeSelected.length );
	if( idType == "usuario" ){
		if( confirm( "Desea eliminar este usuario de este grupo?" ) ){
			padre = document.getElementById( idTreeSelected ).parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.firstChild.lastChild.firstChild.firstChild;
			id_group = padre.id.substring( 0, padre.id.indexOf('_') );  
			id_employee = idTreeSelected.substring( 0, idTreeSelected.indexOf('_') );
			window.top.document.location = URLBaseLive + "index.php?function=edicionManual&subfunction=grupos&delUserGroup=" + id_employee + "&id_group=" + id_group;
		}
	}else{
		alert( "Debe seleccionar un usuario" );
	}
}

function addEmployeeToGroup( id_employee, id_group ){
	window.opener.top.document.location = URLBaseLive + "index.php?function=edicionManual&subfunction=grupos&addUserGroup=" + id_employee + "&id_group=" + id_group + "&treeView=true";
	window.close();
}

function addTypeEmployee(){
	window.top.document.location = URLBaseLive + "index.php?function=edicionManual&subfunction=tipoEmpleado&level=agregarTipoEmpleado&superType="+ idTreeSelected;
}

function editTypeEmployee(){
	window.top.document.location = URLBaseLive + "index.php?function=edicionManual&subfunction=tipoEmpleado&level=agregarTipoEmpleado&id_type="+ idTreeSelected;
}