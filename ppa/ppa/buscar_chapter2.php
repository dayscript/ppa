<?
session_start();
require_once("config1.php");

if( isset( $_GET['slot'] ) || isset( $_POST['slot'] )){
  $slot = $_GET['slot'];
  if( trim( $slot ) == "" ){
    $slot = $_POST['slot'];
  }
}else{
  $slot = "1";
}
if( isset( $_GET['mes'] ) || isset( $_POST['mes'] )){
  $mes = $_GET['mes'];
  if( trim( $mes ) == "" ){
    $mes = $_POST['mes'];
  }
}else{
  $mes = "01";
}
if( isset( $_GET['ano'] ) || isset( $_POST['ano'] )){
  $ano = $_GET['ano'];
  if( trim( $ano ) == "" ){
    $ano = $_POST['ano'];
  }
}else{
  $ano = "2003";
}
if( isset( $_GET['time'] ) ){
  $time = $_GET['time'];
}else{
  $time = "00:00";
}
if( isset( $_GET['date'] ) ){
  $date = $_GET['date'];
}else{
  $date = "2003-01-01";
}
if( isset( $_GET['duration'] ) ){
  $duration = $_GET['duration'];
}else{
  $duration = "";
}
if( isset( $_GET['channel'] ) ){
  $channel = $_GET['channel'];
}else{
  $channel = 0;
}
if( isset( $_POST['asignar'] ) ){
  if( trim( $_POST['channel'] ) != "" && trim( $_POST['chapter'] ) != "" ){
    $channel = new Channel( $_POST['channel'] );
    $chapter = new Chapter( $_POST['chapter']);
    $slot = new Slot( $_POST['slot']);
    $slot->addChapter( $chapter );
    $slot->setChannel( $channel->getId() );
    $slot->setDate( $_POST['date'] );
    $slot->setTime( $_POST['time'] );
    $slot->setDuration( $_POST['duration'] );
    $slot->commit();
    $channel->addSlot( $slot );
    $channel->commit();
    $_SESSION['programs_found'][$_POST['chapter']] = stripslashes($_POST['program']);
    $slot_asignado = true;
  }
}
?>
<html>
<head>
<title>
PPA
</title>
<style type="text/css">
<!--
a {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #1111FF;
	text-decoration: none;
}
body,table {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #444444;
}
input, select, textarea, {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #444444;
	border: 1px solid #000000;
	background-color: #DDEEFF;
}
.large {
	width: 300;
}
.new_search
{
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #F3893A;
	cursor: pointer;
}
-->
</style>
</head>
<body>
<? if( $slot_asignado ){?>
<script>
//window.opener.location = "../ppa.php?asign_chapters=1&paso=2&mes=<?=$mes?>&ano=<?=$ano?>&channel=<?=$channel->getId()?>&rand=<?=rand(1,100)?>";
window.close();
</script>
<? } ?>
<form name="chapters" method="post" action="buscar_chapter2.php">
<?
//$program = urldecode( stripslashes($_GET['program']) );
//$program = stripslashes($_GET['program']);<salto>$program = str_replace( "\'", "'", $program );<salto>$program = str_replace( '\"', '"', $program );<salto><salto>$program1 = explode(" ", $program );<salto>

