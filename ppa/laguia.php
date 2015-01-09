<?
if( !isset( $_POST['Descargar_RTF'] ) ){
	require("header.php");
}
setlocale (LC_ALL,"spanish");
if(isset($_GET['upload']) ){
	require("laguia/upload.php");
} else if(isset($_POST['upload']) ){
	require("laguia/upload2.php");
} else if(isset($_POST['channels_time']) ){
	if( isset($_POST['Descargar_RTF']) ){
	  require("laguia/export_laguia.php");	
	}else{
	  require("laguia/4.php");
	} 
} else if(isset($_POST['channels']) ){
	require("laguia/3.php");
} else if(isset($_POST['mes']) && isset($_POST['ano']) ){
	require("laguia/2.php");
} else {
	require("laguia/1.php");
}
if( !isset( $_POST['Descargar_RTF'] ) ){
	require("footer.php");
}
?>