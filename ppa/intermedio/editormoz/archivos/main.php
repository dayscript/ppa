<?
	if(isset($_GET['directorio']))
		$nombre_directorio=$_GET['directorio'];
	else{
		$nombre_directorio=substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],"/"));
		$nombre_directorio=substr($nombre_directorio,0,strrpos($nombre_directorio,"/"));
		$nombre_directorio=substr($nombre_directorio,0,strrpos($nombre_directorio,"/"));
		//$nombre_directorio=substr($nombre_directorio,0,strrpos($nombre_directorio,"/"));
	}
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style>
	.presionado{
		border-top: 1px solid buttonshadow;
		border-left: 1px solid buttonshadow;
		border-bottom: 1px solid buttonhighlight;
		border-right: 1px solid buttonhighlight;
		background: #DDDDFF;
	}
	.soltado{
		border-top: 1px solid #FFFFFF;
		border-left: 1px solid #FFFFFF;
		border-bottom: 1px solid #FFFFFF;
		border-right: 1px solid #FFFFFF;
		background: #FFFFFF;
	}

</style>
<script>
	function abrirVinculo(nombre_directorio,nombre_archivo){
		document.location="main.php?directorio="+nombre_directorio+nombre_archivo;
		top.frames['cabezote'].document.location="cabezote.php?directorio="+nombre_directorio+nombre_archivo;
	}
	function borde(imagen){
		imagen.className="presionado";

	}
	function sinBorde(imagen,archivo){
		imagen.className="soltado";
		top.frames['abrir'].document.getElementById('nombre_archivo').value=archivo;
	}
</script>
</head>

<body>
<table width="90%" border=0>
<?
			$directorio=opendir($nombre_directorio);
			$i=0;
			while ($archivo = readdir($directorio)) {
				
				if ($archivo != "." && $archivo != "..") {
					if($i%2 == 0 )
						echo "<tr>\n";
						
					if(is_dir($nombre_directorio."/".$archivo)){
						echo "<td align='center'><img src='menu-images/menu_folder_closed.gif' width=30><br><a href='#' onClick=\"javascript:abrirVinculo('".$nombre_directorio."/','".$archivo."')\">".$archivo."</td>";
					}else{
						echo "<td align='center'><img src='menu-images/menu_link_txt.gif' width=30 onMouseDown=\"javascript:borde(this);\" onMouseUp=\"javascript:sinBorde(this,'".$nombre_directorio."/".$archivo."');\"><br>".$archivo."</td>";
					}
					
					$i++;
					if( $i%2 == 0 )
						echo "</tr>\n";
				}	
       		}
			closedir($directorio);
?>
</table>
</body>
</html>
