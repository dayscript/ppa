<?
header("Content-type: text/csv");
header("Content-disposition: attachment; filename=destacados_" . $_GET["date"] . ".csv");

define("STRING", "1");
define("DELIMITER", ",");

function resizeImage($src,$w,$h)
{
list($width_orig, $height_orig) = getimagesize($src);
$image_p = imagecreatetruecolor($w, $h);
$image = imagecreatefromjpeg($src);
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $w, $h, $width_orig, $height_orig);	
return $image_p;
}

function filterString( $str, $type=false )
{
	$str = str_replace("\"", "'", $str );
	$str = ereg_replace("[\r|\n]", "", $str );
	if($type==STRING)
		return "\"" . $str . "\"";
	else
		return $str;
}

if( isset($_GET["id_client"]) && $_GET["id_client"]!=0 )
{
	$subsql = "SELECT channel.id " .
		"FROM channel, client_channel " . 
		"WHERE client_channel.channel = channel.id " .
		"AND client_channel.client = " .$_GET['id_client']; 
}
else
	$subsql = "";

$sql = "SELECT c.id cid, s.title, chn.name, s.date, s.time, c.description " .
	"FROM highlights h, slot s, chapter c, slot_chapter sc, channel chn " . 
	"WHERE " .
	"h.id_slot = s.id " .
	"AND s.id = sc.slot " .
	"AND s.channel = chn.id " .
	"AND sc.chapter = c.id " .
	"AND s.date like '" . $_GET['date'] . "%' " .
	($subsql==""?"":"AND chn.id IN ( " . $subsql . ") ") .
	"ORDER BY name";
//echo $sql;exit;
$result = db_query($sql);
@mkdir("/tmp/70x70");
@mkdir("/tmp/260x260");
while(($row=db_fetch_array($result))!==false)
{
	echo 
	filterString($row['title'], STRING) . DELIMITER . 
	filterString($row['name'], STRING) . DELIMITER . 
	$row['date'] . DELIMITER . 
	$row['time']  . DELIMITER . 
	filterString($row['description'], STRING) . "\r\n";

//	$img = resizeImage("/home/ppa/public_html/chapter_images/70x70/" . $row["cid"] . ".jpg", 64,64);
//	imagejpeg($img, "/tmp/64x64/" . $row["name"] . "/" . $row["title"] . ".jpg");
/*
	if(!file_exists("/tmp/70x70/". $row["name"] . "/"))mkdir("/tmp/70x70/". $row["name"] . "/");
	copy("/home/ppa/public_html/chapter_images/70x70/" . $row['cid'] . ".jpg", 
		"/tmp/70x70/" . $row["name"] . "/" . str_replace(":", ",", $row["title"] ) . ".jpg");

	if(!file_exists("/tmp/260x260/". $row["name"] . "/"))mkdir("/tmp/260x260/". $row["name"] . "/");
	copy("/home/ppa/public_html/chapter_images/260x260/" . $row['cid'] . ".jpg", 
		"/tmp/260x260/" . $row["name"] . "/" . str_replace(":", ",", $row["title"] ) . ".jpg");

*/
/*	copy("/tmp/70x70/" . $row["name"] . "/" . $row["title"] . ".jpg", 
	"/home/ppa/public_html/chapter_images/70x70/" . $row['cid'] . ".jpg");

	copy("/tmp/260x260/" . $row["name"] . "/" . $row["title"] . ".jpg", 
	"/home/ppa/public_html/chapter_images/260x260/" . $row['cid'] . ".jpg");*/
}
?>