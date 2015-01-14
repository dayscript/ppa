<?
//$link = mysql_pconnect("localhost", "dayscript", "kfc3*9mn") or mysql_die();
//$db ="program";
//mysql_select_db($db) or die("Unable to select database");
//require_once("../../include/db.inc.php");

Class Program
{
	var $id;
	var $title;
	var $DEBUG;
	var $SYNC;
	
	function Program()
	{
		$DEBUG = false;
		$SYNC = false;
		
		$sql = "CREATE TEMPORARY TABLE program ".                  
		       "( ".                                                  
		       "    id int(10) NOT NULL auto_increment, ".
		       "    title varchar(255), ".      
		       "    PRIMARY KEY  (`id`), ".     
		       "    UNIQUE KEY `title` ( `title` ), ".     
		       "    KEY `id_title` ( `title` ) ".     
		       ")";                      
		db_query($sql,$this->DEBUG, $this->SYNC) or die(db_error());
	}
	
	function getId()
	{
		return $this->id;
	}
		
	function getTitle()
	{
		return $this->title;
	}
	
	function selectAllProgram()
	{
		$sql = "SELECT * FROM program ORDER BY id ";
		$this->result = db_query($sql,$this->DEBUG, $this->SYNC);
	}
	
	function query( $sql )
	{
		$this->result = db_query($sql,$this->DEBUG, $this->SYNC);
	}
	
	function appendReg( $id, $title )
	{
		$sql = "INSERT INTO program VALUES (" .
//		       " '" . $id ."', " .
		       " '', " .
		       " '" . addslashes( trim( $title ) ) ."' " .
		       ")";
		return db_query($sql,$this->DEBUG, $this->SYNC);
	}
	
	function fetchProgramArray()
	{
		if( $row = db_fetch_array( $this->result ) )
		{
			$this->id      = $row['id'];
			$this->title   = $row['title'];
			return true;
		}
		else
		{
			return false;
		}	
	}
	
	function getLastDbError()
	{
		return db_errno();
	}

	function getNumRows()
	{
		return db_numrows( $this->result );
	}

	function getInsertId()
	{
		return db_insert_id();
	}
}

?>