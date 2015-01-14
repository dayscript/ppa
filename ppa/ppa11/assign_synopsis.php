<?
require_once("include/util.inc.php");
require_once("ppa/config.php");
$date = isset( $_GET['date'] ) ? $_GET['date'] : date("Y-m");

$sql = "SELECT title FROM slot " .
  "LEFT JOIN slot_chapter " .
  "ON slot.id = slot_chapter.slot " .
  "WHERE date like '" . $date . "%' AND " .
  "slot.channel = " . $_GET['id'] . " AND " .
  "slot_chapter.chapter is NULL GROUP BY title";


$result = db_query ( $sql );

$str = "[";// . $field_names . ",";
while( $row = db_fetch_array( $result, MYSQL_ASSOC ) )
{
	$str .= "[";
	foreach($row as $r)
	{
		$r = str_replace( "\"", "\\\"", trim( $r ) );
//		$str .= "\"" . ereg_replace( chr(160), "", $r ) . "\", ";
		$str .= "\"" . $r . "\", ";
	}
	$str = substr( $str, 0, -2 ) . "], ";
}
$result = ereg_replace(", $", "", $str ) . "]";

$chn       = new Channel( $_GET['id'] );
?>
<script language="javascript" src="include/Table.js"></script>
<script language="javascript" src="include/LoadXML.js"></script>
<script>

function selectSGroup( elem ) {
	$( 'input.' + elem.value).attr( 'checked', elem.checked );
}

