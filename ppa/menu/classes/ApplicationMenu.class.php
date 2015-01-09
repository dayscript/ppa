<?
	require_once( $PATH . "classes/Auths.class.php" );
	
	class ApplicationMenu{
		var $auths;
		var $menuContainer;
		
		function ApplicationMenu(){
			$this->menuContainer = array();
			$this->auths = NULL;
		}
		
		function validateUser( $user, $passwd ){
			$sql = 	"SELECT id_auth, id_user " .
					"FROM auths " .
					"WHERE login='" . $user . "' AND passwd=PASSWORD('" . $passwd . "')";
			$res = db_query($sql);
			if($row = db_fetch_array($res)){
				$this->auths = new Auths( $row['id_auth'] , $row['id_user'], $user, $passwd );					
				$this->menuContainer = $this->auths->getAllowMenus();
				return TRUE;
			}else
				return FALSE;
		}
				
		function getFullName(){
			return $this->auths->getFullName();
		}
		function getUserEmail(){
			return $this->auths->getUserEmail();
		}
		function getAllowMenus(){
			return $this->menuContainer;
		}
	}
?>