<?
$path = stripslashes(substr($_SERVER['PATH_TRANSLATED'],0,strpos($_SERVER['PATH_TRANSLATED'],addslashes(str_replace("/","\\",$_SERVER['SCRIPT_NAME']))))) . "\\";
require_once($path . "class/Application.class.php");
session_start();
$expires = mktime(date("h"),date("i"),date("s"),(date("m")+1),date("d"),date("Y"));
$actDate = mktime(date("h"),date("i"),date("s"),date("m"),date("d"),date("Y"));

if(isset($_COOKIE['auth']) && $_COOKIE['auth'] == 1){
	$auth = $_COOKIE['auth'];
	if ( $_COOKIE['user'] == "bayer" ){
		$auth = 0;
		$_POST['usuario'] = "visitante";
		$_POST['password'] = "visitante";
	}else{
		$usuario = $_COOKIE['user'];
		$idUser = $_COOKIE['idUser'];
	}
		
}
else{
	$auth = 0;
	$usuario = "";
	$password = "";
	$_POST['usuario'] = "visitante";
	$_POST['password'] = "visitante";
}

if (!session_is_registered('app')){
	$app = new Application(1);
}
else{
	$app = $_SESSION['app'];
}
if (isset($_POST['usuario']) && isset($_POST['password']) && $auth != 1){
	$usuario = $_POST['usuario'];
	$password = $_POST['password'];
	$idUser = $app->authenticate($usuario, $password);
	//echo $idUser;
	if($idUser != -1){
		
		$_COOKIE['auth'] = $auth;
		$_COOKIE['user'] = $usuario;
		$_COOKIE['idUser'] = $idUser;
		$_COOKIE['expires'] = 0;
		$auth = 1;
		setcookie("auth",$auth,$expires);
		setcookie("user",$usuario,$expires);
		setcookie("idUser",$idUser,$expires);
		setcookie("expires",$expires,$expires);
		$_COOKIE['expires']= $expires;
		//echo "auth: ".$_COOKIE['auth'];
		session_register('app');
		$_SESSION['app'] = $app;
		
	}
}
else if(isset($_COOKIE['auth']) && $_COOKIE['auth']== 1){
	if(isset($_COOKIE['expires'])&&((($_COOKIE['expires'] - $actDate)/(60*60*24)) > 0) && ((($_COOKIE['expires'] - $actDate)/(60*60*24)) < 10) ){
		setcookie("auth",$auth,$expires);
		setcookie("user",$usuario,$expires);
		setcookie("idUser",$idUser,$expires);
		setcookie("expires",$expires,$expires);
	}
	if(!session_is_registered('app')){
		session_register('app');
		$_SESSION['app'] = $app;
	}
}
?>
