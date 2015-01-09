<?



	 /*************************************************
	 * @ "Application_groups" object definition
	 * @ Author:		Juank y Nelson
	 * @ Created:		May - 05 - 2004 - 7:02:06
	 * @ Modified:		May - 05 - 2004 - 7:02:06
	 * @ Version:		1.0
	*************************************************/


	class ApplicationGroups	{
		/**
		 * @ Object var definitions. 
		**/
		var $idGroup;		//	ID_GROUP { int }
		var $name;		//	NAME { string }
		var $description;		//	DESCRIPTION { blob }


	 /*************************************************
	 * @ Constructor(s) of object ApplicationGroups
	*************************************************/
		/**
		 * @ Creates an empty ApplicationGroups object or filled with values. 
		**/
		function ApplicationGroups (  $aIdGroup = FALSE, $aName = FALSE, $aDescription = FALSE  )	{
			if ( $aIdGroup !== FALSE )
				$this->load( $aIdGroup );
			else	{
				if ( $aName !== FALSE )
					$this->name = $aName;
				else
					$this->name = FALSE;
				if ( $aDescription !== FALSE )
					$this->description = $aDescription;
				else
					$this->description = FALSE;
			}
		}

	 /*************************************************
	 * @ Analizer(s) of object ApplicationGroups
	*************************************************/
		/**
		 * @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print( )	{
			echo "
				<ul><br>
				<li><b> ( APPLICATIONGROUPS ) </b></li>
				<ul>
					<li><b>ID_GROUP: </b>'" . $this->idGroup . "'
					</li>
					<li><b>NAME: </b>'" . $this->name . "'
					</li>
					<li><b>DESCRIPTION: </b>'" . $this->description . "'
					</li>
				</ul>
				</ul>
				";
			}

		/**
		 * Returns ID_GROUP of ApplicationGroups
		**/
		function getIdGroup( )	{
			return $this->idGroup;
		}
		/**
		 * Returns NAME of ApplicationGroups
		**/
		function getName( )	{
			return $this->name;
		}
		/**
		 * Returns DESCRIPTION of ApplicationGroups
		**/
		function getDescription( )	{
			return $this->description;
		}

	 /*************************************************
	 * @ Modifier(s) of object ApplicationGroups
	*************************************************/
		/**
		 * Sets ID_GROUP of ApplicationGroups
		**/
		function setIdGroup( $aIdGroup )	{
			$this->idGroup = $aIdGroup;
		}
		/**
		 * Sets NAME of ApplicationGroups
		**/
		function setName( $aName )	{
			$this->name = $aName;
		}
		/**
		 * Sets DESCRIPTION of ApplicationGroups
		**/
		function setDescription( $aDescription )	{
			$this->description = $aDescription;
		}

	 /*************************************************
	 * @ Persistence of object ApplicationGroups
	*************************************************/
		/**
		 * @ Returns a duplication of ApplicationGroups object information
		**/
		function duplicate ( $aIdGroup = FALSE )	{
			$double = new ApplicationGroups( $aIdGroup );
			$double->name = $this->name;
			$double->description = $this->description;
			$double->commit( );
			$double->commit( );
			return $double;
		}

		/**
		 * @ Updates or Inserts ApplicationGroups information, depending
		 * @ upon existence of valid primary key. 
		**/
		function commit ( )	{
			$sql  = " SELECT id_group FROM application_groups WHERE id_group = '$this->idGroup'";
			$result = db_query( $sql );

			if ( db_numrows( $result ) > 0 )	{
				$sql  = " UPDATE application_groups SET";
				$sql .= " name = '" . $this->name . "',";
				$sql .= " description = " . ( ( $this->description != NULL )? "'" . $this->description . "'" : "NULL" );
				$sql .= " WHERE id_group = '" . $this->idGroup . "'";
				db_query( $sql );

			}	else	{
				$sql = " INSERT INTO application_groups " .
						"( id_group, name, description ) " .
						"VALUES ( " .
						"'" . $this->idGroup . "', " .
						"'" . $this->name . "', " .
						( ( $this->description != NULL )? "'" . $this->description . "'" : "NULL" ) . " )";
						
				db_query( $sql );
				$this->idGroup = db_id_insert( );
			}
		}

		/**
		 * @ Deletes ApplicationGroups object from database. 
		**/
		function erase ( )	{
			$sql = "DELETE FROM application_groups";
			$sql .= " WHERE id_group =  '$this->idGroup'";
			db_query( $sql );
		}

		/**
		 * @ Loads ApplicationGroups attributes from the database
		 * @ and assigns them to active object.
		**/
		function load ( $aIdGroup )	{
			$sql  = " SELECT * FROM application_groups";
			$sql .= " WHERE id_group = " . $aIdGroup;
			$result = db_query( $sql );
			$row = db_fetch_array( $result );

			$this->idGroup = $aIdGroup;
			$this->name = $row['name'];
			$this->description = $row['description'];
			
		}
	}

?>