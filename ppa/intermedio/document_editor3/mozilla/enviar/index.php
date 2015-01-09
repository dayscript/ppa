<?
	session_start();
?>
<html>
<head>
<title>Envio Correos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
	<?
		if(!session_is_registered('ruta_archivo')){
			?>
			alert("Debe tener un Documento Abierto para poderlo Enviar");
			window.close();
			<?
		}
	?>
	function abrirArchivo(){
		document.location="menu_empty.php?abrir="+document.getElementById('nombre_archivo').value;
	}
</script>
<style>
	TH {
		FONT-WEIGHT: normal;
		FONT-SIZE: 11px;
		COLOR: #FFFFFF;
		FONT-FAMILY: Verdana, Helvetica, sans-serif;
	}

	TD, A {
		FONT-WEIGHT: normal;
		FONT-SIZE: 11px;
		COLOR: #FFFFFF;
		FONT-FAMILY: Verdana, Helvetica, sans-serif;
		TEXT-DECORATION: none
	}
</style>
</head>
<body bgcolor="#EEEEFF">
<table width="90%" align=center>
<form action="index2.php" method="post">
	<th bgcolor="9999FF" colspan=2>
		Envio De Correos
	</th>
	<tr height=40>
		<td bgcolor="9999FF" width="40%">
			Escriba el Asunto Del Correo
		</td>
		<td bgcolor="DDDDFF" align=center >
			<input type="text" name="asunto" size=30>
		</td>
	</tr>
	<tr height=40>
		<td bgcolor="9999FF" >
			Escriba quien envia el Correo
		</td>
		<td bgcolor="DDDDFF" align=center>
			<input type="text" name="from" size=30>
		</td>
	</tr>
	<tr height=40>
		<td bgcolor="9999FF" >
			Utilizar Base De datos para los Correos?
		</td>
		<td bgcolor="DDDDFF" align=center>
			<input type="radio" name="uso_base_de_datos" value="true" checked><font color="#333333">Si</font>
			<input type="radio" name="uso_base_de_datos" value="false"><font color="#333333">No</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="9999FF" align=center colspan=2>
			<input type="submit" value="Siguiente >>" >
		</td>
	</tr>
</form>
</table>
</body>
</html>
