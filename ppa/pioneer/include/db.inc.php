<?
#------------------------------
# Database Functions - Implementation
#------------------------------

function db_query($sql, $echo=false, $sync=true)
{	
	global $db, $link;
	if ($echo){
		echo "\n<b>SQL query : $sql</b><br />\n";
	}
	if( eregi( "delete", $sql ) )
	{
		$h = fopen( "/tmp/ppa.log", "a" );
		fwrite( $h, date("Ymd H:i") . ": " . $_SESSION['user'] . " " . $_SERVER["SCRIPT_FILENAME"] . " " . $sql . "\n" );
		fclose( $h );
	}
	if( $sync && eregi( "delete|update|insert", $sql ) )
	{
		$h = fopen( "/home/ppa/backup/sync_" . date("H") . ".sql", "a" );
		fwrite( $h, $sql . ";\n" );
		fclose( $h );
	}
	return mysql_db_query($db,$sql,$link);
}

function db_query2($sql, $echo=false)
{	
	global $db, $link;
	if ($echo){
		echo "\n<b>SQL query : $sql</b><br />\n";
	}
	if( eregi( "delete", $sql ) )
	{
		$h = fopen( "", "a" );
		fwrite( $h, date("Ymd H:i") . ": " . $_SERVER["SCRIPT_FILENAME"] . " " . $sql . "\n" );
		fclose( $h );
	}
	if( !($result = mysql_db_query($db,$sql,$link) ) )
	{
		echo $sql;
		echo db_error() . "\n";
		return $result;
	}
	else
	{
		return $result;
	}
}

function db_numrows($result)
{   
    if( trim( $result ) != "" ){
	  return mysql_num_rows($result);
	}else{
	   return 0;
	}
}

function db_fetch_array($result, $result_type=MYSQL_BOTH)	
{
	return mysql_fetch_array($result, $result_type );
}

function db_insert_id()
{   
	return mysql_insert_id();
}

function db_id_insert()
{   
	return mysql_insert_id();
}

function db_affected_rows()
{   
	global $link;
	return mysql_affected_rows($link);
}

function db_error()
{   
	global $link;
	return mysql_error($link);
}

function db_errno()
{   
	global $link;
	return mysql_errno($link);
}

function db_close()
{   
	global $link;
	return mysql_close($link);
}
?>