<?php
	
	//El archivo es llamado por report_futbol_list.php, se encarga de armar la tabla con resultados según filtros
	//las expresiones para evaluar através de regexp se cargan de la base de datos y las fechas se envian a este archivo por get

	require("mysql.class.php");
	require("reports.func.php");
	
	//comprobamos que exista una fecha de lo contrario mostramos error y salimos
	if(isset($_GET['date_in']) && isset($_GET['date_in']) && $_GET['date_in'] != '' && $_GET['date_out'] != ''){

		$live_futbol = $_GET['vivo'];
		$date_in = $_GET['date_in'];
		$date_out = $_GET['date_out'];
		$date_in_array = explode("-",$date_in);
		$date_out_array = explode("-",$date_out);
		$date_in_mk = mktime(0,0,0,$date_in_array[1],$date_in_array[2],$date_in_array[0]);
		$date_out_mk = mktime(0,0,0,$date_out_array[1],$date_out_array[2],$date_out_array[0]);
		if($date_in_mk >= $date_out_mk){ //la fecha desde no puede ser mayor que la fecha hasta
			echo 'Errores en la fecha, revise';
			exit;
		}
	}else{
		echo 'Errores en la fecha, revise';
		exit;
	}

	//traemos las expresiones a evaluar a través de regexp que esten activas
	$res_sql = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
	$res = $res_sql->get_results("SELECT * FROM REP_regexp WHERE enable='1'");
	$res_sql->__Close__();
	$condition_allow = ''; //las expresiones serán almacenadas en estas variables
	$condition_noallow = '';
	
	//valores reservados para regexp son escapados
	foreach($res as $expresions){
		if($expresions->allow == '1'){
			$condition_allow .= mysql_regexp_escape_string($expresions->expresion).'|';
		}else{
			$condition_noallow .= mysql_regexp_escape_string($expresions->expresion).'|';
		}
	}
	$condition_allow = substr($condition_allow,0,-1);
	$condition_noallow = substr($condition_noallow, 0,-1);

	// Arma la estructura del SQL para enviarle a mysql
	$sql = "
		SELECT slot.*, channel.*
		FROM slot, channel, client_channel
		WHERE slot.date >= '".$date_in."'
		AND slot.date <= '".$date_out."'";
		//si las condiciones a usar en regexp están vacias no se incluye en el sql
		if($condition_allow != '') $sql .= "AND slot.title REGEXP '".$condition_allow."' ";
		if($condition_noallow != '') $sql .= "AND slot.title NOT REGEXP '".$condition_noallow."' ";
		$sql .= "AND slot.channel = channel.id ";
		if($live_futbol == 'true') $sql .= "AND slot.new_episode = 1 "; //si live_checkbox seleccionado filtra los que sean en vivo
		$sql .= "
			AND channel.id = client_channel.channel 
			AND client_channel.client = 67 
			ORDER BY channel.name, slot.date, slot.time, slot.title 
		";
	
	$list_sql = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
	$list = $list_sql->get_results($sql);
	$list_sql->__Close__();

	if(!$list){ //si no se encuentran resultados se envia al navegador la respuesta y se termina la ejecución del script
		echo 'no se encontraron resultados';
		exit;
	}

	//buffer de salida para los resultados
	$buffer_list = '';
	//$buffer_list .= '<strong>'.$sql.'</strong><br>';
	//Arma la tabla de resultados, la almacena y luego la muestra
	$buffer_list .= '<div>Resultados para fecha entre: <strong>'.$date_in.'</strong> y <strong>'.$date_out.'</strong></div>';
	$buffer_list .= '<table class="center_2 list_">';
	$buffer_list .= '<tr>';
	$buffer_list .= '<th>date</th><th>time</th><th>title</th><th>name</th><th>Canal</th><th>Vivo</th>';
	$buffer_list .= '</tr>';
	foreach($list as $item){
		if($item->new_episode == '1') $vivo_item = 'Si'; else $vivo_item = 'No';
		$buffer_list .= '<tr>';
		$buffer_list .= '<td>';
		$buffer_list .= $item->date;
		$buffer_list .= '</td>';
		$buffer_list .= '<td>';
		$buffer_list .= $item->time;
		$buffer_list .= '</td>';
		$buffer_list .= '<td>';
		$buffer_list .= $item->title;
		$buffer_list .= '</td>';
		$buffer_list .= '<td>';
		$buffer_list .= $item->name;
		$buffer_list .= '</td>';
		$buffer_list .= '<td>';
		$buffer_list .= $item->channel;
		$buffer_list .= '</td>';
		$buffer_list .= '<td>';
		$buffer_list .= $vivo_item;
		$buffer_list .= '</td>';
		$buffer_list .= '</tr>';
	}
	$buffer_list .= '</table>';
	echo $buffer_list; //imprime
?>