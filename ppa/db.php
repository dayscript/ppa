<?
	$link = mysql_connect("dayscript.com","dayscript","kfc3*9mn");
	mysql_select_db("tvcable");
	$db = "tvcable";
#------------------------------
# Database Functions - Implementation
#------------------------------

function db_query ($sql, $echo=false)
{	global $db, $link;
	if ($echo){
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
?>