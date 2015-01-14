<?
/*moveslotshour.php?channel_id=129&date=2005-07-01&time=08:15:00&numhoras=-3*/
//require_once("config1.php");
require_once("include/db.inc.php");
require_once("include/config.inc.php");
if( isset( $_GET['channel_id'] ) && isset( $_GET['start_date'] ) && isset( $_GET['stop_date'] ) && isset( $_GET['numhoras'] ) )
{
	if( $_GET['numhoras'] > 0 )
	{
		db_query ("DELETE FROM slot WHERE date ='" . $_GET['stop_date'] ."' AND time >= '" .  (24 - $_GET['numhoras']) . ":00:00' AND channel = '" . $_GET['channel_id'] . "'", 1 );
		echo mysql_affected_rows() . "\n";
		
		$sql = "SELECT id, time, date " .
			"FROM slot " .
			"WHERE channel = '" . $_GET['channel_id'] . "'" .
			" AND date BETWEEN '" . $_GET['start_date'] . "' AND '" . $_GET['stop_date']  . "' " .
			" ORDER BY date DESC, time DESC";
	}
	else
	{
//		$sql = 'SELECT id, time, date FROM slot WHERE channel = '.$_GET['channel_id'].' AND date >=  "'.$_GET['date'].'" ORDER BY date ASC, time ASC';
		db_query ( "DELETE FROM slot WHERE date ='" . $_GET['start_date'] ."' AND time <= '" . ( (-1) * $_GET['numhoras'] ) . ":00:00' AND channel = '" . $_GET['channel_id'] . "'", 1 );
		echo mysql_affected_rows() . "\n";

		$sql = "SELECT id, time, date " .
			"FROM slot " .
			"WHERE channel = '" . $_GET['channel_id'] . "'" .
			" AND date BETWEEN '" . $_GET['start_date'] . "' AND '" . $_GET['stop_date']  . "' " .
			" ORDER BY date ASC, time ASC";
	}
	echo "<a href=\"moveslotshour.php\">REGRESAR</a><br>";
	$query = db_query( $sql,1 );
	while( $row = db_fetch_array( $query ) )
	{
		$time = $row['time'];
		$newtime = date( "H:i:s", strtotime( $row['date']." ".$row['time'] ) + $_GET['numhoras']*(60*60) );
		$newdate = date( "Y-m-d", strtotime( $row['date']." ".$row['time'] ) + $_GET['numhoras']*(60*60) );
		
		$sql1 = "update slot set time = '".$newtime."', date = '".$newdate."' where id = ". $row['id'] ." ";
		if( !db_query( $sql1,1 ) )
		{
			db_query("DELETE FROM SLOT WHERE id=" . $row['id'], 1 );
		}
	}

	echo "<strong>Fin</strong>";
}
else
{
	$sql = "SELECT id, name FROM channel ORDER BY name";
	$query = db_query( $sql );
?>
<script>
function Verifica()
{
	if( document.f1.numhoras.value == "" || document.f1.date.value == "")
	{
		alert("Debe rellenar todos los campos");
		return false;
	}
	if( confirm("Seguro que desea mover las horas al ID " + document.f1.channel_id.value ) )
		return true;
	else
		return false;
}
</script>
<form name="f1" method="get" onsubmit="return Verifica()">
<table>
  <tr>
    <th colspan="2" align="center">Move Slots Hour</th>
  </tr>
  <tr>
    <td>Canal:</td>
    <td><select name="channel_id">
<?
	while( $row = db_fetch_array( $query ) )
	{
?>
  <option value="<?=$row['id']?>"><?=$row['name'] . " (". $row['id'] .")" ?></option>
<?	} ?>
</select></td>
  </tr>
  <tr>
    <td>Fecha Inicial (YYYY-MM-DD):</td>
    <td><input type="text" name="start_date"></td>
  </tr>
  <tr>
    <td>Fecha Final (YYYY-MM-DD):</td>
    <td><input type="text" name="stop_date"></td>
  </tr>
  <tr>
    <td>Horas (+ ó -):</td>
    <td><input type="text" name="numhoras"></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="enviar" value="Enviar"></td>
  </tr>
</table>
</form>
<? } ?>
