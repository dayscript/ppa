<?
//new
$start_time_script = time();
$STEP = (isset($_GET["print_type"]) || $_GET["print_type"] == "printer" )?3000:30;
$MAX_LINES = 56;
$MAX_CHARS_BY_LINE = 34;

/*
$startDate = isset($_GET["start_date"])? date("Y-m-d", customStrToTime($_GET["start_date"])):date("Y-m-d");
$startTime = isset($_GET["start_date"])? date("H:i", customStrToTime($_GET["start_date"])):date("H:i");
$endDate = isset($_GET["end_date"])? date("Y-m-d", customStrToTime($_GET["end_date"])):date("Y-m-d",strtotime($startDate)+(DAY*30));
$endTime = isset($_GET["end_date"])? date("H:i", customStrToTime($_GET["end_date"])):date("H:i",strtotime($startDate)+(DAY*30));
*/
$startDate = date("Y-m-d", $time);
$startTime = date("H:i", $time);
$endDate = date("Y-m-d", $etime);
$endTime = date("H:i", $etime);

$channel = isset($_GET["channel"])? substr($_GET["channel"],0,-1):"";
$category = isset($_GET["category"])? strtolower($_GET["category"]):"";
$title = isset($_GET["title"])? $_GET["title"]:"";
$actor = isset($_GET["actor"])? $_GET["actor"]:"";
$offset = isset($_GET["offset"])&&$_GET["offset"]>0?$_GET["offset"]*$STEP:0;

