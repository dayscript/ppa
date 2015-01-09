<?

if( !session_is_registered("application")){
	if(isset($_POST['usuario']) && isset($_POST['password']) && $_POST['usuario']!="" && $_POST['password']!=""){
		
		$application = new Application();
		if( $application->validateUser( $_POST['usuario'], $_POST['password'] ) ){
			session_register("application");
			$_SESSION['application'] = $application;
		}
		
	}
}

?>