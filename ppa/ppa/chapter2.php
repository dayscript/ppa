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
if( isset( $_GET['time'] )  ){
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

$program = $_GET['program'];
$program = str_replace("\n", "", $program);
$program = str_replace("\r", "", $program);
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
<script>
<? if( $_GET['tipo'] == 'movie' ){
  if( strstr( $program, "'" ) ){
  ?>
 window.location="movies.php?new=1&add_movie=1&slot=<?=$slot?>&popup=1&dia=<?=$dia?>&mes=<?=$mes?>&ano=<?=$ano?>&time=<?=$time?>&date=<?=$date?>&duration=<?=$duration?>&channel=<?=$channel?>&program=<?=$program?>";
 window.resizeTo(625,600);
<?   
   }else{
?>
 window.location='movies.php?new=1&add_movie=1&slot=<?=$slot?>&popup=1&dia=<?=$dia?>&mes=<?=$mes?>&ano=<?=$ano?>&time=<?=$time?>&date=<?=$date?>&duration=<?=$duration?>&channel=<?=$channel?>&program=<?=$program?>';
 window.resizeTo(625,600);
<?
   }
?>
<?}?>
<? 
if( $_GET['tipo'] == 'series' ){
  if( strstr( $program, "'" ) ){
  ?>
 window.location="series.php?new=1&add_series=1&slot=<?=$slot?>&popup=1&dia=<?=$dia?>&mes=<?=$mes?>&ano=<?=$ano?>&time=<?=$time?>&date=<?=$date?>&duration=<?=$duration?>&channel=<?=$channel?>&program=<?=$program?>";
 window.resizeTo(625,625);
  <?}else{?>
 window.location='series.php?new=1&add_series=1&slot=<?=$slot?>&popup=1&dia=<?=$dia?>&mes=<?=$mes?>&ano=<?=$ano?>&time=<?=$time?>&date=<?=$date?>&duration=<?=$duration?>&channel=<?=$channel?>&program=<?=$program?>';
 window.resizeTo(625,625);
  <?}?>
<?}?>
<? 
if( $_GET['tipo'] == 'especiales_documentales' ){
  if( strstr( $program, "'" ) ){
  ?>
 window.location="specials.php?new=1&add_special=1&slot=<?=$slot?>&popup=1&dia=<?=$dia?>&mes=<?=$mes?>&ano=<?=$ano?>&time=<?=$time?>&date=<?=$date?>&duration=<?=$duration?>&channel=<?=$channel?>&program=<?=$program?>";
 window.resizeTo(625,600);
<?
   }else{
?>
 window.location='specials.php?new=1&add_special=1&slot=<?=$slot?>&popup=1&dia=<?=$dia?>&mes=<?=$mes?>&ano=<?=$ano?>&time=<?=$time?>&date=<?=$date?>&duration=<?=$duration?>&channel=<?=$channel?>&program=<?=$program?>';
 window.resizeTo(625,600);
<?
   }
}?>
</script>
<form name="f1" action="chapter2.php" method="get">
<table align="center">
<tr>
<td>
Tipo: 
</td>
<td>
<select name="tipo" onChange="window.document.forms[0].submit()">
<option>Seleccione un tipo</option>
<option value="movie">Pel&iacute;cula</option>
<option value="series">Series</option>
<option value="especiales_documentales">Especiales y Documentales</option>
</select>
<input type = "hidden" name="slot" value="<?=$slot?>">
<input type = "hidden" name="program" value="<?=$program?>">
<input type = "hidden" name="mes" value="<?=$mes?>">
<input type = "hidden" name="ano" value="<?=$ano?>">
<input type = "hidden" name="date" value="<?=$date?>">
<input type = "hidden" name="time" value="<?=$time?>">
<input type = "hidden" name="duration" value="<?=$duration?>">
<input type = "hidden" name="channel" value="<?=$channel?>">
</td>
</tr>
</table>
</form>
</body>
</html>