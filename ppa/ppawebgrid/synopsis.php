<?
$CHAPTER_IMAGES_PATH = "chapter_images/70x70";
$CHAPTER_IMAGES_URL  = "http://" . $URL_PPA . "/ppa/chapter_images/70x70";
$CHANNEL_LOGOS_URL   = "http://" . $URL_PPA . "/ppa/ppa";

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

if(file_exists($CHAPTER_IMAGES_PATH . "/" . $row["id"] . ".jpg") )
	$img_src = $CHAPTER_IMAGES_URL . "/" . $row["id"] . ".jpg";
else
	$img_src = $CHANNEL_LOGOS_URL . "/" . $channel['logo'];

/*** start printing stuff ***/
$output = '<table class="popup_more" width="100%">';
$output .= '<tr><td colspan="2"><h1>' . $channel['title'] . '</h1></td></tr>';
$output .= '<tr><td style="text-align:center"><img src="' . $img_src . '" /></td>';
$output .= '<td><div class="desc">';
$output .= '<div><h3>Canal:</h3>' . $channel['name'] . ' </div>'; 
$output .= '<div><h3>Fecha:</h3>' . $channel['date'] . ' </div>'; 
$output .= '<div><h3>Hora:</h3>' . $channel['time'] . ' </div>'; 
$output .= '</div></td></tr>';
/*** end printing stuff ***/

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

		$output .= '<tr><td colspan="2"><br/>' . $row['description'] . '</td></tr>';
		$output .= '<tr><td colspan="2" class="attr">';
		$output .= '<div><h3>Género:</h3>' . $row['gender'] . ' </div>'; 
		$output .= '<div><h3>Clasificación:</h3>' . $row['rated'] . ' </div>'; 
		$output .= '<div><h3>Año:</h3>' . $row['year'] . ' </div>'; 
		$output .= '<div><h3>Director:</h3>' . $row['director'] . ' </div>'; 
		$output .= '<div><h3>Actores:</h3>' . $row['actors'] . ' </div>'; 
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
		
		$output .= '<tr><td colspan="2"><br/>' . $row['description'] . '</td></tr>';
		$output .= '<tr><td colspan="2" class="attr">';
		$output .= '<div><h3>Género:</h3>' . $row['gender'] . ' </div>'; 
		$output .= '<div><h3>Clasificación:</h3>' . $row['rated'] . ' </div>'; 
		$output .= '<div><h3>Año:</h3>' . $row['year'] . ' </div>'; 
		$output .= '<div><h3>Actores:</h3>' . $row['starring'] . ' </div>'; 
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

		$output .= '<tr><td colspan="2"><br/>' . $row['description'] . '</td></tr>';
		$output .= '<tr><td colspan="2" class="attr">';
		$output .= '<div><h3>Género:</h3>' . $row['gender'] . ' </div>'; 
		$output .= '<div><h3>Clasificación:</h3>' . $row['rated'] . ' </div>'; 
		$output .= '<div><h3>Actores:</h3>' . $row['starring'] . ' </div>'; 
		$output .= '</td></tr>';
	}
}
$sql = "SELECT * " .
	"FROM slot " .
	"WHERE " .
	"date >= '" . date( 'Y-m-d' ) . "' AND " .
  "slot.channel = " . $channel['id'] . " AND " .
	"slot.title = '" . addslashes( $channel['title'] ) . "' " .
	"ORDER BY date, time LIMIT 0, 5";
	
$result = db_query( $sql );

if( db_numrows($result) > 0 )
	$output .= '<tr><td colspan="2" class="attr"><b>Otras emisiones</b></td></tr>';

while( $row = db_fetch_array( $result ) )
{

		$output .= '<tr>' .
		'<td>' . $row['date'] . '<br/>' . '</td>' .
		'<td>' . to12H( $row['time'] ) . '</td>' .
		'</tr>';		
}

$output .= '</table>';
echo $output;
?>