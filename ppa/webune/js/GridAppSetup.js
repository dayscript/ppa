	var WEB_GRID_LOCATION = "http://190.27.201.2/ppa/webune/";
//var WEB_GRID_LOCATION = "http://200.75.105.77/ppa/webune/";
//var WEB_GRID_LOCATION = "http://190.27.201.2/ppa/webune/";
//var WEB_GRID_LOCATION = "http://200.71.33.249/ppa/webune/";
//var WEB_GRID_LOCATION = "http://200.71.33.251/webgrid/";
var PPA_GRID_PAGE = "grid.php";
var SERVICES_PATH = "readfile.php";
var ppaCitiesName   = ["- Seleccione una -","Apartad�","Armenia","Barbosa","Barrancabermeja","Bello interactiva","Bogot� interactiva","Bucaramanga","Buga","Caldas","Carepa","Cartagena","Cartago","Chigorod�","Copacabana","C�cuta","Dos Quebradas","El Carmen de Vivoral","El Retiro","Envigado","Florida Blanca","Girardota","Gir�n","Guarna","Itag�i","La Ceja","La Estrella","La Uni�n","La Virginia","Manizales","Marinilla","Medell�n interactiva","Medell�n","Pereira","Popay�n","Rionegro","Sabaneta","Turbaco","Turbo","Villa Mar�a"];
var ppaCitiesId = ["-1", "88","90","78","85","18","18","63","98","78","88","64","17","88","78","16","17","78","78","78","63","78","63","78","78","78","78","78","17","87","78","18","78","17","89","78","78","64","88","87"];
document.write("<script type=\"text/javascript\" src='" + WEB_GRID_LOCATION + "js/LoadXML.js'></scr"+"ipt>");
function ppaImport(str){ if(str == 'grid') { if(typeof(GridApp)=="undefined") document.write("<script type=\"text/javascript\" charset=\"iso-8859-1\" src=\"" + WEB_GRID_LOCATION + "js/gridApp.js\"></scr"+"ipt>"); } else if(str == 'channel') { if(typeof(ppaChannel)=="undefined") document.write("<script type=\"text/javascript\" charset=\"iso-8859-1\" src='" + WEB_GRID_LOCATION + "js/channelApp.js'></scr"+"ipt>"); } else if(str == 'filters') { if(typeof(Calendar)=="undefined") document.write("<script type=\"text/javascript\" charset=\"iso-8859-1\" src=\"" + WEB_GRID_LOCATION + "js/calendar.js\"></scr"+"ipt>"); if(typeof(GridApp)=="undefined") document.write("<script type=\"text/javascript\" charset=\"iso-8859-1\" src=\"" + WEB_GRID_LOCATION + "js/gridApp.js\"></scr"+"ipt>"); document.write("<script type=\"text/javascript\" charset=\"iso-8859-1\" src=\"" + WEB_GRID_LOCATION + "js/filtersApp.js\"></scr"+"ipt>"); } else if(str == 'highlights') { document.write("<script type=\"text/javascript\" charset=\"iso-8859-1\" src='" + WEB_GRID_LOCATION + "js/highlightsApp.js'></scr"+"ipt>"); }};
function gridAppIncludeJs(script_filename) { var html_doc = document.getElementsByTagName('head').item(0); var js = document.createElement('script'); js.setAttribute('language', 'javascript'); js.setAttribute('type', 'text/javascript'); js.setAttribute('src', script_filename); html_doc.appendChild(js); return false;};
function gridAppIncludeCss(css_filename) { var html_doc = document.getElementsByTagName('head').item(0); var js = document.createElement('link'); js.setAttribute('rel', 'stylesheet'); js.setAttribute('type', 'text/css'); js.setAttribute('media', 'all'); js.setAttribute('href', css_filename); html_doc.appendChild(js); return false;};
function ppaGetMainNode(node){mainNode = node.firstChild; if(mainNode.nodeType != 1) mainNode = mainNode.nextSibling; return mainNode;};
function showError( err ){ alert( err.statusText );};
function changeStatus( request ){ var yPos = document.body.scrollTop; var state_div = document.getElementById( "grid_container" ); switch ( request.readyState ) { case 1: state_div.innerHTML = "<div style=\"color:red;\">Error en el servidor</div>"; case 2: state_div.innerHTML = "<div style=\"color:red;\">Datos cargados</div>"; case 3: state_div.innerHTML = "<div style=\"color:red;\">Inicializando</div>"; case 4: if ( request.status == 200 || request.status == 304 ){ state_div.innerHTML = ""; } else { state_div.innerHTML = "<div>Cargando...</div>"; } break; default: state_div.innerHTML = "<div style=\"color:red;\">Cargando...</div>"; break; } };
function getWindowSize( ){ var w = 0, h = 0; if ( self.innerHeight ) /* all except Explorer*/ { w = self.innerWidth; h = self.innerHeight; } else if (document.documentElement && document.documentElement.clientHeight) { w = document.documentElement.clientWidth; h = document.documentElement.clientHeight;} else if (document.body) /* other Explorers*/ { w = document.body.clientWidth; h = document.body.clientHeight; } return { "w" : w, "h" : h };};
function getPageSize(){ var w = document.body.scrollWidth; var h = document.body.scrollHeight;  w = w ? w : 0;  h = h ? h : 0; return { "w" : w, "h" : h };};
function getPageScroll(){ var w = window.pageXOffset || document.body.scrollLeft || document.documentElement.scrollLeft;  var h = window.pageYOffset || document.body.scrollTop || document.documentElement.scrollTop;  w = w ? w : 0;  h = h ? h : 0;  return { "w" : w, "h" : h };};
function ppaConsultGrid(ofs){  if( typeof(GridApp.gridContainer) == "undefined" ) { GridApp.frm.action = GridApp.gridPage; GridApp.frm.submit(); } else GridApp.consultGrid(ofs); return false;};
function getIdFromCityName(str){for(var i in ppaCitiesName){if(str == ppaCitiesName[i]) return ppaCitiesId[i];}return 0;}