<?
$output = "";
$dom = new DOMDocument('1.0', 'ISO-8859-1');
$dRoot = $dom->createElement("filters");
$dom->appendChild($dRoot);

/******************
 * TELMEX FILTERS SECTION
 *******************/
if( $PROVIDER == TELMEX ) {

	$grillas = array( );
	$result = mysql_query( "SELECT * FROM client WHERE name LIKE 'TELMEX%' AND city IS NOT NULL ORDER BY name" );
	while( ( $row = mysql_fetch_assoc( $result ) ) != null )    {
		if( !isset( $grillas[$row['city']] ) )
			$grillas[$row['city']] = array( );

		$name = strtolower( trim( preg_replace( '/TELMEX[\\W]*/i' , '', $row['name'] ) ) );
		$grillas[$row['city']][] = array ( "n" => ucwords ( $name ), "id" => $row['id'] );
	}

	/* Main */
	$output .= '<div id="ppa_filters_container">';
	$output .= '<h1>Buscar en Televisi&oacute;n</h1>';
	$output .= '<p>Para iniciar la búsqueda, selecione una ciudad:</p>';

	/* Headers */
	$sql    = "SELECT distinct( _group ) FROM client_channel WHERE client = 65 ORDER BY 1";
	$result = db_query( $sql );

	$output .= '<div class="selects">';

	if (isset ($_REQUEST["nG"])) {
		$output .= '<script>';
		$output .= 'var gP = new Object();';
		foreach ( $grillas as $k => $v )    {
			$output .= 'gP["' . $k . '"] = new Array();';

			for ($j = 0; $j < count ($v); $j++) {
				$output .= 'gP["' . $k . '"][' . $j . '] = new Object();';
				$output .= 'gP["' . $k . '"][' . $j . ']["id"] = "' . $v[$j]["id"] . '";';
				$output .= 'gP["' . $k . '"][' . $j . ']["n"] = "' . $v[$j]["n"] . '";';
			}
		}
		$output .= 'function doSelC(cName) {';
		$output .= 'oCntrl = document.getElementById("header_filter"); ';
		$output .= 'while (oCntrl.length) oCntrl.remove(0); ';
		$output .= 'oCntrl.options[0] = new Option("Plataforma", 0);  ';
		$output .= 'if (!gP[cName]) return; ';
		$output .= 'for (j = 0; j < gP[cName].length; j++) {';
		$output .= 'oCntrl.options[j + 1] =new Option(gP[cName][j]["n"], gP[cName][j]["id"]);  ';
		$output .= '}';
		$output .= '}';
		$output .= '</script>';

		$output .= '<select id="c_filter" name="c_header" onchange="doSelC(this.value)">';
		$output .= '<option value="0"> - Seleccione la Ciudad - </option>';
		foreach ($grillas as $k => $v) {
			$output .= '<option value="' . $k . '">' . ucwords( $k ) . '</option>';
		}
		$output .= '</select>';

		$output .= '<select id="header_filter" name="header" >';
		$output .= '<option value="0">Plataforma</option>';
		$output .= '</select>';
	} else {
		$output .= '<select id="header_filter2" onchange="ga.header =  + this.value; ga.consultGrid(\'category\', \'Todas\' );document.getElementById(\'filterCond1\').style.display = this.selectedIndex != 0? \'\' : \'none\'; document.getElementById(\'filterCond2\').style.display = this.selectedIndex != 0? \'\' : \'none\';" name="header" >';
		$output .= '<option value="0"> - Seleccione Ciudad - </option>';
		foreach ( $grillas as $city => $list )  {
			foreach ( $list as $service )
				$output .= '<option value="' . $service['id'] . '">' . htmlentities( $service['n'] ) . '</option>';
		}
		$output .= '</select>';
	}

	$output .= '<br/><br/>';

	/* Category */
	$sql = "SELECT _group
			FROM client_channel, client
			WHERE client_channel.client = client.id
			AND client.name LIKE 'TELMEX%'
			GROUP BY client_channel._group
			ORDER BY client_channel._group";

	$result = db_query( $sql );
	$output .= '<div id="filterCond1" style="display:none;"><select id="category_filter">';
	$output .= '<option value="Todas"> - Todas las Categor&iacute;as - </option>';
	while( $row = db_fetch_array( $result ) )
	{
		if($row['_group'] != "" )
			$output .= '<option value="' . $row['_group'] . '">' . htmlentities( ucfirst( $row['_group'] ) ) . '</option>';
	}
	$output .= '</select>';

	/* Channel */
	$sql = "SELECT channel.id, channel.name
			FROM channel, client_channel, client
			WHERE channel.id =  client_channel.channel
			AND client_channel.client = client.id
			AND client.name LIKE 'TELMEX%'
			GROUP BY channel.id
			ORDER BY channel.name";
	$result = db_query( $sql );
	$output .= '<select id="channel_filter">';
	$output .= '<option value="0"> - Todos los Canales - </option>';
	while( $row = db_fetch_array( $result ) )
	{
		$output .= '<option value="' . $row['id'] . '">' . htmlentities( ucfirst( $row['name'] ) ). '</option>';
	}
	$output .= '</select>';
	$output .= '</div></div>';

	/* Actor */
	$output .= '<div class="inputs" id="filterCond2" style="display:none;">';
	$output .= '<p><div class="title">Actor</div><div class="title">Título</div></p>';
	$output .= '<input class="filter_input" type="text" id="search_actor" name="search_actor" /><img id="go_search_actor" src="images/go.gif" />';

	/* Title */
	$output .= '<input class="filter_input" type="text" id="search_title" name="search_title" /><img id="go_search_title" src="images/go.gif" />';
	$output .= '</div>';
	$output .= '</div>';
}

