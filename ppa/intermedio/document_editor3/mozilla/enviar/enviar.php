<?
	session_start();
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
		FONT-SIZE: 13px;
		COLOR: #333333;
		FONT-FAMILY: Verdana, Helvetica, sans-serif;
		TEXT-DECORATION: none
	}
</style>
</head>
<body bgcolor="#EEEEFF">
<table width="100%" align="center">
<tr>
	<td height="180" align="center" valign="center">
<?

	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: ".$_SESSION['from']."\r\n";
	$headers .= "Reply-To: ".$_SESSION['from']."\r\n";
		
	$asunto = $_SESSION['asunto'];

	$mensaje=implode("\n",file($_SESSION['ruta_archivo']));

	if($_SESSION['uso_base_de_datos']=="true"){
		$conexion=mysql_connect("localhost","dayscript","kfc3*9mn");
		mysql_select_db($_POST['base_datos'],$conexion);
		$sql="SELECT ".$_POST['campo']." FROM ".$_POST['tablas'];
		$resultado=mysql_query($sql,$conexion);

		echo "Enviando ".mysql_num_rows($resultado)." correos";

		while( $fila=mysql_fetch_array($resultado) ){
			/*$destinatario=$fila[0];
			echo $destinatario." -> ";

			if( mail($destinatario,$asunto,$mensaje,$headers) ){
				echo "OK<br>";
			}else{
				echo "FALLO<br>";
			}*/
		}
	}else{
		echo "¡Por favor espere!";
		mail($_SESSION['destinatario'],$asunto,$mensaje,$headers);
		session_unregister('destinatario');
		unset($_SESSION['destinatario']);
	}

	session_unregister('uso_base_de_datos');
	unset($_SESSION['uso_base_de_datos']);
	session_unregister('asunto');
	unset($_SESSION['asunto']);
	session_unregister('from');
	unset($_SESSION['from']);

	echo "<br>Terminado";
	?>
		<script>
			window.setTimeout("window.close()",5000);
		</script>
	<?
?>
	</td>
</tr>
</table>
</body>
</html>
