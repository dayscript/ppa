<?
$slot = $_GET['slot'];

$sql = "SELECT * FROM slot, channel " .
	"WHERE " .
	"slot.channel = channel.id AND " .
	"slot.id = " . $slot;
	
$row = db_fetch_array( db_query( $sql ) );

$channel['id']   = $row['id'];
$channel['title']= $row['title'];
$channel['logo'] = $row['logo'];
$channel['name'] = $row['name'];
$channel['date'] = $row['date'];
$channel['time'] = $row['time'];

$output = "";
$sql = "SELECT chapter.id, chapter.movie, chapter.serie, chapter.special FROM slot_chapter, chapter " .
	"WHERE " .
	"chapter.id = slot_chapter.chapter AND " .
	"slot_chapter.slot = " . $slot;
$output = "";	
$row = db_fetch_array( db_query( $sql ) );
if( $row )
{
//	$output = '<table class="synopsis_table" width="100%">';
	$output = '<table class="popup_more" width="100%">';
	$output .= '<tr><td colspan="2"><h1>' . $channel['title'] . '</h1></td></tr>';
	$output .= '<tr><td style="text-align:center"><img src="http://200.71.33.249/~ppa/ppa/' . $channel['logo'] . '" /></td>';
	$output .= '<td><div class="desc">';
	$output .= '<div><h3>Canal:</h3>' . $channel['name'] . '&nbsp</div>'; 
	$output .= '<div><h3>Fecha:</h3>' . $channel['date'] . '&nbsp</div>'; 
	$output .= '<div><h3>Hora:</h3>' . $channel['time'] . '&nbsp</div>'; 
	$output .= '</div></td></tr>';

	if( $row['movie'] != 0 )
	{
		$sql = "SELECT movie.title, movie.spanishtitle, movie.gender, movie.rated, movie.year, movie.actors, movie.director, " .
			"chapter.description " .
			"FROM chapter, movie " .
			"WHERE " .
			"chapter.movie = movie.id AND " .
			"chapter.id = "  . $row['id'];
			
		$row = db_fetch_array( db_query( $sql ) );

		$output .= '<tr><td colspan="2"><br>' . $row['description'] . '</td></tr>';
		$output .= '<tr><td colspan="2" class="attr">';
		$output .= '<div><h3>Género:</h3>' . $row['gender'] . '&nbsp</div>'; 
		$output .= '<div><h3>Clasificación:</h3>' . $row['rated'] . '&nbsp</div>'; 
		$output .= '<div><h3>Año:</h3>' . $row['year'] . '&nbsp</div>'; 
		$output .= '<div><h3>Director:</h3>' . $row['director'] . '&nbsp</div>'; 
		$output .= '<div><h3>Actores:</h3>' . $row['starring'] . '&nbsp</div>'; 
		$output .= '</td></tr>';
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
		
		$output .= '<tr><td colspan="2"><br>' . $row['description'] . '</td></tr>';
		$output .= '<tr><td colspan="2" class="attr">';
		$output .= '<div><h3>Género:</h3>' . $row['gender'] . '&nbsp</div>'; 
		$output .= '<div><h3>Clasificación:</h3>' . $row['rated'] . '&nbsp</div>'; 
		$output .= '<div><h3>Año:</h3>' . $row['year'] . '&nbsp</div>'; 
		$output .= '<div><h3>Actores:</h3>' . $row['starring'] . '&nbsp</div>'; 
		$output .= '</td></tr>';
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

		$output .= '<tr><td colspan="2"><br>' . $row['description'] . '</td></tr>';
		$output .= '<tr><td colspan="2" class="attr">';
		$output .= '<div><h3>Género:</h3>' . $row['gender'] . '&nbsp</div>'; 
		$output .= '<div><h3>Clasificación:</h3>' . $row['rated'] . '&nbsp</div>'; 
		$output .= '<div><h3>Actores:</h3>' . $row['starring'] . '&nbsp</div>'; 
		$output .= '</td></tr>';
	}
}
else
{
	$sql = "SELECT * " .
		"FROM slot " .
		"WHERE " .
		"date BETWEEN '" . date( 'Y-m-d' ) . "' AND '" . date( 'Y-m-d', time() + ( DAY * 4 ) ) . "' AND " .
    "slot.channel = " . $channel['id'] . " AND " .
		"slot.title = '" . addslashes( $channel['title'] ) . "' " .
		"ORDER BY date, time";
		
	$result = db_query( $sql );
	
//	$output = ;
	$output = '<table class="popup_more" width="100%">';
	$output .= '<tr><td colspan="2"><h1>' . $channel['title'] . '</h1></td></tr>';
	$output .= '<tr><td style="text-align:center"><img src="http://200.71.33.249/~ppa/ppa/' . $channel['logo'] . '" /></td>';
	$output .= '<td><div class="desc">';
	$output .= '<div><h3>Canal:</h3>' . $channel['name'] . '</div>'; 
	$output .= '<div><h3>Fecha:</h3>' . $channel['date'] . '</div>'; 
	$output .= '<div><h3>Hora:</h3>' . $channel['time'] . '</div>'; 
	$output .= '</div></td></tr>';
	$output .= '<tr><td colspan="2"><h2>Otras emisiones</h2></td></tr>';

	while( $row = db_fetch_array( $result ) )
	{

			$output .= '<tr>' .
			'<td>' . $row['date'] . '<br>' . '</td>' .
			'<td>' . to12H( $row['time'] ) . '</td>' .
			'</tr>';		
	}
}
$output .= '</table>';
echo $output;
?>