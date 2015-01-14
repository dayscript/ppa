<?php 

	$DEBUG = false;

	if(strpos( $_SERVER['HTTP_USER_AGENT'], "DayDev" ) !== false){
		define('DEVELOPER', true);
		echo 'desarrollador';
		 /*error_reporting(E_ALL);
		 ini_set("display_errors", 1);*/
	}else{
		define('DEVELOPER', false);
	}

	$client_search = isset($_GET['client']) ? $_GET['client'] : 10 ;

	$sql = "" .
		"SELECT id, name " . 
		"FROM client ORDER BY name "
		 ;

	$result20 = db_query( $sql, $DEBUG );

	while( $row20 = db_fetch_array( $result20 ) )
	{
		$client[] = $row20;
	}


	$sql = "SELECT GROUP_CONCAT( cc.number SEPARATOR ', ') as number2, cc.channel, cc.number, c.name 
			FROM client_channel as cc
			INNER JOIN channel as c ON cc.channel = c.id
			WHERE cc.client = ".$client_search.
			" AND  cc.number > 0 
			GROUP BY c.id".
			" ORDER BY cc.number ASC";	

	$result = db_query( $sql, $DEBUG );
	$result10 = db_query( $sql, $DEBUG );

	$channels_array = array();
	while( $row2 = db_fetch_array( $result10 ) ){
		$channels_array[] = $row2['channel'];
	}
	

	//otros canales;
	/*$sql2 = "SELECT c.id as channel, c.name 
			FROM channel as c 
			WHERE c.id NOT IN ( ".implode(',',$channels_array)." )
			GROUP BY c.id
			ORDER BY c.name ASC";
	$result2 = db_query( $sql2, $true );*/



	$sql3 = "SELECT * FROM CHK_test_programming WHERE Date_format( create_date, '%Y-%m-%d' ) = '".date("Y-m-d")."'";
	$result3 = db_query( $sql3, $DEBUG );

	$history_now = array();
	while( $row3 = db_fetch_array( $result3 ) ){
		$history_now[ $row3['id_channel'] ] = array(
			'check_test' => $row3['check_test'],
			'comment' => $row3['comment']
		);
	}
	


?>

<!-- includes -->
<script src="include/jquery.min.js" type="text/javascript"></script>