$program = $_GET['program'];
if( trim( $program ) != "" ){
//	$sql = "select id, title, spanishTitle from chapter where ";<salto>	if( strstr( $program, "'" ) ){<salto>  		$sql .= ' title like "%'.trim( $program ).'%" or spanishTitle like "%'.trim( $program ).'%"'; <salto>	}else{<salto> 		 $sql .= " title like '%".trim( $program )."%' or spanishTitle like '%".trim( $program )."%'"; <salto>	}<salto>	for( $i = 0; $i < count( $program1 ); $i++ ){<salto>  		if( strlen( $program1[$i] ) >= 3 && ( !strstr( strtolower( $program1[$i] ), " the " ) && !strstr( strtolower( $program1[$i] ), " and " ) && !strstr( strtolower( $program1[$i] ), "los" ) && !strstr( strtolower( $program1[$i] ), "les" ) && !strstr( strtolower( $program1[$i] ), "las" )) )<salto>  		{<salto>    			if( strstr( $program1[$i], ":") )<salto>    			{<salto>      				$pos = strpos( $program1[$i], ":" );<salto>      				$program1[$i] = substr( $program1[$i], 0, $pos );<salto>    			}<salto>	    		if( strstr( $program1[$i], ",") )<salto>	    		{<salto>	      			$pos = strpos( $program1[$i], "," );<salto>	      			$program1[$i] = substr( $program1[$i], 0, $pos );<salto>	    		}<salto>			if( strstr( $program1[$i], "-") )<salto>			{<salto>      				$pos = strpos( $program1[$i], "-" );<salto>      				$program1[$i] = substr( $program1[$i], 0, $pos );<salto>    			}<salto>    			if( strstr( $program1[$i], "'" ) )<salto>    			{<salto>				$sql .= ' or title like "%'.trim( $program1[$i] ).'%" or spanishTitle like "%'.trim( $program1[$i] ).'%"'; <salto>    			}<salto>    			else<salto>    			{<salto>      				$sql .= " or title like '%".trim( $program1[$i] )."%' or spanishTitle like '%".trim( $program1[$i] )."%'";       <salto>    			}<salto>  		}<salto>	}<salto>	$sql .= "order by title";
	
	$program = explode(" ", $program);
	if( count( $program ) > 1 )
	{
		foreach( $program as $word )
		{
			if( strlen( $word ) > 3 ) 
			{
				$spanishtitle .= "spanishtitle like '%" . addslashes( $word ) . "%' AND " ;
				$title .= "title like '%" . addslashes( $word ) . "%' AND " ;
			}
		}
	}
	else
	{
		$spanishtitle .= "spanishtitle like '%" . addslashes( $program[0] ) . "%' AND " ;
		$title .= "title like '%" . addslashes( $program[0] ) . "%' AND " ;		
	}
	
	$spanishtitle = substr( $spanishtitle, 0, -5 ); //strlen( " AND " ) = 5;
	$title = substr( $title, 0, -5 );
	$sql = "SELECT id, title, spanishTitle " . 
		"FROM chapter " .
		"WHERE " .
		"( " . $title . " ) OR " .
		"( " . $spanishtitle . " )";
		
	$query = db_query( $sql );
	while( $row = db_fetch_array( $query ) ){
  		$found_programs[$row['id']] = $row['title'];
  		$found_programs1[$row['id']] = $row['spanishTitle'];
	}
?>
<b><font size="2">Buscando Programa:</font></b><br><?=$_GET['program']?><br><br>
<? if( count( $found_programs ) > 0 ){?>
<b><font size="2">Programas Encontrados:</font></b>
<br>
<br>
<table>
<?
$keys = array_keys( $found_programs );
for( $i = 0; $i < count( $found_programs ); $i++ ){
  echo "<tr><td>";
  echo "<b><a href='#' onClick='window.open(\"info_chapter.php?id=$keys[$i]\", \"null1\",\"height=150,width=300,status=yes,toolbar=no,menubar=no,location=no,scrollbars=yes\" )'><font size='1' color='#217199'>".$found_programs[$keys[$i]]."</font>&nbsp;/&nbsp;<font size='1' color='#009966'>".$found_programs1[$keys[$i]]."</font></a></b>";
  echo "<input type='radio' name='chapter' value='".$keys[$i]."'>";
  echo "</td></tr>";
}
?>
</table>
<br>
<input type = "hidden" name="program" value="<?=isset($_GET['oriprogram']) ? $_GET['oriprogram'] : $_GET['program']?>">
<input type = "hidden" name="ano" value="<?=$ano?>">
<input type = "hidden" name="mes" value="<?=$mes?>">
<input type = "hidden" name="slot" value="<?=$slot?>">
<input type = "hidden" name="date" value="<?=$date?>">
<input type = "hidden" name="time" value="<?=$time?>">
<input type = "hidden" name="duration" value="<?=$duration?>">
<input type = "hidden" name="channel" value="<?=$channel?>">
<input type="submit" name="asignar" value="Asignar">
<input type="button" name="regresar" value="Regresar" onClick="history.back()">
<? } 
 }
?>
</form>
<?
if( count( $found_programs ) == 0 ){
?>
<b><font size="2">No se encontraron programas</font></b><br><br>
<?
}
?>
<? if( !isset($_GET['oriprogram'] ) ) 
{?>
Seleccione las palabras para la siguiente búsqueda (click) <br>
<script>
	function fillSearch(t)
	{
		if( document.mysearch.title.value )
			document.mysearch.title.value = document.mysearch.title.value + " " + t.firstChild.data;
		else
			document.mysearch.title.value = t.firstChild.data;
	}
	
	function doSearch( )
	{
		document.location = "?<?=eregi_replace("&program=", "&oriprogram=", $_SERVER['QUERY_STRING'] )?>&program=" + document.mysearch.title.value;
	}
</script>
<form id="25" name="mysearch">
<?
	$words = explode(" ", ereg_replace("\"", "", trim( $_GET['program'] ) ) );
	foreach( $words as $word )
	{
		echo "<span class=\"new_search\" onClick=\"fillSearch(this)\">" . $word ."</span> ";
	}
?>
	<br><br>
	<input name="title" type="text" />
	<input onClick="doSearch()" type="button" value="Enviar" />
</form>
<? } ?>
</body>
</html>
