<?
header("Content-type: text/xml; charset=ISO-8859-1");
define("DB_HOST", "localhost");
define("DB_NAME", "ppa");
define("DB_USER", "ppa");
define("DB_PASS", "kfc3*9mn");
define("DB_DEBG", false);

include("ppa11/class/Highlight.class.php");
include("ppa11/class/dao/DataBase.class.php");
$IMAGEPATH = "http://" . $URL_PPA . "/ppa/chapter_images";

if( $_GET['mode'] == "random" )
{
	$db = new DataBase( DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_DEBG );
	$q = isset($_GET['q']) ? ($_GET['q']>0 ? $_GET['q']:3):3;
	$d = isset($_GET['d']) ? (ereg("[0-9]{4}-[0-9]{2}", $_GET['d']) ? $_GET['d']:date("Y-m-d")):date("Y-m-d");

/*	$sql = "SELECT id_slot FROM highlights, slot " .
		"WHERE slot.id = highlights.id_slot " .
		"AND slot.date like '" . $d ."-%' " .
		"ORDER BY RAND() limit 0, " . $q;*/
//	$sql = "SELECT h.id_highlight, ch.name, s.title, s.time, s.date, c.description sdesc, c.id, c.serie, c.movie, c.special, c.points FROM " . 
	$sql = "SELECT h.id_highlight, ch.name, s.title, s.time, s.date, c.description sdesc, c.id, c.points FROM " . 
		"highlights h, channel ch, slot s, chapter c, slot_chapter sc, client_channel cc WHERE " .
		"s.id = h.id_slot AND " .
		"s.id = sc.slot AND " .
		"s.channel = ch.id AND " .
		"cc.channel = ch.id AND " .
		"cc.client = " . $ID_CLIENT . " AND " .
		"c.id = sc.chapter AND " .
		"h.type = 'all' AND " .
//		"s.date like '" . $d ."-%' " .
		"s.date >= '" . $d ."' " .
		"ORDER BY RAND() limit 0, " . $q;

	$db->query( $sql );

/*	echo '<?xml version="1.0" encoding="ISO-8859-1"?>';*/
	$dom = new DOMDocument('1.0', 'ISO-8859-1');
	$dRoot = $dom->createElement("list");
	$dom->appendChild($dRoot);

	while($row = $db->fetchArray())
	{
		$hEl = $dom->createElement("hlight");
		$dRoot->appendChild( $hEl );
		$hEl->setAttribute("score", $row["points"]);
		$hEl->setAttribute("id", $row["id_highlight"]);
		$hEl->setAttribute("time", $row["time"]);
		$hEl->setAttribute("date", $row["date"]);

		$title = $dom->createElement("tit");
		$desc  = $dom->createElement("desc");
		$img   = $dom->createElement("img");
		$chn   = $dom->createElement("chn");

		$hEl->appendChild( $title );
		$hEl->appendChild( $desc );
		$hEl->appendChild( $img );
		$hEl->appendChild( $chn );

		$text = $dom->createTextNode(utf8_encode($row["title"]));
		$title->appendChild( $text );
		
		$text = $dom->createTextNode(utf8_encode($row["sdesc"]));
		$desc->appendChild( $text );
		
		$text = $dom->createTextNode($IMAGEPATH . "/70x70/" . $row["id"] . ".jpg");
		$img->appendChild( $text );
		
		$text = $dom->createTextNode(utf8_encode($row["name"]));
		$chn->appendChild( $text );
		
		$hEl->appendChild( $title );
	}
	echo $dom->saveXML();
}
else if( $_GET['mode'] == "highlight" && isset($_GET["id"]))
{
	$h = new Highlight();
	$h->setImageUrl("http://" . $URL_PPA . "/ppa/chapter_images" );
	$h->setIdHighLight( $_GET["id"] );
	$h->setIdClient( 65 );
	$h->load();

	$dom = new DOMDocument('1.0', 'ISO-8859-1');
	$dRoot = $dom->createElement("list");
	$dom->appendChild($dRoot);

	$hEl = $dom->createElement("hlight");
	$dRoot->appendChild( $hEl );
	$hEl->setAttribute("score", $h->getScore());
	$hEl->setAttribute("id", $h->getIdHighlight());
	$hEl->setAttribute("time", $h->getTime());
	$hEl->setAttribute("date", $h->getDate());

	$title = $dom->createElement("tit");
	$desc  = $dom->createElement("desc");
	$img   = $dom->createElement("img");
	$chn   = $dom->createElement("chn");

	$hEl->appendChild( $title );
	$hEl->appendChild( $desc );
	$hEl->appendChild( $img );
	$hEl->appendChild( $chn );

	$text = $dom->createTextNode(utf8_encode($h->getTitle()));
	$title->appendChild( $text );
	
	$text = $dom->createTextNode(utf8_encode($h->getLdesc()));
	$desc->appendChild( $text );
	
	$text = $dom->createTextNode($h->getImage( "260x260" ) );
	$img->appendChild( $text );
	
	$text = $dom->createTextNode($h->getChannel( ) );
	$chn->appendChild( $text );
	
	$hEl->appendChild( $title );

	echo $dom->saveXML();
}
else if( $_GET['mode'] == "category" && isset($_GET["c"]))
{
	$db = new DataBase( DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_DEBG );
	$q = isset($_GET['q']) ? ($_GET['q']>0 ? $_GET['q']:3):3;
	$d = isset($_GET['d']) ? (ereg("[0-9]{4}-[0-9]{2}", $_GET['d']) ? $_GET['d']:date("Y-m-d")):date("Y-m-d");
	$sql_p1 = "";
	$sql_p2 = "";
	
	switch($_GET["c"])
	{
		case "special":
		$sql_p2 = ", special";
//		$sql_p1 = "c.special > 0 AND special.gender<>'Animacion' AND special.gender<>'Infantil' AND special.gender<>'Documental' AND special.id = c.special AND ";
		$sql_p1 = "c.special > 0 AND (special.gender='Musical' OR special.gender='Deportes') AND special.id = c.special AND ";
		break;
		case "serie":
		$sql_p2 = ", serie";
		$sql_p1 = "c.serie > 0 AND serie.gender<>'Animacion' AND serie.gender<>'Infantil' AND serie.gender<>'Documental' AND serie.id = c.serie AND ";
		break;
		case "movie":
		default:
		$sql_p2 = ", movie";
		$sql_p1 = "c.movie > 0 AND movie.gender<>'Animacion' AND movie.gender<>'Infantil' AND movie.gender<>'Documental' AND movie.id = c.movie AND ";
		break;
	}
	
	if($_GET["c"] == "children" ) //a very crazy case
	{
		$sql = "SELECT h.id_highlight, ch.name, s.title, s.time, s.date, c.description sdesc, c.id, c.points FROM " . 
			"highlights h, channel ch, slot s, chapter c, slot_chapter sc, client_channel cc, movie m WHERE " .
			"s.id = h.id_slot AND " .
			"s.id = sc.slot AND " .
			"s.channel = ch.id AND " .
			"cc.channel = ch.id AND " .
			"cc.client = " . $ID_CLIENT . " AND " .
			"c.id = sc.chapter AND " .
			"h.type = 'all' AND " .
			"c.movie > 0 AND " .
			"m.id = c.movie AND " .
			"( m.gender = 'Infantil' OR m.gender = 'Animacion' ) AND " .
			"s.date >= '" . $d ."' ";
						
		$sql .= " UNION ";

		$sql .= "SELECT h.id_highlight, ch.name, s.title, s.time, s.date, c.description sdesc, c.id, c.points FROM " . 
			"highlights h, channel ch, slot s, chapter c, slot_chapter sc, client_channel cc, serie m WHERE " .
			"s.id = h.id_slot AND " .
			"s.id = sc.slot AND " .
			"s.channel = ch.id AND " .
			"cc.channel = ch.id AND " .
			"cc.client = " . $ID_CLIENT . " AND " .
			"c.id = sc.chapter AND " .
			"h.type = 'all' AND " .
			"c.serie > 0 AND " .
			"m.id = c.serie AND " .
			"( m.gender = 'Infantil' OR m.gender = 'Animacion' ) AND " .
			"s.date >= '" . $d ."' ";
						
		$sql .= " UNION ";
	
		$sql .= "SELECT h.id_highlight, ch.name, s.title, s.time, s.date, c.description sdesc, c.id, c.points FROM " . 
			"highlights h, channel ch, slot s, chapter c, slot_chapter sc, client_channel cc, special m WHERE " .
			"s.id = h.id_slot AND " .
			"s.id = sc.slot AND " .
			"s.channel = ch.id AND " .
			"cc.channel = ch.id AND " .
			"cc.client = " . $ID_CLIENT . " AND " .
			"c.id = sc.chapter AND " .
			"h.type = 'all' AND " .
			"c.special > 0 AND " .
			"m.id = c.special AND " .
			"( m.gender = 'Infantil' OR m.gender = 'Animacion' ) AND " .
			"s.date >= '" . $d ."' ";
									
		$sql = "SELECT * FROM (" . $sql . ") t WHERE sdesc <> '' " .
		"ORDER BY date, time LIMIT 0, " . $q;
	}
	else if($_GET["c"] == "documentary" ) //another crazy case
	{
		$sql = "SELECT h.id_highlight, ch.name, s.title, s.time, s.date, c.description sdesc, c.id, c.points FROM " . 
			"highlights h, channel ch, slot s, chapter c, slot_chapter sc, client_channel cc, movie m WHERE " .
			"s.id = h.id_slot AND " .
			"s.id = sc.slot AND " .
			"s.channel = ch.id AND " .
			"cc.channel = ch.id AND " .
			"cc.client = " . $ID_CLIENT . " AND " .
			"c.id = sc.chapter AND " .
			"h.type = 'all' AND " .
			"c.movie > 0 AND " .
			"m.id = c.movie AND " .
			"( m.gender = 'Documental' ) AND " .
			"s.date >= '" . $d ."' ";
						
		$sql .= " UNION ";

		$sql .= "SELECT h.id_highlight, ch.name, s.title, s.time, s.date, c.description sdesc, c.id, c.points FROM " . 
			"highlights h, channel ch, slot s, chapter c, slot_chapter sc, client_channel cc, serie m WHERE " .
			"s.id = h.id_slot AND " .
			"s.id = sc.slot AND " .
			"s.channel = ch.id AND " .
			"cc.channel = ch.id AND " .
			"cc.client = " . $ID_CLIENT . " AND " .
			"c.id = sc.chapter AND " .
			"h.type = 'all' AND " .
			"c.serie > 0 AND " .
			"m.id = c.serie AND " .
			"( m.gender = 'Documental' ) AND " .
			"s.date >= '" . $d ."' ";
						
		$sql .= " UNION ";
	
		$sql .= "SELECT h.id_highlight, ch.name, s.title, s.time, s.date, c.description sdesc, c.id, c.points FROM " . 
			"highlights h, channel ch, slot s, chapter c, slot_chapter sc, client_channel cc, special m WHERE " .
			"s.id = h.id_slot AND " .
			"s.id = sc.slot AND " .
			"s.channel = ch.id AND " .
			"cc.channel = ch.id AND " .
			"cc.client = " . $ID_CLIENT . " AND " .
			"c.id = sc.chapter AND " .
			"h.type = 'all' AND " .
			"c.special > 0 AND " .
			"m.id = c.special AND " .
			"( m.gender = 'Documental' ) AND " .
			"s.date >= '" . $d ."' ";
									
		$sql = "SELECT * FROM (" . $sql . ") t WHERE sdesc <> '' " .
		"ORDER BY date, time LIMIT 0, " . $q;
	}
	else
	{
		$sql = "SELECT h.id_highlight, ch.name, s.title, s.time, s.date, c.description sdesc, c.id, c.points FROM " . 
			"highlights h, channel ch, slot s, chapter c, slot_chapter sc, client_channel cc" . $sql_p2 . " WHERE " .
			"s.id = h.id_slot AND " .
			"s.id = sc.slot AND " .
			"s.channel = ch.id AND " .
			"cc.channel = ch.id AND " .
			"cc.client = " . $ID_CLIENT . " AND " .
			"c.id = sc.chapter AND " .
			"h.type = 'all' AND " .
			$sql_p1 .
			"s.date >= '" . $d ."' " .
			"AND c.description <> '' " .
			"ORDER BY s.date, s.time LIMIT 0, " . $q;
	}
	$db->query( $sql );

	$dom = new DOMDocument('1.0', 'ISO-8859-1');
	$dRoot = $dom->createElement("list");
	$dom->appendChild($dRoot);

	while($row = $db->fetchArray())
	{
		$hEl = $dom->createElement("hlight");
		$dRoot->appendChild( $hEl );
		$hEl->setAttribute("score", $row["points"]);
		$hEl->setAttribute("id", $row["id_highlight"]);
		$hEl->setAttribute("time", $row["time"]);
		$hEl->setAttribute("date", $row["date"]);

		$title = $dom->createElement("tit");
		$desc  = $dom->createElement("desc");
		$img   = $dom->createElement("img");
		$chn   = $dom->createElement("chn");

		$hEl->appendChild( $title );
		$hEl->appendChild( $desc );
		$hEl->appendChild( $img );
		$hEl->appendChild( $chn );

		$text = $dom->createTextNode(utf8_encode($row["title"]));
		$title->appendChild( $text );
		
		$text = $dom->createTextNode(utf8_encode($row["sdesc"]));
		$desc->appendChild( $text );
		
		$text = $dom->createTextNode($IMAGEPATH . "/70x70/" . $row["id"] . ".jpg");
		$img->appendChild( $text );
		
		$text = $dom->createTextNode(utf8_encode($row["name"]));
		$chn->appendChild( $text );
		
		$hEl->appendChild( $title );
	}
	echo $dom->saveXML();
}
?>