/******************
 * UNE FILTERS SECTION
 *******************/
elseif( $PROVIDER == UNE )
{
	$categoriesEl = $dom->createElement("categories");
	$dRoot->appendChild($categoriesEl);
	/* Category */
	$sql    = "SELECT distinct( _group ) cat FROM client_channel WHERE client = " . $ID_CLIENT . " ORDER BY 1";
	$result = db_query( $sql );
	while( $row = db_fetch_array( $result ) )
	{
		$el = $dom->createElement("cat");
		$el->setAttribute("id", $row["cat"]);
		$el->appendChild($dom->createTextNode(ucfirst($row["cat"])));
		$categoriesEl->appendChild($el);
	}

	$channelsEl = $dom->createElement("channels");
	$dRoot->appendChild($channelsEl);
	/* Channel */
	$sql = "SELECT channel.* " .
			"FROM channel, client_channel " .
			"WHERE channel.id =  client_channel.channel " .
			"AND client_channel.client = " . $ID_CLIENT . " " .
			(isset($_GET["category"])?("AND client_channel._group='" . $_GET["category"] . "' "):"") .
			"ORDER BY channel.name";
	$result = db_query( $sql );
	while( $row = db_fetch_array( $result ) )
	{
		$el = $dom->createElement("chn");
		$el->setAttribute("id", $row["id"]);
		$el->appendChild($dom->createTextNode(utf8_encode(ucfirst($row["name"]))));
		$channelsEl->appendChild($el);
	}
//	header("Content-type:text/xml");
	header("Content-Type: text/xml; charset=ISO-8859-1");
	$output = $dom->saveXML();
}
/******************
 * INTER FILTERS SECTION
 *******************/
elseif( $PROVIDER == INTER )
{
	$categoriesEl = $dom->createElement("categories");
	$dRoot->appendChild($categoriesEl);
	/* Category */
	$sql    = "SELECT distinct( _group ) cat FROM client_channel WHERE client = " . $ID_CLIENT . " ORDER BY 1";
	$result = db_query( $sql );
	while( $row = db_fetch_array( $result ) )
	{
		$el = $dom->createElement("cat");
		$el->setAttribute("id", $row["cat"]);
		$el->appendChild($dom->createTextNode(ucfirst($row["cat"])));
		$categoriesEl->appendChild($el);
	}

	$channelsEl = $dom->createElement("channels");
	$dRoot->appendChild($channelsEl);
	/* Channel */
	$sql = "SELECT channel.* " .
			"FROM channel, client_channel " .
			"WHERE channel.id =  client_channel.channel " .
			"AND client_channel.client = " . $ID_CLIENT . " " .
			(isset($_GET["category"])?("AND client_channel._group='" . $_GET["category"] . "' "):"") .
			"ORDER BY channel.name";
	$result = db_query( $sql );
	while( $row = db_fetch_array( $result ) )
	{
		$el = $dom->createElement("chn");
		$el->setAttribute("id", $row["id"]);
		$el->appendChild($dom->createTextNode(utf8_encode(ucfirst($row["name"]))));
		$channelsEl->appendChild($el);
	}
//	header("Content-type:text/xml");
	header("Content-Type: text/xml; charset=ISO-8859-1");
	$output = $dom->saveXML();
}

elseif( $PROVIDER == ZONAWOW )
{
	$categoriesEl = $dom->createElement("categories");
	$dRoot->appendChild($categoriesEl);
	/* Category */
	$sql    = "SELECT distinct( _group ) cat FROM client_channel WHERE client = " . $ID_CLIENT . " ORDER BY 1";
	$result = db_query( $sql );
	while( $row = db_fetch_array( $result ) )
	{
		$el = $dom->createElement("cat");
		$el->setAttribute("id", $row["cat"]);
		$el->appendChild($dom->createTextNode(ucfirst($row["cat"])));
		$categoriesEl->appendChild($el);
	}

	$channelsEl = $dom->createElement("channels");
	$dRoot->appendChild($channelsEl);
	/* Channel */
	$sql = "SELECT channel.* " .
			"FROM channel, client_channel " .
			"WHERE channel.id =  client_channel.channel " .
			"AND client_channel.client = " . $ID_CLIENT . " " .
			(isset($_GET["category"])?("AND client_channel._group='" . $_GET["category"] . "' "):"") .
			"ORDER BY channel.name";
	$result = db_query( $sql );
	while( $row = db_fetch_array( $result ) )
	{
		$el = $dom->createElement("chn");
		$el->setAttribute("id", $row["id"]);
		$el->appendChild($dom->createTextNode(utf8_encode(ucfirst($row["name"]))));
		$channelsEl->appendChild($el);
	}
//	header("Content-type:text/xml");
	header("Content-Type: text/xml; charset=ISO-8859-1");
	$output = $dom->saveXML();
}

echo $output;
?>