<?
require_once("include/util.inc.php");
require_once("ppa/config.php");

$sql     = "SELECT * FROM client ORDER BY name";
$result  = db_query( $sql );
$headers = "[";
while( $row = db_fetch_array( $result ) )
{
	$headers .= "[\"" . $row["id"] . "\", \"" . $row["name"] . "\"], ";
}
$headers = ereg_replace(", $", "", $headers ) . "]";
?>
<script language="javascript" src="include/Table.js"></script>
<script>
function editHeader( id )
{
//	document.location = "ppa11.php?location=edit_header&id=" + id;
	window.open("ppa11.php?location=edit_header&id=" + id);
}

function arrayToTable( arr )
{
	if( arr.length == 0 ) return false;
	table = new Table( 0, (arr[0].length ) );
	table.appendInId( "div_serie" );
	table.setAttribute( "align", "center" );
	table.setAttribute( "border", "0" );
	table.setAttribute( "width", "80%" );
	table.getTable().className = "titulo";

	var row = table.insertRow();
	row.childNodes[0].innerHTML = "ID";
	row.childNodes[1].innerHTML = "NOMBRE";
	row.bgColor     = "#99eeff";
	

	for( var i=0; i<arr.length; i++ )
	{
		row = table.insertRow();
		row.bgColor     = "#DDEEFF";
		row.onmouseover = new Function( "this.setAttribute('bgcolor', '#99EEFF', 0); return false;" );
		row.onmouseout  = new Function( "this.setAttribute('bgcolor', '#DDEEFF', 0); return false;" );
		row.onmouseup   = new Function( "editHeader('" + arr[i][0] + "')" );

		for( var j=0; j<arr[0].length; j++ )
		{
			row.childNodes[j].innerHTML = arr[i][j];
		}
	}
	return true;
}

function addHeader()
{
	document.location = "ppa.php?add_client=1";
}

function init()
{
	var headers   = <?=$headers?>;
	
	arrayToTable( headers );
}
window.onload = init;
</script>
<div id="otrodiv"class="titulo" >Cabeceras<br><br></div>
<div class="titulo" align="left" style="position:relative;left:100px"><b><a href="#;" onmouseup="addHeader();">:::Agregar Cabecera</a><b></div>
<div id="div_serie" style="width:95%;"></div>