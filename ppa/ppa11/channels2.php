<?
$STYLE['yes'] = 'style="font-weight: bold; color: #FFFFFF; background : #00BF00; cursor: pointer;"';
$STYLE['no']  = 'style="font-weight: bold; color: #FFFFFF; background : #D70000; cursor: pointer;"';
$DEBUG	      = false;
$ONEDAY       = 86400;
$HIGHLIGHT_TYPE = (isset($_GET['type'])?"&type=" . $_GET['type']:"");

if( $_GET['date'] != '' )
	$date = strtotime( $_GET['date'] );
else
	$date = time() + ( $ONEDAY * 2 );

$id_client = isset($_GET['client']) ? $_GET['client'] : 0;

$client     = array();
$id_channel = array(0);
	
//if( !isset( $_GET['client'] ) || !ereg( "[0-9]*", $_GET['client'] ) )
if( !isset( $_GET['client'] ) || !is_numeric( $_GET['client'] ) )
{
	$_GET['client'] = 0;
}

if( !isset( $_GET['prog'] ) || !ereg( "yes|no", $_GET['prog'] ) )
{
	$_GET['prog'] = "yes";
}

if( !isset( $_GET['audio'] ) || !ereg( "yes|no", $_GET['audio'] ) )
{
	$_GET['audio'] = "no";
}

/**************************************
* Get Clients
**************************************/

$sql = "" .
	"SELECT id, name " . 
	"FROM client ORDER BY name "
	 ;

$result = db_query( $sql, $DEBUG );

while( $row = db_fetch_array( $result ) )
{
	$client[] = $row;
}

/**************************************
* Get channels without programing for a client
**************************************/
if( $_GET['client'] > 0 && strtolower( $_GET['prog'] ) == "no" )
{
	$sql = "" .
		"SELECT DISTINCT(channel) FROM slot WHERE date = '" . date("Y-m-d", $date ) . "'";
		
	$sql = "" .
		"SELECT channel.id, channel.name, channel.description  " . 
		"FROM channel, client_channel " .
		"WHERE " .
		" client_channel.channel = channel.id " .
		" AND client_channel.client = ". $_GET['client'] ." " .
		" AND channel.id NOT IN( " . $sql . " ) " .
		( $_GET['audio'] == "no" ? " AND client_channel._group <> 'audio' " : "" ) .
		"ORDER by channel.name ";
	
	$result = db_query( $sql, $DEBUG);
	
	$channel    = array();
	while( $row = db_fetch_array( $result ) )
	{
		$id_channel[] = $row['id'];
		$channel[] = $row;
	}
}
/**************************************
* Get channels with programing for a client
**************************************/
else if( $_GET['client'] > 0 && strtolower( $_GET['prog'] ) == "yes" )
{
	$sql = "" .
		"SELECT DISTINCT(channel) FROM slot WHERE date = '" . date("Y-m-d", $date ) . "'";

	$sql = "" .
		"SELECT channel.id, channel.name, channel.description  " . 
		"FROM channel, client_channel " .
		"WHERE " .
		" client_channel.channel = channel.id " .
		" AND client_channel.client = ". $_GET['client'] ." " .
		" AND channel.id IN( " . $sql . " ) " .
		"ORDER by channel.name ";
	
	$result = db_query( $sql, $DEBUG);
	
	$channel    = array();
	while( $row = db_fetch_array( $result ) )
	{
		$id_channel[] = $row['id'];
		$channel[] = $row;
	}
}
/**************************************
* Get valid channels without programing
**************************************/
else if( $_GET['client'] == -1 && strtolower( $_GET['prog'] ) == "no" )
{
	$sql = "" .
		"SELECT DISTINCT(channel) " . 
		"FROM slot " . 
		"WHERE " . 
		"  slot.date = '" . date("Y-m-d", $date ) . "' ";

	$sql = "SELECT DISTINCT(channel) FROM client_channel WHERE channel NOT IN (" . $sql . ")";

	$sql = "" .
		"SELECT channel.id, channel.name, channel.description, '00:00:00' time " . 
		"FROM channel " . 
		"WHERE " . 
		"  channel.id IN (" . $sql . ") " .
		"ORDER by channel.name ASC";
	
	$result = db_query( $sql, $DEBUG);
	
	$channel    = array();
	while( $row = db_fetch_array( $result ) )
	{
		$id_channel[] = $row['id'];
		$channel[] = $row;
	}
}
/**************************************
* Get all channels without programing
**************************************/
else if( $_GET['client'] == 0 && strtolower( $_GET['prog'] ) == "no" )
{
	$sql = "" .
		"SELECT channel.id " . 
		"FROM slot " . 
		"INNER JOIN channel " . 
		"ON slot.channel = channel.id " .
		"WHERE " . 
		"  slot.date = '" . date("Y-m-d", $date ) . "' " .
		"GROUP BY channel.id " .
		"ORDER by channel.name ASC";

	$sql = "" .
		"SELECT channel.id, channel.name, channel.description, '00:00:00' time " . 
		"FROM channel " . 
		"WHERE " . 
		"  channel.id NOT IN (" . $sql . ") " .
		"ORDER by channel.name ASC";
	
	$result = db_query( $sql, $DEBUG );
	
	$channel    = array();
	while( $row = db_fetch_array( $result ) )
	{
		$id_channel[] = $row['id'];
		$channel[] = $row;
	}
}
/**************************************
* Get all channels with programing
**************************************/
else
{
	$sql = "" .
		"SELECT channel.id, channel.name, channel.description, MAX(slot.time) time " . 
		"FROM slot " . 
		"INNER JOIN channel " . 
		"ON slot.channel = channel.id " .
		"WHERE " . 
		"  slot.date = '" . date("Y-m-d", $date ) . "' " .
		"GROUP BY channel.id " .
		"ORDER by channel.name ASC";
	
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
		newLocation('<?=$_GET['client']?>', '<?=$_GET['prog']?>', '<?=$_GET['audio']?>', cal.date.print("%Y-%m-%d") );
	}
	function newLocation(client, prog, audio, date)
	{
		if( date )
			document.location = "?location=<?=$_GET['location'].$HIGHLIGHT_TYPE?>&client=" + client + "&prog=" + prog + "&audio=" + audio + "&date=" + date;
		else
			document.location = "?location=<?=$_GET['location'].$HIGHLIGHT_TYPE?>&client=" + client + "&prog=" + prog + "&audio=" + audio;
	}
