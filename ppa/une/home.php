<?
function selectDate( $date )
{
	$year    = substr($date,0,4);
	$month   = substr($date,5,2);
	$output  = "<select style=\"width:100px\" id=\"date\" name=\"date\">";
	for( $i = -1; $i < 2; $i++ )
	{
		$tmpDate = date( "Y-m", mktime( 0, 0, 0, $month+$i, 1, $year ) );
		$output .= "<option value=\"" . $tmpDate . "\" " . ($i==0 ? "selected" : "") . ">" . $tmpDate . "</option>";
	}
		$output .= "</select>";
		
		return $output;
}
?>
<script>
function getFile()
{
	var d = document.getElementById("date");
	window.location = "ppa11.php?location=une_gcw&nohead=true&date=" + d.value;
}
</script>
<table width="400" class="textos" style="border: 1px solid #DDDDDD">
	<tr>
		<td class="titulo" colspan="2" bgcolor="#EEEEEE">Gernerador de archivos</td>
	</tr>
	<tr valign="top">
		<td style="border: 1px solid #DDDDDD">Guía Interactiva</td>
		<td style="border: 1px solid #DDDDDD" >- <a href="ppa11.php?location=une_gxi&nohead=true&days=8">8 Días</a><br>
		- <a href="ppa11.php?location=une_gxi&nohead=true&days=15">15 Días</a><br>
		- <a href="ppa11.php?location=une_gxi&nohead=true&days=15&next_month=part1">Mes Siguiente Parte1</a><br>
		- <a href="ppa11.php?location=une_gxi&nohead=true&days=15&next_month=part2">Mes Siguiente Parte2</a></td>
	</tr>
	<tr valign="top">
		<td style="border: 1px solid #DDDDDD">Guía Web</td>
		<td style="border: 1px solid #DDDDDD" >- <a href="ppa11.php?location=une_gxw&nohead=true&days=8">8 Días</a><br>
		- <a href="ppa11.php?location=une_gxw&nohead=true&days=15">15 Días</a><br>
		- <a href="ppa11.php?location=une_gxw&nohead=true&days=15&next_month=part1">Mes Siguiente Parte1</a><br>
		- <a href="ppa11.php?location=une_gxw&nohead=true&days=15&next_month=part2">Mes Siguiente Parte2</a></td>
	</tr>
	<tr valign="top">
		<td style="border: 1px solid #DDDDDD">PPV</td>
		<td style="border: 1px solid #DDDDDD" >- <a href="ppa11.php?location=une_gxp&nohead=true&days=8">8 Días</a><br>
		- <a href="ppa11.php?location=une_gxp&nohead=true&days=15">15 Días</a><br>
		- <a href="ppa11.php?location=une_gxp&nohead=true&days=15&next_month=part1">Mes Siguiente Parte1</a><br>
		- <a href="ppa11.php?location=une_gxp&nohead=true&days=15&next_month=part2">Mes Siguiente Parte2</a></td>
	</tr>
	<tr valign="top">
		<td style="border: 1px solid #DDDDDD">TEST CSV</td>
		<td style="border: 1px solid #DDDDDD" ><?=selectDate( ( isset( $_GET['date'] ) ? $_GET['date'] : date( "Y-m" ) ) )?>
			<a href="#;" onclick="getFile()">Descargar</a><br>
	</tr>
</table>