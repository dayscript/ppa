<?
require_once("include/db.inc.php");
require_once("include/util.inc.php");
require_once("ppa/config.php");
$search_title   = trim( stripcslashes( $_GET['title'] ) );
$title          = "";
$spanishtitle   = "";
$found_programs = array();
if( $search_title != "" )
{
	$exact = false;
	if( ereg( "^\"(.*)\"$", $search_title )	)
	{
		$search_title = array( str_replace("\"", "", $search_title ) );
		$exact = true;
	}
	else 
	{
		$search_title = explode(" ", $search_title);
	}
	
	if( count( $search_title ) > 1 )
	{
		foreach( $search_title as $word )
		{
			if( strlen( $word ) > 3 ) 
			{
				$title        .= "title like '%" . addslashes( $word ) . "%' AND " ;
				$spanishtitle .= "spanishtitle like '%" . addslashes( $word ) . "%' AND " ;
			}
		}
	}
	else
	{
/*		$spanishtitle .= "spanishtitle = '" . addslashes( $search_title[0] ) . "' AND " ;
		$title .= "title = '" . addslashes( $search_title[0] ) . "' AND " ;		*/
		$spanishtitle .= "spanishtitle like '%" . addslashes( $search_title[0] ) . "%' AND " ;
		$title .= "title like '%" . addslashes( $search_title[0] ) . "%' AND " ;
	}
	
	$spanishtitle = substr( $spanishtitle, 0, -5 ); //strlen( " AND " ) = 5;
	$title = substr( $title, 0, -5 ); //strlen( " AND " ) = 5;
	
	if( $title != "" )
	{
		$sql = "SELECT id, title, spanishTitle " . 
			"FROM chapter " .
			"WHERE " .
			"( " . $title . " ) OR " .
			"( " . $spanishtitle . " )";
//			echo $sql;
		$query = db_query( $sql );
		while( $row = db_fetch_array( $query ) )
		{
	  		$found_programs[$row['id']] = $row['title'];
	  		$found_programs_spanish[$row['id']] = $row['spanishTitle'];
		}
	}
}
?>
<?
header( "Content-type: text/xml" );
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n<chapters>";
if( isset( $_GET['debug'] ) && $_GET['debug'] == "true" ) echo "<query>" . $sql . "</query>";
foreach ( $found_programs as $id => $eng )
{	
	echo "<title id=\"" . $id . "\">";
	echo str_replace( "&", "&amp;", $eng ) . " / " . str_replace( "&", "&amp;", $found_programs_spanish[ $id ] );
	echo "</title>";
}
echo "</chapters>";
?>