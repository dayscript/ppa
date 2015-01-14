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
	 * 25    Bogotá
	 * 42    Bucaramanga
	 * 40    Cali
	 * 43    Cartagena
	 * 59    Cúcuta
	 * 16    Duitama
	 * 65    Girardot
	 * 37    Ibagué
	 * 41    Medellín
	 * 183    Montería
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
				168    Apartadó
				175    Armenia
				149    Baranoa
				179    Barrancabermeja
				155    Barrancas
				30    Barranquilla
				29    Barranquilla
				103    Bogotá
				10    Bogotá
				18    Bogotá
				21    Bucaramanga
				22    Bucaramanga
				182    Buga
				95    Cali
				97    Cali
				134    Campo Alegre
				23    Cartagena
				24    Cartagena
				158    Cerete
				178    Chinácota
				176    Chinchiná
				110    Chiquinquira
				160    Chiriguana
				151    Cienaga
				159    Cienaga De Oro
				156    Codazzi
				28    Cúcuta
				27    Cúcuta
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
				32    Ibagué
				31    Ibagué
				63    Ibagué
				171    Ituango
				114    Leticia
				105    Libano
				162    Maicao
				127    Mariquita
				14    Medellín
				13    Medellín
				113    Melgar
				120    Mocoa
				143    Montería
				19    Neiva
				20    Neiva
				141    Paipa
				177    Pamplona
				180    Pasto
				12    Pereira
				15    Pereira
				117    Pitalito
				153    Plato
				181    Popayán
				86    Popayán
				140    Puerto Asis
				169    Puerto Berrio
				133    Puerto Boyaca
				119    Puerto Carreño
				131    Puerto Lopez
				174    Pupiales
				145    Riohacha
				152    Sabana Larga
				157    Sahagun
				164    San Juan
				135    San Martin
				166    San Onofre
				173    Sandoná
				34    Santa Marta
				33    Santa Marta
				129    Santa Rosa
				170    Segovia
				136    Sibundoy
				147    Since
				144    Sincelejo
				90    Sincelejo
				111    Sogamoso
				167    Tarazá
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
	 * 	60    Barranquilla Básica
	 * 	25    Bogotá Básica
	 * 	42    Bucaramanga básica
	 * 40    Cali Básica
	 * 43    Cartagena Básica
	 * 59    Cúcuta básica
	 * 16    Duitama básica
	 * 65    Girardot Básica
	 * 37    Ibagué Básica
	 * 41    Medellín Básica
	 * 183    Montería Básica
	 * 38    Neiva Básica
	 * 77    Santa Marta Básica
	 * 98    Sogamoso Básica
	 * 101    Tunja Básica
	 * 85    Valledupar Básica
	 * 39    Villavicencio Básica
	 */
	
	$texto = "132    Acacias Análoga
				161    Aguachica Análoga
				124    Anapoima Análoga
				168    Apartadó Análoga
				175    Armenia Análoga
				149    Baranoa Análoga
				179    Barrancabermeja Análoga
				155    Barrancas Análoga
				30    Barranquilla Análoga
				29    Barranquilla Digital Avanzada
				103    Bogotá Análoga
				10    Bogotá Digital Avanzada
				18    Bogotá Digital Básica
				21    Bucaramanga Análoga
				22    Bucaramanga Digital Avanzada
				182   Buga Análoga
				95    Cali Análoga
				97    Cali Digital Avanzada
				134    Campo Alegre Análoga
				23    Cartagena Análoga
				24    Cartagena Digital Avanzada
				158    Cerete Análoga
				178    Chinácota Análoga
				176    Chinchiná Análoga
				110    Chiquinquira Análoga
				160    Chiriguana Análoga
				151    Cienaga Análoga
				159    Cienaga De Oro Análoga
				156    Codazzi Análoga
				28    Cúcuta Análoga
				27    Cúcuta Digital Avanzada
				128    Duitama Análoga
				165    El Banco Análoga
				123    Espinal Análoga
				109    Florencia Análoga
				154    Fonseca Análoga
				118    Fresno Análoga
				148    Fundacion Análoga
				112    Fusagasuga Análoga
				150    Galapa Análoga
				137    Garzon Análoga
				115    Girardot Análoga
				139    Granada Análoga
				126    Honda Análoga
				32    Ibagué Análoga
				31    Ibagué Digital Avanzada
				63    Ibagué Digital Básica
				171    Ituango Análoga
				114    Leticia Análoga
				105    Libano Análoga
				162    Maicao Análoga
				127    Mariquita Análoga
				14    Medellín Análoga
				13    Medellín Digital Avanzada
				113    Melgar Análoga
				120    Mocoa Análoga
				143    Montería Análoga
				19    Neiva Análoga
				20    Neiva Digital Avanzada
				141    Paipa Análoga
				177    Pamplona Análoga
				180    Pasto Análoga
				12    Pereira Análoga
				15    Pereira Digital Avanzada
				117    Pitalito Análoga
				153    Plato Análoga
				181    Popayán Análoga
				86    Popayán Digital Avanzada
				140    Puerto Asis Análoga
				169    Puerto Berrio Análoga
				133    Puerto Boyaca Análoga
				119    Puerto Carreño Análoga
				131    Puerto Lopez Análoga
				174    Pupiales Análoga
				145    Riohacha Análoga
				152    Sabana Larga Análoga
				157    Sahagun Análoga
				164    San Juan Análoga
				135    San Martin Análoga
				166    San Onofre Análoga
				173    Sandoná Análoga
				34    Santa Marta Análoga
				33    Santa Marta Digital Avanzada
				129    Santa Rosa Análoga
				170    Segovia Análoga
				136    Sibundoy Análoga
				147    Since Análoga
				144    Sincelejo Análoga
				90    Sincelejo Análoga
				111    Sogamoso Análoga
				167    Tarazá Análoga
				130    Tauramena Análoga
				125    Tocaima Análoga
				146    Tolu Análoga
				102    Tunja Análoga
				104    Ubate Análoga
				142    Valledupar Análoga
				138    Villa De Leyva Análoga
				121    Villa Garzon Análoga
				163    Villanueva Análoga
				36    Villavicencio Análoga
				35    Villavicencio Digital Avanzada
				122    Villeta Análoga
				116    Yopal Análoga";
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
	$output .= '<p>Para iniciar la búsqueda, selecione una ciudad:</p>';

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
		$output .= '<option value="60">Barranquilla Básico</option>';
		$output .= '<option value="30">Barranquilla Análogo</option>';
		$output .= '<option value="29">Barranquilla Avanzada</option>';
		$output .= '<option value="103">Bogotá TV Básica</option>';
		$output .= '<option value="25">Bogotá Digital Básica</option>';
		$output .= '<option value="10">Bogotá Digital Avanzada</option>';
		$output .= '<option value="14">Bucaramanga Análogo</option>';
		$output .= '<option value="21">Bucaramanga Básico</option>';
		$output .= '<option value="22">Bucaramanga Avanzada</option>';
		$output .= '<option value="40">Cali Análogo</option>';
		$output .= '<option value="95">Cali Básico</option>';
		$output .= '<option value="97">Cali Avanzada</option>';
		$output .= '<option value="43">Cartagena Análogo</option>';
		$output .= '<option value="23">Cartagena Básico</option>';
		$output .= '<option value="24">Cartagena Avanzada</option>';
		$output .= '<option value="59">Cúcuta Análogo</option>';
		$output .= '<option value="28">Cúcuta Básico</option>';
		$output .= '<option value="27">Cúcuta Avanzada</option>';
		$output .= '<option value="115">Girardot Básico</option>';
		$output .= '<option value="37">Ibagué Análogo</option>';
		$output .= '<option value="32">Ibagué Básico</option>';
		$output .= '<option value="31">Ibagué Avanzada</option>';
		$output .= '<option value="14">Medellín Análogo</option>';
		$output .= '<option value="41">Medellín Básica</option>';
		$output .= '<option value="13">Medellín Avanzada</option>';
		$output .= '<option value="38">Neiva Análogo</option>';
		$output .= '<option value="19">Neiva Básico</option>';
		$output .= '<option value="20">Neiva Avanzada</option>';
		$output .= '<option value="12">Pereira Análogo</option>';
		$output .= '<option value="15">Pereira Avanzada</option>';
		$output .= '<option value="77">Santa Marta Análogo</option>';
		$output .= '<option value="34">Santa Marta Básico</option>';
		$output .= '<option value="33">Santa Marta Avanzada</option>';
		$output .= '<option value="101">Tunja Análogo</option>';
		$output .= '<option value="102">Tunja Básico</option>';
		$output .= '<option value="39">Villavicencio Análogo</option>';
		$output .= '<option value="36">Villavicencio Básico</option>';
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
	$output .= '<option value="0">Categoría</option>';
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