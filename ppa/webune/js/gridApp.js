GridApp = function(){};
GridApp.isShowHeadend = true;GridApp.isLockHeadend = false;GridApp.isLockChannel = false;GridApp.isLockGender = false;GridApp.headerName = "";GridApp.channel = 0;GridApp.category = 0;GridApp.title = "";GridApp.actor = "";GridApp.startDate = "";GridApp.endDate = "";GridApp.header = -1;
GridApp.setGender = function( str ){	GridApp.category = str;};
GridApp.setHeadend = function( str ){
GridApp.headerName= str.toLowerCase();for(var i in ppaCitiesName){try{if(str.toLowerCase() == ppaCitiesName[i].toLowerCase()){GridApp.header= ppaCitiesId[i];	break;}}catch(e){}}};
GridApp.setChannel = function( str ){	GridApp.channel= str;};
GridApp.setTitle = function( str ){	GridApp.title = str;};
GridApp.setActor = function( str ){	GridApp.actor = str;};
GridApp.setStartDate = function( str ){	GridApp.startDate = str;};
GridApp.setEndDate = function( str ){	GridApp.endDate = str;};
GridApp.setHeader = function( str ){	GridApp.header= str;};
GridApp.hover = function( el ){	el.saveClassName = el.className;	el.className = "cat_td_line_1_over";};
GridApp.hout = function( el ){	el.className = el.saveClassName;};
GridApp.getScrollXY = function( ){  var scrOfX = 0, scrOfY = 0;  if( typeof( window.pageYOffset ) == 'number' ) {    /*Netscape compliant*/    scrOfY = window.pageYOffset;    scrOfX = window.pageXOffset;  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {    /*DOM compliant*/    scrOfY = document.body.scrollTop;    scrOfX = document.body.scrollLeft;  } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {    /*IE6 standards compliant mode*/    scrOfY = document.documentElement.scrollTop;    scrOfX = document.documentElement.scrollLeft;  }  return { "x" : scrOfX, "y" : scrOfY };};GridApp.getWindowSize = function( ){  var w = 0, h = 0;	if ( self.innerHeight ) /*all except Explorer*/	{	w = self.innerWidth;	h = self.innerHeight; }	else if (document.documentElement && document.documentElement.clientHeight)	{	w = document.documentElement.clientWidth;	h = document.documentElement.clientHeight;	}	else if (document.body) /*other Explorers*/	{	w = document.body.clientWidth;	h = document.body.clientHeight; }  return { "w" : w, "h" : h };};
GridApp.getPopUpPos = function( x, y, w, h ){	var xPos = 0, yPos = 0;	var scrOff = GridApp.getScrollXY();	var wSize  = GridApp.getWindowSize();	var security = 20;	xPos = x + scrOff['x'];	yPos = y + scrOff['y'];	if( ( x + w ) > ( wSize['w'] ) ) xPos = ( scrOff['x'] + wSize['w'] ) - w;	if( ( y + h ) > ( wSize['h'] ) ) yPos = ( scrOff['y'] + wSize['h'] ) - h;	return {"x": xPos - security, "y": yPos - security};};
GridApp.showSynopsisPopUp = function( ht ){	try{	document.getElementById("popup_content").innerHTML = ht.responseText;	}catch(e){}	var pos = GridApp.getPopUpPos( ht.x, ht.y, ht.popup_div.clientWidth, ht.popup_div.clientHeight );	ht.popup_div.style.left     = pos["x"] + "px";	ht.popup_div.style.top      = pos["y"] + "px";	};
GridApp.synopsisPopUp = function( slot, e ){	var pos = 0;	if( slot == 0 ) return;	if( !document.getElementById( "synopsis_popup" ) )		var popup_div = document.createElement( "div" );	else		var popup_div = document.getElementById( "synopsis_popup" );				popup_div.id  = "synopsis_popup";	popup_div.innerHTML = '<div class="top" style="text-align:right" onclick="this.parentNode.parentNode.removeChild( this.parentNode )"><img src="' + WEB_GRID_LOCATION + 'images/close.gif" /></div>';	popup_div.innerHTML += '<div class="center" id="popup_content">Cargando...</div>';	popup_div.innerHTML += '<div class="bottom"></div>';	popup_div.style.position = "absolute";	document.body.appendChild( popup_div );		var _url      = SERVICES_PATH + "?action=synopsis&slot=" + slot;	var ht        = new HTTPRequest( );	ht.timeout    = 2000;	ht.url        = _url;	ht.onError    = showError;	ht.onSuccess  = GridApp.showSynopsisPopUp;	ht.async      = true;	ht.cache      = true;	ht.popup_div  = popup_div;	ht.x          = e.clientX;	ht.y          = e.clientY;	ht.process( );		var pos = GridApp.getPopUpPos( e.clientX, e.clientY, popup_div.clientWidth, popup_div.clientHeight );	popup_div.style.left     = pos["x"] + "px";	popup_div.style.top      = pos["y"] + "px";	};

GridApp.showGrid = function( lx ){
GridApp.gridContainer.innerHTML = lx.responseText;
};

GridApp.consultGrid = function( ofs ){var _url = "";try{GridApp.channel = document.getElementById("ppa_channel").value;GridApp.category = document.getElementById("ppa_gender").value;GridApp.title = document.getElementById("ppa_title").value;GridApp.actor = document.getElementById("ppa_actor").value;GridApp.startDate = document.getElementById("ppa_startDate").value;GridApp.endDate = document.getElementById("ppa_endDate").value;}catch(e){alert("PPA:" + e);}GridApp._offset = ofs != null ? ofs : GridApp._offset;_url += SERVICES_PATH + "?action=xsearch";_url += (GridApp.channel != 0 ? "&channel=" + GridApp.channel : "");_url += (GridApp.category != 0 ? "&category=" + GridApp.category : "");_url += (GridApp.title != "" ? "&title=" + GridApp.title : "");_url += (GridApp.actor != "" ? "&actor=" + GridApp.actor : "");_url += (GridApp.startDate.replace(/[^0-9\-]/g,"") != "" ? "&start_date=" + GridApp.startDate : "");_url += (GridApp.endDate.replace(/[^0-9\-]/g,"") != "" ? "&end_date=" + GridApp.endDate : "");_url += (GridApp._offset != 0 ? "&offset=" + GridApp._offset : "" );_url += (GridApp.header != 0 ? "&header=" + GridApp.header : "" );var sp = document.getElementById("synopsis_popup");if(sp) sp.parentNode.removeChild(sp);var ht = new HTTPRequest( );ht.timeout = 2000;ht.url = _url;ht.onerror = showError;ht.onChangeState = changeStatus;ht.onSuccess = new Function( "lx", "GridApp.showGrid(lx);" );ht.async = true;ht.cache = true;ht.process();};
GridApp.goNext = function( val ){	GridApp._offset += val;	GridApp.consultGrid( GridApp._offset );};
GridApp.createGrid = function()
{
if(!GridApp.c)
{
GridApp.c=true;
document.write("<div id=\"gridapp_container\">");
document.write("<div id=\"grid_container\"></div>");
document.write("</div>");
setTimeout("GridApp.createGrid()", 1000);
}
else
{
GridApp.gridContainer = document.getElementById("grid_container");
GridApp.consultGrid( 0 );
}
};

GridApp.showHeadend = function(b){GridApp.isShowHeadend = b;};
GridApp.lockHeadend = function(){GridApp.isLockHeadend = true;};
GridApp.lockChannel = function(){GridApp.isLockChannel = true;};
GridApp.lockGender = function(){GridApp.isLockGender = true;};
GridApp.c = false;