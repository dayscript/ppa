<?
function phpArray2JsArray( $arr )
{
	$str = "";
	foreach($arr as $val)
	{
		if(is_numeric($arr))
			$str .= $val . ",";
		else
			$str .= "\"" .  addslashes($val) . "\",";
	}
	return "[" . substr($str,0,-1) . "]";
}

function getReadyString( $str )
{
		if(is_numeric($str))
			$ret = $str;
		else
			$ret = "\"" .  htmlentities( addslashes($str) ). "\"";	
	
		return $ret;
}

$sql = "SELECT channel.name, channel.logo, client_channel._group, client_channel.number " .
	"FROM channel, client_channel WHERE " .
	"client_channel.channel = channel.id AND " .
	"client_channel.client = " . $ID_CLIENT . 
	" ORDER BY client_channel._group, client_channel.number";

$result = db_query( $sql );
while( $row = db_fetch_array( $result ) )
{
	$grp = $row["_group"];
	if($grp == "Adultos" || $grp == "Premium" || $grp == "PPV"  || $grp == "PPV Adultos")
		$grp=$grp."*";
	$chn_list[$grp][] = array("image" => ($row["logo"]==""?"channel_logos/empty.gif":$row["logo"]), 
		"number" => $row["number"],
		"name" => $row["name"] );
}

$categories_js_array = "";
$i = 0;
foreach($chn_list as $k => $v)
{
	$categories_js_array .= "\"" . ucfirst( addslashes($k) ) . "\",";
	foreach($v as $ch)
	{
		$chn_grps[$i][] = $ch;	
	}
	$i++;
}
$categories_js_array = "[" . substr($categories_js_array,0,-1) . "];";

$channels_js_array = "";
for($i=0,$max=count($chn_grps);$i < $max;$i++)
{
	$tmpstr = "";
	foreach($chn_grps[$i] as $k => $v)
	{
		$tmpstr .= "[" . getReadyString($v["number"]) . "," . 
			getReadyString($v["name"]) . "," . 
			getReadyString($v["image"]) . "],";
	}
	$channels_js_array .= $i . ":[" . substr($tmpstr,0,-1) . "],";
}
$channels_js_array = "{" . substr($channels_js_array,0,-1) . "}";
?>
var categories = <?=$categories_js_array ?>;
var channels   = <?=$channels_js_array ?>;