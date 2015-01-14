<?
set_time_limit( 0 );
$gender_table=Array("G"=>4,"PG"=>10,"PG-13"=>13,"R"=>18,"NC-17"=>18,"X"=>18,"MM"=>18,""=>18);

$link = mysql_pconnect("localhost", "ppa", "kfc3*9mn") or mysql_die();
$db ="ppa";
mysql_select_db($db) or die("Unable to select database");
require_once("../include/db.inc.php");
require_once("../include/util.inc.php");
require_once("../class/Ftp.class.php");
require_once("class/Properties.class.php");
require_once("class/WriteFile.class.php");
require_once("class/Schedule2.class.php");
require_once("class/Program.class.php");

$sql = "SELECT channel.id chid, name, chapter.id cid, date, time, slot.title, chapter.description, movie.gender, movie.rated " .
"FROM slot, slot_chapter, chapter, channel, movie " .
"WHERE " . 
"date BETWEEN '2009-08-14' AND '2009-08-19' AND " . 
"channel in (127, 433, 434, 128, 129, 358, 132, 133, 130, 131 ) " . 
"AND chapter.id = slot_chapter.chapter " . 
"AND slot.id = slot_chapter.slot " . 
"AND slot.channel = channel.id " . 
"AND movie.id = chapter.movie " . 
"ORDER BY channel.name, slot.date, slot.time";

$result = db_query( $sql );

//$dom = new DOMDocument('1.0', 'ISO-8859-1');
$dom = new DOMDocument('1.0', 'UTF-8');
$root = $dom->createElement("SIS_EventImport_2_0");
$dom->appendChild($root);
$channel = "";
//$event_schedule_durations=array();
//$event_schedule_duration=0;
$i=0;
while($row = db_fetch_array($result))
{
//<EventSchedule>
	$tStart=str_replace("-", ".", $row["date"]) . "-" . $row["time"];
	
	if($channel!=$row["chid"])
	{
		$event_schedule_durations[$i++]=0;
		$e_s = $dom->createElement("EventSchedule");
		$root->appendChild($e_s);
//		$e_s->setAttribute("sService", $row["chid"]);
		$e_s->setAttribute("sService", $i++);
		$e_s->setAttribute("tStart", $tStart);
		$channel=$row["chid"];

		if($e_s->previousSibling)
		{	
			$e_s->previousSibling->setAttribute("tEnd", $evn->getAttribute("tBoxStart") );
			$e_s->previousSibling->removeChild($evn);
		}
	}

//<Event>
	$evn = $dom->createElement("Event");
	$e_s->appendChild($evn);

	$evn->setAttribute("uId", $row["cid"]);
	$evn->setAttribute("tBoxStart", $tStart);

//<ShortDescriptor>
	$s_d = $dom->createElement("ShortDescriptor", utf8_encode(substr($row["description"],0,252) . "..." ) );
	$evn->appendChild($s_d);

	$s_d->setAttribute("sLang", "spa");
	$s_d->setAttribute("sName", utf8_encode($row["title"]));
	$s_d->setAttribute("sTitle", utf8_encode($row["title"]));

//<ParentalRatingDescriptor>
	$prd = $dom->createElement("ParentalRatingDescriptor");
	$evn->appendChild($prd);

//<Rating>
	$rat = $dom->createElement("Rating");
	$prd->appendChild($rat);

	$rat->setAttribute("sCountry", "USA");
	$rat->setAttribute("eAge", $gender_table[$row["rated"]]);

	if($evn->previousSibling)
	{
		$prv_time = str_replace( ".","-",str_replace( "-"," ",$evn->previousSibling->getAttribute("tBoxStart") ) );
		$prv      = strtotime($prv_time);
		$cur      = strtotime($row["date"] . " " . $row["time"]);
		$dur      = ($cur - $prv)/60;
		$prv_evn = $evn->previousSibling;
		$prv_evn->setAttribute("dBoxDur", sprintf("%02d",floor($dur/60)) . ":" . sprintf("%02d:00",($dur%60) ) );
	}
}
//$e_s->setAttribute("duration", "1H00M");
//$root->removeChild($e_s);
$e_s->removeChild($evn);
header("Content-type: text/xml; charset=ISO-8859-1'");
echo $dom->saveXML();
exit;