<?

require_once ("class/HtmlParser.class.php");
require_once ("../include/util.inc.php");
		
for( $j=15; $j<=16; $j+=1){ 
	print ( "dia "      . $j . "\n" ); 
	$url_site = "http://www.tv5.org/TV5Site/programmes/grille.php?dateClick=17&date_jour=2006-08-" . $j . "17+15%3A55%3A26&periode=7&genre=0&libelle_signal=TV5+Am%E9rique+latine+et+Cara%EFbes&libelle_zone=Colombie"; 
	$sitio = new htmlParser($url_site);

	if (!empty($sitio->arSitio))
	{
		
		$sitio->seleccion( "matinée" );
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