<?
	require_once($PATH . "classes/ApplicationUser.class.php");

	 /*************************************************
	 * @ "Auths" object definition
	 * @ Author:		Juan Carlos Romero
	 * @ Created:		April - 28 - 2004 - 11:30:50
	 * @ Modified:		April - 28 - 2004 - 11:30:50
	 * @ Version:		1.0
	*************************************************/


	class Auths	{
		/**
		 * @ Object var definitions. 
		**/
		var $idAuth;		//	ID_AUTH { int }
		var $applicationUser;		//	ID_USER { int }
		var $login;		//	LOGIN { string }
		var $passwd;		//	PASSWD { string }


	 /*************************************************
	 * @ Constructor(s) of object Auths
	*************************************************/
		/**
		 * @ Creates an empty Auths object or filled with values. 
		**/
		function Auths (  $aIdAuth = FALSE, $aIdUser = FALSE,  $aLogin = FALSE, $aPasswd = FALSE  )	{
			$this->idAuth = $aIdAuth;
			 
			if( $aIdUser != "" )
				$this->applicationUser = new ApplicationUsers( $aIdUser );
			else
				$this->applicationUser = NULL;
				
			$this->login = $aLogin;
			$this->passwd = $aPasswd;
		}

	 /*************************************************
	 * @ Analizer(s) of object Auths
	*************************************************/
		/**
		 * @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print( )	{
			echo "
				<ul><br>
				<li><b> ( AUTHS ) </b></li>
				<ul>
					<li><b>ID_AUTH: </b>'" . $this->idAuth . "'
					</li>
					<li><b>ID_USER: </b>'" . $this->applicationUser . "'
					</li>
					<li><b>LOGIN: </b>'" . $this->login . "'
					</li>
					<li><b>PASSWD: </b>'" . $this->passwd . "'
					</li>
				</ul>
				</ul>
				";
			}

		/**
		 * Returns ID_AUTH of Auths
		**/
		function getIdAuth( )	{
			return $this->idAuth;
		}
		/**
		 * Returns ID_USER of Auths
		**/
		function getApplicationUser( )	{
			return $this->applicationUser;
		}
		/**
		 * gets LOGIN of Auths
		**/
		function getLogin( )	{
			return $this->login;
		}
		/**
		 * gets PASSWD of Auths
		**/
		function getPasswd()	{
			return $this->passwd;
		}
		
		function getFullName(){
			return $this->applicationUser->getFirstName() . " " . $this->applicationUser->getLastName();
		}
		
		function getUserEmail(){
			return $this->applicationUser->getEmail();
		}
	 /***********************************************
	 *
	 *		GetAllowMenus
	 *		jcromero
	 *
	 ************************************************/
	 	function getAllowMenus(){
			$menus = array( );
			$arr = array( );
			
			if (!isset($this->idAuth) || empty($this->idAuth))
				return $menus;
			
			$sql  = "SELECT options.*, menus.read_only FROM options, menus, user_groups, application_groups ";
			$sql .= "WHERE options.id_option = menus.id_option ";
			$sql .= "AND menus.id_group = application_groups.id_group ";
			$sql .= "AND application_groups.id_group = user_groups.id_group ";
			$sql .= "AND user_groups.id_auth = '" . $this->idAuth . "' ";
			$sql .= "ORDER BY options.id_parent, options.description";
			
			$result = db_query( $sql );
			while ($row = db_fetch_array( $result ) )
				$arr[] = $row;

			for ($c = 0; $c < count ($arr); $c++)	{
				if (is_null($arr[$c]['id_parent']))	{
					$pos = count ($menus);
					$menus[$pos]['menu']['name'] = $arr[$c]['description'];
					$menus[$pos]['menu']['function'] = $arr[$c]['function_'];
					$menus[$pos]['menu']['readonly'] = $arr[$c]['read_only'];
					$menus[$pos]['submenus'] = $this->searchChildMenuFrom ( $c, $arr[$c]['id_option'], $arr );
				}
			}			
			
			return $menus;
		}
	 
	 	function searchChildMenuFrom ( $index, $parent, $arr )	{
			$menus = array ();
			for ($c = $index + 1; $c < count ($arr); $c++)	{
				if ($parent == $arr[$c]['id_parent'])	{
					$pos = count ($menus);
					$menus[$pos]['menu']['name'] = $arr[$c]['description'];
					$menus[$pos]['menu']['function'] = $arr[$c]['function_'];
					$menus[$pos]['menu']['readonly'] = $arr[$c]['read_only'];
					$menus[$pos]['submenus'] = $this->searchChildMenuFrom ( $c, $arr[$c]['id_option'], $arr );
				}
			}
			return $menus;
		}
	 /*************************************************
	 * @ Modifier(s) of object Auths
	*************************************************/
		/**
		 * Sets ID_AUTH of Auths
		**/
		function setIdAuth( $aIdAuth )	{
			$this->idAuth = $aIdAuth;
		}
		/**
		 * Sets ID_USER of Auths
		**/
		function setApplicationUser( $applicationUser )	{
			$this->applicationUser = $applicationUser;
		}
		/**
		 * Sets LOGIN of Auths
		**/
		function setLogin( $aLogin )	{
			$this->login = $aLogin;
		}
		/**
		 * Sets PASSWD of Auths
		**/
		function setPasswd( $aPasswd )	{
			$this->passwd = $aPasswd;
		}

	 /*************************************************
	 * @ Persistence of object Auths
	*************************************************/
		/**
		 * @ Returns a duplication of Auths object information
		**/
		function duplicate ( $aIdAuth = FALSE )	{
			$double = new Auths( $aIdAuth );
			$double->applicationUser = ( ( $this->applicationUser != NULL )? $this->applicationUser : NULL );
			$double->login = $this->login;
			$double->passwd = $this->passwd;
			$double->commit( );
			$double->commit( );
			return $double;
		}

		/**
		 * @ Updates or Inserts Auths information, depending
		 * @ upon existence of valid primary key. 
		**/
		function commit ( )	{
			$sql  = " SELECT id_auth FROM auths WHERE id_auth = '$this->idAuth'";
			$result = db_query( $sql );

			if ( db_numrows( $result ) > 0 )	{
				$sql  = " UPDATE auths SET";
				$sql .= " id_user = " . ( ( $this->applicationUser != NULL )? "'" . $this->applicationUser->getIdUser() . "'" : "NULL" ) . ",";
				$sql .= " login = '$this->login',";
				$sql .= " passwd = PASSWORD( '" . $this->passwd . "' )";
				$sql .= " WHERE id_auth = '" . $this->idAuth . "'";
				db_query( $sql );

			}	else	{
				$sql = 	"INSERT INTO ".
						"auths ( id_auth, id_user, login, passwd ) " .
						"VALUES " .
						"( '" . $this->idAuth . "', " .
						"'" . ( ( $this->applicationUser != NULL )? "'" . $this->applicationUser->getIdUser() . "'" : "NULL" ) . "', " .
						"'" . $this->login . "', ".
						"PASSWORD( '" . $this->passwd . "' ) )";
						
				db_query( $sql );
				$this->idAuth = db_id_insert( );
			}
		}

		/**
		 * @ Deletes Auths object from database. 
		**/
		function erase ( )	{
			$sql = "DELETE FROM auths";
			$sql .= " WHERE id_auth =  '" . $this->idAuth . "'";
			db_query( $sql );
		}

		/**
		 * @ Loads Auths attributes from the database
		 * @ and assigns them to active object.
		**/
		function load ( $aIdAuth )	{
			$sql  = " SELECT * FROM auths";
			$sql .= " WHERE id_auth = " . $aIdAuth;
			$result = db_query( $sql );
			$row = db_fetch_array( $result );

			$this->idAuth = $aIdAuth;
			if( $row['id_user'] != NULL )
				$this->applicationUser = new ApplicationUsers( $row['id_user'] );
			else
				$this->applicationUser = NULL;
				
				
			$this->login = $row['login'];
			$this->passwd = $row['passwd'];
			
		}
		
		
	}

?>