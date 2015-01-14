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

<h5>Reportes de transmisiones de futbol en Colombia</h5>
<form name="filtersfrm" action="" method="post">
<table class="center_ filters_" width="90%">
	<tr>
		<th colspan="3">Filtros</th>
	</tr>
	<tr>
		<th>Fecha</th>
		<th>Expresiones permitidas</th>
		<th>Expresiones NO permitidas</th>
	</tr>
	<tr>
		<td>
			<div>Fecha inicial</div>
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
			<div>Fecha final</div>
			<div class="date_calendar" onmouseover="this.style.backgroundColor='#ffffff';" onmouseout="this.style.backgroundColor='#ffffff';" id="show_date_out"><?=date('Y-m-d')?></div>
			<input id="date_out" name="date_out" value="<?php print(date('Y-m-d'));?>" size="25" type="hidden">
			<script type="text/javascript">
				Calendar.setup({
					inputField     :    	"date_out",
					/*date           :    	new Date( <?=date('Y,n,d', $date)?>),*/
					daFormat       :    	"%Y-%m-%d",
					ifFormat       :    	"%Y-%m-%d",
					displayArea    :    	"show_date_out", 
					singleClick    :    	true,
					timeFormat		:    	24,
					/*onClose        :    onChangeDate*/
				});
			</script>
			<hr> <!-- Selección de partidos en vivo o no -->
			<input type="checkbox" name="live_futbol" id="live_futbol" onchange='$("#update_link").click();'> En vivo(Selec: Si / No selec: No)
		</td>
		<td id="td_1">
			<?php
				$allow = get_allowed_regexp();
				foreach($allow as $reg_exp){
					echo wraper_regexp($reg_exp->expresion, $reg_exp->id_regexp, $reg_exp->enable);
				}
				echo wraper_regexp('<input type="text" name="add_allow" id="txt_allow" size="25">', 'add_allow');
			?>
		</td>
		<td id="td_0">
			<?php
				$allow = get_allowed_regexp(false);
				foreach($allow as $reg_exp){
					echo wraper_regexp($reg_exp->expresion, $reg_exp->id_regexp, $reg_exp->enable);
				}
				echo wraper_regexp('<input type="text" name="add_noallow" id="txt_noallow" size="25">', 'add_noallow');
			?>
		</td>
	</tr>
</table>
</form>

<a href="javascript:void(0)" class="update_link" id="update_link">Actualizar resultados</a>
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

<script type="text/javascript">
$(document).ready(function(){
	$(".update_link").click(function(event){
		var randomnumber=Math.floor(Math.random()*100000);
		$("#result_table").load('reports/get_results_futbol_list.php?date_in=' + $('#date_in').val() + '&date_out=' + $('#date_out').val() + '&vivo=' + $('#live_futbol').is(':checked') + '&randnum=' + randomnumber);
	});
	$(".date_calendar").css("background","#FFF");
	$(".update_link").click();
});
</script>