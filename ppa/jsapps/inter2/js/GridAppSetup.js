//var WEB_GRID_LOCATION = "http://10.10.93.5/ppa/jsapps/inter2/";
var WEB_GRID_LOCATION = "http://spinebone.net/ppa/jsapps/inter2/";
var PPA_GRID_PAGE = "grid.php";
var PPA_PRINT_GRID_PAGE = "print.php";
var SERVICES_PATH = "readfile.php";
//var ppaCitiesName   = ["- Seleccione una -","Acarigua","Anaco","Bachaquero","Barinas","Barquisimeto","Caracas","Carora","Chichiriviche","Ciudad Bolivar","Coro","Cuman�","El Tigre","Guanare","Los Teques","Maracaibo","Margarita","Matur�n","Merida","Puerto Cabello","Puerto La Cruz","Punta de Mata","Punto Fijo","Region Central","Rubio","San Cristobal","San Felipe","San Fernando de Apure","Tinaco","Tinaquillo","Tucacas","Ure�a","Valera","Vigia"];
//var ppaCitiesId = ["-1","48","55","76","53","26","46","7","72","51","81","91","54","92","57","45","82","79","50","93","62","84","49","44","94","61","52","74","73","75","106","83","58","80"];
var ppaCitiesName   = ["- Seleccione una -","Acarigua","Anaco","Bachaquero","Barcelona","Barinas","Barinitas","Barquisimeto","Bejuma","Cabimas","Cabudare","Cagua","Caja Seca","Caracas","Carora","Carrizal","Carvajal","Charallave","Chichiriviche","Ciudad Bolivar","Ciudad Ojeda","Cocorote","Cocorotico","Coro","C�a","Cuman�","Cumarebo","Ejido","El Tigre","El Tigrito","El Vig�a","Flor de Patria","Guacara","Guanare","Guarenas","Guatire","G�ig�e","La Cejita","La Concepci�n","La Guaira","La Pradera","La Puerta","La Vela","La Victoria","Lagunillas","Lecher�a","Los Teques","Maracaibo","Maracay","Mariara","Matur�n","M�rida","Miranda","Montalban","Mor�n","Naguanagua","Nirgua","Nueva Esparta","Palo Negro","Pam pam","Pampanito","P�ritu","Porlamar","Puerto Cabello","Puerto La Cruz","Punta de Mata","Punto Fijo","Region Central","Rubio ","Salom","San Antonio","San Antonio del T�chira","San Carlos","San Cristobal","San Diego","San Felipe","San Fernando de Apure","San Francisco","San Joaqu�n","San Juan","San Mateo","Santa Cruz","Santa Rita","Tariba","Tinaco","Tinaquillo","Tocuyito","Tucacas","Tur�n","Turmero","Ure�a","Valencia","Valera","Villa de Cura","Villa Flamingo","Yaritagua"];
var ppaCitiesId = ["-1","48","55","76","62","53","53","26","186","187","26","44","185","46","47","57","58","56","72","51","187","52","52","81","56","91","184","50","54","54","80","58","44","92","46","46","44","58","58","46","44","58","81","44","45","62","57","45","44","44","79","50","186","186","106","44","186","82","44","58","58","48","82","93","62","84","49","44","94","186","57","83","73","61","44","52","74","45","44","188","44","44","45","61","73","75","44","106","48","44","83","44","58","44","72","26"];
document.write("<script type='text/javascript' src='" + WEB_GRID_LOCATION + "js/LoadXML.js'></scr"+"ipt>");

