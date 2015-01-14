<?
//new
$start_time_script = time();
$STEP = 30;
$startDate = isset($_GET["start_date"])? date("Y-m-d H:i", strtotime($_GET["start_date"])):date("Y-m-d H:i");
$endDate = isset($_GET["end_date"])? date("Y-m-d H:i", strtotime($_GET["end_date"])):date("Y-m-d H:i",strtotime($startDate)+(DAY*30));
$channel = isset($_GET["channel"])? $_GET["channel"]:"";
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
		(ereg("^[0-9]+$", $channel) ? "AND channel.id = " . $channel . " " : "" ) .
		($channel != "" && !ereg("^[0-9]+$", $channel) ? "AND IFNULL(client_channel.custom_shortname, channel.shortName) = '" . $channel . "' " : "" );
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

	$sql = "SELECT * FROM (" . $sql . ") AS t WHERE CONCAT(date,\" \",time) BETWEEN '" . $startDate . "' AND '" . $endDate . "' ORDER BY date, time LIMIT " . $offset . ", " . $STEP;
}
else
{
	$sql = "SELECT channel.id id_channel, channel.logo, IFNULL(client_channel.custom_shortname, channel.shortName) name, client_channel.number " . 
		"FROM channel, client_channel " .
		"WHERE channel.id = client_channel.channel " . 
		"AND client = " . $ID_CLIENT . " " .
		($category != "" ? "AND client_channel._group = '" . $category . "' " : "" ) .
		(ereg("^[0-9]+$", $channel) ? "AND channel.id = " . $channel . " " : "" ) .
		($channel != "" && !ereg("^[0-9]+$", $channel) ? "AND IFNULL(client_channel.custom_shortname, channel.shortName) = '" . $channel . "' " : "" );
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
		
	$sql = "SELECT * FROM (" . $sql . ") AS t WHERE CONCAT(date,\" \",time) BETWEEN '" . $startDate . "' AND '" . $endDate . "' ORDER BY date, time LIMIT " . $offset . ", " . $STEP;
/*		$hd = fopen("/tmp/sql", "w");
		fwrite($hd, $sql);
		fclose($hd);*/
}
/*$search_string = "Fecha y hora: " . $startDate . " / " .
	"Género: " . ($category==""?"todos":$category) . " / " .
	"Canal: " . ($channel==""?"todos":$chns[$channel]["name"]) . " / " .
	"Título: " . ($title==""?"todos":$title) . " / " .
	"Actor o director: " . ($actor==""?"todos":$actor);*/

$search_string = "<b>Fecha y hora:</b> " . $_GET["start_date"] . 
	($category==""?"":" / <b>Género:</b> ".$category) .
	($channel==""?"":" / <b>Canal:</b> ".$chns[$channel]["name"]) .
	($title==""?"":" / <b>Título:</b> ".$title) .
	($actor==""?"":" / <b>Actor o director:</b> ".$actor);
$today = date("Y-m-d");

$result = db_query( $sql );
$n_results = db_numrows($result);
?>
	<table class="cat_table" width="100%">
			<tr class="cat_td_line_1"><td colspan="4" style="text-align:center"><?=$search_string?></td></tr>
      <tr class="cat_table_title">
        <th class="cat_table_title" width="40"><div class="chn_title">CANAL</div></th>
        <th class="cat_table_title" width="200"><img class="goback" onclick="GridApp.goNext(-1)" src="http://<?=$URL_PPA?>/ppa/ppawebgrid/images/goback.gif" /><div class="hour">Programa</div></th>
        <th class="cat_table_title" width="100"><div class="hour">Fecha</div></th>
        <th class="cat_table_title"><div class="hour">Más Información</div><img class="gonext" onclick="if(<?= ($n_results < $STEP?"false":"true")?>)GridApp.goNext(1);else alert('No hay más resultados para la búsqueda');" src="http://<?=$URL_PPA?>/ppa/ppawebgrid/images/gonext.gif" /></th>
      </tr>
<?	
		while( $row = db_fetch_array($result) )
		{
?>
      <tr height="45">
			  <td class="<?= ($line%2) == 0 ? "cat_td_title_1" : "cat_td_title_2" ?>" onclick="document.getElementById('ppa_channel').value = <?=$row["id_channel"]?>;GridApp.consultGrid()">
			  	<div class="ppa_chn_info"><img src="<?=( $chns[$row["id_channel"]]["logo"] != "" ? "http://" . $URL_PPA . "/ppa/ppa/" . $chns[$row["id_channel"]]["logo"] : "http://" . $URL_PPA . "/ppa/images/empty.gif" )?>"/><div class="ppa_chn_name"><?= $chns[$row["id_channel"]]["name"] ."<br/>". $chns[$row["id_channel"]]["number"] ?></div></div></td>
        <td style="cursor:pointer" onmouseover="GridApp.hover(this)" onmouseout="GridApp.hout(this)" onmouseup="GridApp.synopsisPopUp(<?= $row['id'] ?>, event);" class="cat_td_line_1"><div class="program"><?=xmlentities($row['title'])?></div></td>
        <td style="cursor:pointer" onmouseover="GridApp.hover(this)" onmouseout="GridApp.hout(this)" onmouseup="GridApp.synopsisPopUp(<?= $row['id'] ?>, event);" class="cat_td_line_1"><?=date("Y-m-d h:i a", strtotime($row['date'] ." ". $row['time']))?></td>
        <td style="cursor:pointer" onmouseover="GridApp.hover(this)" onmouseout="GridApp.hout(this)" onmouseup="GridApp.synopsisPopUp(<?= $row['id'] ?>, event);" class="cat_td_line_1"><?=xmlentities($row["description"])?><?=$row["director"]!=""?"<br/>Director:" . xmlentities($row["director"]):""?><?=$row["starring"]!=""?"<br/>Actores:" . xmlentities($row["starring"]):""?></td>
      </tr>
<? } ?>
</table>
