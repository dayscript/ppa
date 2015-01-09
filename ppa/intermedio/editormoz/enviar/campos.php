
<html>
<head>
<title>Envio Correos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
<form action="final.php" method="post">
	<th bgcolor="9999FF">
		Envio De Correos - Base de Datos : <?= $_POST['base_datos'] ?>
	</th>
	<tr>
		<td bgcolor="9999FF">
			Por favor Seleccione El campo donde se encuentran los correos.
		</td>
	</tr>
	<tr>
		<td align=center bgcolor="DDDDFF" height=50>
			<select name="campo">
			<?
				$conexion=mysql_connect('localhost', 'dayscript', 'kfc3*9mn');
    				$campos = mysql_listfields($_POST['base_datos'],$_POST['tablas'],$conexion);

    				for($i=0;$i<mysql_num_fields($campos);$i++)
        				echo "<option>".mysql_field_name($campos, $i)."\n";
    			?>
			<select>
		</td>
	</tr>
	<tr>
		<td bgcolor="9999FF" align=center>
			<input type="hidden" name="tablas" value="<?= $_POST['tablas'] ?>">
			<input type="hidden" name="base_datos" value="<?= $_POST['base_datos'] ?>">
			<input type="submit" value="Siguiente >>" >
		</td>
	</tr>
</form>
</table>
</body>
</html>
