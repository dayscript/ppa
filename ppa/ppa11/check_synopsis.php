<?
require_once("include/util.inc.php");
require_once("ppa/config.php");
$date = isset( $_GET['date'] ) ? $_GET['date'] : date("Y-m");

function sinopsisQuery( $prg_type )
{
	global $date;
	switch( $prg_type )
	{
		case "serie":
			$fields      = "serie.id, chapter.id cid, slot.title slt_title, serie.title ser_title, serie.spanishtitle ser_title_sp, serie.rated";
		break;
		case "special":
			$fields      = "special.id, chapter.id cid, slot.title slt_title, special.title sp_title, special.spanishtitle sp_title_sp, special.rated";
		break;
		case "movie":
			$fields      = "movie.id, chapter.id cid, slot.title slt_title, movie.title mov_title, movie.spanishtitle mov_title_sp, movie.englishtitle mov_title_en, movie.rated, movie.tvrated, movie.year";
		break;
	}

	$sql = "SELECT " . $fields . " FROM slot, slot_chapter, chapter, " . $prg_type . " " .
	  "WHERE " .
	  " slot.id = slot_chapter.slot AND " .
	  " chapter.id = slot_chapter.chapter AND " .
	  " chapter." . $prg_type . " = " . $prg_type . ".id AND " .
	  " chapter." . $prg_type . " > 0 AND " .
	  " slot.channel = '" . $_GET['id'] . "' AND " .
	  " slot.date >= '" . $date . "-01'" .
	  " GROUP BY slot_chapter.chapter ORDER BY slot.title";

	$result = db_query ( $sql );
	
	$str = "[";
	while( $row = db_fetch_array( $result, MYSQL_ASSOC ) )
	{
		$str .= "[";
		foreach($row as $r)
		{
			$str .= "\"" . addslashes( ereg_replace( "\n|\r", "", $r ) ) . "\", ";
		}
		$str = substr( $str, 0, -2 ) . "], ";
	}
	$str = ereg_replace(", $", "", $str ) . "]";
	return $str;
}

$s_result  = sinopsisQuery( "serie" );
$sp_result = sinopsisQuery( "special" );
$m_result  = sinopsisQuery( "movie" );

$chn       = new Channel( $_GET['id'] );
?>
<script language="javascript" src="include/Table.js"></script>
<script>
function markProgram( id )
{
	var program = document.getElementById( id );
	program.checked = true;
}

function arrayToTable( tit, page, arr )
{
	if( arr.length == 0 ) return false;
	table = new Table( arr.length, (arr[0].length + 3) );
	table.appendInId( "div_serie" );
	table.setAttribute( "align", "center" );
	table.setAttribute( "border", "0" );
	table.setAttribute( "width", "100%" );
	table.getTable().className = "textos";
	for( var i=0; i<arr.length; i++ )
	{
		table.getRow(i).bgColor     = "#DDEEFF";
		table.getRow(i).onmouseover = new Function( "this.setAttribute('bgcolor', '#99EEFF', 0); return false;" );
		table.getRow(i).onmouseout  = new Function( "this.setAttribute('bgcolor', '#DDEEFF', 0); return false;" );
		table.getRow(i).onmousedown = new Function( "markProgram('" + arr[i][0] + "')" );

		for( var j=0; j<arr[0].length; j++ )
		{
			table.getCell(i,j).innerHTML = arr[i][j];
		}
		table.getCell(i,j).innerHTML   = "<a href=\"ppa.php?edit_" + page + "=1&id=" + arr[i][0] + "\" target=\"blank\">ver</a>";
		table.getCell(i,j+1).innerHTML = "<a href=\"#;\" onclick=\"deleteSynopsis('" + arr[i][1] + "')\">eliminar</a>";
		table.getCell(i,j+2).innerHTML = "<input type=\"radio\" id=\"" + arr[i][0] + "\" name=\"checked\" >";
	}
	var cell = table.insertRow(0).firstChild;
	table.setColSpan( 0, 0, arr[0].length + 2);
	cell.innerHTML = tit;
	cell.setAttribute( "align", "center" );
	return true;
}

function deleteSynopsis( chapter )
{
	window.open("?location=del_synopsis&nohead=true&channel=<?=$chn->getId()?>&chapter=" + chapter + "&date=<?=$date?>", "", "width=200, height=100");
}

function init()
{
	var series   = <?=$s_result?>;
	var specials = <?=$sp_result?>;
	var movies   = <?=$m_result?>;
	
	arrayToTable( "Series", "series", series );
	arrayToTable( "Especiales", "special", specials );
	arrayToTable( "Películas", "movie", movies );
}
window.onload = init;
</script>
<div id="otrodiv"class="titulo" ><?=$chn->getId()?>. <?=$chn->getName()?></div>
<div class="description">(<?=$chn->getDescription()?>)</div>
<div id="div_serie" style="width:95%;"></div>