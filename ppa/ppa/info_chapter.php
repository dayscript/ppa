<?
require_once("config1.php");

if( isset( $_GET['id'] ) ){
  $id = $_GET['id'];
}else{
  $id = 1;
}

$chapter = new Chapter( $id );
$title = $chapter->getTitle();
$titleEs = $chapter->getspanishTitle();
$description = $chapter->getDescription();
$movie = $chapter->getMovie();
$serie = $chapter->getSerie();
$special = $chapter->getSpecial();
if( trim( $movie ) != "0" ){
  $tipo = "Pel&iacute;cula";
}else{
  if( trim( $serie ) != "0" ){
    $tipo = "Serie";  
  }else{
    if( trim( $special ) != "0" ){
      $tipo = "Especial";  
     }
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
<table>
<? if( $tipo == "Serie" ){
   $serie = new Serie( $chapter->getSerie() );
   $ano = $serie->getYear();
?>
<tr>
<td><b>A&ntilde;o: </b><?=$ano?></td>
</tr>
<? }else{
     if( $tipo == "Pel&iacute;cula" ){
       $movie = new Movie( $chapter->getMovie() );
       $ano = $movie->getYear();
?>
<tr>
<td><b>A&ntilde;o: </b><?=$ano?></td>
</tr>
<?	   
	 } 
} ?>
<tr>
<td><b>Tipo: </b><?=$tipo?></td>
</tr>
<tr>
<td><b>T&iacute;tulo: </b><?=$title?></td>
</tr>
<tr>
<td><b>T&iacute;tulo Espa&ntilde;ol: </b><?=$titleEs?></td>
</tr>
<tr>
<td><b>Descripci&oacute;n: </b><?=$description?></td>
</tr>
<? if( $tipo == "Serie" ){
   $serie = new Serie( $chapter->getSerie() );
   $protagonistas = $serie->getStarring();
?>
<tr>
<td><b>Protagonistas: </b><?=$protagonistas?></td>
</tr>
<? }else{
     if( $tipo == "Pel&iacute;cula" ){
       $movie = new Movie( $chapter->getMovie() );
       $actores = $movie->getActors();
?>
<tr>
<td><b>Actores: </b><?=$actores?></td>
</tr>
<?	   
	 }else{
	   if( $tipo == "Especial" ){
         $special = new Special( $chapter->getSpecial() );
         $protagonistas = $special->getStarring();	   
?>		 
<tr>
<td><b>Protagonistas: </b><?=$protagonistas?></td>
</tr>
<?		 
	   }
	 }	 
} ?>
</table>
</body>
</html>