<script type="text/javascript">
	function asignarImagen( url, chapter, channel )  {

		if( !url )  {
			alert( 'Debe ingresar la URL a una imagen.' );
			return;
		}


		$('#img'+ channel + ' .imgsHolder').addClass( 'loading' );

		$.getJSON("ppa11/ajax/test_programming/setimage.php",
			{
				url: url,
				chapter: chapter,
				id: '<?= rand() ?>',
				id2: ( Math.random( ) * 100000 )
			},
			function( data ) {

				$('#img'+ channel + ' .imgsHolder').removeClass( 'loading' );

				if( data.added == '1' )
					$( '#imgC' + channel ).click( );
				else
					alert( 'La imagen no puede ser cargada!' );
			}, "json"
		);
		//http://t0.gstatic.com/images?q=tbn:ANd9GcT0UMdJgLeviV8n_hm5lKOHBiKE22cdO8LG539NdeLm0NctMdOk8A

	}
	$(document).ready(function(){

		$("select[id='client']").change(function(){
			window.location="/ppa/ppa11.php?location=programming&prog=yes&client=" + $(this).val();
		});

		//buscar programacion de un canal
		$("img[class='analysis']").click(function(){
			$('#loading__'+ $(this).attr('value') ).html("<img src='ppa11/images/loading6.gif' alt='cargando...'/>");
			$.getJSON("ppa11/ajax/test_programming/find_slot.php",
			  {
			    id_channel: $(this).attr('value'),
			    id: '<?= rand() ?>'
			  },
			  function(data) {
				$('#loading__'+ data.channel ).html("");

				$("div[id^=slot_check_]").html('');

				$('#slot_check_'+ data.channel ).html('');
				$('#slot_check_'+ data.channel ).append("<div><textarea id='comment_"+ data.channel  +"'></textarea></div>");
				$('#slot_check_'+ data.channel ).append( "<img class='save_on' value='"+ data.channel +"' src='ppa11/images/check_on.png' title='Correcto' />&nbsp;" );
				$('#slot_check_'+ data.channel ).append( "<img class='save_off' value='"+ data.channel +"' src='ppa11/images/check_off.png'  title='Incorrecto' />&nbsp;" );
				$('#slot_check_'+ data.channel ).append( "<img class='save_correct' value='"+ data.channel +"' src='ppa11/images/corregido.png'  title='Corregido' />&nbsp;" );

				$("div[id^=slot_channel_]").html('');
				$('#slot_channel_'+ data.channel ).html( "<table><tr><th colspan='3'>"+ data.title +"</th></tr><tr><td>Inicio:</td><td>"+ data.hora_inicio +"</td><td id='img"+ data.channel +"' rowspan='2'></td></tr><tr><td>Fin:<td>"+ data.hora_fin +"</td></tr></table>" );

				var imgHolder = $('#img'+ data.channel);
				imgHolder.append( '<a href="http://www.google.com/search?nr=&tbm=isch&q=' + encodeURIComponent( data.title ) + '" target="PPA_IMG">Buscar im&aacute;genes en Google &raquo;</a>' );

				var images = '<div class="imgsHolder">';
				var imageActive = false;
				for( var c = 0; c < data.images.length; c ++ )  {
					images += '<img src="/ppa/chapter_images/260x260/' + data.images[c].id_chapter + '.jpg?' + ( Math.random( ) * 1000 ) + '" ' + ( data.images[c].id_chapter == data.chapter ? 'class="active"' : '' ) + ' />'
					imageActive = imageActive || ( data.images[c].id_chapter == data.chapter );
				}

				if( data.images.length == 0 )
				images += '<em> No hay im&aacute;genes</em>';

				images += '</div>';
				imgHolder.append( images );

				  if( data.chapter )
					imgHolder.append( '<input type="text" name="nImg_' + data.chapter + '" id="nImg_' + data.chapter + '" value="" /> <a href="#" onclick="asignarImagen( $(\'#nImg_' + data.chapter + '\').val(), ' + data.chapter + ', ' + data.channel + ' );return false;" >Asignar esta imagen &raquo;</a>' );
				 else
					imgHolder.append( '<b>Por alguna raz&oacute;n no se encontr&oacute; "chapter" y no se puede asignar imagen.</b>' );
			  });
		});

		//guardar
		$("body").delegate("img[class^='save_']","click",function(){
			var commet_check = $('#comment_'+ $(this).attr('value') ).val();

			$('#loading__'+ $(this).attr('value') ).html("<img src='ppa11/images/loading6.gif' alt='cargando...'/>");

			$("div[id^=slot_check_]").html('');
			$("div[id^=slot_channel_]").html('');

			var save_type = 0;
			if( $(this).attr('className') == 'save_on' ){
				save_type = 1;
			}
			if( $(this).attr('className') == 'save_correct' ){
				save_type = 2;
			}


			$("div[id=comment_old_"+$(this).attr('value')+"]").html( commet_check );
			$.getJSON("ppa11/ajax/test_programming/save_test.php", {
				id_channel: $(this).attr('value'),
				save_type: save_type,
				comment: commet_check,
				id: '<?= rand() ?>'

			},function(data) {
				$('#loading__'+ data.channel ).html("");
				$('#row_channel_'+ data.channel ).removeClass('test_err');
				$('#row_channel_'+ data.channel ).removeClass('test_ok');
				$('#row_channel_'+ data.channel ).removeClass('test_correct');
				if( save_type == 1){
					$('#row_channel_'+ data.channel ).addClass('test_ok');
				}
				if( save_type == 0 ){
					$('#row_channel_'+ data.channel ).addClass('test_err');
				}
				if( save_type == 2 ){
					$('#row_channel_'+ data.channel ).addClass('test_correct');
				}
			});

		});

	});
</script>

