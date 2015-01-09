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
$IMAGEPATH = "http://200.75.113.177/ppa/chapter_images";
$ID_CLIENT = "67";
$MAXITEMS  = 7;

$db = new DataBase( DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_DEBG );
$db2 = new DataBase( DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_DEBG );
$q = isset($_GET['q']) ? ($_GET['q']>0 ? $_GET['q']:$MAXITEMS):$MAXITEMS;

/*	$sql = "SELECT id_slot FROM highlights, slot " .
		"WHERE slot.id = highlights.id_slot " .
		"AND slot.date like '" . $d ."-%' " .
		"ORDER BY RAND() limit 0, " . $q;*/
/*	$sql = "SELECT h.id_highlight, ch.name, s.title, s.time, s.date, c.description sdesc, c.id, c.serie, c.movie, c.special, c.points FROM " . 
		"highlights h, channel ch, slot s, chapter c, slot_chapter sc, client_channel cc WHERE " .
		"s.id = h.id_slot AND " .
		"s.id = sc.slot AND " .
		"s.channel = ch.id AND " .
		"cc.channel = ch.id AND " .
		"cc.client = " . $ID_CLIENT . " AND " .
		"c.id = sc.chapter AND " .
		"h.type = 'all' AND " .
		"s.date like '" . $d ."-%' " .
		"ORDER BY RAND() limit 0, " . $q;*/

/* Version Modificada Daniel Mejia */

$_REQUEST['t'] = str_replace("Escriba una palabra clave..." , "", $_REQUEST['t']);
$c = (isset($_REQUEST['c']) && $_REQUEST['c'] != "") ? $_REQUEST['c'] : false;
$g = (isset($_REQUEST['g']) && $_REQUEST['g'] != "") ? $_REQUEST['g'] : false;
$o = (isset($_REQUEST['o']) && $_REQUEST['o'] != "") ? $_REQUEST['o'] : "s.date, s.time";
$p = (isset($_REQUEST['p']) && $_REQUEST['p'] != "") ? $_REQUEST['p'] : 0;
$a = (isset($_REQUEST['a']) && $_REQUEST['a'] != "") ? $_REQUEST['a'] : "ASC";
$t = (isset($_REQUEST['t']) && $_REQUEST['t'] != "") ? $_REQUEST['t'] : false;
$d = (isset($_REQUEST['d']) && $_REQUEST['d'] != "") ? (ereg("[0-9]{4}-[0-9]{2}-[0-9]{2}", $_REQUEST['d']) ? $_REQUEST['d']:date("Y-m-d")) : false;

$sql = "SELECT h.id_highlight, ch.name, s.title, s.time, s.date, c.description sdesc, c.id, c.serie, c.movie, c.special, c.points FROM " ;
$sql2 = "SELECT count(h.id_highlight) as total FROM " ;
$sqlc .= "highlights h, channel ch, slot s, chapter c, slot_chapter sc, client_channel cc WHERE " ;
$sqlc .= "s.id = h.id_slot AND " ;
$sqlc .= "s.id = sc.slot AND " ;
$sqlc .= "s.channel = ch.id AND " ;
$sqlc .= "cc.channel = ch.id AND " ;
$sqlc .= "cc.client = " . $ID_CLIENT . " AND " ;
$sqlc .= "c.id = sc.chapter AND " ;
if($c !== false){
    $sqlc .= "ch.id = " . $c . " AND ";
}
if($t !== false){
    $sqlc .= "( s.title like '%" . $t . "%' OR c.description like '%" . $t . "%' ) AND " ;
}
if($g !== false){
    $sqlc .= '' ; // TODO
}
if($d !== false){
    $sqlc .= " s.date >= '" . $d . " 00:00:00' AND  s.date <= '" . $d . " 23:59:59' AND " ;
} 
else{
     $sqlc .= " s.date >= '" . date ("Y-m-d") . "' AND ";
}
$sqlc .= "h.type = 'all' " ;
$sql = $sql . $sqlc . "ORDER BY " . $o  . " " . $a . " limit " . ($p*$q) . ", " . $q;
$sql2 = $sql2 . $sqlc;

// echo $sql2;

/* Fin Modificada */

	$db->query( $sql );
	$db2->query( $sql2 );
        if($row2 = $db2->fetchArray()){
	  $total = $row2['total'];
	} else {
	  $total = 0;
	}
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
	$rssDesc->appendChild( $dom->createTextNode(utf8_encode("Programación destacada")) );
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
	//<total> Modificado para retorar el total de registros 
	$rssTotal = $dom->createElement("total");
	$rssTotal->appendChild( $dom->createTextNode($total) );
	$dChannel->appendChild($rssTotal);
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
		
		$text = $dom->createTextNode($IMAGEPATH . "/70x70/" . $row["id"] . ".jpg");
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