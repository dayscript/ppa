<?
	if(isset($_GET['directorio']))
		$directorio=$_GET['directorio'];
	else{
		$nombre_directorio=substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],"/"));
		$nombre_directorio=substr($nombre_directorio,0,strrpos($nombre_directorio,"/"));
		$nombre_directorio=substr($nombre_directorio,0,strrpos($nombre_directorio,"/"));
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
	function atras(){
		window.history.back();
		top.frames['text'].history.back();
	}
</script>
</head>
<body bgcolor="#D4D0C8">
 Directorio Actual:<li><?= (strlen($directorio)>45?substr($directorio,0,45)."...":$directorio) ?></li>
 <input type="button" onClick="javascript:atras();" value="atras">
</body>
</html>