if($actor == "")
{
	$sql = "SELECT channel.id id_channel, channel.logo, IFNULL(client_channel.custom_shortname, channel.shortName) name, client_channel.number " . 
		"FROM channel, client_channel " .
		"WHERE channel.id = client_channel.channel " . 
		"AND client = " . $ID_CLIENT . " " .
		($category != "" ? "AND client_channel._group = '" . $category . "' " : "" ) .
		(ereg("^[0-9,]+$", $channel) ? "AND channel.id in (" . $channel . ") " : "" ) .
//		(ereg("^[0-9]+$", $channel) ? "AND channel.id = " . $channel . " " : "" ) .
		($channel != "" && !ereg("^[0-9,]+$", $channel) ? "AND IFNULL(client_channel.custom_shortname, channel.shortName) = '" . $channel . "' " : "" );
	$result = db_query( $sql );	
	while($row = db_fetch_array($result))
	{
		$chns[$row["id_channel"]]["logo"] = $row["logo"];
		$chns[$row["id_channel"]]["name"] = $row["name"];
		$chns[$row["id_channel"]]["number"] = $row["number"];
		$id_chns .= $row["id_channel"] . ",";
	}
	if($id_chns == "") $id_chns = 0;
	else $id_chns = substr($id_chns,0,-1);

	$sql = "SELECT slot.id, slot.title, slot.date, slot.time, chapter.description, slot.channel id_channel " . 
		"FROM slot " . 
		"LEFT JOIN slot_chapter ON slot_chapter.slot = slot.id " . 
		"LEFT JOIN chapter ON slot_chapter.chapter = chapter.id " .
		"WHERE date BETWEEN '" . $startDate . "' AND '" . $endDate . "' " .
		"AND slot.channel in (" . $id_chns . ") " .
		($title != "" ? "AND slot.title like '%" . $title . "%' " : "" ) .
		"ORDER BY date, time";
//		"ORDER BY date, time LIMIT " . $offset . ", " . (2*$STEP);

//	$sql = "SELECT * FROM (" . $sql . ") AS t WHERE CONCAT(date,\" \",time) BETWEEN '" . $startDate . "' AND '" . $endDate . "' ORDER BY date, time LIMIT " . $offset . ", " . $STEP;
	if($print_type=="printer" && $print_mode == "channel")
		$sql = "SELECT * FROM (" . $sql . ") AS t WHERE CONCAT(date,\" \",time) BETWEEN '" . $startDate . " " . $startTime . "' AND '" . $endDate . " " . $endTime . "' ORDER BY id_channel, date, time LIMIT " . $offset . ", " . $STEP;
	else
		$sql = "SELECT * FROM (" . $sql . ") AS t WHERE CONCAT(date,\" \",time) BETWEEN '" . $startDate . " " . $startTime . "' AND '" . $endDate . " " . $endTime . "' ORDER BY date, time LIMIT " . $offset . ", " . $STEP;
}
else
{
	$sql = "SELECT channel.id id_channel, channel.logo, IFNULL(client_channel.custom_shortname, channel.shortName) name, client_channel.number " . 
		"FROM channel, client_channel " .
		"WHERE channel.id = client_channel.channel " . 
		"AND client = " . $ID_CLIENT . " " .
		($category != "" ? "AND client_channel._group = '" . $category . "' " : "" ) .
		(ereg("^[0-9,]+$", $channel) ? "AND channel.id in ( " . $channel . ") " : "" ) .
//		(ereg("^[0-9]+$", $channel) ? "AND channel.id = " . $channel . " " : "" ) .
		($channel != "" && !ereg("^[0-9,]+$", $channel) ? "AND IFNULL(client_channel.custom_shortname, channel.shortName) = '" . $channel . "' " : "" );
	$result = db_query( $sql );	
	while($row = db_fetch_array($result))
	{
		$chns[$row["id_channel"]]["logo"] = $row["logo"];
		$chns[$row["id_channel"]]["name"] = $row["name"];
		$chns[$row["id_channel"]]["number"] = $row["number"];
		$id_chns .= $row["id_channel"] . ",";
	}
	if($id_chns == "") $id_chns = 0;
	else $id_chns = substr($id_chns,0,-1);

	$sql = "(";
	$sql .= "SELECT slot.id, slot.title, slot.date, slot.time, movie.actors as starring, movie.director, chapter.description, slot.channel id_channel " . 
		"FROM movie " . 
		"JOIN chapter ON chapter.movie = movie.id " . 
		"JOIN slot_chapter ON slot_chapter.chapter = chapter.id " . 
		"JOIN slot ON slot.id = slot_chapter.slot AND slot.date BETWEEN '" . $startDate . "' AND '" . $endDate . "' " .
		"	AND channel in (" . $id_chns . ") " . 
		($title != "" ? "AND slot.title like '%" . $title . "%' " : "" ) .
		"WHERE " .
		($actor != "" ? " (movie.actors like '%" . $actor . "%' OR movie.director like '%" . $actor . "%')" : "" ) .
		"ORDER BY date, time";// .
//		" LIMIT " . $offset . ", " . $STEP;
		
	$sql .= ") UNION (";

	$sql .= "SELECT slot.id, slot.title, slot.date, slot.time, special.starring, '' director, chapter.description, slot.channel id_channel " . 
		"FROM special " . 
		"JOIN chapter ON chapter.special = special.id " . 
		"JOIN slot_chapter ON slot_chapter.chapter = chapter.id " . 
		"JOIN slot ON slot.id = slot_chapter.slot AND slot.date BETWEEN '" . $startDate . "' AND '" . $endDate . "' " .
		"	AND channel in (" . $id_chns . ") " . 
		($title != "" ? "AND slot.title like '%" . $title . "%' " : "" ) .
		"WHERE " .
		($actor != "" ? " (special.starring like '%" . $actor . "%' )" : "" ) .
		"ORDER BY date, time";// .
//		" LIMIT " . $offset . ", " . $STEP;

	$sql .= ") UNION (";

	$sql .= "SELECT slot.id, slot.title, slot.date, slot.time, serie.starring, '' director, chapter.description, slot.channel id_channel " . 
		"FROM serie " . 
		"JOIN chapter ON chapter.serie = serie.id " . 
		"JOIN slot_chapter ON slot_chapter.chapter = chapter.id " . 
		"JOIN slot ON slot.id = slot_chapter.slot AND slot.date BETWEEN '" . $startDate . "' AND '" . $endDate . "' " .
		"	AND channel in (" . $id_chns . ") " . 
		($title != "" ? "AND slot.title like '%" . $title . "%' " : "" ) .
		"WHERE " .
		($actor != "" ? " (serie.starring like '%" . $actor . "%' )" : "" ) .
		"ORDER BY date, time";// .
//		" LIMIT " . $offset . ", " . $STEP;

	$sql .= ")";
		
	if($print_type=="printer" && $print_mode == "channel")
		$sql = "SELECT * FROM (" . $sql . ") AS t WHERE CONCAT(date,\" \",time) BETWEEN '" . $startDate . " " . $startTime . "' AND '" . $endDate . " " . $endTime . "' ORDER BY date, time LIMIT " . $offset . ", " . $STEP;
	else
		$sql = "SELECT * FROM (" . $sql . ") AS t WHERE CONCAT(date,\" \",time) BETWEEN '" . $startDate . " " . $startTime . "' AND '" . $endDate . " " . $endTime . "' ORDER BY id_channel, date, time LIMIT " . $offset . ", " . $STEP;
/*		$hd = fopen("/tmp/sql", "w");
		fwrite($hd, $sql);
		fclose($hd);*/
}
//$search_string = "<b>Fecha y hora:</b> " . $_GET["start_date"] . 
$search_string = "<b>Fecha y hora:</b> " . date("d/m/Y h:i a", $time) . " hasta " . date("d/m/Y h:i a", $etime) .
	($category==""?"":" / <b>Género:</b> ".$category) .
	($channel==""?"":" / <b>Canal:</b> ".$chns[$channel]["name"]) .
	($title==""?"":" / <b>Título:</b> ".$title) .
	($actor==""?"":" / <b>Actor o director:</b> ".$actor);