function ppaImport(str){
	if(str == 'grid'){
		if(typeof(GridApp)=="undefined") document.write("<script type='text/javascript' charset=\"ISO-8859-1\" src='" + WEB_GRID_LOCATION + "js/gridApp.js'></scr"+"ipt>");
	}else if(str == 'channel') {
		if(typeof(ppaChannel)=="undefined") document.write("<script type='text/javascript' src='" + WEB_GRID_LOCATION + "js/channelApp.js'></scr"+"ipt>");
	}
	else if(str == 'filters'){
		if(typeof(Calendar)=="undefined") document.write("<script type='text/javascript' src='" + WEB_GRID_LOCATION + "js/calendar.js'></scr"+"ipt>");
		if(typeof(timeField)=="undefined") document.write("<script type='text/javascript' src='" + WEB_GRID_LOCATION + "js/timeField.js'></scr"+"ipt>");
		if(typeof(GridApp)=="undefined") document.write("<script type='text/javascript' charset=\"ISO-8859-1\" src='" + WEB_GRID_LOCATION + "js/gridApp.js'></scr"+"ipt>");
		gridAppIncludeCss(WEB_GRID_LOCATION + "css/filters.css");
		gridAppIncludeCss(WEB_GRID_LOCATION + "css/grid.css");
		gridAppIncludeCss(WEB_GRID_LOCATION + "css/update.css");
		document.write("<script type='text/javascript' charset=\"ISO-8859-1\" src='" + WEB_GRID_LOCATION + "js/filtersApp.js'></scr"+"ipt>");
	} else if(str == 'highlights') {
		document.write("<script type='text/javascript' src='" + WEB_GRID_LOCATION + "js/highlightsApp.js'></scr"+"ipt>");
	}
};
function gridAppIncludeJs(script_filename) {
	var html_doc = document.getElementsByTagName('head').item(0);
	var js = document.createElement('script');
	js.setAttribute('language', 'javascript');
	js.setAttribute('type', 'text/javascript');
	js.setAttribute('src', script_filename);
	html_doc.appendChild(js);
	return false;
};
function gridAppIncludeCss(css_filename) {
	var html_doc = document.getElementsByTagName('head').item(0);
	var js = document.createElement('link');
	js.setAttribute('rel', 'stylesheet');
	js.setAttribute('type', 'text/css');
	js.setAttribute('media', 'all');
	js.setAttribute('href', css_filename);
	html_doc.appendChild(js);
	return false;
};
function ppaGetMainNode(node){
	mainNode = node.firstChild;
	if(mainNode.nodeType != 1) mainNode = mainNode.nextSibling;
	return mainNode;
};
function showError( err ){
	alert( err.statusText );
};
function changeStatus( request ){
	var yPos = document.body.scrollTop;
	var state_div = document.getElementById( "grid_container" );
	switch ( request.readyState ) {
		case 1:
			state_div.innerHTML = "<div style=\"color:red;\">Error en el servidor</div>";
		case 2:
			state_div.innerHTML = "<div style=\"color:red;\">Datos cargados</div>";
		case 3:
			state_div.innerHTML = "<div style=\"color:red;\">Inicializando</div>";
		case 4:
			if ( request.status == 200 || request.status == 304 ){
			state_div.innerHTML = "";
		} else {
			state_div.innerHTML = "<div>Cargando...</div>";
		}
		break;
		default:
			state_div.innerHTML = "<div style=\"color:red;\">Cargando...</div>";
			break;
	}
};
function getWindowSize( ){
	var w = 0, h = 0;
	if ( self.innerHeight ) /* all except Explorer*/ {
		w = self.innerWidth;
		h = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) {
		w = document.documentElement.clientWidth;
		h = document.documentElement.clientHeight;
	} else if (document.body) /* other Explorers*/ {
		w = document.body.clientWidth;
		h = document.body.clientHeight;
	}
	return {
		"w" : w, 
		"h" : h
	};

};
function getPageSize(){
	var w = document.body.scrollWidth;
	var h = document.body.scrollHeight;
	w = w ? w : 0;
	h = h ? h : 0;
	return {
		"w" : w, 
		"h" : h
	};

};
function getPageScroll(){
	var w = window.pageXOffset || document.body.scrollLeft || document.documentElement.scrollLeft;
	var h = window.pageYOffset || document.body.scrollTop || document.documentElement.scrollTop;
	w = w ? w : 0;
	h = h ? h : 0;
	return {
		"w" : w, 
		"h" : h
	};

};
function ppaConsultGrid(ofs){
	if( typeof(GridApp.gridContainer) == "undefined" ) {
		GridApp.frm.action = GridApp.gridPage;
		GridApp.frm.submit();
	} else GridApp.consultGrid(ofs);
	return false;
};
function getIdFromCityName(str){
	for(var i in ppaCitiesName){
		if(str == ppaCitiesName[i]) return ppaCitiesId[i];
	}
	return 0;
}

