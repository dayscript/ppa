<?
require_once("include/util.inc.php");
require_once("ppa/config.php");

$sql = "SELECT description, serie, movie, special " .
	"FROM chapter " .
	"WHERE id = " . $_GET['chapter'];
	
$result = db_query($sql);
$row    = db_fetch_array( $result );
$description['long'] = $row['description'];
if( $row['movie'] != 0 )
{
	$prg_type = "Película";
	$sql = "SELECT englishTitle title, spanishTitle, description, year, actors, director " .
		"FROM movie " .
		"WHERE id = " . $row['movie'];
}
else if( $row['serie'] != 0 )
{
	$prg_type = "Serie";
	$sql = "SELECT title, spanishTitle, description, year, starring actors " .
		"FROM serie " .
		"WHERE id = " . $row['serie'];
}
else if( $row['special'] != 0 )
{
	$prg_type = "Especial";
	$sql = "SELECT title, spanishTitle, description, starring actors " .
		"FROM special " .
		"WHERE id = " . $row['special'];
}

$result = db_query($sql);
$row    = db_fetch_array( $result );
$description['short'] = $row['description'];
?>
<html>
<head>
<title>&gt;&gt;&gt;&gt;&gt; Dayware - PPA&lt;&lt;&lt;&lt;&lt;</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="include/estilos.css" type=text/css rel=stylesheet>
</head>
<body>
<table class="textos" style="border: 1px solid #0000CC;" width="100%" height="380">
	<tr>
		<td style="font-size: 18px;" class="titulo" colspan="2" align="center"><?=$prg_type?></td>
	</tr>
	<tr>
		<td class="titulo" >Titulo</td>
		<td><?=$row['title']?> / <?=$row['spanishTitle']?></td>
	</tr>
	<tr>
		<td class="titulo" >Año</td>
		<td><?=$row['year']?></td>
	</tr>
	<tr>
		<td class="titulo" >Actores</td>
		<td><?=$row['actors']?></td>
	</tr>
	<tr>
		<td class="titulo" >Director</td>
		<td><?=$row['director']?></td>
	</tr>
	<tr>
		<td class="titulo" >Descripcion larga</td>
		<td><?=$description['long']?></td>
	</tr>
	<tr>
		<td class="titulo" >Descripcion corta</td>
		<td><?=$description['short']?></td>
	</tr>
</table>
</body>
</html>