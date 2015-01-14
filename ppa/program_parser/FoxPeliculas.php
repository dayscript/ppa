<?

require_once ("class/HtmlParser.class.php");
require_once ("../include/util.inc.php");
		
for( $j=1; $j<=60; $j+=7){ print ( "dia "      . $j . "\n" ); $url_site = 
"http://www.foxmoviechannel.com/schedweek.asp?Date=09/" . $j . "/2006"; $sitio = 
new htmlParser($url_site);

	if (!empty($sitio->arSitio))
	{
		
		$sitio->seleccion( "<table border=\"0\" cellpadding=\"0\" cellspacing=\"2\" width=\"1750\">" , "Letterbox" );
		$sitio->arStripTags();
		$sitio->arSitio = $sitio->seleccion;
//			print_r($sitio->arSitio);

		for( $i=7; $i<=count( $sitio->arSitio ); $i+=3)
		{
			print($sitio->arSitio[$i] . " " . $sitio->arSitio[$i+1] ."\n");
		}
	} 
	else 
	{
		$error_message = "Error Fatal, No es posible acceder la direccion especificada.";
	}
	unset( $sitio );

}
?>