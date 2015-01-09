<?



	 /*************************************************
	 * @ "Application_user" object definition
	 * @ Author:		Juan Carlos Romero
	 * @ Created:		April - 28 - 2004 - 11:30:50
	 * @ Modified:		April - 28 - 2004 - 11:30:50
	 * @ Version:		1.0
	*************************************************/


	class ApplicationUsers	{
		/**
		 * @ Object var definitions. 
		**/
		var $idUser;		//	ID_USER { int }
		var $firstName;		//	FIRST_NAME { string }
		var $lastName;		//	LAST_NAME { string }
		var $gender;		//	GENDER { string }
		var $phone;		//	PHONE { string }
		var $movilPhone;		//	MOVIL_PHONE { string }
		var $email;		//	EMAIL { string }
		var $address;		//	ADDRESS { string }
		var $addressZone;		//	ADDRESS_ZONE { string }
		var $id_group;
		var $login;


	 /*************************************************
	 * @ Constructor(s) of object ApplicationUser
	*************************************************/
		/**
		 * @ Creates an empty ApplicationUser object or filled with values. 
		**/
		function ApplicationUsers(  $aIdUser = FALSE, $aFirstName = NULL, $aLastName = NULL, $aGender = NULL, $aPhone = NULL, $aMovilPhone = NULL, $aEmail = NULL, $aAddress = NULL, $aAddressZone = NULL  ){

			if ( $aIdUser !== FALSE )
				$this->load( $aIdUser );
			else{
				$this->firstName = $aFirstName;
				$this->lastName = $aLastName;
				$this->gender = $aGender;
				$this->phone = $aPhone;
				$this->movilPhone = $aMovilPhone;
				$this->email = $aEmail;
				$this->address = $aAddress;
				$this->addressZone = $aAddressZone;
				
			}
		}

	 /*************************************************
	 * @ Analizer(s) of object ApplicationUser
	*************************************************/
		/**
		 * @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print( )	{
			echo "
				<ul><br>
				<li><b> ( APPLICATIONUSER ) </b></li>
				<ul>
					<li><b>ID_USER: </b>'" . $this->idUser . "'
					</li>
					<li><b>FIRST_NAME: </b>'" . $this->firstName . "'
					</li>
					<li><b>LAST_NAME: </b>'" . $this->lastName . "'
					</li>
					<li><b>GENDER: </b>'" . $this->gender . "'
					</li>
					<li><b>PHONE: </b>'" . $this->phone . "'
					</li>
					<li><b>MOVIL_PHONE: </b>'" . $this->movilPhone . "'
					</li>
					<li><b>EMAIL: </b>'" . $this->email . "'
					</li>
					<li><b>ADDRESS: </b>'" . $this->address . "'
					</li>
					<li><b>ADDRESS_ZONE: </b>'" . $this->addressZone . "'
					</li>
				</ul>
				</ul>
				";
			}

		/**
		 * Returns ID_USER of ApplicationUser
		**/
		function getIdUser( )	{
			return $this->idUser;
		}
		/**
		 * Returns FIRST_NAME of ApplicationUser
		**/
		function getFirstName( )	{
			return $this->firstName;
		}
		/**
		 * Returns LAST_NAME of ApplicationUser
		**/
		function getLastName( )	{
			return $this->lastName;
		}
		/**
		 * Returns GENDER of ApplicationUser
		**/
		function getGender( )	{
			return $this->gender;
		}
		/**
		 * Returns PHONE of ApplicationUser
		**/
		function getPhone( )	{
			return $this->phone;
		}
		/**
		 * Returns MOVIL_PHONE of ApplicationUser
		**/
		function getMovilPhone( )	{
			return $this->movilPhone;
		}
		/**
		 * Returns EMAIL of ApplicationUser
		**/
		function getEmail( )	{
			return $this->email;
		}
		/**
		 * Returns ADDRESS of ApplicationUser
		**/
		function getAddress( )	{
			return $this->address;
		}
		/**
		 * Returns ADDRESS_ZONE of ApplicationUser
		**/
		function getAddressZone( )	{
			return $this->addressZone;
		}
		
		
		function getLogin()	{
			return $this->login;
		}
		
		function getGroup()	{
			return $this->id_group;
		}

	 /*************************************************
	 * @ Modifier(s) of object ApplicationUser
	*************************************************/
		/**
		 * Sets ID_USER of ApplicationUser
		**/
		function setIdUser( $aIdUser )	{
			$this->idUser = $aIdUser;
		}
		/**
		 * Sets FIRST_NAME of ApplicationUser
		**/
		function setFirstName( $aFirstName )	{
			$this->firstName = $aFirstName;
		}
		/**
		 * Sets LAST_NAME of ApplicationUser
		**/
		function setLastName( $aLastName )	{
			$this->lastName = $aLastName;
		}
		/**
		 * Sets GENDER of ApplicationUser
		**/
		function setGender( $aGender )	{
			$this->gender = $aGender;
		}
		/**
		 * Sets PHONE of ApplicationUser
		**/
		function setPhone( $aPhone )	{
			$this->phone = $aPhone;
		}
		/**
		 * Sets MOVIL_PHONE of ApplicationUser
		**/
		function setMovilPhone( $aMovilPhone )	{
			$this->movilPhone = $aMovilPhone;
		}
		/**
		 * Sets EMAIL of ApplicationUser
		**/
		function setEmail( $aEmail )	{
			$this->email = $aEmail;
		}
		/**
		 * Sets ADDRESS of ApplicationUser
		**/
		function setAddress( $aAddress )	{
			$this->address = $aAddress;
		}
		/**
		 * Sets ADDRESS_ZONE of ApplicationUser
		**/
		function setAddressZone( $aAddressZone )	{
			$this->addressZone = $aAddressZone;
		}
		
		function setLogin( $login )	{
			$this->login = $login;
		}
		
		function setGroup( $id_group )	{
			$this->id_group = $id_group;
		}

	 /*************************************************
	 * @ Persistence of object ApplicationUser
	*************************************************/
		/**
		 * @ Returns a duplication of ApplicationUser object information
		**/
		function duplicate ( $aIdUser = FALSE )	{
			$double = new ApplicationUser( $aIdUser );
			$double->first_name = $this->first_name;
			$double->last_name = $this->last_name;
			$double->gender = $this->gender;
			$double->phone = $this->phone;
			$double->movil_phone = $this->movil_phone;
			$double->email = $this->email;
			$double->address = $this->address;
			$double->address_zone = $this->address_zone;
			$double->commit( );
			$double->commit( );
			return $double;
		}

		/**
		 * @ Updates or Inserts ApplicationUser information, depending
		 * @ upon existence of valid primary key. 
		**/
		function commit(){
			$sql  = "SELECT id_user " .
					"FROM application_users " .
					"WHERE id_user = '" . $this->idUser . "'";
					
			$result = db_query( $sql );
			
			if ( db_numrows( $result ) > 0 )	{
				$sql  = "UPDATE application_users SET " .
						"first_name = '" . $this->firstName . "', " .
						"last_name = '" . $this->lastName . "', " .
						"gender = " . ( ( $this->gender != NULL )? "'" . $this->gender . "'" : "NULL" ) . ", " .
						"phone = " . ( ( $this->phone != NULL )? "'" . $this->phone . "'" : "NULL" ) . ", " .
						"movil_phone = " . ( ( $this->movilPhone != NULL )? "'" . $this->movilPhone . "'" : "NULL" ) . ", " .
						"email = " . ( ( $this->email != NULL )? "'" . $this->email . "'" : "NULL" ) . ", " .
						"address = " . ( ( $this->address != NULL )? "'" . $this->address . "'" : "NULL" ) . ", " .
						"address_zone = " . ( ( $this->addressZone != NULL )? "'" . $this->addressZone . "'" : "NULL" ) . " " .
						 "WHERE id_user = '" . $this->idUser . "'";
						
				db_query( $sql );

			}else{
				$sql = 	"INSERT INTO application_users " .
						"( id_user, first_name, last_name, gender, phone, movil_phone, email, address, address_zone ) " .
						"VALUES " .
						"( '$this->idUser', " .
						"'$this->firstName', " .
						"'$this->lastName', " .
						( ( $this->gender != NULL )? "'" . $this->gender . "'" : "NULL" ) . ", " .
						( ( $this->phone != NULL )? "'" . $this->phone . "'" : "NULL" ) . ", " .
						( ( $this->movilPhone != NULL )? "'" . $this->movilPhone . "'" : "NULL" ) . ", " .
						( ( $this->email != NULL )? "'" . $this->email . "'" : "NULL" ) . ", " .
						( ( $this->address != NULL )? "'" . $this->address . "'" : "NULL" ) . ", " .
						( ( $this->addressZone != NULL )? "'" . $this->addressZone . "'" : "NULL" ) . " )";
				
				db_query( $sql );
				$this->idUser = db_id_insert( );
			}
		}

		/**
		 * @ Deletes ApplicationUser object from database. 
		**/
		function erase ( )	{
			$sql = "DELETE FROM application_users";
			$sql .= " WHERE id_user =  '$this->idUser'";
			db_query( $sql );
		}

		/**
		 * @ Loads ApplicationUser attributes from the database
		 * @ and assigns them to active object.
		**/
		function load ( $aIdUser )	{
			
			$sql  = " SELECT * FROM application_users";
			$sql .= " WHERE id_user = " . $aIdUser;
			$result = db_query( $sql );
			$row = db_fetch_array( $result );

			$this->idUser = $aIdUser;
			$this->firstName = $row['first_name'];
			$this->lastName = $row['last_name'];
			$this->gender = $row['gender'];
			$this->phone = $row['phone'];
			$this->movilPhone = $row['movil_phone'];
			$this->email = $row['email'];
			$this->address = $row['address'];
			$this->addressZone = $row['address_zone'];
			
			$sql = "SELECT a.id_group, b.login " .
					"FROM user_groups a, auths b " .
					"WHERE a.id_auth = b.id_auth AND b.id_user = " . $this->idUser;
			
			$result = db_query( $sql );
			$row = db_fetch_array( $result );
			$this->login = $row['login'];
			$this->id_group = $row['id_group'];
		}
	}

?>