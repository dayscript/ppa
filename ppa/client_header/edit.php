<?
require_once("include/util.inc.php");
require_once("ppa/config.php");
$client   = new Client( $_GET['id'] );
$channels = $client->getChannelArray();
$js_array = "[";
foreach( $channels as $channel )
{
	$js_array .= "[\"" . $channel["channel"]->getId() . "\", \"" .
		$channel["number"] . "\", \"" .
		ereg_replace( "\r|\n", "", addslashes( $channel["channel"]->getName( ) . ' <em class="description">[' . ( $channel['custom_shortname'] ? $channel['custom_shortname'] : $channel["channel"]->getShortName( ) ) . ']</em> ' ) ). "\", \"" .
		ereg_replace( "\r|\n", "", addslashes( $channel["channel"]->getDescription( ) ) ). "\", \"" .
		ereg_replace( "\r|\n", "", addslashes( $channel["channel"]->getGroup() ) ) . "\"], ";
}
$js_array = ereg_replace(", $", "", $js_array ) . "]";

?>
<script language="javascript" src="include/Table.js"></script>
<script>
function arrayToTable( arr )
{
	if( arr.length == 0 ) return false;
	var table = new Table( 0, (arr[0].length + 1) );
	table.appendInId( "div_content" );
	table.setAttribute( "align", "center" );
	table.setAttribute( "border", "0" );
	table.setAttribute( "width", "80%" );
	table.getTable().className = "titulo";

	var row = table.insertRow();
	row.childNodes[0].width = "52";
	row.childNodes[2].width = "52";
	row.childNodes[3].width = "52";

	row.childNodes[0].innerHTML = "<b>Frec.</b>";
	row.childNodes[1].innerHTML = "<b>Nombre</b>";
	row.childNodes[2].innerHTML = "<b>Cat.</b>";
	row.childNodes[3].innerHTML = "<b>Props.</b>";
	row.childNodes[4].innerHTML = "<b>Borrar</b>";
	row.bgColor     = "#99eeff";
	

	for( var i=0; i<arr.length; i++ )
	{
		row = table.insertRow();
		row.bgColor     = "#DDEEFF";
		row.onmouseover = new Function( "this.setAttribute('bgcolor', '#99EEFF', 0); return false;" );
		row.onmouseout  = new Function( "this.setAttribute('bgcolor', '#DDEEFF', 0); return false;" );

		row.childNodes[0].innerHTML = arr[i][1] + '<br /><em class="description">ID: ' + arr[i][0] + '</em>';
		row.childNodes[1].innerHTML = arr[i][2] + "<div class=\"description\">" + arr[i][3] + "</div>";
		row.childNodes[2].innerHTML = arr[i][4];
		
		row.childNodes[3].innerHTML = "<img src=\"ppa11/images/props.gif\">";
		row.childNodes[3].onclick   = new Function( "editChannel(" + arr[i][0] + ");" );
		row.childNodes[3].style.cursor = "pointer";
		row.childNodes[3].align     = "center";

		row.childNodes[4].innerHTML = "<img src=\"ppa11/images/remove.gif\">";
		row.childNodes[4].onclick   = new Function( "removeChannel(" + arr[i][0] + "," + arr[i][1] + ");" );
		row.childNodes[4].style.cursor = "pointer";
		row.childNodes[4].align     = "center";
	}
	return true;
}

function addChannel()
{
	document.location = "ppa11.php?location=add_client_channels&id_client=<?=$client->getId()?>";
}

function editChannel( idChannel )
{
	window.open( "ppa11.php?location=edit_client_channel&id_client=<?=$client->getId()?>&id_channel=" + idChannel );
}

function removeChannel( idChannel, number )
{
	if( confirm( "¿Está seguro de eliminar el canal" + ( number ? " " + number : "" ) + "?" ) )
		window.open( "ppa11.php?location=edit_client_channel&nohead=true&id_client=<?=$client->getId()?>&remove_channel=" + idChannel + '&remove_number=' + number );
}

function init()
{
	var channels   = <?=$js_array?>;
	
	arrayToTable( channels );
}
window.onload = init;
</script>
<div id="otrodiv" class="titulo" ><?=$client->getName()?><br><br></div>
<div class="titulo" align="left" style="position:relative;left:100px"><b><a href="#;" onclick="addChannel();">:::Agregar Canal</a><b></div>
<div id="div_content" style="width:95%;"></div>