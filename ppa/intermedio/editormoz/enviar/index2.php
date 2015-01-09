<?
	session_start();
?>
<html>
<head>
<title>Envio Correos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
	<?
		$uso_base_de_datos=$_POST['uso_base_de_datos'];
		$from=$_POST['from'];
		$asunto=$_POST['asunto'];
		session_register('uso_base_de_datos');
		session_register('from');
		session_register('asunto');
		if($uso_base_de_datos=="false"){
			?>
				document.location="correo_manual.php";
			<?
		}
	?>
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
<form action="tablas.php" method="post">
	<th bgcolor="9999FF">
		Envio De Correos
	</th>
	<tr>
		<td bgcolor="9999FF">
			Por favor Seleccione la Base De Datos Que desea Utilizar
		</td>
	</tr>
	<tr>
		<td align=center bgcolor="DDDDFF" height=50>
			<select name="base_datos">
			<?
				$conexion=mysql_connect('localhost', 'dayscript', 'kfc3*9mn');
    				$base_de_datos = mysql_list_dbs($conexion);

    				for($i=0;$i<mysql_num_rows($base_de_datos);$i++)
        				echo "<option>".mysql_db_name($base_de_datos, $i)."\n";
    			?>
			<select>
		</td>
	</tr>
	<tr>
		<td bgcolor="9999FF" align=center>
			<input type="submit" value="Siguiente >>" >
		</td>
	</tr>
</form>
</table>
</body>
</html>