/* new update */
var path_inter = WEB_GRID_LOCATION;
var code_interval = 0;
var code_interval_2 = 0;

$(document).ready(function(){
	/*
	$("#ppa_filterContainer").prepend( $("#new_content_form")  );
	$("#new_city").append( $("#ppa_city", "#ppa_filterContainer") );
	$("#new_gender").append( $("#ppa_gender", "#ppa_filterContainer") );
	$("#new_channel").append( $("#ppa_channel", "#ppa_filterContainer") );
	$("#new_date_start").append( $("#ppa_startDate", "#ppa_filterContainer") );
	$("#new_date_end").append( $("#ppa_endDate", "#ppa_filterContainer") );
	$.each( $('.time_container'), function(key , value){
		if( key == 0 ){
			$("#new_hour_start").append( $(this) );
		}
		if( key == 1 ){
			$("#new_hour_end").append( $(this) );
		}
	});
	$("#new_title").append( $("#ppa_title", "#ppa_filterContainer") );
	$("#new_actor").append( $("#ppa_actor", "#ppa_filterContainer") );
	$(".ppa_btnSearch", "#ppa_filterContainer").attr('value', '');
	$("#new_btn_submit").append( $(".ppa_btnSearch", "#ppa_filterContainer") );

	$("#ppa_filterContainer > *:not('#new_content_form')").remove();
	$("#ppa_filterContainer").css('display', 'block');
	*/
	$("h5","#ppa_filterContainer").addClass('azul1 VAGT texto13');

	$("#grid_container").bind("DOMSubtreeModified", function(){
		if( $('.city_title').length > 0 ){
			$('#grid_container').unbind('DOMSubtreeModified');
			edit_grid_container();
		}
	});

	$('#gridapp_container').delegate("td[class^='cat_td_line']", "click", function() {
		refresh_popup_info();
	});

});

function edit_grid_container(){
		$('.program', '#grid_container').wrapInner("<h6 class='azul1 VAGB texto11' />");
		$('.gender', '#grid_container').wrapInner("<h6 class='VAGT texto11' />");
		$('.hour', '#grid_container').wrapInner("<h1 class='azul1 VAGT uppercase-text' />");
		$('.chn_title', '#grid_container').wrapInner("<h2 class='azul1 VAGT uppercase-text' />");

	var titles = $('th.cat_table_title', '#grid_container');
	//console.log( titles.eq(1) );
	titles.eq(1).addClass( 'first_t' );
	titles.eq(-1).addClass( 'last_t' );
	//$('th.cat_table_title:get(1)', '#grid_container').addClass( "first_t" );
	//$('th.cat_table_title:last-child', '#grid_container').addClass( "last_t" );

	//$('.ppa_chn_name', '#grid_container').wrapInner("<h6 class='Thin uppercase' />");

		$.each( $('.ppa_chn_name', '#grid_container'), function(key, value){
	//console.log( key );
	//console.log( value );
		});

	$( '.goback', '#grid_container' ).attr('src', path_inter+'images/goback.jpg');
	$( '.gonext', '#grid_container' ).attr('src', path_inter+'images/gonext.jpg');

	$( '.goback', '#grid_container' ).click( refresh_grid_container );
	$( '.gonext', '#grid_container' ).click( refresh_grid_container );
	$( 'input.ppa_btnSearch', '#ppa_filterContainer' ).click( refresh_grid_container );

	$('td','.cat_td_line_1').wrapInner( "<div class='new_header_name_date' />" );
	$('td','.cat_td_line_1').prepend( "<div class='new_header_name_chanel'><h2 class='azul1 VAGB uppercase-text'>"+ $('.city_name', '.city_title').text() + "</h2></div>" );

	var cadena_name_date = $('.new_header_name_date', '#grid_container').text();
	cadena_name_date = cadena_name_date.replace("Resultados para:","");

	$('.new_header_name_date', '#grid_container').html( "<div>Resultados para: " + cadena_name_date + "</div>");

	var city_title =
		'<div class="city_title">' +
			'<div id="gprint_image"></div>IMPRIMIR TODA LA PROGRAMACI&Oacute;N: ' +
			'<a onclick="GridApp.forPrintGrid(\'date\')" href="#;">Por fecha</a> ' +
			'<a onclick="GridApp.forPrintGrid(\'channel\')" href="#;">Por canal</a>' +
		'</div>'

	$('.city_title', '#grid_container').remove( );
	$('.new_header_name_date', '#grid_container').prepend( city_title );


	$.each( $( '.ppa_chn_name', '#grid_container' ), function(){
		var cadena_original = $( this ).html();
		var new_text = cadena_original.split('<br>');

		var cadena_remp = new_text[1].replace('<span>', '');
		cadena_remp = cadena_remp.replace('</span>', '');

		$( this ).html( "<h6 class='VAGB texto11 gris'>" + new_text[0] +"</h6><h6 class='VAGT texto11 gris'>"+ cadena_remp +"</h6>" );
	} );

}

