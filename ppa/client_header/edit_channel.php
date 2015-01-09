<?
require_once("include/util.inc.php");
require_once("ppa/config.php");

$client   = new Client( $_GET['id_client'] );

if( isset( $_GET['remove_channel'] ) && $_GET['remove_channel'] != "" )
{
	if( $client->removeChannel( $_GET['remove_channel'], ( isset( $_GET['remove_number'] ) ? $_GET['remove_number'] : null ) ) )
		echo "<script>alert( 'Eliminado el canal " . ( isset( $_GET['remove_number'] ) ? $_GET['remove_number'] : "" ) . "' );window.opener.location.reload();</script>";
	echo "<script>window.close();</script>";
}

if( isset( $_POST['send'] ) && $_POST['send'] == "Aceptar" )
{
	if( $client->editChannel( $_POST['id_channel'], $_POST['number'], $_POST['group'], $_POST['custom_shortname'], $_POST['old_number'] ) )
		echo "<script>window.opener.location.reload();</script>";
	echo "<script>window.close();</script>";
}

$channel  = $client->getChannel( $_GET['id_channel'] );

?>
<script>
function onlyNumbers( el )
{
	el.value = el.value.replace(/[^0-9]/g,'');
}

function init()
{
	document.f1.number.focus();
}

window.onload = init;
</script>
<style>
#div_content td, #div_content table
{
	border: 1px solid #DDDDDD;
}
</style>
<div id="otrodiv" class="titulo" ><?=$client->getName()?><br>
<?=$channel['channel']->getName()?><div class="description"><?=$channel['channel']->getDescription()?></div><br><br></div>
<div id="div_content" style="width:95%;">
<form name="f1" method="post" action="ppa11.php?<?=$_SERVER['QUERY_STRING']?>&nohead=true">
<input type="hidden" name="old_number" value="<?=$channel['number']?>">
 <table class="textos" width="400">
   <tr>
     <td class="titulo">N&uacute;mero:</td>
     <td><input type="text" name="number" onkeyup="onlyNumbers(this);" value="<?=$channel['number']?>"></td>
   </tr>
   <tr>
     <td class="titulo">Grupo:</td>
     <td><select name="group" style="width:130">
<?
$sql = "
SELECT _group
FROM `client_channel`
WHERE client IN ( SELECT id FROM client WHERE LEFT(TRIM(name), 6) = 'TELMEX')
GROUP BY _group
ORDER BY _group";
$result  = db_query( $sql );
$groups = '';
while( $row = db_fetch_array( $result ) )	{
	$groups .= '<option value="' . $row['_group'] . '">' . $row['_group'] . '</option>';

?>
		<option value="<?= $row['_group'] ?>" <?= ( strcasecmp( $row['_group'], $channel['channel']->getGroup() ) == 0 ? 'selected="selected"' : '' ) ?>><?= $row['_group'] ?></option>
<?
}
?>
     </select></td>
   </tr>
   <tr class="titulo">
     <td>Nombre corto personal:<div class="description">(Solo aplica para esta cabecera)</div></td>
     <td><input type="text" name="custom_shortname" value="<?=$channel['custom_shortname']?>"></td>
   </tr>
   <tr>
     <td colspan="2" align="center"><input type="hidden" name="id_channel" value="<?=$channel['channel']->getId()?>">
     <input type="submit" name="send" value="Aceptar"></td>
   </tr>
 </table>
</form>
</div>