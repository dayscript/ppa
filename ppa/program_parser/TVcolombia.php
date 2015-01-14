<?

require_once ("class/HtmlParser.class.php");
require_once ("../include/util.inc.php");
		
for( $j=0; $j<=7; $j++){ print ( "dia "      . $j . "\n" ); $url_site = 
"http://www.tvcolombia.com/programas.php?dia=" . $j;
$sitio = new 
htmlParser($url_site);

	if (!empty($sitio->arSitio))
	{
		
		$sitio->seleccion( "Seleccione" );
		$sitio->arStripTags();
		$sitio->arSitio = $sitio->seleccion;
//			print_r($sitio->arSitio);

		for( $i=1; $i<=count( $sitio->arSitio ); $i++)
		{
			if( ereg(":", $sitio->arSitio[$i] ) )
				print($sitio->arSitio[$i] . "\t" . $sitio->arSitio[++$i] ."\n");
		}
	} 
	else 
	{
		$error_message = "Error Fatal, No es posible acceder la direccion especificada.";
	}
	unset( $sitio );


}
?>