</script>
<table width="200" class="titulo">
  <tr>
  	<td align="center" colspan="2" style="font-size: 18;font-family: Arial, Helvetica, sans-serif;;"><?
  switch( $_GET['location'] )
	{
		case "channels2":
			echo "Programación";
		break;
		case "highlight":
			if($_GET['type'] == "all") $type_name = "generales";
			else if($_GET['type'] == "fut") $type_name = "futbol";
			echo "Destacados " . $type_name;
		break;
		case "sinopsis":
			echo "Sinopsis";
		break;		
		case "tvcfl": //Lista grillas fijas revista TvCable
			echo "Grillas Fijas Tv Cable";
		break;		
		case "svchl": //Lista grillas Superview
			echo "Grillas Excel Superview";
		break;		
		case "ccchl": //Lista grillas cablecentro
			echo "Grillas Cablecentro";
		break;		
		case "unechl": //Lista grillas Superview
			echo "Canales UNE";
		break;		
	}
?>  </td>
  </tr>
  <tr >
    <td align="rigth">
      Cabecera
    </td>
    <td align="left">
      <select name="client" onChange="newLocation(this.value, '<?=$_GET['prog']?>', '<?=$_GET['audio']?>')">
         <option value="0">-- Todos --</option>
         <option value="-1" <?= $_GET['client'] == "-1" ? "selected" : "" ?>>Canales válidos</option>
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
	        date           :    new Date( <?=date('Y,n,d', $date)?>),
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
      Con Programación?
    </td>
    <td align="center">
      <table><tr><td width="60" align="center" <?=$_GET['prog']=="yes" ? $STYLE['yes'] : $STYLE['no'] ?> onclick="newLocation(<?=$_GET['client']?>,'<?=$_GET['prog']=="yes" ? "no" : "yes"?>', '<?=$_GET['audio']?>', '<?=$_GET['date']?>')" ><?=$_GET['prog']=="yes" ? "SI" : "NO"?></td></tr></table>
    </td>
  </tr>
<? if( $_GET['client'] != 0 && $_GET['prog'] == "no" ) { ?>
  <tr >
    <td align="rigth">
      ¿Con canales de audio?
    </td>
    <td align="center">
      <table><tr><td width="60" align="center" <?=$_GET['audio']=="yes" ? $STYLE['yes'] : $STYLE['no'] ?> onclick="newLocation(<?=$_GET['client']?>,'<?=$_GET['prog']?>', '<?=$_GET['audio']=="yes" ? "no" : "yes"?>', '<?=$_GET['date']?>')" ><?=$_GET['audio']=="yes" ? "SI" : "NO"?></td></tr></table>
    </td>
  </tr>
<? } ?>
</table>
<script src="ppa11/js/channels.js" ></script>
<script>
<?
//echo "var _date = \"" . ( isset( $_GET['date'] ) ? substr( $_GET['date'], 0, -3 ) : date( "Y-m" ) ) . "\";\n";
	echo "var _date = \"" . date("Y-m", $date ) . "\";\n";
	echo "var id_client = \"" . $id_client . "\";\n";
	echo "channels = [";
	foreach( $channel as $reg )
	{
		echo "[\"" . ereg_replace( "\n|\r", "", addslashes( $reg['id'] ) ) . "\", \"" . ereg_replace( "\n|\r", "", addslashes( $reg['name'] ) ) . "\", \"" . ereg_replace( "\n|\r", "", addslashes( $reg['description'] ) ) . "\", \"" . ereg_replace( "\n|\r", "", addslashes( $reg['time'] ) ) . "\" ], ";
	}
	echo "];\n";

	switch( $_GET['location'] )
	{
		case "channels2":
			echo "showProgram( channels )";
		break;
		case "sinopsis":
			echo "showSynopsis( channels )";
		break;		
		case "highlight":
			echo "showHgl( channels, '" . $_GET["type"] ."' )";
		break;		
		case "tvcfl":
			echo "showTvcfl( channels )";
		break;		
		case "svchl":
			echo "showSvchl( channels )";
		break;		
		case "unechl":
			echo "showUnechl( channels )";
		break;		
		case "ccchl":
			echo "showCcchl( channels )";
		break;		
	}
?>
</script>
