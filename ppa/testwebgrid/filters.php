<?
	/* Main */
	$output = '<div id="ppa_filters_container">';
	$output .= '<h1>Buscar en televisión</h1>';
	$output .= '<p>Realice su búsqueda por uno o más criterios</p>';

	/* Category */
	$sql    = "SELECT distinct( _group ) FROM client_channel WHERE client = 65 ORDER BY 1";
	$result = db_query( $sql );
	$output .= '<div class="selects">';
	$output .= '<select id="category_filter">';
	$output .= '<option value="0">Categoría</option>';
	$output .= '<option value="Todas">Todas</option>';
	while( $row = db_fetch_array( $result ) )
	{
		$output .= '<option value="' . $row['_group'] . '">' . ucfirst( $row['_group'] ) . '</option>';
	}
	$output .= '</select>';

	/* Channel */
	$sql = "SELECT channel.* " .
		"FROM channel, client_channel " .
		"WHERE channel.id =  client_channel.channel AND " .
		"client_channel.client = 65 ORDER BY channel.name";
	$result = db_query( $sql );
	$output .= '<select id="channel_filter">';
	$output .= '<option value="0">Canal</option>';
	while( $row = db_fetch_array( $result ) )
	{
		$output .= '<option value="' . $row['id'] . '">' . ucfirst( $row['name'] ) . '</option>';
	}
	$output .= '</select>';
	$output .= '</div>';

	/* Actor */
	$output .= '<div class="inputs">';
	$output .= '<p><div class="title">Actor</div><div class="title">Título</div></p>';
	$output .= '<input class="filter_input" type="text" id="search_actor" name="search_actor" /><img id="go_search_actor" src="images/go.gif" />';

	/* Title */
	$output .= '<input class="filter_input" type="text" id="search_title" name="search_title" /><img id="go_search_title" src="images/go.gif" />';
	$output .= '</div>';
	$output .= '</div>';
	echo $output;
?>