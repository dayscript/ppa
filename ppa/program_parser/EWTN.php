<?

require_once ("class/HtmlParser.class.php");
require_once ("../include/util.inc.php");
		
for( $j=1; $j<=10; $j+=1){ 
	print ( "dia "      . $j . "\n" ); 
	$url_site = "http://www.ewtn.com/tv/PAS9grid2.asp";
	$sitio = new htmlParser($url_site);

	if (!empty($sitio->arSitio))
	{
		
		$sitio->seleccion( "AVANCE DE PROGRAMACIÓN ESPECIAL"  );
		$sitio->arStripTags();
		$sitio->arSitio = $sitio->seleccion;
			print_r($sitio->arSitio);
exit;
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