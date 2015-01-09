<?
	/**
	 * Database Functions - Implementation
	**/
	
	function db_query ( $sql, $echo = false )	{
		global $db, $link, $queries;
	
		$queries ++;
		$result = mysql_db_query( $db, $sql, $link );
		if ( mysql_error ( ) )	{
			echo "<br><font color='#FFCC00'><b>MySQL ERROR : " . mysql_error ( ) . "</b></font><br>";
			$echo = TRUE;
		}
		if ( $echo ){
			echo "<br><font color='#FFCC00'><b>SQL query : " . $sql . "</b></font><br>";
		}
		return $result;
	}
	
	function db_connect ( $dsn, $user, $password, $cursor = false )	{
		return mysql_connect( "localhost", $user, $password );
	}
	
	function db_select ( $db )	{
		global $link;
		mysql_select_db( $db );
	}
	
	function db_numrows ( $result )	{
		return mysql_num_rows ( $result );
	}
	
	function db_fetch_array ( $result )	{
		return mysql_fetch_array ( $result );
	}
	
	function db_id_insert ( )	{
		global $link;
		return mysql_insert_id ( $link );
	}
	
	function db_affectedrows ( )	{   
	   return mysql_affected_rows ( );
	}

	function id_by_field_like ($table, $field, $value, $add_sql = '')	{
		$id = false;
		$sql = "SELECT * FROM " . $table . " WHERE " . $field . " LIKE '" . $value . "' " . $add_sql;
		$result = db_query ($sql);
		if (db_numrows ($result) > 0)	{
			$row = db_fetch_array ($result);
			$id = $row[0];
		}
		return $id;
	}

	function id_by_field_equal ($table, $field, $value, $add_sql = '')	{
		$id = false;
		$sql = "SELECT * FROM " . $table . " WHERE " . $field . " = '" . $value . "' " . $add_sql;
		$result = db_query ($sql);
		if (db_numrows ($result) > 0)	{
			$row = db_fetch_array ($result);
			$id = $row[0];
		}
		return $id;
	}

	function conditional_query ($if, $condition, $then)	{
		$result = db_query ($if);
		$exist = (db_numrows ($result) > 0);
		if ( $exist == $condition )	{
			db_query ($then);
			return true;
		}
		return false;
	}

?>
