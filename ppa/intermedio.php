<?
if( !isset( $_POST['Descargar_RTF'] ) ){
	require("header.php");
}
setlocale (LC_ALL,"spanish");
if(isset($_GET['upload']) ){
	require("intermedio/upload.php");
} else if(isset($_POST['upload']) ){
	require("intermedio/upload2.php");
}else if(isset($_GET['download']) ){
	require("intermedio/download.php");
} else if(isset($_POST['download']) ){
	require("intermedio/download2.php");
} else if(isset($_POST['channels_time']) ){
	if( isset($_POST['Descargar_RTF']) ){
	  require("intermedio/export_intermedio.php");	
	}else{
	  require("intermedio/4.php");
	} 
} else if(isset($_POST['channels']) ){
	require("intermedio/3.php");
} else if(isset($_POST['mes']) && isset($_POST['ano']) ){
         require("intermedio/2.php");
} else if(isset($_POST['show_sinop'])){
         require("intermedio/html_sinopsis.php");		   
} else if(isset($_POST['show_sinop1'])){
         require("intermedio/html_sinopsis1.php");		
} else if(isset($_POST['show_sinop_faltante'])){
         require("intermedio/sinopsis_faltante.php");		   
} else if(isset($_POST['list_sinop']) ){
	require("intermedio/list_sinopsis.php");
} else if(isset($_POST['channels1']) ){
	require("intermedio/list_programs_sinopsis.php");
} else if(isset($_POST['mes1']) && isset($_POST['ano1']) ){
	require("intermedio/list_channels_sinopsis.php");
} else {
    if( isset( $_GET['id'] ) ){
	  require("intermedio/revista.php");
	}else{
          if( isset( $_GET['eliminar_sinop'] ) ){
	    require("intermedio/list_sinopsis.php");
	  }else{
	     require('intermedio/index.php');
	  }
	}
}
if( !isset( $_POST['Descargar_RTF'] ) ){
	require("footer.php");
}
?>