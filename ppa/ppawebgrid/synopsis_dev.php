<?
$CHAPTER_IMAGES_PATH = "chapter_images/260x260";
$CHAPTER_IMAGES_URL  = "http://" . $URL_PPA . "/ppa/chapter_images/260x260";
$CHANNEL_LOGOS_URL   = "http://" . $URL_PPA . "/ppa/channel_images/300x300";
$INTER_PROMOS_URL   = "http://" . $URL_PPA . "/ppa/inter_promo_images";
//http://190.27.201.2/ppa/channel_images/300x300

$slot = $_GET['slot'];

$sql = "SELECT * FROM slot, channel " .
	"WHERE " .
	"slot.channel = channel.id AND " .
	"slot.id = " . $slot;
	
$row = db_fetch_array( db_query( $sql ) );
$fixedTime = fixGmtTime($row["date"] . " " . $row["time"], GMT);
$ts = strtotime($fixedTime["date"] . " " . $fixedTime["time"]);

$channel['id']   = $row['id'];
$channel['title']= $row['title'];
$channel['logo'] = $row['logo'];
$channel['name'] = $row['name'];

$output = "";
$sql = "SELECT chapter.id, chapter.movie, chapter.serie, chapter.special FROM slot_chapter, chapter " .
	"WHERE " .
	"chapter.id = slot_chapter.chapter AND " .
	"slot_chapter.slot = " . $slot;
$output = "";	
$row = db_fetch_array( db_query( $sql ) );

if(file_exists($CHAPTER_IMAGES_PATH . "/" . $row["id"] . ".jpg") )
	$img_src = $CHAPTER_IMAGES_URL . "/" . $row["id"] . ".jpg";
else if($PROVIDER == INTER)
	$img_src = $INTER_PROMOS_URL . "/" . rand(1,3) . ".jpg";
else
	$img_src = $CHANNEL_LOGOS_URL . "/" . $channel['id'] . ".jpg";

/*** start printing stuff ***/
$output .= '<div class="image"><img src="' . $img_src . '" /></div>';
$output .= '<div class="synopsis"><h1>' . $channel['title'] . '</h1>';
$output .= '<div class="attr"><h3>Canal:</h3><div>' . $channel['name'] . ' </div></div>'; 
$output .= '<div class="attr"><h3>Fecha:</h3><div>' . date("m/d/y", $ts ) . ' </div></div>'; 
$output .= '<div class="attr"><h3>Hora:</h3><div>' . date("h:i a", $ts ) . ' </div></div>'; 
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

		$output .= '<div class="desc">' . $row['description'] . '</div>';
		$output .= '<tr><td colspan="2" class="attr">';
		$output .= '<div class="attr"><h3>Género:</h3><div>' . $row['gender'] . ' </div></div>'; 
		$output .= '<div class="attr"><h3>Clasificación:</h3><div>' . $row['rated'] . ' </div></div>'; 
		$output .= '<div class="attr"><h3>Año:</h3><div>' . $row['year'] . ' </div></div>'; 
		$output .= '<div class="attr"><h3>Director:</h3><div>' . $row['director'] . ' </div></div>'; 
		$output .= '<div class="attr"><h3>Actores:</h3><div>' . $row['actors'] . ' </div></div>'; 
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
		
		$output .= '<div class="desc">' . $row['description'] . '</div>';
		$output .= '<div class="attr"><h3>Género:</h3><div>' . $row['gender'] . ' </div></div>'; 
		$output .= '<div class="attr"><h3>Clasificación:</h3><div>' . $row['rated'] . ' </div></div>'; 
		$output .= '<div class="attr"><h3>Año:</h3><div>' . $row['year'] . ' </div></div>'; 
		$output .= '<div class="attr"><h3>Actores:</h3><div>' . $row['starring'] . ' </div></div>'; 
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

		$output .= '<div class="desc">' . $row['description'] . '</div>';
		$output .= '<div class="attr"><h3>Género:</h3><div>' . $row['gender'] . ' </div></div>'; 
		$output .= '<div class="attr"><h3>Clasificación:</h3><div>' . $row['rated'] . ' </div></div>'; 
		$output .= '<div class="attr"><h3>Actores:</h3><div>' . $row['starring'] . ' </div></div>'; 
	}
}
/*
$sql = "SELECT * " .
	"FROM slot " .
	"WHERE " .
	"date >= '" . date( 'Y-m-d' ) . "' AND " .
  "slot.channel = " . $channel['id'] . " AND " .
	"slot.title = '" . addslashes( $channel['title'] ) . "' " .
	"ORDER BY date, time LIMIT 0, 5";
	
$result = db_query( $sql );

if( db_numrows($result) > 0 )
	$output .= '<div>Otras emisiones</div>';

while( $row = db_fetch_array( $result ) )
{

		$output .= '<div>' .
		'<div>' . $row['date'] . '<br/>' . '</div>' .
		'<div>' . to12H( $row['time'] ) . '</div>' .
}*/

$output .= '</div>';
echo $output;
?>