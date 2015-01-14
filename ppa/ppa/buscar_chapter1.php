<?
session_start();
require_once("config1.php");
print_r( $_GET );
if( isset( $_GET['dia'] ) || isset( $_POST['dia'] )){
  $dia = $_GET['dia'];
  if( trim( $dia ) == "" ){
    $dia = $_POST['dia'];
  }
}else{
  $dia = "0";
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
if( isset( $_GET['tipo_archivo'] ) || isset( $_POST['ano'] ) ){
  $tipo_archivo = $_GET['tipo_archivo'];
  if( trim( $tipo_archivo ) == "" ){
    $tipo_archivo = $_POST['tipo_archivo'];
  }
}else{
  $tipo_archivo = "1";
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
    $slot = new Slot();
    $slot->addChapter( $chapter );
    $slot->setChannel( $channel->getId() );
    $slot->setDate( $_POST['date'] );
    $slot->setTime( $_POST['time'] );
    $slot->setDuration( $_POST['duration'] );
    $slot->commit();
    $channel->addSlot( $slot );
    $channel->commit();
    $_SESSION['programs_found'][$_POST['chapter']] = $_POST['program'];
    $slot_asignado = true;
	$cache_string = '$cache["' . $_POST['cache_title'] . '"] = "' . $_POST['chapter'] . '";';
	$cache_file = fopen("cache.txt", "a");
	fputs($cache_file,$cache_string . "\n");
	fclose($cache_file);
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
-->
</style>
</head>
<body>
<? if( $slot_asignado ){?>
<script>
//window.opener.location = "../ppa.php?paso=2&dia=<?=$dia?>&mes=<?=$mes?>&ano=<?=$ano?>&tipo_archivo=<?=$tipo_archivo?>&id=<?=$channel->getId()?>&asign_program=1&bd=<?=$bd?>&rand=<?=rand(1,100)?>";
//window.opener.location = "channels_prueba.php?paso=2&dia=<?=$dia?>&mes=<?=$mes?>&ano=<?=$ano?>&tipo_archivo=<?=$tipo_archivo?>&id=<?=$channel->getId()?>&asign_program=1&bd=<?=$bd?>&rand=<?=rand(1,100)?>";
window.close();
</script>
<? } ?>
<form name="chapters" method="post" action="buscar_chapter1.php">
<?
$program = $_GET['program'];
if(isset($cache[$program]) && $cache[$program]!=""){
    $slot_asignado = true;
}
if( $slot_asignado ){?>
<script>
window.close();
</script>
<? } 

$program1 = explode(" ", $program );

$sql = "select id, title, spanishTitle from chapter where ";
if( strstr( $program, "'" ) ){
  $sql .= ' title like "%'.trim( $program ).'%" or spanishTitle like "%'.trim( $program ).'%"'; 
}else{
  $sql .= " title like '%".trim( $program )."%' or spanishTitle like '%".trim( $program )."%'"; 
}

for( $i = 0; $i < count( $program1 ); $i++ ){
  if( strlen( $program1[$i] ) >= 3 && !strstr( strtolower( $program1[$i] ), "the" ) && !strstr( strtolower( $program1[$i] ), "and" ) && !strstr( strtolower( $program1[$i] ), "los" ) && !strstr( strtolower( $program1[$i] ), "les" ) && !strstr( strtolower( $program1[$i] ), "las" ) ){
    if( strstr( $program1[$i], ":") ){
      $pos = strpos( $program1[$i], ":" );
      $program1[$i] = substr( $program1[$i], 0, $pos );
    }
    if( strstr( $program1[$i], ",") ){
      $pos = strpos( $program1[$i], "," );
      $program1[$i] = substr( $program1[$i], 0, $pos );
    }
    if( strstr( $program1[$i], "'" ) ){
	$sql .= ' or title like "%'.trim( $program1[$i] ).'%" or spanishTitle like "%'.trim( $program1[$i] ).'%"'; 
    }else{
      $sql .= " or title like '%".trim( $program1[$i] )."%' or spanishTitle like '%".trim( $program1[$i] )."%'";       
    }
  }
}
$sql .= "order by title";
$query = db_query( $sql );
while( $row = db_fetch_array( $query ) ){
  $found_programs[$row['id']] = $row['title'];
  $found_programs1[$row['id']] = $row['spanishTitle'];
}

?>
<b><font size="2">Buscando Programa:</font></b><br><?=$program?><br><br>
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
<input type = "hidden" name="bd" value="<?=$bd?>">
<input type = "hidden" name="dia" value="<?=$dia?>">
<input type = "hidden" name="mes" value="<?=$mes?>">
<input type = "hidden" name="ano" value="<?=$ano?>">
<input type = "hidden" name="tipo_archivo" value="<?=$tipo_archivo?>">
<input type = "hidden" name="date" value="<?=$date?>">
<input type = "hidden" name="time" value="<?=$time?>">
<input type = "hidden" name="cache_title" value="<?=$program?>">
<input type = "hidden" name="duration" value="<?=$duration?>">
<input type = "hidden" name="channel" value="<?=$channel?>">
<input type = "hidden" name="program" value="<?=$program?>">
<input type="submit" name="asignar" value="Asignar">
</form>
<?}?>
<?
if( count( $found_programs ) == 0 ){
?>
<b><font size="2">No se encontraron programas</font></b>
<?
}
?>
</body>
</html>
