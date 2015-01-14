<?
$MAXITEMS = 30;
if( isset( $_GET['prg_type'] ) )
{
	require_once("include/util.inc.php");
	require_once("ppa/config.php");

	$initpos  = isset( $_GET['initpos'] ) ? $_GET['initpos'] : 0;
		
	function sinopsisQuery( $prg_type )
	{
		global $MAXITEMS, $initpos;
		
		switch( $prg_type )
		{
			case "serie":
				$fields      = "serie.id, chapter.id cid, chapter.title ctitle, serie.title title, serie.spanishtitle stitle, serie.gender, serie.rated, serie.starring, chapter.description cdescription, serie.description sdescription ";
			break;
			case "special":
				$fields      = "special.id, chapter.id cid, chapter.title ctitle, special.title title, special.spanishtitle stitle, special.gender, special.rated, special.starring, chapter.description cdescription, special.description sdescription";
			break;
			case "movie":
				$fields      = "movie.id, chapter.id cid, movie.title title, movie.spanishtitle stitle, movie.englishtitle mtitlem, movie.gender, movie.rated, movie.tvrated, movie.actors, movie.director, chapter.description cdescription, movie.description mdescription";
			break;
		}
	
		$sql = "SELECT " . $fields . " FROM chapter, " . $prg_type . " " .
		  "WHERE " .
		  " chapter." . $prg_type . " = " . $prg_type . ".id AND " .
		  " chapter." . $prg_type . " > 0 AND " .
		  " chapter.title like '" . $_GET['l'] . "%' " .
		  " ORDER BY chapter.title" .
		  " LIMIT " . $initpos . ", " . $MAXITEMS;
	
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
	$chapters = sinopsisQuery( $_GET['prg_type'] );
}
else $chapters = "[]";

?>
<script language="javascript" src="include/Table.js"></script>
<script language="javascript" src="include/LoadXML.js"></script>
<script>
function popupShowSynopsis( chapter )
{
	if( prg_type == "serie" )
		window.open( "ppa.php?edit_series=1&id=" + chapter );
	else
		window.open( "ppa.php?edit_" + prg_type + "=1&id=" + chapter );
}

function arrayToTable( arr )
{
	if( arr.length == 0 ) return false;
	var table = new Table( 0, arr[0].length + 1 );
	var row;
	var col;

	table.setAttribute( "align", "center" );
	table.setAttribute( "border", "0" );
	table.setAttribute( "width", "100%" );
	table.getTable().className = "textos";

	for( var i=0; i<arr.length; i++ )
	{
		row = table.insertRow();
		row.bgColor     = "#DDEEFF";
		row.onmouseover = new Function( "this.setAttribute('bgcolor', '#99EEFF', 0); return false;" );
		row.onmouseout  = new Function( "this.setAttribute('bgcolor', '#DDEEFF', 0); return false;" );
		row.onmouseup   = new Function( "popupShowSynopsis(" + arr[i][0] + ")" );

		col = row.firstChild;

		for( var j=0; j<arr[0].length; j++ )
		{
			col.innerHTML = arr[i][j];
			col = col.nextSibling;
		}
	}
	table.appendInId( "div_serie" );
	return true;
}

function listSynopsis( l )
{
	document.location = "ppa11.php?location=list_synopsis&prg_type=" + prg_type + "&l=" + l;
}

function movePos( dir )
{
	document.location = "ppa11.php?location=list_synopsis&prg_type=" + prg_type + "&l=" + letter + "&initpos=" + ( initpos + (dir*MAXITEMS) );
}

function init()
{
	var chapters   = <?=$chapters?>;
	document.getElementById( prg_type ).checked = "true";
	
	arrayToTable( chapters );
}
var prg_type  = "<?=isset($_GET['prg_type'])?$_GET['prg_type']:"serie"?>";
var letter    = "<?=isset($_GET['l'])?$_GET['l']:"1"?>";
var initpos   = <?=isset($_GET['initpos'])?$_GET['initpos']:"0"?>;
var MAXITEMS = <?=$MAXITEMS?>;

window.onload = init;
</script>
<div id="state_div" style="position:absolute; left:0px; top:0px;"></div>
	<div>
	<form name="f">
		<input id="serie" type="radio" onmouseup="prg_type=this.value" name="prg_type" value="serie" checked="true">Series
		<input id="movie" type="radio" onmouseup="prg_type=this.value" name="prg_type" value="movie" >Películas
		<input id="special" type="radio" onmouseup="prg_type=this.value" name="prg_type" value="special" >Especiales
	</form>
	<br> 
<? for( $i=0; $i<=9; $i++ ){ ?>
	<a href="#;" onmouseup="listSynopsis('<?=$i?>')"><?=$i?></a> /
<?}?><br>
<? for( $i=65; $i<=90; $i++ ){ ?>
	<a href="#;" onmouseup="listSynopsis('<?=chr($i+32)?>')"><?=chr($i)?></a> /
<?}?>
</div>
<div id="navigation_top" align="center" style="width:95%;" align="left">	
	<a href="#;" onmouseup="movePos(-1);"><<</a> --
	<a href="#;" onmouseup="movePos(1);">>></a> 
</div>
<div id="div_serie" style="width:95%;"></div>
<div id="navigation_button" align="center" style="width:95%;" align="left">	
	<a href="#;" onmouseup="movePos(-1);"><<</a> --
	<a href="#;" onmouseup="movePos(1);">>></a> 
</div>
