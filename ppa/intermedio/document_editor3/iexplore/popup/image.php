<?
require("../../../class/Application.class.php");
if(isset($_POST['option']))$option = $_POST['option'];
else if(isset($_GET['option']))$option = $_GET['option'];
else	$option = "main";

switch($option){
	case "main":{
		include("image_main.php");
		break;
	}
	case "browse":{
		include("image_browse.php");
		break;
	}
	case "bank":{
		include("image_bank.php");
		break;
	}
}

?>