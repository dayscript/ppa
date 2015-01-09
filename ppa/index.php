<?
//ini_set('display_errors', 0);
error_reporting(E_ERROR);

require_once( "menu/include/config.inc.php" );
require_once( "menu/classes/ApplicationMenu.class.php" );
require_once( "menu/include/auth.inc.php" );
require_once( "menu/include/util.inc.php" );
require_once("class/Application.class.php");
require_once( "ppa/include/config1.inc.php" );
session_start();

if(!session_is_registered("app")){
	$app = new Application("include/config.php");
	session_register("app");
	$_SESSION["app"] = $app;
} else {
	if(!isset($app) && isset($_SESSION["app"])){
		$app = $_SESSION["app"];
	}	
}

if( (isset( $_POST['function']) && $_POST['function']== "close") || (isset($_GET['function']) && $_GET['function']== "close") ){
	unset($user);
	session_unregister("user");
	session_unregister("pass");
   session_unregister("applicationMenu");
	unset( $_SESSION['applicationMenu'] );
}
if(isset($_SESSION['user']) && $_SESSION['user'] !=""){
	$validate = true;
} else {

	if(isset($_GET['user'])){
		$user = $_GET['user'];
	}else if(isset($_POST['user'])){
		$user = $_POST['user'];
	}else{
		$user = "";
	}


	if(isset($_GET['password'])){
		$password = $_GET['password'];
	} else if(isset($_POST['password'])){
		$password = $_POST['password'];
	} else{
		$password = "";
	}
	if( $user != "" && $password != ""  ){
		if( !session_is_registered("applicationMenu")){
			$applicationMenu =  new ApplicationMenu();
			if( $applicationMenu->validateUser( $user, $password ) ){
				$username = $applicationMenu->getFullName();
   	  		session_register("applicationMenu");
			  	session_register("user");
		   	session_register("username");
		    	session_register("password");
	  	  	  	$_SESSION['applicationMenu'] = $applicationMenu;
		    	$_SESSION['user'] = $user;
		    	$_SESSION['username'] = $username;
		    	$_SESSION['password'] = $password;
			  	$validate = true;
   		}else{
				$validate = false;
			}
		}else{
			if( $_SESSION['applicationMenu']->validateUser( $user, $password ) ){
				$username = $applicationMenu->getFullName();
    	  		$_SESSION['applicationMenu'] = $applicationMenu;
	    		$_SESSION['user'] = $user;
	    		$_SESSION['username'] = $username;
	    		$_SESSION['password'] = $password;
		  		$validate = true;
			}else{
		  		$validate = false;		
			}		
		}
	}else{
		$validate = false;
	}
	
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>DAYSCRIPT LTDA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.textosLogin {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #666666;
	font-weight: bold;
}
.textosError {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #00CC00;
	font-weight: bolder;
}
input, select {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #555555;
	margin: 0px;
	padding: 0px;
	width: 100px;
}
.submit{
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #555555;
	margin: 0px;
	padding: 0px;
	height: 16px;
	width: 55px;
}
-->
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
function popup(pagina,ancho,alto,scrollbar,name){
	newWindow=open(pagina,name,'resizable=no,status=no,scrollbars='+scrollbar+',menubar=no,width='+ancho+',height='+alto);
}

//-->
</script>
</head>
<?
if($validate){
	include("header.php");
	include("home.php");
	include("footer.php");
}else{
	include("index2.php");
}?>
</noframes>
</html>