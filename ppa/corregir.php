<?
$link = mysql_connect("localhost","dayscript","kfc3*9mn");
mysql_select_db("program");
$db = "program";
require_once( "include/db.inc.php" );



$sql = "select id,title from movie where title like '%,'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = substr($row[1],0,strlen($row[1])-1);
	echo $str . "<br>";
	$sql = "update movie set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from movie where spanishTitle like '%,'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = substr($row[1],0,strlen($row[1])-1);
	echo $str . "<br>";
	$sql = "update movie set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,title from movie where title like '%, El'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "El " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update movie set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from movie where spanishTitle like '%, El'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "El " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update movie set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,title from movie where title like '%, En'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "En " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update movie set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from movie where spanishTitle like '%, En'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "En " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update movie set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}

$sql = "select id,title from movie where title like '%, La'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "La " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update movie set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from movie where spanishTitle like '%, La'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "La " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update movie set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,title from movie where title like '%, Los'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "Los " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update movie set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from movie where spanishTitle like '%, Los'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "Los " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update movie set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,title from movie where title like '%, The'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "The " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update movie set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from movie where spanishTitle like '%, The'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "The " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update movie set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,title from movie where title like '%, Las'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "Las " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update movie set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from movie where spanishTitle like '%, Las'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "Las " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update movie set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}




$sql = "select id,title from chapter where title like '%, The'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "The " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update chapter set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from chapter where spanishTitle like '%, The'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "The " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update chapter set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,title from chapter where title like '%, En'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "En " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update chapter set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from chapter where spanishTitle like '%, En'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "En " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update chapter set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,title from chapter where title like '%,'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = substr($row[1],0,strlen($row[1])-1);
	echo $str . "<br>";
	$sql = "update chapter set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from chapter where spanishTitle like '%,'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = substr($row[1],0,strlen($row[1])-1);
	echo $str . "<br>";
	$sql = "update chapter set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,title from chapter where title like '%, El'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "El " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update chapter set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from chapter where spanishTitle like '%, El'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "El " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update chapter set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}

$sql = "select id,title from chapter where title like '%, La'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "La " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update chapter set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from chapter where spanishTitle like '%, La'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "La " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update chapter set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}


$sql = "select id,title from chapter where title like '%, Los'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "Los " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update chapter set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from chapter where spanishTitle like '%, Los'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "Los " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update chapter set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,title from chapter where title like '%, Las'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "Las " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update chapter set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from chapter where spanishTitle like '%, Las'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "Las " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update chapter set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}




$sql = "select id,title from serie where title like '%, The'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "The " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update serie set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from serie where spanishTitle like '%, The'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "The " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update serie set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,title from serie where title like '%, En'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "En " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update serie set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from serie where spanishTitle like '%, En'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "En " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update serie set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,title from serie where title like '%,'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = substr($row[1],0,strlen($row[1])-1);
	echo $str . "<br>";
	$sql = "update serie set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from serie where spanishTitle like '%,'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = substr($row[1],0,strlen($row[1])-1);
	echo $str . "<br>";
	$sql = "update serie set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,title from serie where title like '%, El'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "El " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update serie set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from serie where spanishTitle like '%, El'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "El " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update serie set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}

$sql = "select id,title from serie where title like '%, La'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "La " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update serie set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from serie where spanishTitle like '%, La'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "La " . substr($row[1],0,strlen($row[1])-4);
	echo $str . "<br>";
	$sql = "update serie set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}


$sql = "select id,title from serie where title like '%, Los'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "Los " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update serie set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from serie where spanishTitle like '%, Los'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "Los " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update serie set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}

$sql = "select id,title from serie where title like '%, Las'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "Las " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update serie set title='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}
$sql = "select id,spanishTitle from serie where spanishTitle like '%, Las'";
$res = db_query($sql);
while($row = db_fetch_array($res)){
	$str = "Las " . substr($row[1],0,strlen($row[1])-5);
	echo $str . "<br>";
	$sql = "update serie set spanishTitle='" . addslashes($str) . "' where id='" . $row[0] . "'";
	db_query($sql);
}