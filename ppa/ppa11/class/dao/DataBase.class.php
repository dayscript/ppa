<?

Class DataBase
{
	var $link;
	var $db;
	var $result;
	var $debug;
	var $SHOWERRORS;
	var $SHOWQUERY;
	
	function DataBase($host, $usr, $pass, $db, $debug=false)
	{
		$this->link = mysql_pconnect($host, $usr, $pass);
		mysql_select_db($db);
		$this->db=$db;
		$this->debug = $debug;
		$this->SHOWERRORS = false;
		$this->SHOWQUERY = false;
	}

	function query ( $sql, $showquery=false )
	{	
		if( !($this->result = mysql_db_query( $this->db,$sql,$this->link ) ) )
		{
			if( $this->debug || $this->SHOWERRORS )
			{
				echo $sql . "\n";
				echo $this->error();
			}
			return false;
		}
		else
		{
			if( $this->debug || $showquery || $this->SHOWQUERY )
			{
				echo $sql . "\n";
			}
			return true;
		}
	}

	function fetchArray( )
	{
		return mysql_fetch_array($this->result);
	}

	function insertId()
	{   
		return mysql_insert_id();
	}

	function affectedRows()
	{   
		return mysql_affected_rows();
	}

	function numRows()
	{   
		return mysql_num_rows( $this->result );
	}

	function error()
	{   
		return mysql_error($this->link);
	}
	
	function errno()
	{   
		return mysql_errno($this->link);
	}
	
	function close()
	{   
		return mysql_close($this->link);
	}
}
?>
