<?
include('../ppa/include/config.inc.php');
include('../include/db.inc.php');

define("CLIENT", 64);

$sql = "" .
	"SELECT movie.title, movie.actors, movie.director, chapter.description " .
	"FROM movie, chapter ".
	"WHERE ".
	"  chapter.movie =  movie.id " .
	"  AND movie.title like '". $_GET['title'] ."'";
$row = db_fetch_array( db_query($sql) );
$actors = $row['actors'];

if($row['title'] == "")
{
	$sql = "" .
		"SELECT serie.title, serie.starring, chapter.description " .
		"FROM serie, chapter ".
		"WHERE ".
		"  chapter.serie =  serie.id " .
		"  AND serie.title like '". $_GET['title'] ."'";

	$row = db_fetch_array( db_query($sql) );
	$actors = $row['starring'];
}
if($row['title'] == "")
{
	$sql = "" .
		"SELECT special.title, special.starring, chapter.description " .
		"FROM special, chapter ".
		"WHERE ".
		"  chapter.special =  special.id " .
		"  AND special.title like '". $_GET['title'] ."'";

	$row = db_fetch_array( db_query($sql) );
	$actors = $row['starring'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?=$row['title']=="" ? "Fechas de Emisión" : "Sinopsis"?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="320" border="0" cellspacing="0" cellpadding="2" align="center">
  <tr>
    <td align="center"><img src="images/logo_costavision.jpg" width="131" height="42"></td>
  </tr>
</table>
<?
if($row['title'] == "")
{
	include("title.php");
}
else
{
?>
<table width="320" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td valign="top"><table width="320" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td class="title_big_properties" background="images/tit_properties.jpg"><?=$row['title']?></td>
      </tr>
      <tr>
        <td height="3">&nbsp;</td>
      </tr>
      <tr>
        <td class="text_properties"><?=$row['description']?></td>
      </tr>
<? if($row['director']!="") { ?>
      <tr>
        <td height="20" class="title_properties">Director:</td>
      </tr>
      <tr>
        <td height="15" class="text_properties"> <?=$row['director']?> </td>
      </tr>
<? } ?>
<? if($actors!="") { ?>
      <tr>
        <td height="20" class="title_properties">Actores:</td>
      </tr>
      <tr>
        <td height="15" class="text_properties"> <?=$actors?> </td>
      </tr>
<? } ?>
    </table></td>
  </tr>
</table>
<? } ?>
<table width="320" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td align="right"><a href="http://www.dayscript.com" target="_blank"><img border="0" src="images/logo_dayscript.jpg"></a></td>
  </tr>
</table>
</body>
</html>
