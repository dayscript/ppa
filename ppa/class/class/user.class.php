<?
	
	/**
	 * definition class for user
	 * implemented for generator program only
	 *
	 * @author   Nelson Daza
	 * @created  July 08 2003
	 * @modified July 08 2003
	 * @version  1.0
	**/
	
	require_once("globals.php");
	require_once("table.class.php");
	
	class User {
		/**
		 * Identification value
		**/
		var $name;		/* name to show */
		var $host;		/* host name */
		var $db;		/* database */
		var $db_user;	/* database user name */
		var $db_password;	/* database password */
		var $db_tables;		/* database tables */
		var $gen_tables;		/* array of generator tables */
	
		/**********************************************
		* Constructors
		**********************************************/
		function User ($aName = false, $aHost = false, $aDB = false, $aDBUser = false, $aDBPassword = false, $aDBTables = false)	{
			if ($aName)
				$this->name = $aName;
			if ($aHost)
				$this->host = $aHost;
			if ($aDB)
				$this->db = $aDB;
			if ($aDBUser)
				$this->db_user = $aDBUser;
			if ($aDBPassword)
				$this->db_password = $aDBPassword;
			if ($aDBTables)
				$this->$db_tables = $aDBTables;
			$this->gen_tables = array();
		}
		/**********************************************
		* Analizers
		**********************************************/
		/**
		* Prints HTML representation of this object.
		*/
		function _print(){
		    echo "<li><b>(user)</b></li>";
			echo "<ul>";
			echo "<li><b>Name:</b> '".$this->name."'</li>";
			echo "<li><b>Host Name:</b> '".$this->host."'</li>";
			echo "<li><b>DataBase:</b> '".$this->db."'</li>";
			echo "<li><b>DataBase User Name:</b> '".$this->db_user."'</li>";
			echo "<li><b>DataBase Password:</b> '".$this->db_password."'</li>";
			echo "<li><b>DataBase tables:</b> '";
			print_r($this->db_tables);
			echo "'</li></ul>";
		}
		/**
		 * Returns the name of the user.
		**/
		function getName(){
		   return $this->name;
		}
		/**
		 * Returns the host to work on.
		**/
		function getHost(){
		   return $this->host;
		}
		/**
		 * Returns the database name.
		**/
		function getDB(){
		   return $this->db;
		}
		/**
		 * Returns the database user name.
		**/
		function getDBUser(){
		   return $this->db_user;
		}
		/**
		 * Returns the database password of the user.
		**/
		function getDBPassword(){
		   return $this->db_password;
		}
		/**
		 * Returns the database tables.
		**/
		function getDBTables(){
		   return $this->db_tables;
		}
		/**
		 * Returns the generator tables.
		**/
		function getGenTables(){
		   return $this->gen_tables;
		}
	
		/**
		 * Returns the generator tables by it's name.
		**/
		function TableByName($Name){
			for ($c = 0; $c < count($this->gen_tables); $c ++)	{
				if (strcasecmp($this->gen_tables[$c]->getName(), $name) == 0)
					return $this->gen_tables[$c];
			}
			$tbl = new Table();
			$tbl->setName($Name);
			$this->gen_tables[] = &$tbl;
					
			return $tbl;
		}
		
		/**********************************************
		* Modifiers
		**********************************************/
	
		/**
		 * Sets the name of the user.
		**/
		function setName($aName){
		   $this->name = $aName;
		}
		/**
		 * Sets the host to work on.
		**/
		function setHost($aHost){
		   $this->host = $aHost;
		}
		/**
		 * Sets the database name.
		**/
		function setDB($aDB){
		   $this->db = $aDB;
		}
		/**
		 * Sets the database user name.
		**/
		function setDBUser($aDBUser){
		   $this->db_user = $aDBUser;
		}
		/**
		 * Sets the database password of the user.
		**/
		function setDBPassword($aDBPassword){
		   $this->db_password = $aDBPassword;
		}
		/**
		 * Sets the database tables.
		**/
		function setDBTables($aDBTables){
		   $this->db_tables = $aDBTables;
		}
		/**
		 * Sets the generator tables.
		**/
		function setGenTables($aGenTables){
		   $this->gen_tables = $aGenTables;
		}
	}
?>
