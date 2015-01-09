<?
require_once('../include/db.inc.php');
require_once('../class/Application.class.php');
session_start();
$_SESSION['app']->connect();

if( isset( $_POST['descripcion'] ) ){
  $sql1 = "update p_program set actor = '".$_POST['actores']."', director = '".$_POST['director']."', description = '".$_POST['descripcion']."' where id = ".$_POST['id'];
  db_query( $sql1 );   
  $actualizar = true;
}else{
  $sql = " SELECT p.id, p.title, p.actor, p.director, p.description FROM p_sinopsis s, p_program p WHERE s.program = p.id AND s.id=".$_GET['id'];
  $query = db_query( $sql );
  $row = db_fetch_array( $query );
  $titulo = $row['title'];
  $actores = $row['actor'];
  $director = $row['director'];
  $descripcion = $row['description'];
  $id = $row['id'];
}
?>
<html>
<head>
<title>
>>>>> Dayware <<<<<
</title>
<link href="../estilos.css" type=text/css rel=stylesheet>
<?
if( $actualizar ){
?>
<script>
window.close()
</script>
<?
}
?>
</head>
<body>
<form method="post" action="edit_sinop.php">
<table width="100%">
<tr>
<td>Título: &nbsp; &nbsp; &nbsp;<?=$titulo?></td>
</tr>
<tr>
<td>Actores: <input type="text" size="50" name="actores" value="<?=$actores?>"></td>
</tr>
<tr>
<td>Director: <input type="text" name="director" value="<?=$director?>"></td>
</tr>
<tr>
<td>
<input type="hidden" name="id" value="<?=$id?>">
<textarea cols="60" rows="10" name="descripcion"><?=$descripcion?></textarea>
</td>
</tr>
<tr>
<td align="center">
<img src="images/boton_cambiar.gif" onclick="document.forms[0].submit()" >&nbsp;&nbsp;<img src="images/boton_cancelar.gif" onclick="window.close()">
</td>
</tr>
</table>
</form>
</body>
</html>
