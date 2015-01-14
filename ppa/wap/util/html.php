<?
function htmlLink( $name, $location, $class = false)
{
	$name     = remove_special_chars( $name );
	$location = remove_special_chars( $location );
	if( $class ) echo( "<a href=\"?l=" . $location . "\" class=\"" . $class . "\">" . $name . "</a><br />" );
	else echo( "<a href=\"?l=" . $location . "\">" . $name . "</a><br />" );
}

function htmlMenu( $arr, $class = false )
{
	echo( "<p>" );
	foreach( $arr as $item )
	{
		htmlLink( $item[0], $item[1], $class );
	}
	echo( "</p>" );
}

function printCommonButtons()
{
	echo( "<p><a href=\"?;\">Home</a>" );
	echo( "</p>" );
}

function remove_special_chars( $str )
{
	$str = str_replace("&", "&amp;", $str);
	
	return $str;
}
?>