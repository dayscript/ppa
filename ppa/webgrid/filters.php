<!--style type="text/css">
<!--
.style1 {font-size: 9px}
.style2 {font-size: 9}
-->
<!--/style-->
							    <div class="espacio">
									<?
									$sql    = "SELECT distinct( _group ) FROM client_channel WHERE client = 65 ORDER BY 1";
									$result = db_query( $sql );
									?>
									<select name="id_categorias" id="id_categorias" onChange="cambio_canal(this.options[this.selectedIndex].value);" style="width:179px; height:20px;">
									  <option value="0">Elija una categor&iacute;a</option>
									<?
									while( $row = db_fetch_array( $result ) )
									{
									?>
										<option value="<?=$row['_group']?>"><?=ucfirst( $row['_group'] )?></option>
									<?
									}
									?>
									</select>
								</div>
							    <div class="espacio" id="canales">
									<select style="width:179px; height:20px;">
										<option>Elija un canal</option>
									</select>
								</div>






<?
	/* Main */
/*	$output = '<div id="ppa_filters_container">';
	/* Title */
/*	$output .= '<div class="filters_title">Filtros</div>';

	/* Category */
/*	$sql    = "SELECT distinct( _group ) FROM client_channel WHERE client = 65 ORDER BY 1";
	$result = db_query( $sql );
	$output .= '<div class="filter_title">Por categor&iacute;a</div>';
	$output .= '<select id="category_filter">';
	$output .= '<option value="0">-- Elija una --</option>';
	while( $row = db_fetch_array( $result ) )
	{
		$output .= '<option value="' . $row['_group'] . '">' . ucfirst( $row['_group'] ) . '</option>';
	}
	$output .= '</select>';

	/* Channel */
/*	$sql = "SELECT channel.* " .
		"FROM channel, client_channel " .
		"WHERE channel.id =  client_channel.channel AND " .
		"client_channel.client = 65 ORDER BY channel.name";
	$result = db_query( $sql );
	$output .= '<div class="filter_title">Por canal</div>';
	$output .= '<select id="channel_filter">';
	$output .= '<option value="0">-- Elija uno --</option>';
	while( $row = db_fetch_array( $result ) )
	{
		$output .= '<option value="' . $row['id'] . '">' . ucfirst( $row['name'] ) . '</option>';
	}
	$output .= '</select>';

	/* Actor */
/*	$output .= '<div class="filter_title">Por actor</div>';
	$output .= '<input class="filter_input" type="text" id="search_actor" name="search_actor" /><input id="go_search_actor" class="go_button" type="button" name="go" value="IR" />';

	/* Title */
/*	$output .= '<div class="filter_title">Por t&iacute;tulo</div>';
	$output .= '<input class="filter_input" type="text" id="search_title" name="search_title" /><input id="go_search_title" class="go_button" type="button" name="go" value="IR" />';
	$output .= '</div>';
	echo $output;*/
?>