<?
require_once("config1.php");
require_once("../include/util.inc.php");

/*******
 *  paso 1
*********/

if( empty( $_GET ) && empty( $_POST ) )
{
	$sql = "SELECT * FROM channel order by name";
	$result = db_query( $sql );
	while ( $row = db_fetch_array( $result ) )
	{
		$channels[$row['id']] =  $row['name'];
	}
?>
<form name="chn" method="post">
	<select name="channel" >
<? foreach( $channels as $id => $channel )
{
?>
	<option value="<?=$id?>"><?=$channel?> (<?=$id?>)</option>
<?}?>
</select><br>
<input type="text" value="<?=date("Y-m")?>" name="date" ><br>
<input type="hidden" value="2" name="step" >
<input type="submit" value="OK" name="ok" >
</form>
<?
}

/*******
 *  paso 2
*********/
if( $_POST['step'] == "2" )
{
	$sql = "SELECT * FROM slot, slot_chapter " .
	  "WHERE " .
	  " slot.id = slot_chapter.slot AND " .
	  " slot.channel = '" . $_POST['channel'] . "' AND " .
	  " slot.date >= '" . $_POST['date'] . "-01'" .
	  " GROUP BY slot_chapter.chapter ORDER BY slot.title";
	$result = db_query ($sql);
	while( $row = db_fetch_array( $result ) )
	{
		$chapter[$row['chapter']] = $row['title'];
	}
?>
<form name="chapter" method="post">
	<select name="chapter" >
	<option value="all">---- todos -----</option>
<? foreach( $chapter as $id => $title )
{
?>
	<option value="<?=$id?>"><?=$title ?> (<?=$id?>)</option>
<?}?>
</select><br>
<input type="hidden" value="<?=$_POST['date']?>" name="date" >
<input type="hidden" value="<?=$_POST['channel']?>" name="channel" >
<input type="hidden" value="3" name="step" >
<input type="submit" value="OK" name="ok" >
</form>
<? 
}
/*******
 *  paso 3
*********/
if( $_POST['step'] == "3" )
{
	$sql = "SELECT * FROM slot, slot_chapter " .
	  "WHERE " .
	  " slot.id = slot_chapter.slot AND " .
	  " slot.channel = '" . $_POST['channel'] . "' AND ";
	if( $_POST['chapter'] != "all" )
		$sql .= " slot_chapter.chapter = '" . $_POST['chapter'] . "' AND "; 
	$sql .= " slot.date >= '" . $_POST['date'] . "-01'";
	  
	$result = db_query ($sql );
	while( $row = db_fetch_array( $result ) )
	{
		$slot_chapter[] = $row['id'] ."-". $row['chapter'] . " - " . $row['title'];
		$slot[]         = $row['id'];
	}
	$sql = "DELETE FROM slot_chapter WHERE slot in( " . implode( ",", $slot ) . ")";
	db_query( $sql );
}
?>
