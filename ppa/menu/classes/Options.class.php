<?



	 /*************************************************
	 * @ "Options" object definition
	 * @ Author:		Juank y Nelson
	 * @ Created:		May - 05 - 2004 - 6:54:20
	 * @ Modified:		May - 05 - 2004 - 6:54:20
	 * @ Version:		1.0
	*************************************************/


	class Options	{
		/**
		 * @ Object var definitions. 
		**/
		var $idOption;		//	ID_OPTION { int }
		var $idParent;		//	ID_PARENT { int }
		var $description;		//	DESCRIPTION { string }
		var $function;		//	FUNCTION_ { string }
		var $position;		//	POSITION { int }


	 /*************************************************
	 * @ Constructor(s) of object Options
	*************************************************/
		/**
		 * @ Creates an empty Options object or filled with values. 
		**/
		function Options (  $aIdOption = FALSE, $aIdParent = FALSE, $aDescription = FALSE, $aFunction = FALSE, $aPosition = FALSE  )	{
			if ( $aIdOption !== FALSE )
				$this->load( $aIdOption );
			else	{
				if ( $aIdParent !== FALSE )
					$this->idParent = $aIdParent;
				else
					$this->idParent = FALSE;
				if ( $aDescription !== FALSE )
					$this->description = $aDescription;
				else
					$this->description = FALSE;
				if ( $aFunction !== FALSE )
					$this->function = $aFunction;
				else
					$this->function = FALSE;
				if ( $aPosition !== FALSE )
					$this->position = $aPosition;
				else
					$this->position = FALSE;
			}
		}

	 /*************************************************
	 * @ Analizer(s) of object Options
	*************************************************/
		/**
		 * @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print( )	{
			echo "
				<ul><br>
				<li><b> ( OPTIONS ) </b></li>
				<ul>
					<li><b>ID_OPTION: </b>'" . $this->idOption . "'
					</li>
					<li><b>ID_PARENT: </b>'" . $this->idParent . "'
					</li>
					<li><b>DESCRIPTION: </b>'" . $this->description . "'
					</li>
					<li><b>FUNCTION_: </b>'" . $this->function . "'
					</li>
					<li><b>POSITION: </b>'" . $this->position . "'
					</li>
				</ul>
				</ul>
				";
			}

		/**
		 * Returns ID_OPTION of Options
		**/
		function getIdOption( )	{
			return $this->idOption;
		}
		/**
		 * Returns ID_PARENT of Options
		**/
		function getIdParent( )	{
			return $this->idParent;
		}
		/**
		 * Returns DESCRIPTION of Options
		**/
		function getDescription( )	{
			return $this->description;
		}
		/**
		 * Returns FUNCTION_ of Options
		**/
		function getFunction( )	{
			return $this->function;
		}
		/**
		 * Returns POSITION of Options
		**/
		function getPosition( )	{
			return $this->position;
		}

	 /*************************************************
	 * @ Modifier(s) of object Options
	*************************************************/
		/**
		 * Sets ID_OPTION of Options
		**/
		function setIdOption( $aIdOption )	{
			$this->idOption = $aIdOption;
		}
		/**
		 * Sets ID_PARENT of Options
		**/
		function setIdParent( $aIdParent )	{
			$this->idParent = $aIdParent;
		}
		/**
		 * Sets DESCRIPTION of Options
		**/
		function setDescription( $aDescription )	{
			$this->description = $aDescription;
		}
		/**
		 * Sets FUNCTION_ of Options
		**/
		function setFunction( $aFunction )	{
			$this->function = $aFunction;
		}
		/**
		 * Sets POSITION of Options
		**/
		function setPosition( $aPosition )	{
			$this->position = $aPosition;
		}

	 /*************************************************
	 * @ Persistence of object Options
	*************************************************/
		/**
		 * @ Returns a duplication of Options object information
		**/
		function duplicate ( $aIdOption = FALSE )	{
			$double = new Options( $aIdOption );
			$double->id_parent = $this->id_parent;
			$double->description = $this->description;
			$double->function_ = $this->function_;
			$double->position = $this->position;
			$double->commit( );
			$double->commit( );
			return $double;
		}

		/**
		 * @ Updates or Inserts Options information, depending
		 * @ upon existence of valid primary key. 
		**/
		function commit ( )	{
			$sql  = " SELECT id_option FROM options WHERE id_option = '$this->idOption'";
			$result = db_query( $sql );

			if ( db_numrows( $result ) > 0 )	{
				$sql  = " UPDATE options SET";
				$sql .= " id_parent = " . ( ( $this->idParent != NULL )? "'" . $this->idParent . "'" : "NULL" ) . ",";
				$sql .= " description = " . ( ( $this->description != NULL )? "'" . $this->description . "'" : "NULL" ) . ",";
				$sql .= " function_ = '$this->function',";
				$sql .= " position = '$this->position'";
				$sql .= " WHERE id_option = '$this->idOption'";
				db_query( $sql );

			}	else	{
				$sql = " INSERT INTO options " .
						"( id_option, id_parent, description, function_, position ) " .
						"VALUES ( " .
						"'" . $this->idOption . "', " .
						( ( $this->idParent != NULL )? "'" . $this->idParent . "'" : "NULL" ) . ", " .
						( ( $this->description != NULL )? "'" . $this->description . "'" : "NULL" ) . ", " .
						"'$this->function', " .
						"'$this->position' )";
						
				db_query( $sql );
				$this->idOption = db_id_insert( );
			}
		}

		/**
		 * @ Deletes Options object from database. 
		**/
		function erase ( )	{
			$sql = "DELETE FROM options";
			$sql .= " WHERE id_option =  '$this->idOption'";
			db_query( $sql );
		}

		/**
		 * @ Loads Options attributes from the database
		 * @ and assigns them to active object.
		**/
		function load ( $aIdOption )	{
			$sql  = " SELECT * FROM options";
			$sql .= " WHERE id_option = " . $aIdOption;
			$result = db_query( $sql );
			$row = db_fetch_array( $result );

			$this->idOption = $aIdOption;
			$this->idParent = $row['id_parent'];
			$this->description = $row['description'];
			$this->function = $row['function_'];
			$this->position = $row['position'];
			
		}
	}

?>