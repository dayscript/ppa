<?
error_reporting(E_ERROR);
require_once("config1.php");
if( isset( $_GET['id_slot'] ) )
	$id_slot = $_GET['id_slot'];
else	if( isset( $_POST['id_slot'] ) )	
	$id_slot = $_POST['id_slot'];

if( isset( $_POST['send'] ) ){
	$sql = "UPDATE slot SET title = '".$_POST['title']."' WHERE id = ".$id_slot;
	db_query( $sql );	
}

$sql = "SELECT title FROM slot WHERE id = ".$id_slot;
$query = db_query( $sql );
$row = db_fetch_array( $query );
$slot_title = $row['title'];

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
<? if( isset( $id_slot ) ){?>
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
<table>
<tr><td>Nuevo Título:</td><td><input type="text" name="title" value="<?=$slot_title?>"></td></tr>
<tr><td colspan="2" align="center"><input type="hidden" name="id_slot" value="<?=$id_slot?>"><input type="submit" name="send" value="Enviar"></td></tr>
</table>
</form>
<? } ?>
</body>
</html>