<?
header("Content-type: application/xml");
include('ppa/include/config.inc.php');
include('include/db.inc.php');
include('class/Ftp.class.php');
  
class XmlFile
{
  
  function XmlFile($id)
  {
    $this->writeLn("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>");
  }
  
  function writeLn ($str)
  {
  	echo $str . "\n";
  }

  function writeHead($str)
  {
    $this->writeLn( "<head name=\"". $str ."\">" );  	
  }

  function writeChannel($shortname, $name, $numb)
  {
    $this->writeLn( "  <channel shortname=\"". urlencode(strtoupper($shortname)) ."\" name=\"". urlencode(ucwords(strtolower($name))) ."\" number=\"". $numb ."\">" );
  }
 	
  function writeDate($str)
  {
    $this->writeLn( "    <date value=\"". $str ."\">" );
  }

  function writeSlot($time, $title)
  {
    $this->writeLn( "      <slot hour=\"". $time ."\" title=\"". addslashes($this->textFormat($title)) ."\" />" );
  }

  function closeDate()
  {
    $this->writeLn( "    </date>" );
  }

  function closeChannel()
  {
    $this->writeLn( "  </channel>" );
  }

  function closeHead()
  {
    $this->writeLn( "</head>" );
  }

  function close()
  {
  	fclose($this->hd);
  }

  function textFormat($str)
  {
    $str = str_replace("\n", "", $str);
    $str = str_replace("\r", "", $str);
    $str = trim($str);
    $str = strtolower($str);
    $str = ucwords($str);
    $str = urlencode($str);
    return $str;
  }
}

$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.name ".
       "FROM channel, client, client_channel ".
       "WHERE client.id = client_channel.client ". 
       "AND channel.id=client_channel.channel ".
       "AND client.id = ". $_GET['id'] ." ".
       "ORDER BY client_channel.number";

$result = db_query($sql );
$i = 0;
while( $row = db_fetch_array($result) )
{
	$channels[$i]['number']    = $row['number'];
	$channels[$i]['name']      = $row['name'];
	$channels[$i]['shortname'] = $row['shortname'];
	$i++;
}

$sql = "SELECT client.name head, client_channel.number, channel.shortname, channel.name, slot.time, slot.title, slot.date ".
       "FROM channel, client, client_channel, slot ".
       "WHERE client.id = client_channel.client ". 
       "AND channel.id=client_channel.channel ".
       "AND client.id = ". $_GET['id'] ." ".
       "AND slot.channel = channel.id ".
       "AND slot.date BETWEEN '2005-10-01' and '2005-10-03' ".
//       "AND slot.date BETWEEN '". date("Y-m-d", time() - 86400) ."' and '". date("Y-m-d", time() + 86400) ."' ".
       "ORDER BY client_channel.number, slot.date, slot.time ";

$result = db_query($sql);
$row    = db_fetch_array($result);
$fl     = new XmlFile($_GET['id']);

$fl->writeHead( $row['head'] );
$ip = $row['ip'];

$i = 0;
while( $row )
{
  if($channels[$i]['number'] == $row['number'] )
  {
	  $channel  = $row['shortname'];
	  $date     = $row['date'];
	  $fl->writeChannel( $channel, $row['name'], $row['number'] );
	  $fl->writeDate( $date );
	  for(; $row['shortname'] == $channel; $row=db_fetch_array($result))
	  {
	  	if($date!=$row['date']) 
	  	{
	  	  $fl->closeDate();
	  	  $date = $row['date'];
	  	  $fl->writeDate( $date );
	  	}
	    $fl->writeSlot( substr($row['time'], 0,5), $row['title'] );
	    $channel = $row['shortname'];
	  }
	  $fl->closeDate();
	  $fl->closeChannel();
   }
   else
   {
	  $fl->writeChannel( $channels[$i]['shortName'], $channels[$i]['name'], $channels[$i]['number'] );
	  $fl->writeDate( '0000-00-00' );
  	  $fl->writeSlot( '00:00', 'Programación Sin Confirmar' );
  	  $fl->closeDate();
	  $fl->closeChannel();   	 
   }
   $i++;
}

$fl->closeHead();
?>
