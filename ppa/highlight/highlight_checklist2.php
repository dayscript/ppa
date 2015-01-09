<?
	$sql = "SELECT slot.id, highlights.title FROM highlights, slot WHERE slot.id = highlights.id_slot AND " .
		"slot.channel = '" . $_GET['id'] . "' AND " .
		"slot.date like '" . $_GET['date'] . "-%'";
	$result = db_query($sql);
	$ids = "";
	while($row = db_fetch_array($result))
	{
		$ids .= $row['id'] . ",";
		if($row['title'] != null )
			$titles .= $row['id'] . ": '" . $row['title'] . "',";
	}
	$ids    = substr($ids, 0, -1);
	$titles = substr($titles, 0, -1);
?>
<script>
var counter=0;
var ids    = [<?=$ids?>];
var titles = {<?=$titles?>};

function hglBack( )
{
	document.getElementById( "backform" ).submit();
}

function togleCheckbox( id, opt )
{
	var chkbox = document.getElementById( "spec_" + id );
	
	if(chkbox.disabled) return;
	
	if( !opt ) chkbox.checked  = !chkbox.checked;
	if( chkbox.checked ) counter++;
	else	counter--;
	
	showCounter();
}

function showCounter()
{
	var div_counter = document.getElementById( "div_counter" );
	var xCenter     = window.pageXOffset + 10;// + ( window.innerWidth / 2 );
	var yCenter     = window.pageYOffset + 10;// + ( window.innerHeight / 2 );
	
	div_counter.style.top  = yCenter + "px";
	div_counter.style.left = xCenter + "px";
	div_counter.innerHTML  = counter + "";
}

function cleanCheckboxes()
{
	var chkboxes = document.f["specials[]"];
	var state    = document.getElementById( "clean" ).checked;

	for( var i=0; i < chkboxes.length; i++ )
	{
		if( chkboxes[i].type == "checkbox" )
		{ 
			chkboxes[i].checked = state
		};
	}

	if( state ) counter = chkboxes.length;
	else counter = 0;
	
	showCounter();
}

function viewChapter( c )
{
	window.open("ppa11.php?location=viewChapter&nohead=true&chapter=" + c, "", "width=800,resizable=yes" );
}

function init()
{
	for( var i=0; i<ids.length; i++ )
	{
		if( document.getElementById( "spec_" + ids[i] ) )
		{
			document.getElementById( "spec_" + ids[i] ).checked = true;
			if(titles[ids[i]])
				document.getElementById( "specn_" + ids[i] ).value = titles[ids[i]];
		}
	}
}
window.onload = init;
</script>
<?
$sql = "SELECT title FROM slot WHERE id IN ( " . implode( ",", $_POST['specials'] ) . ")";
$result = db_query( $sql, $DEBUG );
$titles = "";
while( $row = db_fetch_array( $result ) )
{
	$titles .= "'" . addslashes( $row['title'] ) . "', ";
}

$titles = substr( $titles, 0, -2 );

$sql = "SELECT id, title, date, time, slot_chapter.chapter FROM slot " .
	"LEFT JOIN slot_chapter ON slot.id = slot_chapter.slot " .
	"WHERE ".
  "date like '" . $_GET['date'] . "%' AND " .
  "channel = " . $_GET['id'] . " AND " .
  "title IN (" . $titles . " ) " .
  "ORDER BY title, date, time";
  
$result = db_query( $sql );
?>
<div id="div_counter" class="titulo" style="color:white; background-color:red; position:absolute; top:0px; left:0px; width:40px;"></div>
<form name="f" method="post" action="?location=hgl_assign&date=<?=$_GET['date']?>&id=<?=$_GET['id']?>&type=<?=$_GET['type']?>&nohead=true">
<div style="font-size: 18;font-family: Arial, Helvetica, sans-serif;">Seleccion de Especiales</div>
<table width="500" class="textos">
	<tr bgcolor="#99EEFF">
		<td align="center" ><input id="clean" name="clean" type="checkbox" onclick="cleanCheckboxes();"></td>
		<td align="center" class="titulo">Título</td>
		<td align="center" class="titulo">Dia</td>
		<td align="center" class="titulo">Hora</td>
		<td align="center" class="titulo">Nuevo nombre</td>
		<td align="center" class="titulo">Ver</td>
		<? if($_GET['type']!="all"){?><td align="center" class="titulo">Dimayor</td><?}?>
	</tr>
<?
$last_title = "";
while( $row = db_fetch_array( $result ) )
{
	if( $last_title != $row['title'] ) $class = "titulo";
	else $class = "";
	$last_title = $row['title'];
	$enabled = true;
	if( $row['chapter']==null ) $enabled = false;
	if( $_GET['type']=="fut") $enabled = true;
?>
		<tr bgcolor="#DDEEFF" onMouseOver="this.setAttribute('bgcolor', '#99EEFF', 0)" onMouseOut="this.setAttribute('bgcolor', '#DDEEFF', 0)" style="cursor:pointer;">
		<td align="center"><input id="spec_<?=$row['id']?>" name="specials[]" type="checkbox" value="<?=$row['id']?>" onclick="togleCheckbox('<?=$row['id']?>', true)" <?=(!$enabled) ? "disabled" : ""?>></td>
		<td onclick="togleCheckbox('<?=$row['id']?>')" <?=$class=="" ? "" : "class=\"" . $class . "\" "?>><?=$row['title']?></td>
		<td onclick="togleCheckbox('<?=$row['id']?>')" <?=$class=="" ? "" : "class=\"" . $class . "\" "?>><?=$row['date']?></td>
		<td onclick="togleCheckbox('<?=$row['id']?>')" <?=$class=="" ? "" : "class=\"" . $class . "\" "?>><?=$row['time']?></td>
		<td style="text-align:center;"><input id="specn_<?=$row['id']?>" name="special_sname[<?=$row['id']?>]"/></td>
		<td style="text-align:center;"><? if( $row['chapter']!=null ){ ?><a href="#;" onclick="viewChapter(<?=$row['chapter']?>);">Sinopsis</a><?}?></td>
		<?if($_GET['type']!="all"){?><td style="text-align:center;"><input type="checkbox" name="subtype[<?=$row['id']?>]" value="dim"/></td><?}?>
	</tr>
<? } ?>
</table>
<input type="button" onClick="hglBack();" name="send" value="<< Regresar ">
<input type="submit" name="send" value="Finalizar >>">
</form>
<form id="backform" method="post" action="?location=hgl_checklist&date=<?=$_GET['date']?>&id=<?=$_GET['id']?>&type=<?=$_GET['type']?>" ><input type="hidden" name="ids" value="<?=implode(",", $_POST['specials'])?>" ></form>