<?
	if(isset($_GET['directorio']))
		$nombre_directorio=$_GET['directorio'];
	else
		$nombre_directorio=$_SERVER['DOCUMENT_ROOT'];
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="mtmcode.js">
	var relocateURL = "/";

	if(parent.frames.length == 0) {
 	 if(document.images) {
    	location.replace(relocateURL);
  	} else {
    	location = relocateURL;
  	}
	}


</script>
<script type="text/javascript" src="mtmtrack.js">
</script>
<script language="JavaScript">

	MTMDefaultTarget = "text";
	var menu=null;
	menu = new MTMenu();
	
	<? 
		function crearMenu($nombre_directorio,$deep){
			$directorio=opendir($nombre_directorio);
			$i=0;
			while ($archivo = readdir($directorio)) {
				if ($archivo != "." && $archivo != "..") {
					if(is_dir($nombre_directorio."/".$archivo)){
						echo "\tmenu.addItem('".$archivo."','main.php?directorio=".$nombre_directorio."/".$archivo."','text','','menu_folder_closed.gif');\n";
					}
				}
       		}
			closedir($directorio);
		}
		
		crearMenu($nombre_directorio,0);
	
	?>

	var MTMIconList = null;
	MTMIconList = new IconList();


</script>
</head>
<body onload="MTMStartMenu(true)" bgcolor="#000033" text="#ffffcc" link="yellow" vlink="lime" alink="red">

</body>
</html>
