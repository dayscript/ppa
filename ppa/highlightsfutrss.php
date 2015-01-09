<?
error_reporting(E_ERROR);
header("Content-type: text/xml");
define("DB_HOST", "localhost");
define("DB_NAME", "ppa");
define("DB_USER", "ppa");
define("DB_PASS", "kfc3*9mn");
define("DB_DEBG", false);

include("ppa11/class/Highlight.class.php");
include("ppa11/class/dao/DataBase.class.php");
$IMAGEPATH = "http://200.75.113.177/ppa/ppa";
$ID_CLIENT = "67";
$MAXITEMS  = 7;

$db = new DataBase( DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_DEBG );
$q = isset($_GET['q']) ? ($_GET['q']>0 ? $_GET['q']:$MAXITEMS):$MAXITEMS;
$d = isset($_GET['d']) ? (ereg("[0-9]{4}-[0-9]{2}-[0-9]{2}", $_GET['d']) ? $_GET['d']:date("Y-m-d")):date("Y-m-d");
$st = (isset($_GET['st'])&&$_GET['st']!="") ? $_GET['st']:false;

$sql = "SELECT h.id_highlight, ch.name, ch.logo, s.title, s.time, s.date FROM " . 
	"highlights h, channel ch, slot s, client_channel cc WHERE " .
	"s.id = h.id_slot AND " .
	"s.channel = ch.id AND " .
	"cc.channel = ch.id AND " .
	"cc.client = " . $ID_CLIENT . " AND " .
	"h.type = 'fut' AND " .
	($st ? ("h.subtype = '" . $st . "'") : ("isnull(h.subtype)") ) . " AND " .
	"s.date >= '" . $d ."' " .
	"ORDER BY date, time asc limit 0, " . $q;

$db->query( $sql );

/*	echo '<?xml version="1.0" encoding="ISO-8859-1"?>';*/
	$dom = new DOMDocument('1.0', 'ISO-8859-1');
//	$dom = new DOMDocument('1.0', 'UTF-8');
	//<rss>
	$dRoot = $dom->createElement("rss");
	$dRoot->setAttribute("version", "2.0");
	$dom->appendChild($dRoot);
	//<channel>
	$dChannel = $dom->createElement("channel");
	$dRoot->appendChild($dChannel);
	//<title>
	$rssTitle = $dom->createElement("title");
	$rssTitle->appendChild( $dom->createTextNode("Cable Media Highlights") );
	$dChannel->appendChild($rssTitle);
	//<link>
	$rssLink = $dom->createElement("link");
	$rssLink->appendChild( $dom->createTextNode("http://www.cable-media.tv") );
	$dChannel->appendChild($rssLink);
	//<description>
	$rssDesc = $dom->createElement("description");
	$rssDesc->appendChild( $dom->createTextNode(utf8_encode("Partidos de futbol del mes")) );
	$dChannel->appendChild($rssDesc);
	//<language>
	$rssLang = $dom->createElement("language");
	$rssLang->appendChild( $dom->createTextNode("es-es") );
	$dChannel->appendChild($rssLang);
	//<copyright>
	$rssCopr = $dom->createElement("copyright");
	$rssCopr->appendChild( $dom->createTextNode("Dayscript Ltda.") );
	$dChannel->appendChild($rssCopr);
	//<ttl>
	$rssTtl = $dom->createElement("ttl");
	$rssTtl->appendChild( $dom->createTextNode("15") );
	$dChannel->appendChild($rssTtl);
	//<image>
	$rssImg = $dom->createElement("image");
	$dChannel->appendChild($rssImg);
	//<url>
	$rssUrl = $dom->createElement("url");
	$rssUrl->appendChild( $dom->createTextNode("http://www.dayscript.com/logo.jpg") );
	$rssImg->appendChild($rssUrl);
	//<title>
	$rssTitle = $dom->createElement("title");
	$rssTitle->appendChild( $dom->createTextNode("CableMedia Ltda.") );
	$rssImg->appendChild($rssTitle);
	//<link>
	$rssLink = $dom->createElement("link");
	$rssLink->appendChild( $dom->createTextNode("http://www.cable-media.tv") );
	$rssImg->appendChild($rssLink);

/* patch for www.golgolgol.net*/
	$hEl = $dom->createElement("item");
	$dChannel->appendChild( $hEl );
/**/

	while($row = $db->fetchArray())
	{
		$hEl = $dom->createElement("item");
		$dChannel->appendChild( $hEl );

		$title = $dom->createElement("title");
		$desc  = $dom->createElement("description");
		$img   = $dom->createElement("url");
		$chn   = $dom->createElement("chn");
		$score = $dom->createElement("stars");
		$id    = $dom->createElement("id");
		$sch   = $dom->createElement("pubDate");

		$hEl->appendChild( $title );
		$hEl->appendChild( $desc );
		$hEl->appendChild( $img );
		$hEl->appendChild( $chn );
		$hEl->appendChild( $score );
		$hEl->appendChild( $id );
		$hEl->appendChild( $sch );

		$text = $dom->createTextNode(utf8_encode($row["title"]));
		$title->appendChild( $text );
		
		$text = $dom->createTextNode(utf8_encode($row["sdesc"]));
		$desc->appendChild( $text );
		
		$text = $dom->createTextNode($IMAGEPATH . "/" . $row["logo"] );
		$img->appendChild( $text );
		
		$text = $dom->createTextNode(utf8_encode($row["name"]));
		$chn->appendChild( $text );
		
		$text = $dom->createTextNode($row["points"]);
		$score->appendChild( $text );
		
		$text = $dom->createTextNode($row["id_highlight"]);
		$id->appendChild( $text );

		$text = $dom->createTextNode($row["date"] . " " . $row["time"]);
		$sch->appendChild( $text );
		
		$hEl->appendChild( $title );
	}
	echo $dom->saveXML();
?>