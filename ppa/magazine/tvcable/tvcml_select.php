<?
function selectDate( $date )
{
	$year    = substr($date,0,4);
	$month   = substr($date,5,2);
	$output  = "<select id=\"date\" name=\"date\">";
	for( $i = 0; $i < 3; $i++ )
	{
		$tmpDate = date( "Y-m", mktime( 0, 0, 0, $month+$i, 1, $year ) );
		$output .= "<option value=\"" . $tmpDate . "\">" . $tmpDate . "</option>";
	}
		$output .= "</select>";
		
		return $output;
}
?>
<script>
	function getXls()
	{
		if( document.getElementById( "group" ).value == 0 )
		{
			alert( "Debe seleccionar un grupo" );
		}
		else
		{	
			var _date  = document.getElementById( "date" ).value;
			var _group = document.getElementById( "group" ).value
			document.location = "?location=tvcfl_gexls&nohead=true&client=<?=$_GET['client']?>&date=" + _date + "&group=" + _group ;
		}
	}
</script>
<form method="GET" action="?location=tvcfl_gexls&client=<?=$_GET['client']?>&">
<table class="titulo">
	<tr>
		<td align="center" colspan="2" style="font-size: 18;font-family: Arial, Helvetica, sans-serif;">Grillas mensuales de TV Cable<br><br></td>
	</tr>
	 <tr>
	 	<td>Grupo:</td>
  <td><select id="group" name="group">
   <option value="0">Seleccione un Grupo </option>
   <option value="cine">Cine </option>
   <option value="culturales">Culturales </option>
   <option value="cyv">Culturales y variedades</option>
   <option value="deportes">Deportes </option>
   <option value="ellosyellas">Ellos y ellas</option>
   <option value="infantiles">Infantiles </option>
   <option value="internacionales">Internacionales </option>
   <option value="musicales">Musicales</option>
   <option value="noticias">Noticias</option>
   <option value="premium">Premium </option>
   <option value="religiosos">Religiosos</option>
   <option value="series">Series</option>
  </select></td>
 </tr>
 <tr><td>Fecha:</td><td><?=selectDate( ( isset( $_GET['date'] ) ? $_GET['date'] : date( "Y-m" ) ) )?></td></tr>  
 <tr><td colspan="2" align="center"><input name="consult" value="Generar" type="button" onclick="getXls()"></td></tr>  
</table>
</form>