$today = date("Y-m-d");

$result = db_query( $sql );
$n_results = db_numrows($result);

/* Printer type and date mode*/
if($print_type=="printer" && $print_mode == "date")
{
$line=0;$tok=false;$col=0;$date_buf='';
?><?
	echo '<div class="print_container"><img src="images/print_head.jpg" /><div class="print">';
		while( $row = db_fetch_array($result) )
		{
			$fixedTime = fixGmtTime($row["date"] . " " . $row["time"], GMT);
			$ts = strtotime($fixedTime["date"] . " " . $fixedTime["time"]);
			if($date_buf!=$fixedTime["date"]){ echo '<h1 class="date">' . date("d/m/y", strtotime($fixedTime["date"])) . "</h1>"; $date_buf=$fixedTime["date"];$chn_date=1;}
			else $chn_date=0;
			if($line==0 && $tok)echo '</div>' . ($col%3==0?'<hr /><img src="images/print_head.jpg" />':'') . '<div class="print">';
?>
<h1><?=date("h:i a", $ts)?></h1><h2><?= $chns[$row["id_channel"]]["name"]?></h2><h3><?=xmlentities($row['title'])?></h3>
<? 
			$line = $line + ceil(strlen($row['title'])/$MAX_CHARS_BY_LINE) + $chn_date;
			if($line>=$MAX_LINES){$line=0;$col++;}
			$tok=true;
		} echo "</div></div>";
} 

/* Printer type and channel mode*/
else if($print_type=="printer" && $print_mode == "channel") 
{
	$line=0;$tok=false;$col=0;$date_buf='';$chn_buf='';
	echo '<div class="print_container"><img src="images/print_head.jpg" /><div class="print">';
		while( $row = db_fetch_array($result) )
		{
			$fixedTime = fixGmtTime($row["date"] . " " . $row["time"], GMT);
			$ts = strtotime($fixedTime["date"] . " " . $fixedTime["time"]);
			
			if($chn_buf!=$row["id_channel"]){ echo '<h1 class="channel">' . $chns[$row["id_channel"]]["name"] . "</h1>"; $chn_buf=$row["id_channel"];$chn_tit=1;}
			else $chn_tit=0;
			if($date_buf!=$fixedTime["date"]){ echo '<h1 class="date">' . $fixedTime["date"] . "</h1>"; $date_buf=$fixedTime["date"];$date_tit=1;}
			else $date_tit=0;
			if($line==0 && $tok)echo '</div>' . ($col%3==0?'<hr /><img src="images/print_head.jpg" />':'') . '<div class="print">';
?>
<h1><?=date("h:i a", $ts)?></h1><h2><?= $chns[$row["id_channel"]]["name"]?></h2><h3><?=xmlentities($row['title'])?></h3>
<? 
			$line = $line + ceil(strlen($row['title'])/$MAX_CHARS_BY_LINE) + $date_tit + $chn_tit;
			if($line>=$MAX_LINES){$line=0;$col++;}
			$tok=true;
		} echo "</div></div>";
} 

