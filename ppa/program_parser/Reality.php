<?

require_once ("class/HtmlParser.class.php");
require_once ("../include/util.inc.php");
		
for( $j=11; $j<=11; $j+=1)
{ 
	print ( "dia " . $j . "\n" ); 
	$url_site = "http://www.realitytv.co.uk/tvguide.php?date=200610" . ( $j <10 ? "0" : "" ) . $j;
	echo $url_site . "\n";
	$sitio = new htmlParser($url_site);

	if (!empty($sitio->arSitio))
	{
		
		$sitio->seleccion( "06:00" );
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