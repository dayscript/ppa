<?
#------------------------------
# Database Functions - Implementation
#------------------------------

function db_query ($sql, $echo=false)
{	global $db, $link, $DEBUG;
	if ($echo || $DEBUG){
		echo "<b>SQL query : $sql</b><br>";
	}
	return mysql_db_query($db,$sql,$link);
}

function db_numrows($result)
{	return mysql_numrows($result);
}

function db_fetch_array($result)
{	return mysql_fetch_array($result);
}
function db_id_insert()
{   return mysql_insert_id();
}
?>