<!-- contenido html -->
<div class='test_programming'>
<table cellpadding="0" cellspacing="1" border="0" width="100%">
	<colgroup>
		<col width="10%" />
		<col width="22%" />
		<col width="20%" />
		<col width="48%" />
	</colgroup>
	<tr>
		<td colspan="2">
			<h2>Test de programaci&oacute;n</h2>
			<div>
				Fecha:<strong><?php echo date("Y-m-d") ?></strong>
			</div>
		</td>
		<td colspan="2" style="text-align:right;" id="conventions">
			<table>
				<tr>
					<td valign="top" width="50%">
						<h4>Bordes canales</h4>
						<div><span style="background-color:green;">&nbsp;</span> Correcto</div>
						<div><span style="background-color:#B1200A;">&nbsp;</span> Incorrecto</div>
						<div><span style="background-color:#FE850C;">&nbsp;</span> Corregido</div>
					</td>
					<td valign="top" width="50%">
						<h4 style="white-space: nowrap">iconos de ejecuci&oacute;n</h4>
						<div style="line-height: 20px">
							<img style="vertical-align: middle" src='ppa11/images/check_on.png' /> Correcto
							<br>
							<img style="vertical-align: middle" src='ppa11/images/check_off.png' /> Inconrrecto
							<br>
							<img style="vertical-align: middle" src='ppa11/images/corregido.png' /> Corregido
							<br>
							<img style="vertical-align: middle" src="ppa11/images/analysis.png" /> Actualizar
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="text-align:right;">
			Cabecera
		</td>
		<td colspan="2">
			<select id="client">
				<?php foreach ($client as $value): ?>
					<option <?php echo $value['id'] == $client_search ? "selected='selected'":""; ?> value="<?php echo $value['id'] ?>">
						<?php echo $value['name'] ?>
					</option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>


	<tr>
		<th width='100'>#</th>
		<th>Nombre</th>
		<th>Comprobar <br />Programaci&oacute;n</th>
		<th></th>
		<th width='40' style='background-color:#fff;'></th>
	</tr>
	<?php while( $row = db_fetch_array( $result ) ): ?>

	<?php
			$class_default_now = '';
			if( isset( $history_now[$row['channel']] ) ){
				$estado = $history_now[$row['channel']]['check_test'];
				switch ( $estado ) {
					case '0': $class_default_now = 'test_err'; break;
					case '1': $class_default_now = 'test_ok'; break;
					case '2': $class_default_now = 'test_correct'; break;
					default: break;
				}
			}
	?>



		<tr id='row_channel_<?= $row['channel'] ?>' class="<?php echo $class_default_now ?>">
			<td><?= $row['number2'] ?></td>
			<td><strong><?= $row['name'] ?></strong></td>
			<td align='center' width='100'>
				<img class='analysis' id="imgC<?= $row['channel'] ?>" value='<?= $row['channel'] ?>' src='ppa11/images/analysis.png' alt='Verificar'/>
				<div style=' margin:5px;' id='slot_check_<?= $row['channel'] ?>'>
				<div>
			</td>
			<td width='250'>
				<?php if( isset( $history_now[$row['channel']] ) && $history_now[$row['channel']]['comment'] != '' ): ?>
					<div class='comment_old' id='comment_old_<?php echo $row['channel'] ?>'><?php echo utf8_decode( $history_now[$row['channel']]['comment'] ) ?></div>
				<?php endif; ?>

				<div id='slot_channel_<?= $row['channel'] ?>'>
				</div>
			</td>
			<td style='border:none !important; background-color:#fff;' id='loading__<?= $row['channel'] ?>'>
			</td>
		</tr>
	<?php endwhile; ?>
	<!--
	<tr>
		<td colspan='4' style='background-color:#fff;'>
			<br /><br /><br /><br />
			<h2>Otros Canales</h2>
		</td>
	</tr>
	<tr>
		<th>#</th>
		<th>Nombre</th>
		<th>Comprobar <br />Programaci&oacute;n</th>
		<th></th>
		<th width='40' style='background-color:#fff;'></th>
	</tr>
	<?php while( $row3 = db_fetch_array( $result2 ) ): ?>
		<tr id='row_channel_<?= $row3['channel'] ?>'>
			<td>&nbsp;</td>
			<td><strong><?= $row3['name'] ?></strong></td>
			<td align='center' width='100'>
				<img class='analysis' value='<?= $row3['channel'] ?>' src='ppa11/images/analysis.png' alt='Verificar'/>
				<div style='float:left; margin-left:5px;' id='slot_check_<?= $row3['channel'] ?>'>
				<div>
			</td>
			<td width='250'>
				<div id='slot_channel_<?= $row3['channel'] ?>'>
				</div>
			</td>
			<td style='border:none !important; background-color:#fff;' id='loading__<?= $row3['channel'] ?>'>
			</td>
		</tr>
	<?php endwhile; ?>
	-->

</table>
</div>