function refresh_grid_container(){
	$('#grid_container').hide();
	if( !document.getElementById('cargando_new') )
		$("#gridapp_container").append("<div id='cargando_new'>Cargando...</div>");
	clearInterval(code_interval);
	code_interval = setInterval( function(){
		if( $('.city_title').length > 0){
			clearInterval(code_interval);
			edit_grid_container();
			$("#cargando_new","#gridapp_container").remove();
			$('#grid_container').show();
		}
	},100 );
}

function refresh_popup_info(){
	var cont  = 0;

	var pholder = $("#placeholder");
	if( pholder.length == 0 )  {
	pholder = $(document.createElement( 'div' ));
	pholder.attr('id','placeholder');

	var popup = $('#synopsis_popup');
	if( popup.length > 0 )
	popup.remove( );

	$(document.body).append( pholder );

	if( popup.length > 0 )
	$(document.body).append( popup );

	pholder.click(function(){
		clearInterval( code_interval_2 );
		$('#synopsis_popup').remove();
		$(this).hide( );
		$(document.body).css( {
			"overflow": "auto"
		});
	});
}

pholder.css( {
		"position": "absolute",
		"top": $(window).scrollTop(),
		"left":'0px',
		"height": '100%',
		"width":'100%',
		"display": 'none',
		"background-color": "#000",
		"opacity": 0.4
	});
	$(document.body).css( {
		"overflow": "hidden"
	});
	pholder.show();

	clearInterval( code_interval_2 );
	code_interval_2 = setInterval( function(){
		if( cont < 100 && $(".synopsis","#synopsis_popup").length < 1 ){
			cont++;
		}
		else{
			$('#synopsis_popup').css( 'opacity', 0 );
			clearInterval( code_interval_2 );

			var close = $('#synopsis_popup .top').click(function( ){
				clearInterval( code_interval_2 );
				pholder.hide( );
				$(document.body).css( {
					"overflow": "auto"
				});
			});

			$('.image','#popup_content').hide();
			$('.synopsis','#popup_content').hide();

			var obj_synopsis = $('.synopsis','#popup_content');
			var obj_popup_content = $( "#popup_content","#synopsis_popup" );

			obj_popup_content.append( "<div class='title_inter'><h1>inter</h1></div>" );
			obj_popup_content.append( "<div class='title_program'><h2 class='gris VAGB uppercase-text'>"+ $('h1',obj_synopsis).text() +"</h2></div>" );
			obj_popup_content.append( "<div class='info_program'><h5 class='VAGT gris vigneta-check'>"+ $('.desc',obj_synopsis).text() +"</h5></div>" );
			obj_popup_content.append( "<div class='content_image'><div>"+ $('.image','#popup_content').html() +"</div></div>");

			$.each( $('div.attr',obj_synopsis), function(){
				$('.data_hour', obj_popup_content ).append( $(this).text() );
			});

			$('#synopsis_popup').css( 'opacity', 1 );

		}

	}, 50 );
}

