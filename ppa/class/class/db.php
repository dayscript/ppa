<?

/**
 * definition of global properties
 * implemented (modified) for generator program only
 *
 * @author   Nelson Daza
 * @created  July 08 2003
 * @modified July 08 2003
 * @version  1.0
**/

/**************************************
 * Database Functions - Implementation
**************************************/
	$LINK = false;
	$DB = false;
	$TBL = false;
	$TBL_LINK = false;

	function db_mysql_connect($aHost = "localhost", $aUser = false, $aPassWord = false)	{
		global $LINK;
		global $DB;
		if ($LINK)	{
			mysql_close($LINK);
			$LINK = false;
			$DB = false;
		}
		$LINK = @mysql_connect($aHost,$aUser,$aPassWord);
		return $LINK;
	}

	function db_mysql_error ()	{
		return mysql_error();
	}

	function db_mysql_select_db($aDB)	{
		global $DB;
		$DB = $aDB;
		return mysql_select_db($DB);
	}

	function db_query ($sql, $echo=false)	{
		if ($echo)
			echo "<b>SQL query : $sql</b><br>";
		return mysql_db_query($DB,$sql,$LINK);
	}

	function db_numrows($result)	{
		return mysql_numrows($result);
	}

	function db_fetch_array($result, $result_type=MYSQL_BOTH)	{
		return mysql_fetch_array($result, $result_type );
	}

	function db_id_insert()	{
		return mysql_insert_id();
	}

	function db_mysql_list_tables ()	{
		global $DB;
		$result = mysql_list_tables($DB);
		for ($c = 0; $c < db_numrows($result); $c++)
			$tbls[$c] = mysql_tablename($result, $c);
		return $tbls;
	}

	function db_mysql_list_dbs ()	{
		$result = mysql_list_dbs();
		for ($c = 0; $c < mysql_num_rows($result); $c++)
			$dbs[$c] = mysql_db_name($result, $c);
		return $dbs;
	}
	
	function db_mysql_list_fields($table_name)	{
		global $TBL;
		global $TBL_LINK;
		global $DB;
		global $LINK;
		
		$TBL = $table_name;
		$TBL_LINK = mysql_list_fields($DB, $TBL, $LINK);
		
		$fields = array();
		for ($c = 0; $c < mysql_num_fields($TBL_LINK); $c ++)	{
			$fields[$c]['name'] = mysql_field_name($TBL_LINK, $c);
			$fields[$c]['type'] = mysql_field_type($TBL_LINK, $c);
			$fields[$c]['size'] = mysql_field_len($TBL_LINK, $c);
			$fields[$c]['flags'] = mysql_field_flags($TBL_LINK, $c);
		}
		return $fields;
	}
?>
