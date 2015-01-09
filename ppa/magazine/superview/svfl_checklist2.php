<script>
var counter=0;

function tvcflBack( )
{
	document.getElementById( "backform" ).submit();
}

function togleCheckbox( id, opt )
{
	var chkbox      = document.getElementById( "spec_" + id );

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

$sql = "SELECT id, title, date, time FROM slot WHERE ".
  "date like '" . $_GET['date'] . "%' AND " .
  "channel = " . $_GET['id'] . " AND " .
  "title IN (" . $titles . " ) " .
  "ORDER BY title, date, time";
  
$result = db_query( $sql, $DEBUG );
?>
<div id="div_counter" class="titulo" style="color:white; background-color:red; position:absolute; top:0px; left:0px; width:40px;"></div>
<form name="f" method="post" action="?location=svfl_gxls&date=<?=$_GET['date']?>&id=<?=$_GET['id']?>&nohead=true">
<div style="font-size: 18;font-family: Arial, Helvetica, sans-serif;">Seleccion de Especiales</div>
<table width="500" class="textos">
	<tr bgcolor="#99EEFF">
		<td align="center" ><input id="clean" name="clean" type="checkbox" onclick="cleanCheckboxes();"></td>
		<td align="center" class="titulo">Título</td>
		<td align="center" class="titulo">Dia</td>
		<td align="center" class="titulo">Hora</td>
	</tr>
<?
$last_title = "";
while( $row = db_fetch_array( $result ) )
{
	if( $last_title != $row['title'] ) $class = "titulo";
	else $class = "";
	$last_title = $row['title'];
?>
		<tr bgcolor="#DDEEFF" onMouseOver="this.setAttribute('bgcolor', '#99EEFF', 0)" onMouseOut="this.setAttribute('bgcolor', '#DDEEFF', 0)" style="cursor:pointer;">
		<td align="center"><input id="spec_<?=$row['id']?>" name="specials[]" type="checkbox" value="<?=$row['id']?>" onclick="togleCheckbox('<?=$row['id']?>', true)" ></td>
		<td onclick="togleCheckbox('<?=$row['id']?>')" <?=$class=="" ? "" : "class=\"" . $class . "\" "?>><?=$row['title']?></td>
		<td onclick="togleCheckbox('<?=$row['id']?>')" <?=$class=="" ? "" : "class=\"" . $class . "\" "?>><?=$row['date']?></td>
		<td onclick="togleCheckbox('<?=$row['id']?>')" <?=$class=="" ? "" : "class=\"" . $class . "\" "?>><?=$row['time']?></td>
	</tr>
<? } ?>
</table>
<input type="button" onClick="svflBack();" name="send" value="<< Regresar ">
<input type="submit" name="send" value="Finalizar >>">
</form>
<form id="backform" method="post" action="?location=svfl_checklist&date=<?=$_GET['date']?>&id=<?=$_GET['id']?>" ><input type="hidden" name="ids" value="<?=implode(",", $_POST['specials'])?>" ></form>