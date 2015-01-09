<?
	session_start();
	
	$destinatario=$_POST['destinatario'];
	session_register('destinatario');
?>
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
		FONT-SIZE: 12px;
		COLOR: #333333;
		FONT-FAMILY: Verdana, Helvetica, sans-serif;
		TEXT-DECORATION: none
	}
</style>
</head>
<body bgcolor="#EEEEFF">
<table width="90%" align=center>
<form action="enviar.php" method="post">
	<th bgcolor="9999FF">
		Envio De Correos
	</th>
	<? if($_SESSION['uso_base_de_datos']=="true"){ ?>
	<tr>
		<td bgcolor="DDDDFF" height=100 valign=center>
			Se utilizara la siguiente configuracion para el Envio de los correos:
			<li><b>De:<b>&nbsp;<i><?= $_SESSION['from'] ?></i></li>
			<li><b>Asunto:<b>&nbsp;<i><?= $_SESSION['asunto'] ?></i></li>
			<li><b>Base De Datos:<b>&nbsp;<i><?= $_POST['base_datos'] ?></i></li>
			<li><b>Tabla:<b>&nbsp;<i><?= $_POST['tablas'] ?></i></li>
			<li><b>Campo:<b>&nbsp;<i><?= $_POST['campo'] ?></i></li>
		</td>
	</tr>
	<tr>
		<td bgcolor="9999FF" align=center>
			<input type="hidden" name="base_datos" value="<?= $_POST['base_datos'] ?>">
			<input type="hidden" name="tablas" value="<?= $_POST['tablas'] ?>">
			<input type="hidden" name="campo" value="<?= $_POST['campo'] ?>">
			<input type="submit" value="Enviar" >
		</td>
	</tr>
	<? }else{ ?>
	<tr>
		<td bgcolor="DDDDFF" height=100 valign=center>
			Se utilizara la siguiente configuracion para el Envio de los correos:
			<li><b>De:<b>&nbsp;<i><?= $_SESSION['from'] ?></i></li>
			<li><b>Asunto:<b>&nbsp;<i><?= $_SESSION['asunto'] ?></i></li>
			<li><b>Para:<b>&nbsp;<i><?= $destinatario ?></i></li>

		</td>
	</tr>
	<tr>
		<td bgcolor="9999FF" align=center>
			<input type="submit" value="Enviar" >
		</td>
	</tr>
	<? } ?>
</form>
</table>
</body>
</html>