/* Screen mode */
else 
{ ?><div>
	<div class="city_title"><div class="left city_name"><?=$CITY_NAME?></div><div class="right">Imprimir: <a href="#;" onclick="GridApp.forPrintGrid('date')">fecha</a> | <a href="#;" onclick="GridApp.forPrintGrid('channel')">canal</a></div><img class="right" src="images/printer.jpg" /></div>
	<table class="cat_table" width="100%">
			<tr class="cat_td_line_1"><td colspan="4" style="text-align:center"><b>Resultados para:</b><br/><?=$search_string?></td></tr>
      <tr class="cat_table_title">
        <th class="cat_table_title" width="40"><div class="chn_title">CANAL</div></th>
        <th class="cat_table_title" width="200"><img class="goback" onclick="GridApp.goNext(-1)" src="images/goback.jpg" /><div class="hour">Programa</div></th>
        <th class="cat_table_title" width="110"><div class="hour">Fecha</div></th>
        <th class="cat_table_title"><div class="hour">Más Información</div><img class="gonext" onclick="if(<?= ($n_results < $STEP?"false":"true")?>)GridApp.goNext(1);else alert('No hay más resultados para la búsqueda');" src="images/gonext.jpg" /></th>
      </tr>
<?	
		while( $row = db_fetch_array($result) )
		{
			$fixedTime = fixGmtTime($row["date"] . " " . $row["time"], GMT);
			$ts = strtotime($fixedTime["date"] . " " . $fixedTime["time"]);
?>
      <tr height="45">
			  <td class="<?= ($line%2) == 0 ? "cat_td_title_1" : "cat_td_title_2" ?>" onclick="document.getElementById('ppa_channel').value = <?=$row["id_channel"]?>;GridApp.consultGrid()">
			  	<div class="ppa_chn_info"><img src="<?=( $chns[$row["id_channel"]]["logo"] != "" ? "http://" . $URL_PPA . "/ppa/ppa/" . $chns[$row["id_channel"]]["logo"] : "images/empty.gif" )?>"/><div class="ppa_chn_name"><?= $chns[$row["id_channel"]]["name"] ."<br/>No.<span>". $chns[$row["id_channel"]]["number"] . "</span>" ?></div></div></td>
        <td style="cursor:pointer" onmouseover="GridApp.hover(this)" onmouseout="GridApp.hout(this)" onmouseup="GridApp.synopsisPopUp(<?= $row['id'] ?>, event);" class="cat_td_line_1"><div class="program"><?=xmlentities($row['title'])?></div></td>
        <td style="cursor:pointer" onmouseover="GridApp.hover(this)" onmouseout="GridApp.hout(this)" onmouseup="GridApp.synopsisPopUp(<?= $row['id'] ?>, event);" class="cat_td_line_1"><?=date("d/m/Y h:i a", $ts)?></td>
        <td style="cursor:pointer" onmouseover="GridApp.hover(this)" onmouseout="GridApp.hout(this)" onmouseup="GridApp.synopsisPopUp(<?= $row['id'] ?>, event);" class="cat_td_line_1"><?=xmlentities($row["description"])?><?=$row["director"]!=""?"<br/>Director:" . xmlentities($row["director"]):""?><?=$row["starring"]!=""?"<br/>Actores:" . xmlentities($row["starring"]):""?>&nbsp;</td>
      </tr>
<? } ?>
</table>
</div>
<? } ?>