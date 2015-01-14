<?
$output = "";
$dom = new DOMDocument('1.0', 'ISO-8859-1');
$dRoot = $dom->createElement("filters");
$dom->appendChild($dRoot);

/******************
* TELMEX FILTERS SECTION
*******************/
if( $PROVIDER == TELMEX ) {

	/*
	 * 60    Barranquilla
	 * 25    Bogot�
	 * 42    Bucaramanga
	 * 40    Cali
	 * 43    Cartagena
	 * 59    C�cuta
	 * 16    Duitama
	 * 65    Girardot
	 * 37    Ibagu�
	 * 41    Medell�n
	 * 183    Monter�a
	 * 38    Neiva
	 * 77    Santa Marta
	 * 98    Sogamoso
	 * 101    Tunja
	 * 85    Valledupar
	 * 39    Villavicencio
	 */
	
	$texto = "132    Acacias
				161    Aguachica
				124    Anapoima
				168    Apartad�
				175    Armenia
				149    Baranoa
				179    Barrancabermeja
				155    Barrancas
				30    Barranquilla
				29    Barranquilla
				103    Bogot�
				10    Bogot�
				18    Bogot�
				21    Bucaramanga
				22    Bucaramanga
				182    Buga
				95    Cali
				97    Cali
				134    Campo Alegre
				23    Cartagena
				24    Cartagena
				158    Cerete
				178    Chin�cota
				176    Chinchin�
				110    Chiquinquira
				160    Chiriguana
				151    Cienaga
				159    Cienaga De Oro
				156    Codazzi
				28    C�cuta
				27    C�cuta
				128    Duitama
				165    El Banco
				123    Espinal
				109    Florencia
				154    Fonseca
				118    Fresno
				148    Fundacion
				112    Fusagasuga
				150    Galapa
				137    Garzon
				115    Girardot
				139    Granada
				126    Honda
				32    Ibagu�
				31    Ibagu�
				63    Ibagu�
				171    Ituango
				114    Leticia
				105    Libano
				162    Maicao
				127    Mariquita
				14    Medell�n
				13    Medell�n
				113    Melgar
				120    Mocoa
				143    Monter�a
				19    Neiva
				20    Neiva
				141    Paipa
				177    Pamplona
				180    Pasto
				12    Pereira
				15    Pereira
				117    Pitalito
				153    Plato
				181    Popay�n
				86    Popay�n
				140    Puerto Asis
				169    Puerto Berrio
				133    Puerto Boyaca
				119    Puerto Carre�o
				131    Puerto Lopez
				174    Pupiales
				145    Riohacha
				152    Sabana Larga
				157    Sahagun
				164    San Juan
				135    San Martin
				166    San Onofre
				173    Sandon�
				34    Santa Marta
				33    Santa Marta
				129    Santa Rosa
				170    Segovia
				136    Sibundoy
				147    Since
				144    Sincelejo
				90    Sincelejo
				111    Sogamoso
				167    Taraz�
				130    Tauramena
				125    Tocaima
				146    Tolu
				102    Tunja
				104    Ubate
				142    Valledupar
				138    Villa De Leyva
				121    Villa Garzon
				163    Villanueva
				36    Villavicencio
				35    Villavicencio
				122    Villeta
				116    Yopal";
	$ciudadesT = explode	("\n", $texto);
	
	/*
	 * 	60    Barranquilla B�sica
	 * 	25    Bogot� B�sica
	 * 	42    Bucaramanga b�sica
	 * 40    Cali B�sica
	 * 43    Cartagena B�sica
	 * 59    C�cuta b�sica
	 * 16    Duitama b�sica
	 * 65    Girardot B�sica
	 * 37    Ibagu� B�sica
	 * 41    Medell�n B�sica
	 * 183    Monter�a B�sica
	 * 38    Neiva B�sica
	 * 77    Santa Marta B�sica
	 * 98    Sogamoso B�sica
	 * 101    Tunja B�sica
	 * 85    Valledupar B�sica
	 * 39    Villavicencio B�sica
	 */
	
	$texto = "132    Acacias An�loga
				161    Aguachica An�loga
				124    Anapoima An�loga
				168    Apartad� An�loga
				175    Armenia An�loga
				149    Baranoa An�loga
				179    Barrancabermeja An�loga
				155    Barrancas An�loga
				30    Barranquilla An�loga
				29    Barranquilla Digital Avanzada
				103    Bogot� An�loga
				10    Bogot� Digital Avanzada
				18    Bogot� Digital B�sica
				21    Bucaramanga An�loga
				22    Bucaramanga Digital Avanzada
				182   Buga An�loga
				95    Cali An�loga
				97    Cali Digital Avanzada
				134    Campo Alegre An�loga
				23    Cartagena An�loga
				24    Cartagena Digital Avanzada
				158    Cerete An�loga
				178    Chin�cota An�loga
				176    Chinchin� An�loga
				110    Chiquinquira An�loga
				160    Chiriguana An�loga
				151    Cienaga An�loga
				159    Cienaga De Oro An�loga
				156    Codazzi An�loga
				28    C�cuta An�loga
				27    C�cuta Digital Avanzada
				128    Duitama An�loga
				165    El Banco An�loga
				123    Espinal An�loga
				109    Florencia An�loga
				154    Fonseca An�loga
				118    Fresno An�loga
				148    Fundacion An�loga
				112    Fusagasuga An�loga
				150    Galapa An�loga
				137    Garzon An�loga
				115    Girardot An�loga
				139    Granada An�loga
				126    Honda An�loga
				32    Ibagu� An�loga
				31    Ibagu� Digital Avanzada
				63    Ibagu� Digital B�sica
				171    Ituango An�loga
				114    Leticia An�loga
				105    Libano An�loga
				162    Maicao An�loga
				127    Mariquita An�loga
				14    Medell�n An�loga
				13    Medell�n Digital Avanzada
				113    Melgar An�loga
				120    Mocoa An�loga
				143    Monter�a An�loga
				19    Neiva An�loga
				20    Neiva Digital Avanzada
				141    Paipa An�loga
				177    Pamplona An�loga
				180    Pasto An�loga
				12    Pereira An�loga
				15    Pereira Digital Avanzada
				117    Pitalito An�loga
				153    Plato An�loga
				181    Popay�n An�loga
				86    Popay�n Digital Avanzada
				140    Puerto Asis An�loga
				169    Puerto Berrio An�loga
				133    Puerto Boyaca An�loga
				119    Puerto Carre�o An�loga
				131    Puerto Lopez An�loga
				174    Pupiales An�loga
				145    Riohacha An�loga
				152    Sabana Larga An�loga
				157    Sahagun An�loga
				164    San Juan An�loga
				135    San Martin An�loga
				166    San Onofre An�loga
				173    Sandon� An�loga
				34    Santa Marta An�loga
				33    Santa Marta Digital Avanzada
				129    Santa Rosa An�loga
				170    Segovia An�loga
				136    Sibundoy An�loga
				147    Since An�loga
				144    Sincelejo An�loga
				90    Sincelejo An�loga
				111    Sogamoso An�loga
				167    Taraz� An�loga
				130    Tauramena An�loga
				125    Tocaima An�loga
				146    Tolu An�loga
				102    Tunja An�loga
				104    Ubate An�loga
				142    Valledupar An�loga
				138    Villa De Leyva An�loga
				121    Villa Garzon An�loga
				163    Villanueva An�loga
				36    Villavicencio An�loga
				35    Villavicencio Digital Avanzada
				122    Villeta An�loga
				116    Yopal An�loga";
	$grillasT = explode	("\n", $texto);
	$grillas = array ();
	for ($i = 0; $i < count ($grillasT); $i++) {
		$ciudad = trim ($grillasT[$i]);
		$ciudad = str_replace ("  ", " ", $ciudad);
		$idC	= substr ($ciudad, 0, strpos ($ciudad, " "));
		$idC	= trim ($idC);
		$ciudad	= str_replace ($idC, "", $ciudad);
		$ciudad = trim ($ciudad);
		
		/*
		 *
		 */
		$ciudad1	= trim ($ciudadesT[$i]);
		$ciudad1 	= str_replace ("  ", " ", $ciudad1);
		$idC1		= substr ($ciudad1, 0, strpos ($ciudad1, " "));
		$idC1		= trim ($idC1);
		$ciudad1	= str_replace ($idC1, "", $ciudad1);
		$ciudad1 	= trim ($ciudad1);
		
		$ciudad	= str_replace ($ciudad1, "", $ciudad);
		$ciudad	= trim ($ciudad);
		/*
		 *
		 */
		
		if (!isset ($grillas[$ciudad1])) {
			$grillas[$ciudad1] = array ();
		}
		
		$grillas[$ciudad1][] = array ("n" => ucwords ($ciudad), "id" => $idC1);
	}


	/* Main */
	$output .= '<div id="ppa_filters_container">';
	$output .= '<h1>Buscar en Televisi&oacute;n</h1>';
	$output .= '<p>Para iniciar la b�squeda, selecione una ciudad:</p>';

	/* Headers */
	$sql    = "SELECT distinct( _group ) FROM client_channel WHERE client = 65 ORDER BY 1";
	$result = db_query( $sql );
	
	$output .= '<div class="selects">';
	
	if (isset ($_REQUEST["nG"])) {
		$output .= '<script>';
		$output .= 'var gP = new Object();';
		foreach ($grillas as $k => $v) {
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
		$output .= '<option value="0"> - Seleccione la Ciudad - </option>';
		$output .= '<option value="60">Barranquilla B�sico</option>';
		$output .= '<option value="30">Barranquilla An�logo</option>';
		$output .= '<option value="29">Barranquilla Avanzada</option>';
		$output .= '<option value="103">Bogot� TV B�sica</option>';
		$output .= '<option value="25">Bogot� Digital B�sica</option>';
		$output .= '<option value="10">Bogot� Digital Avanzada</option>';
		$output .= '<option value="14">Bucaramanga An�logo</option>';
		$output .= '<option value="21">Bucaramanga B�sico</option>';
		$output .= '<option value="22">Bucaramanga Avanzada</option>';
		$output .= '<option value="40">Cali An�logo</option>';
		$output .= '<option value="95">Cali B�sico</option>';
		$output .= '<option value="97">Cali Avanzada</option>';
		$output .= '<option value="43">Cartagena An�logo</option>';
		$output .= '<option value="23">Cartagena B�sico</option>';
		$output .= '<option value="24">Cartagena Avanzada</option>';
		$output .= '<option value="59">C�cuta An�logo</option>';
		$output .= '<option value="28">C�cuta B�sico</option>';
		$output .= '<option value="27">C�cuta Avanzada</option>';
		$output .= '<option value="115">Girardot B�sico</option>';
		$output .= '<option value="37">Ibagu� An�logo</option>';
		$output .= '<option value="32">Ibagu� B�sico</option>';
		$output .= '<option value="31">Ibagu� Avanzada</option>';
		$output .= '<option value="14">Medell�n An�logo</option>';
		$output .= '<option value="41">Medell�n B�sica</option>';
		$output .= '<option value="13">Medell�n Avanzada</option>';
		$output .= '<option value="38">Neiva An�logo</option>';
		$output .= '<option value="19">Neiva B�sico</option>';
		$output .= '<option value="20">Neiva Avanzada</option>';
		$output .= '<option value="12">Pereira An�logo</option>';
		$output .= '<option value="15">Pereira Avanzada</option>';
		$output .= '<option value="77">Santa Marta An�logo</option>';
		$output .= '<option value="34">Santa Marta B�sico</option>';
		$output .= '<option value="33">Santa Marta Avanzada</option>';
		$output .= '<option value="101">Tunja An�logo</option>';
		$output .= '<option value="102">Tunja B�sico</option>';
		$output .= '<option value="39">Villavicencio An�logo</option>';
		$output .= '<option value="36">Villavicencio B�sico</option>';
		$output .= '<option value="35">Villavicencio Avanzada</option>';
		$output .= '</select>';
	}
	
	$output .= '<br/><br/>';

	/* Category */
//	$sql    = "SELECT distinct( _group ) FROM client_channel WHERE client = 10 ORDER BY 1";
	$sql    = "SELECT distinct( _group )
				FROM client_channel
				WHERE client IN (select id from client where operator = 2)
				AND _group != '0'
				ORDER BY 1";
	$result = db_query( $sql );
	$output .= '<div id="filterCond1" style="display:none;"><select id="category_filter">';
	$output .= '<option value="0">Categor�a</option>';
	$output .= '<option value="Todas"> - Todas - </option>';
	while( $row = db_fetch_array( $result ) )
	{
		if($row['_group'] != "" )
			$output .= '<option value="' . $row['_group'] . '">' . htmlentities( ucfirst( $row['_group'] ) ) . '</option>';
	}
	$output .= '</select>';

	/* Channel */
	$sql = "SELECT channel.* " .
		"FROM channel, client_channel " .
		"WHERE channel.id =  client_channel.channel AND " .
		"client_channel.client = 65 ORDER BY channel.name";
	$result = db_query( $sql );
	$output .= '<select id="channel_filter">';
	$output .= '<option value="0">Canal</option>';
	while( $row = db_fetch_array( $result ) )
	{
		$output .= '<option value="' . $row['id'] . '">' . htmlentities( ucfirst( $row['name'] ) ). '</option>';
	}
	$output .= '</select>';
	$output .= '</div></div>';

	/* Actor */
	$output .= '<div class="inputs" id="filterCond2" style="display:none;">';
	$output .= '<p><div class="title">Actor</div><div class="title">T�tulo</div></p>';
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