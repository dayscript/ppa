<?
$STYLE['yes'] = 'style="font-weight: bold; color: #FFFFFF; background : #00BF00; cursor: pointer;"';
$STYLE['no']  = 'style="font-weight: bold; color: #FFFFFF; background : #D70000; cursor: pointer;"';
$DEBUG	      = false;
$ONEDAY       = 86400;

if( $_GET['date'] != '' )
	$date = strtotime( $_GET['date'] );
else
	$date = time() + ( $ONEDAY * 2 );

$client     = array();
$id_channel = array(0);
	
if( !isset( $_GET['client'] ) || !ereg( "[0-9]*", $_GET['client'] ) )
{
	$_GET['client'] = 0;
}

if( !isset( $_GET['prog'] ) || !ereg( "yes|no", $_GET['prog'] ) )
{
	$_GET['prog'] = "yes";
}

/**************************************
* Get Clients
**************************************/

$sql = "" .
	"SELECT id, name " . 
	"FROM client "
	 ;

$result = db_query( $sql, $DEBUG );

while( $row = db_fetch_array( $result ) )
{
	$client[] = $row;
}

/**************************************
* Get channels with program
**************************************/

$sql = "" .
	"SELECT DISTINCT( channel.id ), channel.name, channel.description " . 
	"FROM slot " . 
	"INNER JOIN channel " . 
	"ON slot.channel = channel.id " .
	"WHERE " . 
	"  slot.date = '" . date("Y-m-d", $date ) . "' " .
	"ORDER by channel.name ";

$result = db_query( $sql, $DEBUG );

$channel    = array();
while( $row = db_fetch_array( $result ) )
{
	$id_channel[] = $row['id'];
	$channel[] = $row;
}

/**************************************
* Get channels without program for all client
**************************************/

if( $_GET['client'] == 0 && strtolower( $_GET['prog'] ) == "no" )
{
	$sql = "" .
		"SELECT channel.id, channel.name, channel.description  " . 
		"FROM channel " .
		"WHERE " .
		" channel.id NOT IN( " . implode(",", $id_channel ) . " ) " .
		"ORDER by channel.name ";

	$result = db_query( $sql, $DEBUG );

	$channel    = array();
	while( $row = db_fetch_array( $result ) )
	{
		$id_channel[] = $row['id'];
		$channel[] = $row;
	}
}

/**************************************
* Get channels with program for a client
**************************************/

if( $_GET['client'] != 0 && strtolower( $_GET['prog'] ) == "yes" )
{
	$sql = "" .
		"SELECT channel.id, channel.name, channel.description  " . 
		"FROM channel, client_channel " .
		"WHERE " .
		" client_channel.channel = channel.id " .
		" AND client_channel.client = ". $_GET['client'] ." " .
		" AND channel.id IN( " . implode(",", $id_channel ) . " ) " .
		"ORDER by channel.name ";

	$result = db_query( $sql, $DEBUG );

	$channel    = array();
	while( $row = db_fetch_array( $result ) )
	{
		$id_channel[] = $row['id'];
		$channel[] = $row;
	}
}

/**************************************
* Get channels without program for a client
**************************************/

if( $_GET['client'] != 0 && strtolower( $_GET['prog'] ) == "no" )
{
	$sql = "" .
		"SELECT channel.id, channel.name, channel.description  " . 
		"FROM channel, client_channel " .
		"WHERE " .
		" client_channel.channel = channel.id " .
		" AND client_channel.client = ". $_GET['client'] ." " .
		" AND channel.id NOT IN( " . implode(",", $id_channel ) . " ) " .
		"ORDER by channel.name ";

	$result = db_query( $sql, $DEBUG );

	$channel    = array();
	while( $row = db_fetch_array( $result ) )
	{
		$id_channel[] = $row['id'];
		$channel[] = $row;
	}
}



/**************************************
* Prints HTML
**************************************/
?>
<link href="js/calendar/calendar-blue.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/calendar/calendar.js"></script>
<script type="text/javascript" src="js/calendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="js/calendar/calendar-setup.js"></script>
<script>
	function onChangeDate(cal)
	{
		newLocation('<?=$_GET['client']?>', '<?=$_GET['prog']?>', cal.date.print("%Y-%m-%d") );
	}
	function newLocation(client, prog, date)
	{
		if( date )
			document.location = "?location=<?=$_GET['location']?>&client=" + client + "&prog=" + prog + "&date=" + date;
		else
			document.location = "?location=<?=$_GET['location']?>&client=" + client + "&prog=" + prog;
	}
</script>
<table width="200" class="titulo">	
  <tr >
    <td align="rigth">
      Cabecera
    </td>
    <td align="left">
      <select name="client" onChange="newLocation(this.value, '<?=$_GET['prog']?>')">
         <option value="0">-- Todos --</option>
<?
	foreach( $client as $reg )
	{
?>
         <option value="<?=$reg['id']?>" <?=$reg['id'] == $_GET['client'] ? "selected" : "" ?> ><?=$reg['name']?></option>
<?		
	}
?>
      </select>
    </td>
  </tr>
  <tr >
    <td align="rigth">
      Fecha
    </td>
    <td align="center">
	  <div style="border-style: solid; border-width: thin; width: 200; cursor: pointer; background-color: rgb(255, 255, 255);" onmouseover="this.style.backgroundColor='#ffffff';" onmouseout="this.style.backgroundColor='#ffffff';" id="show_date"><?=date('Y-m-d', $date)?></div>
	  <input id="date" name="date" value="" size="25" type="hidden">
	<script type="text/javascript">
	    Calendar.setup({
	        inputField     :    "date",
	        date           :    new Date( <?=date('Y,m,d', $date)?>),
	        daFormat       :    "%Y-%m-%d",
	        ifFormat       :    "%Y-%m-%d",
	        displayArea    :    "show_date", 
	        timeFormat     :    "12",
	        singleClick    :    true,
	        onClose        :    onChangeDate
	        
	        });
	</script>
    </td>
  </tr>
  <tr >
    <td align="rigth">
      Con Programación
    </td>
    <td align="center">
      <table><tr><td width="60" align="center" <?=$_GET['prog']=="yes" ? $STYLE['yes'] : $STYLE['no'] ?> onclick="newLocation(<?=$_GET['client']?>,'<?=$_GET['prog']=="yes" ? "no" : "yes"?>', '<?=$_GET['date']?>')" ><?=$_GET['prog']=="yes" ? "SI" : "NO"?></td></tr></table>
    </td>
  </tr>
</table>
<table width="700" class="titulo">	
<?
	foreach( $channel as $reg )
	{
?>
  <tr>
    <td bgcolor="#DDEEFF"><?=$reg['name']?><br><div class="description"><?=$reg['description']?></div></td>
  </tr>
<?		
	}
?>
</table>