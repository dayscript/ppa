<?
include( "util/client_channel.php" );

/**
 * Menú Principal/Programación
 */
if( $_GET['l'] == $slang['tvlist'] )
{
	$menu = array(
		array( "Canal", $slang['channel_dir'] ), 
		array( "Categoría", $slang['category'] ),
		array( "Programa", $slang['program_form'] ),	
		array( "Recomendados", $slang['suggest'] ) );	
}

/**
 * Menú Principal/Programación/Canal
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
 * Menú Principal/Programación/Canal/0-9
 * Menú Principal/Programación/Canal/A-C
 * Menú Principal/Programación/Canal/D-F
 * Menú Principal/Programación/Canal/G-L
 * Menú Principal/Programación/Canal/M-P
 * Menú Principal/Programación/Canal/Q-Z
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
 * Menú Principal/Programación/Canal/0-9/$canal
 * Menú Principal/Programación/Canal/A-C/$canal
 * Menú Principal/Programación/Canal/D-F/$canal
 * Menú Principal/Programación/Canal/G-L/$canal
 * Menú Principal/Programación/Canal/M-P/$canal
 * Menú Principal/Programación/Canal/Q-Z/$canal
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
 * Menú Principal/Programación/Canal/0-9/$canal/$hora
 * Menú Principal/Programación/Canal/A-C/$canal/$hora
 * Menú Principal/Programación/Canal/D-F/$canal/$hora
 * Menú Principal/Programación/Canal/G-L/$canal/$hora
 * Menú Principal/Programación/Canal/M-P/$canal/$hora
 * Menú Principal/Programación/Canal/Q-Z/$canal/$hora
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
 * Menú Principal/Programación/Categoría
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
 * Menú Principal/Programación/Categoría/$category
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
 * Menú Principal/Programación/Programa
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
		echo ("<p>Sin resultados para la búsqueda</p>");
		$menu[0] = array("Regresar", $slang['program_form'] );
	}
	if( db_numrows( $result ) > 10 )
	{
		echo ("<p>Demasiados resultados!, por favor seleccione mejor la búsqueda.</p>");
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
 * Menú Principal/Programación/Recomendados
 */
else if( $_GET['l'] == $slang['suggest'] )
{
	echo ( "<p>Esperelo Pronto ...</b>" );
}

/**
 * Menú Principal/Descargas
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
 * Menú Principal/Nosotros
 */
else if( $_GET['l'] == $slang['iam'] )
{
	$menu = array(
		array( "Promociones", "prm" ), 
		array( "Servicio al Cliente", "srv" ),
		array( "Contactenos", "cus" ) );	
}

/**
 * Menú Principal
 */
else
{
	$menu = array(
		array( "Programación", $slang['tvlist'] ), 
		array( "Descargas", $slang['download'] )
//		array( "Intercable", $slang['iam'] ) 
		);
}

htmlMenu( $menu, "a" );
?>