<?
require_once("include/util.inc.php");
require_once("ppa/config.php");

$client = new Client($_GET['id_client']);

if( isset( $_POST['send'] ) && $_POST['send'] == "Aceptar" )
{
	foreach( $_POST['channel'] as $id_channel => $number )
	{
		if( $number != "" )
		{
			$client->addChannel( $id_channel, $number, ( isset( $_POST['group'] ) && isset( $_POST['group'][$id_channel] ) ? $_POST['group'][$id_channel] : null ), "", ( isset( $_POST['replace'] ) && isset( $_POST['replace'][$id_channel] ) && $_POST['replace'][$id_channel] == '1' ) );
			echo "<script>document.location= 'ppa11.php?location=edit_header&id=" . $client->getId() . "';</script>";
		}
	}
}

$channels = array();//$client->getChannelArray();
$channelIds = "";
foreach( $channels as $channel )
{
	$channelIds .= $channel["channel"]->getId() . ", ";
}
$channelIds = ereg_replace(", $", "", $channelIds );

if( $channelIds == ""  )
{
	$sql     = "SELECT * FROM channel ORDER BY name";
}
else
{
	$sql     = "SELECT * FROM channel " .
		"WHERE " .
		"id NOT IN ( " . $channelIds . ")
		ORDER BY name";
}
	
$result  = db_query( $sql );
$headers = "[";
while( $row = db_fetch_array( $result ) )
{
	$headers .= "[\"" . $row["id"] . "\", \"" .
		addslashes( $row["name"] ) . "\", \"" .
		ereg_replace( "\r|\n", "", addslashes( $row["description"] ) ) . "\"], ";
}
$headers = ereg_replace(", $", "", $headers ) . "]";


$sql = "
SELECT _group
FROM `client_channel`
WHERE client IN ( SELECT id FROM client WHERE LEFT(TRIM(name), 6) = 'TELMEX')
GROUP BY _group
ORDER BY _group";
$result  = db_query( $sql );
$groups = '';
while( $row = db_fetch_array( $result ) )
	$groups .= '<option value="' . $row['_group'] . '">' . $row['_group'] . '</option>';


?>
<script language="javascript" src="include/Table.js"></script>
<script>
function arrayToTable( arr )
{
	if( arr.length == 0 ) return false;
	table = new Table( 0, ( arr[0].length + 2 ) );
	table.appendInId( "div_serie" );
	table.setAttribute( "align", "center" );
	table.setAttribute( "border", "0" );
	table.setAttribute( "width", "90%" );
	table.getTable().className = "titulo";

	var row = table.insertRow();
	row.childNodes[0].innerHTML = "ID";
	row.childNodes[1].innerHTML = "NOMBRE";
	row.childNodes[2].innerHTML = "REEMPLAZAR";
	row.childNodes[3].innerHTML = "N&Uacute;MERO";
	row.childNodes[4].innerHTML = "CANAL";
	row.bgColor     = "#99eeff";
	

	for( var i=0; i<arr.length; i++ )
	{
		row = table.insertRow();
		row.bgColor     = "#DDEEFF";
		row.onmouseover = new Function( "this.setAttribute('bgcolor', '#99EEFF', 0); return false;" );
		row.onmouseout  = new Function( "this.setAttribute('bgcolor', '#DDEEFF', 0); return false;" );

		row.childNodes[0].innerHTML = arr[i][0];
		row.childNodes[1].innerHTML = arr[i][1] + "<div class=\"description\">" + arr[i][2] + "</div>";

		row.childNodes[2].align = "center";
		row.childNodes[2].innerHTML = "<input type=\"checkbox\" name=\"replace[" + arr[i][0] + "]\" value=\"1\" >";

		row.childNodes[3].align = "center";
		row.childNodes[3].innerHTML = "<input type=\"text\" name=\"channel[" + arr[i][0] + "]\" style=\"width:40\">";
		
		row.childNodes[4].align = "center";
		row.childNodes[4].innerHTML = '<select name="group[' + arr[i][0] + ']" style="width:80"><?= $groups ?></select>';
}
	row = table.insertRow();
	return true;
}

function init()
{
	var headers   = <?=$headers?>;
	
	arrayToTable( headers );
}
window.onload = init;
</script>
<div id="otrodiv"class="titulo" >Canales</div>
<form method="post">
<div id="div_serie" style="width:95%;"></div>
<input type="submit" name="send" value="Aceptar">
</form>