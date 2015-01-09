<?
$link = mysql_connect("localhost", "root", "krxo4578") or mysql_die();

if(isset($_POST['send']))
{
	$sql = "DELETE FROM highlights where id_slot in (" . implode(",",$_POST["specials"]) . ") " . 
		"AND type = '" . $_GET['type'] . "'";
	db_query( $sql );
}
if(!isset($_POST['ids']))
{
	$sql = "SELECT slot.id FROM highlights, slot WHERE slot.id = highlights.id_slot AND " .
		"slot.channel = '" . $_GET['id'] . "' AND " .
		"slot.date like '" . $_GET['date'] . "-%' AND " .
		"highlights.type = '" . $_GET['type'] . "'";
	$result = db_query($sql);
	$ids = "";
	while($row = db_fetch_array($result))
	{
		$ids .= $row['id'] . ",";
	}
	$ids = substr($ids, 0, -1);
}
else
	$ids = $_POST['ids'];
?>
<script>
function cleanCheckboxes()
{
	counter = 0;
	var chkboxes = document.f["specials[]"];
	var state    = document.getElementById( "clean" ).checked;
	var div_counter = document.getElementById( "div_counter" );

	for( var i=0; i < chkboxes.length; i++ )
	{
		if( chkboxes[i].type == "checkbox" ) chkboxes[i].checked = state;
	}

	if( state ) counter = chkboxes.length;
	else counter = 0;
	
	showCounter();
}

function viewChapter( c, cit )
{
	window.open("ppa11.php?location=viewChapter&nohead=true&chapter=" + c + "&cit=" + cit, "", "width=800,resizable=yes" );
}
</script>
<?
$sql = "SELECT id_chapter_image_type id_cit, name FROM chapter_image_types";
$result = db_query( $sql, $DEBUG );
while( $row = db_fetch_array( $result ) )
{
	$cits[$row["id_cit"]] = $row["id_cit"];
	$cits_names[$row["id_cit"]] = $row["name"];
}
/*$sql = "SELECT ci.id_chapter_image_type id_cit, sc.chapter FROM highlights h, slot_chapter sc, slot s, chapter_images ci " .
	"WHERE " . 
	"sc.slot = h.id_slot AND " .
	"s.id = h.id_slot AND " . 
	"sc.chapter = ci.id_chapter AND " . 
	"s.channel = " . $_GET["id"];	*/

$sql = "SELECT sc.chapter, sc.slot, c.title FROM highlights h, slot_chapter sc, slot s, chapter c " . 
	"WHERE " . 
	"sc.slot = h.id_slot AND " . 
	"s.id = h.id_slot AND " . 
	"c.id = sc.chapter AND " . 
	"s.channel = " . $_GET["id"] . " AND " .
	"h.type = '" . $_GET["type"] . "' AND " .
	"s.date LIKE '" . $_GET["date"] . "%'";
$result = db_query( $sql, $DEBUG );
while( $row = db_fetch_array( $result ) )
{
	$chapters[$row["chapter"]] = $row["title"];
	$slots[$row["chapter"]] = $row["slot"];
}

if( is_array( $chapters )  )
{
	$sql = "SELECT id_chapter, id_chapter_image_type id_cit FROM chapter_images ci " . 
		"WHERE ci.id_chapter IN (" . implode(",", array_keys($chapters)) . ")";
	$result = db_query( $sql, $DEBUG );
	while( $row = db_fetch_array( $result ) )
	{
		if(file_exists("chapter_images/". $cits_names[$row["id_cit"]]."/".$row["id_chapter"].".jpg"))
			$chapter_cits[$row["id_chapter"]][$row["id_cit"]] = $row["id_cit"];
	}
}
else
{
	$chapter_cits = array();
	$chapters     = array();
}
?>
<div id="div_counter" class="titulo" style="color:white; background-color:red; position:absolute; top:0px; left:0px; width:40px;"></div>
<form name="f" method="post" action="ppa11.php?location=hgl_view&id=<?=$_GET["id"]?>&date=<?=$_GET["date"]?>" >
<div style="font-size: 18;font-family: Arial, Helvetica, sans-serif;">Seleccion de Especiales</div>
<table width="500" class="textos">
	<tr bgcolor="#99EEFF">
		<td align="center" class="titulo"><input id="clean" name="clean" type="checkbox" onclick="cleanCheckboxes();" ></td>
		<td align="center" class="titulo">Título</td>
<? foreach( $cits_names as $cit ) {?><td align="center" class="titulo"><?=$cit?></td><?}?>
	</tr>
<?
foreach( $chapters as $id_c => $c )
{
?>
	<tr bgcolor="#DDEEFF" onMouseOver="this.setAttribute('bgcolor', '#99EEFF', 0)" onMouseOut="this.setAttribute('bgcolor', '#DDEEFF', 0)" style="cursor:pointer;">
		<td style="text-align:center"><input type="checkbox" name="specials[]" value="<?=$slots[$id_c]?>" /></td>
		<td onclick="viewChapter(<?=$id_c?>)"><?=$c?></td>
<? foreach( $cits as $cit ) {?><td onclick="viewChapter(<?=$id_c?>,<?=$cit?>)" style="text-align:center"><img src="ppa11/images/<?=isset($chapter_cits[$id_c][$cit])?"ok":"no"?>.gif" /></td><? } ?>
	</tr>
<? } ?>
<tr><td colspan="4" style="text-align:center"><input type="submit" value="Eliminar Marcados" name="send"></td></tr>
</table>
</form>