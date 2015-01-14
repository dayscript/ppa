<?
include( "class/Log.class.php" );
include( "ppa11/class/TextSlot.class.php" );
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
		for( $i=0; $i < strlen( $str ); $i++ )
		{
			if( $reg[$i] != $str[$i] ) $error++;
			if( $error > $allow )
				break;
		}
		if( $error <= $allow ){return $key;}
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
	$slot = new TextSlot( $_GET['id'], $_FILES['program_file']['tmp_name'] );

	/**
	 * if have to update program titles;
	 */
	if( $_POST['update'] == "update" )
	{
		$slot->reset();
		$new_episode_reg = "/\(ne\)$|\(en\)$/i";
		$title_reg = "/\([^)]*\)/i";
//		$new_episode_reg = "/\(en\)$/i";

		while( $slot->next() )
		{
			$new_episode=0;
			$title=trim( $slot->getTitle() );

			if( preg_match( $new_episode_reg , $title) )
			{
				$title = trim( preg_replace($title_reg, "", $title) );
				$new_episode=1;
			}
			
			$title = preg_replace($title_reg, "", $title);

			if($title == "")
				continue;

			$sql = "UPDATE slot SET " .
			  "title = '" . addslashes( $title ) . "', " .
			  "new_episode = '" . $new_episode . "' " .
			  "WHERE " .
				"date = '" . date( "Y-m-d", $slot->getTime( ) ) . "' AND " .
				"time = '" . date( "H:i:s", $slot->getTime( ) ) . "' AND " .
				"channel = '" . $slot->getChannel() . "' ";
				
				$result = db_query( $sql );
				if( db_affected_rows() == 1 ) echo date( "Y-m-d H:i:s", $slot->getTime()  ) . " " . $slot->getTitle() . "<br>";
				else if( db_affected_rows() > 1 ) echo "<span style=\"color:red\">¡Uy, aquí paso algo muy raro!</div>";
		}
		unset($title);
	}
	/**
	 * Search for chapters
	 */
	else if($slot->next())
	{  
		$titles = array();
		$slot->reset();
		$title_reg = "/\(.*\)/i";
		while( $slot->next() && !array_search($slot->getChannel(),$banned_channels_for_synopsis) )
		{
//			$tmp_title = addslashes( strtolower( $title_reg, "", $slot->getTitle() ) );
			$tmp_title = trim( addslashes( strtolower( preg_replace( $title_reg, "", trim( $slot->getTitle() ) ) ) ) );
			if( !array_search( "'" . $tmp_title . "'", $titles ) ) $titles[] = "'" . $tmp_title ."'";
		}
		unset($tmp_title);
	
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

//		$new_episode_reg = "/\(en\)$/i";
		$new_episode_reg = "/\(ne\)$|\(en\)$/i";
		$title_reg = "/\([^)]*\)/i";
//		$title_reg = "/\(.*\)/";
		
		while( $slot->next() )
		{
			$new_episode=0;
			$title=$slot->getTitle();

			if( preg_match( $new_episode_reg , $title) )
			{
				$title = preg_replace($new_episode_reg, "", $title);
				$new_episode=1;
			}

			$title = preg_replace($title_reg, "", $title);

			if($title == "")
				continue;

			$sql = "INSERT INTO slot (id, date, time, duration, channel, title, new_episode) VALUES ( ''," .
				"'" . date( "Y-m-d", $slot->getTime( ) ) . "'," .
				"'" . date( "H:i:s", $slot->getTime( ) ) . "'," .
				"'" . $slot->getDuration() . "', " .
				"'" . $slot->getChannel() . "', " .
				"'" . addslashes( $title ) . "', " .
				"'" . $new_episode . "' " .
				")";
			
			if( db_query( $sql ) && !empty($titles) )
			{
				$tmp_title = trim(strtolower( $title ) );
				if( $chapter = toleranceSearch( $tmp_title, $titles['spanish'], TOLERANCE ) )
				{
					if( !db_query ( "INSERT INTO slot_chapter VALUES ( ''," . db_insert_id() . ", " . $chapter . ")") ) echo db_error() ."<br>";
				}
				else if( $chapter = toleranceSearch( $tmp_title, $titles['english'], TOLERANCE ) )
				{
					if( !db_query ( "INSERT INTO slot_chapter VALUES ( ''," . db_insert_id() . ", " . $chapter . ")") ) echo db_error() ."<br>";
				}
				$prg_counter++;
				unset($tmp_title);
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
