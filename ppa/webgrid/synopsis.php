<?
$slot = $_GET['slot'];
$output = "";
$sql = "SELECT chapter.id, chapter.movie, chapter.serie, chapter.special FROM slot_chapter, chapter " .
	"WHERE " .
	"chapter.id = slot_chapter.chapter AND " .
	"slot_chapter.slot = " . $slot;
	
$row = db_fetch_array( db_query( $sql ) );
$output = '<table class="synopsis_table" width="100%">';
if( $row )
{
	if( $row['movie'] != 0 )
	{
		$sql = "SELECT movie.title, movie.spanishtitle, movie.gender, movie.rated, movie.year, movie.actors, movie.director, " .
			"chapter.description " .
			"FROM chapter, movie " .
			"WHERE " .
			"chapter.movie = movie.id AND " .
			"chapter.id = "  . $row['id'];
			
		$row = db_fetch_array( db_query( $sql ) );

			$output .= '<tr><td class="title" colspan="2">' . $row['title'] . '<div class="subtitle">' . $row['spanishtitle'] . '&nbsp;</div></td></tr>' .
				'<tr><td colspan="2">' . $row['description'] . '&nbsp;</td></tr>' .
				'<tr><td><b>Director</b></td><td>' . $row['director'] . '&nbsp;</td></tr>' .
				'<tr><td><b>Actores</b></td><td>' . $row['actors'] . '&nbsp;</td></tr>' .
				'<tr><td><b>G&eacute;nero</b></td><td>' . $row['gender'] . '&nbsp;</td></tr>' .
				'<tr><td><b>Clasificaci&oacute;n</b></td><td>' . $row['rated'] . '&nbsp;</td></tr>' .
				'<tr><td><b>Año</b></td><td>' . $row['year'] . '&nbsp;</td></tr>';
	}
	else if( $row['serie'] != 0 )
	{
		$sql = "SELECT serie.title, serie.spanishtitle, serie.gender, serie.rated, serie.year, serie.starring, " .
			"chapter.description " .
			"FROM chapter, serie " .
			"WHERE " .
			"chapter.serie = serie.id AND " .
			"chapter.id = " . $row['id'];
			
		$row = db_fetch_array( db_query( $sql ) );

			$output .= '<tr><td class="title" colspan="2">' . $row['title'] . '<div class="subtitle">' . $row['spanishtitle'] . '&nbsp;</div></td></tr>' .
				'<tr><td colspan="2">' . $row['description'] . '&nbsp;</td></tr>' .
				'<tr><td><b>Actores</b></td><td>' . $row['starring'] . '&nbsp;</td></tr>' .
				'<tr><td><b>G&eacute;nero</b></td><td>' . $row['gender'] . '&nbsp;</td></tr>' .
				'<tr><td><b>Clasificaci&oacute;n</b></td><td>' . $row['rated'] . '&nbsp;</td></tr>' .
				'<tr><td><b>Año</b></td><td>' . $row['year'] . '&nbsp;</td></tr>';
	}	
	else if( $row['special'] != 0 )
	{
		$sql = "SELECT special.title, special.spanishtitle, special.gender, special.rated, special.starring, " .
			"chapter.description " .
			"FROM chapter, special " .
			"WHERE " .
			"chapter.special = special.id AND " .
			"chapter.id = " . $row['id'];
			
		$row = db_fetch_array( db_query( $sql ) );

			$output .= '<tr><td class="title" colspan="2">' . $row['title'] . '<div class="subtitle">' . $row['spanishtitle'] . '&nbsp;</div></td></tr>' .
				'<tr><td colspan="2">' . $row['description'] . '&nbsp;</td></tr>' .
				'<tr><td><b>Actores</b></td><td>' . $row['starring'] . '&nbsp;</td></tr>' .
				'<tr><td><b>G&eacute;nero</b></td><td>' . $row['gender'] . '&nbsp;</td></tr>' .
				'<tr><td><b>Clasificaci&oacute;n</b></td><td>' . $row['rated'] . '&nbsp;</td></tr>';
	}
}
else
{
	$sql = "SELECT client_channel.number, channel.name, channel.id ".
	       "FROM channel, client, client_channel ".
	       "WHERE client.id = client_channel.client ". 
	       "AND channel.id=client_channel.channel ".
	       "AND client.id = ". ID_CLIENT ." ".
	       "ORDER BY client_channel.number ";
	
	$result = db_query( $sql );
	
	while( $row = db_fetch_array($result) )
	{
		$channels[$row['id']]['name']   = $row['name'];
		$channels[$row['id']]['number'] = $row['number'];
	}

	$sql = "SELECT title FROM slot " .
		"WHERE " .
		"id = " . $slot;
		
	$row = db_fetch_array( db_query( $sql ) );
	
	$sql = "SELECT * " .
		"FROM slot " .
		"WHERE " .
		"date BETWEEN '" . date( 'Y-m-d' ) . "' AND '" . date( 'Y-m-d', time() + ( DAY * 4 ) ) . "' AND " .
    "channel IN (" . implode( ",", array_keys( $channels ) ) . ") AND ".
		"slot.title = '" . $row['title'] . "' " .
		"ORDER BY date, time";
		
	$result = db_query( $sql );
	
	while( $row = db_fetch_array( $result ) )
	{

		$output .= '<TR height="20">' .
			'<td align="center" ><b>' . $channels[$row['channel']]['number'] . '<br>' . $channels[$row['channel']]['name'] . '</b></td>' .
			'<td align="center" >' . $row['title'] . '</td>' .
			'<td align="center" >' . $row['date'] . '<br>' . to12H( $row['time'] ) . '</td>' .
			'</tr>';		
	}
}
$output .= '</table>';
echo $output;
?>