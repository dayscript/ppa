<?

require_once ("class/HtmlParser.class.php");
require_once ("../include/util.inc.php");
		
for( $j=1; $j<=2; $j+=1){ 
	print ( "dia "      . $j . "\n" ); 
	$url_site = "http://www.telecaribe.com.co/ListaProgramacion.asp?En10aaacb2032C1aa=,0*+/*-++1&Es30bbbad3220M1bb=N%3C=%3C?J"; 
	$sitio = new htmlParser($url_site);

	if (!empty($sitio->arSitio))
	{
		
		$sitio->seleccion( "Programación" );
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