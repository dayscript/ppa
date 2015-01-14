<?
include( "util/client_channel.php" );

/**
 * Men� Principal/Programaci�n
 */
if( $_GET['l'] == $slang['tvlist'] )
{
	$menu = array(
		array( "Canal", $slang['channel_dir'] ), 
		array( "Categor�a", $slang['category'] ),
		array( "Programa", $slang['program_form'] ),	
		array( "Recomendados", $slang['suggest'] ) );	
}

/**
 * Men� Principal/Programaci�n/Canal
 */
else if( $_GET['l'] == $slang['channel_dir'] )
{
	$menu = array(
		array( "0-9", $slang['0-9']  ), 
		array( "A-C", $slang['A-C']  ),
		array( "D-F", $slang['D-F']  ),
		array( "G-L", $slang['G-L']  ),	
		array( "M-P", $slang['M-P']  ),	
		array( "Q-Z", $slang['Q-Z']  ) );	
}

/**
 * Men� Principal/Programaci�n/Canal/0-9
 * Men� Principal/Programaci�n/Canal/A-C
 * Men� Principal/Programaci�n/Canal/D-F
 * Men� Principal/Programaci�n/Canal/G-L
 * Men� Principal/Programaci�n/Canal/M-P
 * Men� Principal/Programaci�n/Canal/Q-Z
 */                                  
else if( $_GET['l'] == $slang['0-9'] ||
	$_GET['l'] == $slang['A-C'] ||
	$_GET['l'] == $slang['D-F'] ||
	$_GET['l'] == $slang['G-L'] ||
	$_GET['l'] == $slang['M-P'] ||
	$_GET['l'] == $slang['Q-Z']
		)
{
	$range = $_GET['l'];
	
	if(ID_CLIENT==0)
	{
	$sql = "SELECT channel.id, channel.name, channel.description " . 
		"FROM channel " .
		"WHERE" .
		" channel.name BETWEEN '" . $range[0] . "' AND '" . $range['1'] . "zzzz'" .
		" ORDER BY name";
	}
	else
	{
	$sql = "SELECT channel.id, channel.name " . 
		"FROM channel, client_channel, client " .
		"WHERE client_channel.client = client.id AND " .
		"client_channel.channel = channel.id AND " .
		"client.id = " . ID_CLIENT . " AND " .
		"channel.name BETWEEN '" . $range[0] . "' AND '" . $range['1'] . "zzzz' AND " .
		"client_channel._group <> 'PPV' " .
		"ORDER BY name";
	}
	$result = db_query( $sql );
	while( $row = db_fetch_array( $result ) )
	{
		$menu[] = array( $row['name'], $slang['channel'] . "&i=" . $row['id'] );
	}
}
	
/**
 * Men� Principal/Programaci�n/Canal/0-9/$canal
 * Men� Principal/Programaci�n/Canal/A-C/$canal
 * Men� Principal/Programaci�n/Canal/D-F/$canal
 * Men� Principal/Programaci�n/Canal/G-L/$canal
 * Men� Principal/Programaci�n/Canal/M-P/$canal
 * Men� Principal/Programaci�n/Canal/Q-Z/$canal
 */                                  
else if( $_GET['l'] == $slang['channel'] )
{
	session_unregister( "id_channel" );
	$_SESSION['id_channel'] = $_GET['i'];
	$menu = array(
		array( "12 am - 06 am ", $slang['00-06'] ), 
		array( "06 am - 12 m ", $slang['06-12'] ), 
		array( "12 m - 04 pm", $slang['12-16'] ),
		array( "04 pm - 07 pm", $slang['16-19'] ),
		array( "07 am - 11 pm", $slang['19-23'] ),	
		array( "11 pm - 06 am", $slang['23-06'] )
	);
}
	
/**
 * Men� Principal/Programaci�n/Canal/0-9/$canal/$hora
 * Men� Principal/Programaci�n/Canal/A-C/$canal/$hora
 * Men� Principal/Programaci�n/Canal/D-F/$canal/$hora
 * Men� Principal/Programaci�n/Canal/G-L/$canal/$hora
 * Men� Principal/Programaci�n/Canal/M-P/$canal/$hora
 * Men� Principal/Programaci�n/Canal/Q-Z/$canal/$hora
 */                                  
