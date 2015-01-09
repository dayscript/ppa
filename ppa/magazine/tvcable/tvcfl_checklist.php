<script>
var counter = 0;
var ids     = [<?=$_POST['ids']?>];

function setNextButton()
{
	var btn = document.getElementById( "next_button" );
	var frm = document.f;

	if( counter == 0 )
	{
		btn.value = "Finalizar >>";
		f.action  = "?location=tvcfl_gxls&date=<?=$_GET['date']?>&id=<?=$_GET['id']?>&nohead=true";
	}
	else
	{
		btn.value = "Siguiente >>";
		f.action = "?location=tvcfl_checklist2&date=<?=$_GET['date']?>&id=<?=$_GET['id']?>";
	}
}

function showCounter()
{
	var div_counter = document.getElementById( "div_counter" );
	var xCenter     = window.pageXOffset + 10;
	var yCenter     = window.pageYOffset + 10;
	
	div_counter.style.top  = yCenter + "px";
	div_counter.style.left = xCenter + "px";
	div_counter.innerHTML  = counter + "";
}

function togleCheckbox( id, opt )
{
	var chkbox      = document.getElementById( "spec_" + id );

	if( !opt ) chkbox.checked  = !chkbox.checked;
	if( chkbox.checked ) counter++;
	else	counter--;
	
	showCounter();	
	setNextButton();
}

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

	setNextButton();
}

function init()
{
	counter = ids.length;
	for( var i=0; i<ids.length; i++ )
	{
		document.getElementById( "spec_" + ids[i] ).checked = true;
	}
	
	setNextButton();
}

window.onload = init;
</script>
<?
$sql = "SELECT id, title, date, time, count(*) sum FROM slot WHERE ".
  "date like '" . $_GET['date'] . "%' AND " .
  "channel = " . $_GET['id'] . " " .
  "GROUP BY title " .
  "ORDER BY sum, date, time";
  
$result = db_query( $sql, $DEBUG );
?>
<div id="div_counter" class="titulo" style="color:white; background-color:red; position:absolute; top:0px; left:0px; width:40px;"></div>
<form name="f" method="post" action="?location=tvcfl_checklist2&date=<?=$_GET['date']?>&id=<?=$_GET['id']?>" >
<div style="font-size: 18;font-family: Arial, Helvetica, sans-serif;">Seleccion de Especiales</div>
<table width="500" class="textos">
	<tr bgcolor="#99EEFF">
		<td align="center" class="titulo"><input id="clean" name="clean" type="checkbox" onclick="cleanCheckboxes();" ></td>
		<td align="center" class="titulo">Veces</td>
		<td align="center" class="titulo">Título</td>
		<td align="center" class="titulo">Dia</td>
		<td align="center" class="titulo">Hora</td>
	</tr>
<?
while( $row = db_fetch_array( $result ) )
{
?>
		<tr bgcolor="#DDEEFF" onMouseOver="this.setAttribute('bgcolor', '#99EEFF', 0)" onMouseOut="this.setAttribute('bgcolor', '#DDEEFF', 0)" style="cursor:pointer;">
		<td align="center"><input id="spec_<?=$row['id']?>" name="specials[]" type="checkbox" value="<?=$row['id']?>" onclick="togleCheckbox('<?=$row['id']?>', true)" ></td>
		<td onclick="togleCheckbox('<?=$row['id']?>')" ><?=$row['sum']?></td>
		<td onclick="togleCheckbox('<?=$row['id']?>')" ><?=$row['title']?></td>
		<td onclick="togleCheckbox('<?=$row['id']?>')" ><?=$row['date']?></td>
		<td onclick="togleCheckbox('<?=$row['id']?>')" ><?=$row['time']?></td>
	</tr>
<? } ?>
</table>
<input id="next_button" type="submit" name="send" value="Siguiente >>">
</form>