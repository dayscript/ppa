<?
include( "ppa/class/Channel.class.php" );

//$date      = isset($_GET["date"])? date("Y-m-d H:i", strtotime($_GET["date"])):date("Y-m-d H:i");
$date      = date("Y-m-d");
$time      = date("H:i:00");
$shortName = isset($_GET["short_name"])? $_GET["short_name"]:"";

$sql = "SELECT id, name, IFNULL(client_channel.custom_shortname, channel.shortName) shortname, logo, review FROM channel, client_channel " .
	"WHERE channel.id = client_channel.channel " .
	"AND client_channel.client in ( select id from `client` where operator = " . $PROVIDER . " ) " .
//	"AND client_channel.client = " . $ID_CLIENT . " " .
	"AND (client_channel.custom_shortname = '" . $shortName ."' OR channel.shortName = '" . $shortName . "')";
$channel_row = db_fetch_array( db_query( $sql ) );
if(!$channel_row){echo "<channel/>";exit;}
	
$dom = new DOMDocument('1.0', 'ISO-8859-1');
$dRoot = $dom->createElement("channel");
$dom->appendChild($dRoot);

$name = $dom->createElement("name");
$dRoot->appendChild($name);
$name->appendChild($dom->createTextNode(utf8_encode($channel_row["name"])));

$short = $dom->createElement("shortName");
$dRoot->appendChild($short);
$short->appendChild($dom->createTextNode(utf8_encode($channel_row["shortname"])));

$logo = $dom->createElement("logo");
$dRoot->appendChild($logo);
$logo->appendChild($dom->createTextNode("http://" . $URL_PPA . "/ppa/channel_images/300x300/" . $channel_row["id"] . ".jpg"));

$review = $dom->createElement("review");
$dRoot->appendChild($review);
$review->appendChild($dom->createTextNode(utf8_encode($channel_row["review"])));

$listing = $dom->createElement("listing");
$dRoot->appendChild($listing);

$sql = "(SELECT title, date, time FROM slot " .
	"WHERE channel = " . $channel_row["id"] . " " .
	"AND ( ( date = '" . $date . "' AND time < '" . $time . "' ) OR " .
	"date < '" . $date . "' )" .
	"ORDER BY date DESC, time DESC " .
	"LIMIT 0, 1) UNION ";

$sql .= "(SELECT title, date, time FROM slot " .
	"WHERE channel = " . $channel_row["id"] . " " .
	"AND ( ( date = '" . $date . "' AND time >= '" . $time . "' ) OR " .
	"date > '" . $date . "' )" .
	"ORDER BY date, time " .
	"LIMIT 0, 4)";

$result = db_query( $sql );
while($row = db_fetch_array($result) )
{
	$prg = $dom->createElement("prg");
	$listing->appendChild($prg);
	$prg->setAttribute("time", to12H( $row["time"] ));
	$prg->appendChild($dom->createTextNode(utf8_encode($row["title"])));
}


echo $dom->saveXML();
?>