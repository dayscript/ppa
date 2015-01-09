<?

if(strstr($_SERVER['HTTP_USER_AGENT'],"MSIE")){
	if(strstr($_SERVER['HTTP_USER_AGENT'],"6.0")){
		include("iexplore/editor.php");
	}

}else{
	if(strstr($_SERVER['HTTP_USER_AGENT'],"Mozilla/5")){
		include("mozilla/editor.php");
	}
}

?>