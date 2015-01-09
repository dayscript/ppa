<?
	class AuthManager{
	
		var $__error;
		
		function AuthManager(){
		}
		
		function getAllUsers(){
			$sql = 	"SELECT a.id_auth, b.id_user, CONCAT( b.first_name, ' ', b.last_name ) AS full_name " .
					"FROM auths a, application_users b ".
					"WHERE a.id_user = b.id_user AND a.id_user IS NOT NULL";
							
			$result = db_query( $sql );
			$users = array();
			$users['appUser'] = array();
			
			while( $row = db_fetch_array( $result ) )
				$users['appUser'][] = $row;
				
			return $users;
		}
		
		function addAuth( $id_appUser, $login, $passwd ){
			$sql = 	"SELECT id_auth " .
					"FROM auths " .
					"WHERE login LIKE '" . $login . "'";
			
			$result = db_query( $sql );
			if( $row = db_fetch_array( $result ) ){
				$this->__error = "Ya existe un usuario con este login";
				return FALSE;
			}
			
			$sql = 	"INSERT INTO auths " .
					"( id_auth, id_user, login, passwd ) " .
					"VALUES " .
					"( '', " .
					 ( ( $id_appUser != NULL )? $id_appUser : "NULL" ) . ", " .
					 "'" . $login . "', " .
					 "PASSWORD( '" . $passwd . "' ) )";
			
			db_query( $sql );
			if( mysql_error() != "" ){
				$this->__error = "Error al crear el login y el password";
				return FALSE;
			}
			
			$this->__error = "";
			return db_id_insert();
		}
		
		function delAuth( $id_user, $id_employee ){
			$sql = "DELETE FROM auths WHERE ";
			if( $id_user != NULL )
				$sql .= "id_user = " . $id_user; 
			
			if( $id_employee != NULL )
				$sql .= "id_employee = " . $id_employee;
				
			db_query( $sql );
			if( mysql_error() != "" ){
				$this->__error = "Error al eliminar el usuario y el password";
				return FALSE;
			}
			
			$this->__error = "";
			return TRUE;
		}
		
		function updateAuth( $id_user, $user, $passwd ){
			$sql = "UPDATE auths " .
					"SET login = '" . $_POST['user'] . "', passwd = PASSWORD( '" . $_POST['passwd'] . "' ) " .
					"WHERE id_auth = " . $id_user;
			
			echo $sql;		
			db_query( $sql );
			if( mysql_error() != "" ){
				$this->__error = "Error al actualizar el login y el password";
				return FALSE;
			}
			
			$this->__error = "";
			return TRUE;
		}
		
		function addGroupAuth( $id_auth, $id_group ){
			$sql = 	"SELECT id_group " .
					"FROM user_groups " .
					"WHERE id_auth = " . $id_auth;
					
			$resul = db_query( $sql );
			if( $row = db_fetch_array( $resul ) ){
				if( $id_group != $row[0] ){
					$sql = 	"UPDATE user_groups " .
							"SET id_group = " . $id_group . " " .
							"WHERE id_auth = " . $id_auth;
							
					db_query( $sql );
				}
			}else{
				$sql = "INSERT INTO user_groups " .
						"( id_group, id_auth ) " .
						"VALUES " .
						"( " . $id_group . ", " . $id_auth . " )";
				db_query( $sql );
			}
		}
		
		function getAuthFromUserId( $idUser ){
			$sql = "SELECT id_auth FROM auths WHERE id_user = " . $idUser;
			$resul = db_query( $sql );
			if( $row = db_fetch_array( $resul ) )
				return $row[0];
			else
				return FALSE;
		}
		
		function getLastError(){
			return $this->__error;
		}
	}
?>