else if( $_GET['l'] == $slang['00-06'] ||
	$_GET['l'] == $slang['06-12'] ||
	$_GET['l'] == $slang['12-16'] ||
	$_GET['l'] == $slang['16-19'] ||
	$_GET['l'] == $slang['19-23'] ||
	$_GET['l'] == $slang['23-06'] )
{
	switch($_GET['l'])
	{
		case $slang['00-06']: $range = array(0, 6); break;
		case $slang['06-12']: $range = array(6, 12); break;
		case $slang['12-16']: $range = array(12, 16); break;
		case $slang['16-19']: $range = array(16, 19); break;
		case $slang['19-23']: $range = array(19, 23); break;
		case $slang['23-06']: $range = array(23, 6); break;
	}
	
	if( $range[0] > $range[1] )
	{
		$date_inf = date("Y-m-d", NOW );
		$date_sup = date("Y-m-d", NOW + 60*60*24 );
		$logic    = "OR";
	}
	else
	{
		$date_inf = $date_sup = date("Y-m-d", NOW );
		$logic    = "AND";
	}
	$menu = array();
	$sql = "SELECT title, date, time, id " .
		"FROM slot " .
		"WHERE " .
		"( ( date = '" . $date_inf . "' AND time >= '" . sprintf( "%02d:00:00", $range[0] - GMT ) . "') " . $logic . " " .
		"( date = '" . $date_sup . "' AND time <= '" . sprintf( "%02d:00:00", $range[1] - GMT ) . "') ) AND " . 
		"channel = " . $_SESSION['id_channel'] . " " .
		"ORDER BY date, time";
	$result = db_query( $sql );
	$i=0;
	while( $row = db_fetch_array( $result ) )
	{
		$time = date("h:i a", strtotime( $row['date'] . " " . $row['time'] ) + GMT*60*60 );
		if( $time == "12:00 pm" ) $time = str_replace( "pm" , "m", $time );
		
		$menu[]      = array( $time . " " . $row['title'] , $slang['synopsis'] . "&t=" . $i );
		$title[$i] = $row['title'];
		$i++;
	}
	session_unregister( "title" );
	$_SESSION['title'] = $title;
}
	
/**
 * Men� Principal/Programaci�n/Categor�a
 */
else if( $_GET['l'] == $slang['category'] )
{
	$menu = array();
	$sql = "SELECT _group " .
		"FROM client_channel " .
		"WHERE client = " . ID_CLIENT . " " .
		"GROUP BY _group " .
		"ORDER BY _group";
	$result = db_query( $sql );
	$i=0;
	while( $row = db_fetch_array( $result ) )
	{
		$menu[]       = array( $row['_group'], $slang['chn_cat'] . "&c=" . $i );
		$category[$i] = $row['_group'];
		$i++;
	}
	session_unregister( "category" );
	$_SESSION['category'] = $category;
}

/**
 * Men� Principal/Programaci�n/Categor�a/$category
 */
else if( $_GET['l'] == $slang['chn_cat'] )
{
	$menu = array();
	$sql = "SELECT channel.id, channel.name " .
		"FROM client_channel, channel " .
		"WHERE client_channel.channel = channel.id AND " .
		"client_channel.client = " . ID_CLIENT . " AND " .
		"_group = '" . $_SESSION['category'][$_GET['c']] . "' " .
		"ORDER BY name";
	$result = db_query( $sql );
	while( $row = db_fetch_array( $result ) )
	{
		$menu[] = array( $row['name'], $slang['channel'] . "&i=" . $row['id'] );
	}
}

/**
 * Men� Principal/Programaci�n/Programa
 */
else if( $_GET['l'] == $slang['program'] )
{
	$menu = array();
	$sql = "SELECT * " .
		"FROM slot " .
		"WHERE " .
		"( ( date = '" . date("Y-m-d", NOW) . "' AND time >= '" . date("H:i:00", NOW) . "') OR " .
		"( date > '" . date("Y-m-d", NOW) . "' ) ) AND " . 
		" channel IN (" . implode(",", client_channel() ) . ") AND " .
		" title like '%" . $_GET['t'] . "%' " .
		"GROUP BY title " .
		"ORDER BY title ";

	$result = db_query( $sql );
	session_unregister( "title" );
	if( db_numrows( $result ) == 0 )
	{
		echo ("<p>Sin resultados para la b�squeda</p>");
		$menu[0] = array("Regresar", $slang['program_form'] );
	}
	if( db_numrows( $result ) > 10 )
	{
		echo ("<p>Demasiados resultados!, por favor seleccione mejor la b�squeda.</p>");
		$menu[0] = array("Regresar", $slang['program_form'] );
	}
	else
	{
		$i=0;
		while( $row = db_fetch_array( $result ) )
		{
			$menu[] = array( $row['title'] , $slang['synopsis'] . "&t=" . $i );
			$title[$i] = $row['title'];
			$i++;
		}
		$_SESSION['title'] = $title;
	}
}

/**
 * Men� Principal/Programaci�n/Recomendados
 */
else if( $_GET['l'] == $slang['suggest'] )
{
	echo ( "<p>Esperelo Pronto ...</b>" );
}

/**
 * Men� Principal/Descargas
 */
else if( $_GET['l'] == $slang['download'] )
{
	$menu = array(
		array( "Videos", "vid" ), 
		array( "Fotos", "img" ),
		array( "Ringtones", "rt" ),	
		array( "Juegos", "gam" ) );	
}

/**
 * Men� Principal/Nosotros
 */
else if( $_GET['l'] == $slang['iam'] )
{
	$menu = array(
		array( "Promociones", "prm" ), 
		array( "Servicio al Cliente", "srv" ),
		array( "Contactenos", "cus" ) );	
}

/**
 * Men� Principal
 */
else
{
	$menu = array(
		array( "Programaci�n", $slang['tvlist'] ), 
		array( "Descargas", $slang['download'] )
//		array( "Intercable", $slang['iam'] ) 
		);
}

htmlMenu( $menu, "a" );
?>