<?
include( "class/Log.class.php" );
include( "ppa11/class/TextSlot2.class.php" );
define ( "TOLERANCE", 20 );
$banned_channels_for_synopsis = array(
	/*adultos*/762,134,506,428,719,135,508,429,720,263,763,427,718,365,507,860,581,
	/*regionales y nacionales venezuela*/25,85,86,88,99,107,111,118,119,120,157,158,159,165,175,176,183,184,209,211,289,291,292,296,298,301,303,308,315,317,318,320,322,325,366,368,373,389,390,394,397,448,451,452,449,557,584,593,594,595,596,597,604,606,607,608,721,728,742,744,749,750,752,754,764,765,775,776,779,781,782,787,791,794,798,808,812,814,815,816,817,818,821,822,824,851,867,873,917,
	/*otros*/31,312,64,743
	);

function toleranceSearch( $str, $arr, $tol )
{
	$allow = strlen( $str ) * $tol / 100;
	
	foreach( $arr as $key => $reg )
	{
		$error = 0;
		if( strlen( $str ) != strlen( $reg ) ) continue;
		for( $i=0; $i<strlen( $str ); $i++ )
		{
			if( $reg[$i] != $str[$i] ) $error++;
			if( $error > $allow )
				break;
		}
		if( $error <= $allow ) return $key;
	}
	return false;
}


/**
 * first step
 */
if( empty( $_FILES ) ){
	$sql= "SELECT * FROM channel WHERE id = " . $_GET['id'];
	$row = db_fetch_array( db_query( $sql ) );
?>
<table border="0">
  <tr>
    <td align="center"><b><?=$row['name']?></b><br><div class="description"><?=$row['description']?></div></td>
  </tr>
  <tr>
    <td class="textos">
      <form enctype="multipart/form-data" action="" method="POST">
          Archivo: <input name="program_file" type="file" />
          <input type="submit" value="Cargar" /><br><br>
          <input type="checkbox" name="update" value="update" /> Actualizar Títulos<br>
      </form>
    </td>
  </tr>
<table>
<?
}
/**
 * second step, when you choose a file
 */
else
{
	try
	{
		$slot = new TextSlot( $_GET['id'], $_FILES['program_file']['tmp_name'] );
		print_r($slot);exit;
	}
	catch (Exception $e)
	{
		echo "Error", $e->getMessage();
	}

	/**
	 * if have to update program titles;
	 */
	if( $_POST['update'] == "update" )
	{
		$slot->reset();
		while( $slot->next() )
		{
			$sql = "UPDATE slot SET " .
			  "title = '" . addslashes( $slot->getTitle() ) . "' " .
			  "WHERE " .
				"date = '" . date( "Y-m-d", $slot->getTime( ) ) . "' AND " .
				"time = '" . date( "H:i:s", $slot->getTime( ) ) . "' AND " .
				"channel = '" . $slot->getChannel() . "' ";
				
				$result = db_query( $sql );
				if( db_affected_rows() == 1 ) echo date( "Y-m-d H:i:s", $slot->getTime()  ) . " " . $slot->getTitle() . "<br>";
				elseif( db_affected_rows() > 1 ) echo "<span style=\"color:red\">¡Uy, aquí paso algo muy raro!</div>";
		}		
	}
	/**
	 * Search for chapters
	 */
	else if($slot->next())
	{  
		$titles = array();
		$slot->reset();
		while( $slot->next() && !array_search($slot->getChannel(),$banned_channels_for_synopsis) )
		{
			if( !array_search( "'" . addslashes( strtolower( $slot->getTitle() ) ) . "'", $titles ) ) $titles[] = "'" . addslashes( strtolower( $slot->getTitle() ) ) ."'";
		}
	
		$sql = "SELECT id, title, spanishtitle " .
			"FROM chapter " .
			"WHERE " .
			"spanishtitle in ( " . implode(",", $titles ) . " )" .
			"OR title in ( " . implode(",", $titles ) . " ) ";
		$result = db_query($sql);
		
		$titles = array();
		while( $row = db_fetch_array( $result ) )
		{
			$titles['spanish'][$row['id']] = strtolower( $row['spanishtitle'] );
			$titles['english'][$row['id']] = strtolower( $row['title'] );
		}
	
		$days_feed = array();
	
	/* update previous program duration*/
		$slot->reset();
		$sql = "SELECT id, date, time, duration FROM slot WHERE channel = " . $slot->getChannel() . " ORDER BY date DESC, time DESC LIMIT 0, 1";
		$row = db_fetch_array( db_query( $sql ) );
		if( isset($row['duration']) )
		{
			$tmp_time = strtotime( $row['date'] . " " . $row['time'] );
			$slot->next();
			if( $tmp_time < $slot->getTime() )
			{
				$sql = "UPDATE slot SET duration = " . ( ( $slot->getTime() - $tmp_time ) / 60 ) . " " .
				  "WHERE id = " . $row['id'];
				db_query( $sql );
			}
		}
	
	/* insert grid */
		$slot->reset();
		$prg_counter = 0;
		$first_date = date( "Y-m-d:H:i", $slot->getTime( ) );
		while( $slot->next() )
		{
			/*if($slot->getTime( "next" ))
			{
				$nextTime = date( "H:i:s", $slot->getTime( "next" ) ) );
				$nextDate = date( "Y-m-d", $slot->getTime( "next" ) ) );
				$currentTime = date( "H:i:s", $slot->getTime( ) );
				$currentDate = date( "Y-m-d", $slot->getTime( ) );
				
				$sql = "DELETE FROM slot WHERE date = '" . $currentDate . "' AND time >= " . $currentTime 
			}*/

			$sql = "INSERT INTO slot VALUES ( ''," .
				"'" . date( "Y-m-d", $slot->getTime( ) ) . "'," .
				"'" . date( "H:i:s", $slot->getTime( ) ) . "'," .
				"'" . $slot->getDuration() . "', " .
				"'" . $slot->getChannel() . "', " .
				"'" . addslashes( $slot->getTitle() ) . "' " .
				")";
			
			if( db_query( $sql ) && !empty($titles) )
			{
				if( $chapter = toleranceSearch( strtolower( $slot->getTitle() ), $titles['spanish'], TOLERANCE ) )
				{
					if( !db_query ( "INSERT INTO slot_chapter VALUES ( ''," . db_insert_id() . ", " . $chapter . ")") ) echo db_error() ."<br>";
				}
				elseif( $chapter = toleranceSearch( strtolower( $slot->getTitle() ), $titles['english'], TOLERANCE ) )
				{
					if( !db_query ( "INSERT INTO slot_chapter VALUES ( ''," . db_insert_id() . ", " . $chapter . ")") ) echo db_error() ."<br>";
				}
				$prg_counter++;
			}
			else
			{
				if( db_error() != "" ) echo db_error() ."<br>";		
				else $prg_counter++;
			}
			
			if( date( "Y-m-d", $slot->getTime( ) ) != $last_date )
			{
				$days_feed[date( "Y-m-d", $slot->getTime( ) )] = "algo" ;
			}
		}
		foreach( $days_feed as $key => $reg )
		{
			echo $key ."<br>";
		}
		$days = count( $days_feed );
		echo $prg_counter . " programas cargados en " . $days . " días";
		Log::write("/home/ppa/backup/listingUpdates.log", 
			$_SESSION['user'] . " i " . $slot->getChannel() . " " . $first_date . " " . $days . " " . $prg_counter . " ");
			
		echo '<br/><a href="ppa.php?show_program=1&paso=1&id=' . $slot->getChannel() . '">Ver programación</a>';
	}
}
?>
