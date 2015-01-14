//la funcion es llamada cuando se solicita borrar un registro (expresion)
function delete_item(id_item){
	//genera numero aleatorio para evitar cache
	var randomnumber=Math.floor(Math.random()*100000);
	var response;
	//pide confirmacion antes de proceder con la eliminacion del registro
	var r = confirm("Confirme eliminar");
	if(r == true){
		//hace el llamado al archivo delete_item.php encargado de borrar un registro
		$.get('reports/delete_item.php?id=' + id_item + '&randnum=' + randomnumber, function(data){
			response = data;
			if(response == 'ok'){
				$("#" + id_item).remove().fadeIn('slow');
			}else{
				alert(response);
			}
			$("#update_link").click();
		});

	}
}
//la funcion es llamada cuando se solicita agregar un registro (expresion)
function add_item(allow){
	//genera numero aleatorio para evitar cache
	var randomnumber=Math.floor(Math.random()*100000);
	var response;
	var value;
	if(allow == 0){
		value = $("#txt_noallow").val();
	}else{
		value = $("#txt_allow").val();
	}
	//valida el campo de texto #txt_noallow y #txt_allow, no pueden estar vacios
	if(value == ''){
		alert('No puede agregar un registro vacio');
		return;
	}
	//hace el llamado al archivo add_item.php encargado de agregar un registro
	$.get('reports/add_item.php?val=' + value + "&allow=" + allow + '&randnum=' + randomnumber, function(data){
		response = data;
		//si la respuesta es un numero, se espera un numero que es el id del registro insertado, entonces actualiza los elementos en el cliente
		if(!isNaN(response)){
			$("#td_" + allow).prepend( function(index, html){
				var buffer = '';
				//***wraper**** ver archivo reports.func.php funcion wraper_regexp
				buffer += '<div class="reg_wraper" id="' + response + '"><div class="rep_expresion">' + value + '</div><div class="rep_delete">';
				if(allow == 1){
					buffer += '<a href="javascript:void(0)" onclick="delete_item(' + response + ')"><img src="reports/delete.gif"></a>';
				}else if(allow == 0){
					buffer += '<a href="javascript:void(0)" onclick="delete_item(' + response + ')"><img src="reports/delete.gif"></a>';
				}
				buffer += '</div>';
				buffer += '<div class="rep_enable"><a href="javascript:void(0)" onclick="enable_item(' + response + ')"><img id="img' + response + '" src="reports/enable.png"></a></div>';
				buffer += '</div>';
				return buffer;
			}).fadeIn('slow');
		}else{
			alert(response); //si la respuesta no es la esperada, hubo un problema y se muestra
		}
		$("#update_link").click();
	});
}
function enable_item(id_item){
	//genera numero aleatorio para evitar cache
	var randomnumber=Math.floor(Math.random()*100000);
	var response;
	//hace el llamado al archivo enable_item.php encargado de activar o desactivar un registro
	$.get('reports/enable_item.php?id=' + id_item + '&randnum=' + randomnumber, function(data){
		response = data;
		//si la respuesta es ok actualiza los elementos en el cliente
		if(response == 'ok'){
			if($("#img" + id_item).attr("src") == 'reports/enable.png'){
				var img_enable = 'reports/disable.png';
			}else{
				var img_enable = 'reports/enable.png';
			}
			$("#img" + id_item).attr("src",img_enable);
		}else{
			alert(response);//si la respuesta no es la esperada, hubo un problema y se muestra
		}
		$("#update_link").click();
	});
}
// funciones para reportes de test de programación
function showprogrammingReport(){
	var randomnumber=Math.floor(Math.random()*100000);
	var response;
	var header_selected;
	var date_selected;
	header_selected = $('#headersslt').val();
	date_selected = $('#date_in').val();
	//hace el llamado al archivo enable_item.php encargado de activar o desactivar un registro
	
	/*$.getJSON( 'ppa11/ajax/test_programming/find_test.php', {client: header_selected, date: date_selected, randnum: randomnumber }, function(data){ 
		
		var html_response = '';
		$('#result_table').append( "<div class='test_programming'><table id='t_result'></table></div>" );
		$.each( data , function(key, value){

			html_response += "<tr><td>"+ value['info'].number2 +"</td><td>"+ value['info'].name +"</td><td>";
			$.each( value['test'] , function(key2, value2){
				html_response += "<div>"+ value2.create_date +"</div>";
			});
			html_response += "</td></tr>";
			$('#t_result').html( html_response );
		});

	});*/

	$.get( 'ppa11/ajax/test_programming/print_find_test.php', {client: header_selected, date: date_selected, randnum: randomnumber }, function(data){ 
		response = data;
		if(response != ''){
			$('#result_table').html(data);
		}
	});

	/*$.get('reports/find_test_programming.php?header=' + header_selected + '&date=' + date_selected + '&randnum=' + randomnumber, function(data){
		response = data;
		//si la respuesta no es NaN actualiza los elementos
		
		if(response != ''){
			$('#result_table').html(data);
		}
	});	*/
}