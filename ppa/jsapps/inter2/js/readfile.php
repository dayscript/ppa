<?
set_time_limit(30);
header("Content-Type: text/xml; charset=latin1");

//readfile( "http://localhost/ppa/unewebgrid.php?provider=3&" . $_SERVER['QUERY_STRING'] );
readfile( "http://www.inter.com.ve/servicios/readfile.php?" . $_SERVER['QUERY_STRING'] );

//readfile( "http://200.75.105.77/ppa/DayAdmin/unewebgrid.php?provider=3&" . $_SERVER['QUERY_STRING'] );
//readfile( "http://216.72.226.24/ppa/DayAdmin/unewebgrid.php?provider=4&" . $_SERVER['QUERY_STRING'] );
?>