function arrayToTable( tit, arr )
{
	if( arr.length == 0 ) return false;
	table = new Table( arr.length, (arr[0].length + 3) );
	table.appendInId( "div_serie" );
	table.setAttribute( "align", "center" );
	table.setAttribute( "cellpadding", "5" );
	table.setAttribute( "cellspacing", "1" );
	table.setAttribute( "border", "0" );
	table.setAttribute( "width", "100%" );
	table.getTable().className = "textos";

	var lastWord = null;
	var lastWordCode = 0;
	var lastWordRows = 0;
	var lastWordRow = null;
	for( var i=0; i<arr.length; i++ )
	{

		var firstWord = (( arr[i][0] ).replace( /\'/g, "\\'").split( " " ))[0].toLowerCase( );
		if( firstWord.length <= 5 )
			firstWord =( arr[i][0] ).replace( /\'/g, "\\'").substr( 0, 9 );

		table.getRow(i).bgColor = "#DDEEFF";
		table.getRow(i).style.cursor = "pointer";
		table.getRow(i).onmouseover = new Function( "this.setAttribute('bgcolor', '#99EEFF', 0); return false;" );
		table.getRow(i).onmouseout  = new Function( "this.setAttribute('bgcolor', '#DDEEFF', 0); return false;" );
		table.getRow(i).className = 'wordGroup_' + lastWordCode;
//		table.getRow(i).onmousedown  = new Function( "_title='" + ( arr[i][0] ).replace( /\'/g, "\\'" ) + "';searchSynopsis('" + ( arr[i][0] ).replace( /\'/g, "\\'" ) + "', this)" );

		if( lastWord != firstWord ) {
			if( lastWord != null )  {
				var cell = lastWordRow.insertCell( 0 );
				cell.rowSpan = lastWordRows + 1;
				cell.vAlign = 'top';
				cell.innerHTML = '<label class="full" for="wordGroup_' + lastWordCode + '"><input type="checkbox" onclick="selectSGroup(this)" id="wordGroup_' + lastWordCode + '" value="wordGroup_' + lastWordCode + '" /><\/label>';
				table.getRow(i).className = 'groupsep';
			}

			lastWordRows = 0;
			lastWordCode ++;
			lastWordRow = table.getRow(i);
			lastWord = firstWord;
		}
		lastWordRows ++;

		table.getCell(i,0).innerHTML = '<input type="checkbox" class="wordGroup wordGroup_' + lastWordCode + '" value="' + ( arr[i][0] ).replace( /\'/g, "\\'" ) + '" />';

		for( var j=0; j<arr[0].length; j++ )
		{
			table.getCell(i,j + 1).innerHTML = arr[i][j];
			table.getCell(i,j + 1).onmousedown  = new Function( "_title='" + ( arr[i][0] ).replace( /\'/g, "\\'" ) + "';searchSynopsis('" + ( arr[i][0] ).replace( /\'/g, "\\'" ) + "', this.parentNode)" );
		}
		j ++;
		table.getCell(i,j).innerHTML   = "<a href=\"#;\">Buscar</a>";
		table.getCell(i,j).onmousedown  = new Function( "_title='" + ( arr[i][0] ).replace( /\'/g, "\\'" ) + "';searchSynopsis('" + ( arr[i][0] ).replace( /\'/g, "\\'" ) + "', this.parentNode)" );

		table.getCell(i,j+1).innerHTML = "<a href=\"#;\" onclick=\"window.open('/ppa/ppa/chapter2.php?program=" + escape( arr[i][0] ) + "', 'popup', 'width=400, height=150, scrollbars=1')\">Nuevo</a>";
//		table.getCell(i,j+1).innerHTML = "<a href=\"#;\" onclick=\"window.open('http://200.71.33.251/ppa/ppa/chapter2.php?program=" + escape( arr[i][0] ) + "', 'popup', 'width=400, height=150, scrollbars=1')\">Nuevo</a>";

//		table.getCell(i,j).innerHTML   = "<a href=\"#;\">Buscar</a>";
//		table.getCell(i,j).innerHTML   = "<a href=\"#;\" onclick=\"_title='" + arr[i][0] + "';searchSynopsis('" + arr[i][0] + "', this.parentNode.parentNode)\">Buscar</a>";
	}

	if( lastWord != null )  {
		var cell = lastWordRow.insertCell( 0 );
		cell.rowSpan = lastWordRows + 1;
		cell.vAlign = 'top';
		cell.innerHTML = '<label class="full" for="wordGroup_' + lastWordCode + '"><input type="checkbox" onclick="selectSGroup(this)" id="wordGroup_' + lastWordCode + '" value="wordGroup_' + lastWordCode + '" /><\/label>';
	}

	var cell = table.insertRow(0).firstChild;
	table.setColSpan( 0, 1, arr[0].length + 1);
	cell.innerHTML = tit;
	cell.setAttribute( "align", "center" );

	var cell = table.getRow(0).insertCell( 0 );
	cell.colSpan = 2;
	cell.innerHTML = '<label for="grp"><b>Sel:<\/b> <\/label>';

	var grp = $( document.createElement( 'input' ) );
	grp.attr( 'type', 'checkbox' );
	grp.attr( 'id', 'grp' );
	grp.click(function(){
		$('input[type=checkbox]').attr( 'checked', this.checked );
	});

	$(cell).append( grp );

	table.insertRow( );

	$('.groupsep').before( document.createElement( 'tr' ) );

	return true;
}

function removeSearchBlock()
{
	try
	{
		if( search_block )
		{
			rowPos = table.getRowPos( search_block );
			table.deleteRow( rowPos );
			chapter = 0;
		}
	} catch(ex) {}
}

function searchSynopsis( tit, el )
{
	removeSearchBlock();
	rowPos       = table.getRowPos( el ) + 1;
	search_block = table.insertRow( rowPos );
	cell         = search_block.firstChild;
	table.setColSpan( rowPos, 0, 4 );
	
	var ht           = new HTTPRequest( );
	ht.timeout       = 20000;
	ht.url           = "ppa11.php?location=search_synopsis&nohead=true&title=" + escape( tit );
	ht.onSuccess     = showFound;
	ht.onChangeState = changeStatus;
	ht.process( );
}

function showFound( lx )
{
	title_arr        = _title.replace( /\s+/g, " " ).split(" ");
	autofill         = "";
	fill_search_text = function( el ){ document.getElementById('search_text').value = document.getElementById('search_text').value + " " + el.innerHTML;}
	for( var i in title_arr )
	{
		autofill += "<span style=\"cursor: pointer\" onclick=\"fill_search_text( this );\">" + title_arr[i] + "</span> ";
	}
	
	xmldoc      = lx.responseXML;
	if( xmldoc.firstChild.nodeName == "xml")
		chapterNode = xmldoc.firstChild.nextSibling;
	else
		chapterNode = xmldoc.firstChild;

	s_table = new Table(chapterNode.childNodes.length, 2);
	
	s_table.setAttribute( "border", "0" );
	s_table.setAttribute( "align", "center" );
	s_table.setAttribute( "width", "100%" );
	s_table.setAttribute( "id", "search_table" );
	s_table.getTable().className = "description";
	
	child = chapterNode.firstChild;
	try
	{
		for(var i=0; child; i++ )
		{
			s_table.getRow( i).className = 'rsinopsis';
			s_table.getCell( i, 0 ).innerHTML = '<input type="radio" name="chapter" onclick="chapter=this.value;" value="' + child.getAttribute("id") + '">';
			s_table.getCell( i, 1 ).innerHTML = "<a href=\"#;\" onclick=\"popupShowSynopsis(" + child.getAttribute("id") + ")\">" + child.firstChild.nodeValue + "</a>";
			s_table.getCell( i, 1 ).setAttribute("width", "100%");
			child = child.nextSibling;
		}
	} catch( ex ){ }
	row = s_table.insertRow();
	cell = row.firstChild;
	cell.colSpan = 2;
	cell.innerHTML = "<br><a href=\"#;\" onclick=\"assignChapter();\">Asignar</a><br><br>" + autofill;

	row = s_table.insertRow();
	cell = row.firstChild;
	cell.colSpan = 2;
	cell.innerHTML = "<input id=\"search_text\" type=\"text\" /> <a href=\"#;\" onclick=\"searchSynopsis(document.getElementById('search_text').value, document.getElementById('search_table').parentNode.parentNode.previousSibling )\">Buscar</a>";

	search_block.firstChild.appendChild( s_table.getTable() );
}

function assignChapter()
{
	d       = "<?=$_GET['date']?>";
	channel = "<?=$_GET['id']?>";

	if( chapter != 0 )
	{
		var grupo = '';
		var seleccion = $('input.wordGroup:checked');
		if( seleccion.length > 0 ) {
			if( confirm( '¿Asignar sinopsis a ' + seleccion.length + ' elemento(s)?' ) )   {
				$.each( seleccion, function( index, elem )  {
					grupo += '&titleGroup[]=' + elem.value;
				});
			}
			else
				return;
		}

		var ht           = new HTTPRequest( );
		ht.timeout       = 20000;
		ht.url           = "ppa11.php?location=assign_chapter&nohead=true&channel=" + channel + "&chapter=" + chapter + "&date=" + d + "&title=" + escape( _title.replace(/\+/g, "%2B") ) + grupo;
		ht.onSuccess     = assignChapterResult;
		ht.onChangeState = changeStatus;
		ht.process( );

		if( debug == "true" )
		{
			var pup = window.open("", "debug", "width=800, height=50, scrollbars=no");
			pup.document.write( ht.url );
			pup.document.write( escape( _title ) );
		}
	}
	else    {
		alert( 'No ha seleccionado una sinopsis.');
	}
}

function assignChapterResult( obj )
{
	if( obj.responseText == "OK" )
	{

		var seleccion = $('input.wordGroup:checked');
		if( seleccion.length > 0 ) {
			$.each( seleccion.closest( 'tr' ), function( index, elem )  {

				if( $( 'td', $(elem)).length == 5 )
					$( 'td:gt(0)', $(elem)).remove( );
				else
					$( 'td', $(elem)).remove( );
			});

		}
		else    {
			//p.removeChild( search_block.previousSibling );
			if( $( 'td', search_block.previousSibling).length == 5 )
				$( 'td:gt(0)', search_block.previousSibling).remove( );
			else
				$( 'td', search_block.previousSibling).remove( );
		}

		var p = search_block.parentNode;
		p.removeChild( search_block );
		search_block = null;
		chapter   = 0;

		$('input:checked').attr( 'checked', false );
	}
	else
	{
		alert( "Ocurrió algún error de tipo '" + obj.responseText + "'");
		if( debug == "true" )
		{
			var pup = window.open("", "debug", "width=800, height=50, scrollbars=no");
			pup.document.write( obj.responseText );
		}
	}
}

function popupShowSynopsis( chapter )
{
		window.open( "ppa11.php?location=show_synopsis&nohead=true&chapter=" + chapter, "show_synopsis1", "width=400, height=400, scrollbars=no" );
}

function changeStatus( request )
{
	var state_div = document.getElementById( "state_div" );
	var yPos = document.body.scrollTop;
//	var xCenter = window.pageXOffset + ( window.innerWidth / 2 );
//	var yCenter = window.pageYOffset + ( window.innerHeight / 2 );
	
//	state_div.setAttribute("style", "position:absolute;top:" + yCenter + "; left:" + xCenter + ";" );
//	state_div.setAttribute("style", "position:absolute;top:" + yPos + "px; left:0px;" );
	state_div.style.top = yPos + "px";


	switch ( request.readyState )
	{
		case 1:
			state_div.innerHTML = "<div style=\"color:red;\">Error en el servidor</div>";
		case 2:
			state_div.innerHTML = "<div style=\"color:red;\">Datos cargados</div>";
		case 3:
			state_div.innerHTML = "<div style=\"color:red;\">Inicializando</div>";
		case 4:
			if ( request.status == 200 || request.status == 304 ){
				state_div.innerHTML = "";
			}
			else {
				state_div.innerHTML = "<div style=\"font-size:16px; font-weight:bold; color:yellow; background-color:red;\">Cargando ...</div>";
			}
		break;
		default:
			state_div.innerHTML = "<div style=\"color:red;\">Cargando...</div>";
		break;
	}	
}

function init()
{
	var arr       = <?=$result?>;
	debug     = "<?=$_GET['debug']?>";
	chapter   = 0;
	_title    = "";

	arrayToTable( "Programas de <?=$_GET['date']?>" , arr );
//	state_div   = document.createElement( "div" );
//	state_div   = document.setAttribute( "id", "state_div" );
//	document.body.appendChild( state_div );
}
var state_div = null;
var arr       = null;
var chapter   = null;
var _title    = null;
var debug     = "false";
window.onload = init;
</script>
<div id="otrodiv"class="titulo" ><?=$chn->getId()?>. <?=$chn->getName()?></div>
<div class="description">(<?=$chn->getDescription()?>)</div>
<div id="div_serie" style="width:60%;" align="center"></div>
<div id="state_div" style="position:absolute; left:0px; top:0px;"></div>