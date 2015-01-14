<?
function fixHour( $str )
{
	$time 	= explode(":", $str);
	$minute	= sprintf("%d", ( $time[1] / 30 )) * 30;
	
	return $time[0] .":". sprintf("%02d", $minute);
}

function to12H( $str )
{
	$str   = explode(":", $str);
	$hours = $str[0] % 12 == 0 ? 12 : $str[0] % 12;
	return $hours .":". $str[1] ." ". ( $str[0] / 12 >= 1 ? "pm" : "am" );
}
?>