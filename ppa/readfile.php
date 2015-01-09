<?
set_time_limit(30);
header("Content-Type: text/xml; charset=latin1");
readfile( "http://200.71.33.249/ppa/unewebgrid.php?provider=3&" . $_SERVER['QUERY_STRING'] );
//readfile( "http://200.75.105.77/ppa/DayAdmin/unewebgrid.php?provider=3&" . $_SERVER['QUERY_STRING'] );
//readfile( "http://200.75.113.177/ppa/DayAdmin/unewebgrid.php?provider=4&" . $_SERVER['QUERY_STRING'] );
?>