<?
	session_start();
?>
<html>
<head>
<?
	if(isset($_GET['abrir'])){

		$ruta_archivo=$_GET['abrir'];
		$ruta_archivo=substr($ruta_archivo,strrpos($ruta_archivo,"/"),strlen($ruta_archivo));
		session_register('ruta_archivo');

		?>
		<script>
			top.opener.document.getElementById("edit").src="..<?= $ruta_archivo ?>";
			top.close();
		</script>
		<?
	}
?>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
	function abrirArchivo(){
		document.location="menu_empty.php?abrir="+document.getElementById('nombre_archivo').value;
	}
</script>
</head>
<body bgcolor="#D4D0C8">
<table width="90%">
	<tr>
		<td align=left width="70%">
			<input type="text" id="nombre_archivo" name="nombre_archivo" size="40">
		</td>
	</tr>
	<tr>
		<td>
			<input type="button" value="abrir" onClick="javascript:abrirArchivo();">
		</td>
	</tr>
</table>
</body>
</html>

