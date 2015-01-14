<?
include('../ppa/include/config.inc.php');
include('../include/db.inc.php');
define("CLIENT", 64);

$headers =  getallheaders();
if( false && ereg( "text/xml", $headers['Accept'] ) )
{
	header( "Location: http://www.dayscript.com" );
	exit;
}
$sql = "SELECT distinct(_group) FROM `client_channel` WHERE  client='" . CLIENT . "' ORDER BY 1";
$result = db_query($sql);

while( $row = db_fetch_array($result) )
{
	$category[] = "\"" .ucwords($row['_group']) . "\" ";
}

$sql = "SELECT channel.name, channel.id ".
       "FROM channel, client, client_channel ".
       "WHERE client.id = client_channel.client ". 
       "AND channel.id=client_channel.channel ".
       "AND client.id = ". CLIENT ." ".
       "ORDER BY channel.name";

$result = db_query( $sql );

while( $row = db_fetch_array($result) )
{
	$channel[] = "[\"" . $row['id'] . "\", \"" . substr( $row['name'], 0, 14 ) . "\"]";
}
?>

function Go ( sel )
{
	document.location = "http://www.costavision.com/result_programa.php?" + sel.name + "=" + sel.value;
//	document.location = "http://www.costavision.com/index.php?option=com_wrapper&Itemid=129&" + sel.name + "=" + sel.value;
}
document.write( '<table width="136" height="127" border="0" cellpadding="0" cellspacing="0"> <tr> <td align="left" valign="top" bgcolor="#1F62B0"><table width="136" border="0" cellspacing="2" cellpadding="2"> <tr align="center"> <td width="1" align="left">&nbsp;</td> <td colspan="3" align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; color: #FFFFFF;">Por Canal</td> </tr> <tr align="center"> <td>&nbsp;</td> <td colspan="2" align="center"><select onChange="Go( this );" name="channel" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #000000;"><option>- Canal -</option>');

var channel = new Array();
channel = [ <?=implode( ",", $channel )?> ]
for( i=0; i<channel.length; i++)
{
	reg = channel[i];
	document.write ( '<option value="' + reg[0] + '">' + reg[1] + '</option>' );
}
document.write( '</select></td> <td>&nbsp;</td> </tr> <tr align="center"> <td align="left">&nbsp;</td> <td colspan="3" align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; color: #FFFFFF;"><br>Por Categor&iacute;a </td> </tr> <tr align="center"> <td>&nbsp;</td> <td colspan="2" align="center"><select onChange="Go( this );" name="category" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #000000;"><option>- Categoría -</option>');

var category = new Array();
category = [ <?=implode( ",", $category )?> ]
for( i=0; i<category.length; i++)
{
	document.write ( '<option value="' + category[i] + '">' + category[i] + '</option>' );
}
document.write( '</select></td> <td>&nbsp;</td> </tr> <tr> <td>&nbsp;</td> <td width="4" align="right">&nbsp;</td> <td width="101" align="right">&nbsp;</td> <td width="17">&nbsp;</td> </tr> </table></td> </tr></table>');
