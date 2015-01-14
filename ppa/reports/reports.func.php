<?php
	//obtiene los registros, usa el parámetro allow para determinar si obtener los que se permiten o los que no
	function get_allowed_regexp($allow = true){

		$regexp_sql = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
		
		if($allow){
			//obtiene los registros de expresiones que se permiten
			$regexp_res = $regexp_sql->get_results("SELECT * FROM REP_regexp WHERE allow='1'");
		}else{
			//obtiene los registros de expresiones que NO se permiten
			$regexp_res = $regexp_sql->get_results("SELECT * FROM REP_regexp WHERE allow='0'");
		}
		
		$regexp_sql->__Close__();

		return $regexp_res;

	}
	//***wraper****
	//Inserta cada registro de expresiones dentro de un contenedor y añade las características funcionales, imágenes y llamados a javascript
	function wraper_regexp($reg_exp, $id_reg, $enable = ''){
		$buffer = '';
		$buffer .= '
			<div class="reg_wraper" id="'.$id_reg.'">
				<div class="rep_expresion">'.$reg_exp.'</div>
				<div class="rep_delete">';
		if($id_reg == 'add_allow'){
			//si es el elemento de input text, agrega la imagen de añadir
			$buffer .= '<a href="javascript:void(0)" onclick="add_item(1)"><img src="reports/add.png"></a>';
		}elseif($id_reg == 'add_noallow'){
			//si es el elemento de input text, agrega la imagen de añadir
			$buffer .= '<a href="javascript:void(0)" onclick="add_item(0)"><img src="reports/add.png"></a>';
		}else{
			//si es un elemento de registro de expresion agrega la img de borrar y especifica el id
			$buffer .= '<a href="javascript:void(0)" onclick="delete_item('.$id_reg.');"><img src="reports/delete.gif"></a>';
		}
		$buffer .= '</div>';
		if($enable != ''){
			//si el elemento está como activo en la base de datos añade la img de activo/inactivo y su respectivo id para desactivar/activar
			if($enable == '1') $img = 'enable.png'; else $img = 'disable.png';
			$buffer .= '<div class="rep_enable"><a href="javascript:void(0)" onclick="enable_item('.$id_reg.')"><img id="img'.$id_reg.'" src="reports/'.$img.'"></a></div>';
		}
		$buffer .= '</div>';
		return $buffer;
	}

	function check_enable_status($id_item){
		//comprueba el estado de un registro de expresión, si esta activo retorna el valor para que quede inactivo y viceversa
		$status_sql = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
		$status = $status_sql->get_row("SELECT * FROM REP_regexp WHERE id_regexp='".$id_item."'");
		$status_sql->__Close__();
		if($status->enable == '0'){
			return '1';
		}else{
			return '0';
		}
	}

	function mysql_regexp_escape_string($string){
		//esta funcion escapa los carácteres especiales de expresiones regulares para que la consulta no los tome como tal sino
		//como cadenas de texto simples

		//array que contiene los carácteres especiales
    	$special_chars = array('-','*', ',', '?', '+', '[', ']', '(', ')', '{', '}', '^', '$', '|', '\\', '.');
    	$replacements = array();

    	foreach ($special_chars as $special_char){
        $replacements[] = '\\' . $special_char;
    	}

    	return str_replace($special_chars, $replacements, $string);
	}
	// la funcion encuentra las cabeceras, tabla clientes de la base de datos de ppa
	function findClientsheaders(){
		$headers_sql = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
		$headers = $headers_sql->get_results("SELECT id, name FROM client ORDER BY name");
		$headers_sql->__Close__();

		$headers_array = array();
		if( !empty($headers) ){
			foreach( $headers as $header ){
				$headers_array[$header->id] = $header->name;
			}
		}

		return $headers_array;
	}

?>