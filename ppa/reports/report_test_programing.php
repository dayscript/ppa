<?php
require('mysql.class.php');
require('reports.func.php');
error_reporting(E_ALL);
$mktime = mktime(0,0,0,date('m'),date('d') - 1,date('Y'));
$date_1 = (date('Y-m-d',$mktime));

//el archivo contiene el formulario y componentes que necesita el reporte de transmisiones de futbol para mostrarse
?>

<link href="js/calendar/calendar-blue.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/calendar/calendar.js"></script>
<script type="text/javascript" src="js/calendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="js/calendar/calendar-setup.js"></script>
<script type="text/javascript" src="include/jquery.min.js"></script>
<script type="text/javascript" src="reports/rep_functions.js"></script>

<h5>Reporte del test de programaci&oacute;n</h5>
<form name="filtersfrm" action="" method="post">
<table class="center_ filters_" width="90%">
	<tr>
		<th colspan="2">Filtros</th>
	</tr>
	<tr>
		<th>Cabecera</th>
		<th>fecha</th>
	</tr>
	<tr>
		<td align="center">
			<?php $headers = findClientsheaders(); ?>
			<select name="headersslt" id="headersslt">
				<option val="0">Seleccione la cabecera</option>

				<?php
				foreach($headers as $header_id=>$header_name){
					//if();
					echo '<option value="'.$header_id.'">'.$header_name.'</option>';
				}
				?>
			</select>
		</td>
		<td align="center">
			<div class="date_calendar" onmouseover="this.style.backgroundColor='#ffffff';" onmouseout="this.style.backgroundColor='#ffffff';" id="show_date_in"><?php echo $date_1;?></div>
			<input id="date_in" name="date_in" value="<?php echo $date_1;?>" size="25" type="hidden">
			<script type="text/javascript">
				Calendar.setup({
					inputField     :    	"date_in",
					daFormat       :    	"%Y-%m-%d",
					ifFormat       :    	"%Y-%m-%d",
					displayArea    :    	"show_date_in", 
					singleClick    :    	true,
					timeFormat		:    	24,
				});
			</script>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<a href="javascript:void(0)" class="update_link" id="update_link">Ver reporte</a>
		</td>
	</tr>
</table>
</form>
<br><br>
<table class="center_ filters_" width="90%">
	<tr>
		<th>Resultados</th>
	</tr>
	<tr>
		<td>
			<div id="result_table"></div>
		</td>
	</tr>
</table>

<br><br><hr>

<script type="text/javascript">
$(document).ready(function(){
	$(".update_link").click(function(event){
		showprogrammingReport();
	});
	$(".date_calendar").css("background","#FFF");
});
</script>