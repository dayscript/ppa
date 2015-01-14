<?
require_once("include/util.inc.php");
require_once("include/db.inc.php");
require_once("ppa/config.php");
if( isset( $_POST['channel'] ) && isset( $_POST['chapter'] ) )
{
	$sql = "SELECT * FROM slot, slot_chapter " .
	  "WHERE " .
	  " slot.id = slot_chapter.slot AND " .
	  " slot.channel = '" . $_POST['channel'] . "' AND ";
	if( $_POST['chapter'] != "all" )
		$sql .= " slot_chapter.chapter = '" . $_POST['chapter'] . "' AND "; 
	$sql .= " slot.date >= '" . $_POST['date'] . "-01'";
	  
	$result = db_query ( $sql );
	while( $row = db_fetch_array( $result ) )
	{
		$slot_chapter[] = $row['id'] ."-". $row['chapter'] . " - " . $row['title'];
		$slot[]         = $row['id'];
	}
	$sql = "DELETE FROM slot_chapter WHERE slot in( " . implode( ",", $slot ) . ")";
	if( db_query( $sql ) )
	{
?>
<div style="width:100%" align="center">
	Sinopsis Eliminada<br>
	<input type="button" value="Cerrar" onClick="window.close()" />
</div>
<? }else{?>
<div style="width:100%" align="center">
	Error al eliminar<br>
	<?=$sql?>
	<input type="button" value="Cerrar" onClick="window.close()" />
</div>
<? }?>
<? }else{?>
<form method="post">
<div style="width:100%" align="center">
	Está seguro que desea Eliminar la sinopsis?<br>
	<input type="submit" value="Aceptar" />
	<input type="button" value="Cerrar" onClick="window.close()" />
	<input type="hidden" name="channel" value="<?=$_GET['channel']?>" />
	<input type="hidden" name="date" value="<?=$_GET['date']?>" />
	<input type="hidden" name="chapter" value="<?=$_GET['chapter']?>" />
</div>
</form>
<? }?>
