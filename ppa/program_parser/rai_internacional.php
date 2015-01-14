<?

require_once ("class/HtmlParser.class.php");
require_once ("../include/util.inc.php");
		
for( $j=1; $j<=20; $j+=1){ 
	print ( "dia "      . $j . "\n" ); 
	$url_site = "http://www.international.rai.it/tv/palinsesti/tvsud/200601$j.shtml";
	$sitio = new htmlParser($url_site);

	if (!empty($sitio->arSitio))
	{
		
		$sitio->seleccion( "ora italiana"  );
		$sitio->arStripTags();
		$sitio->arSitio = $sitio->seleccion;
//			print_r($sitio->arSitio);
//exit;
//		for( $i=4; $i<=count( $sitio->arSitio ); $i+=6)
		{
	//		if( ereg(":", $sitio->arSitio[$i] ) )
			print($sitio->arSitio[$i] . "\t" . $sitio->arSitio[$i+1] ."\t". $sitio->arSitio[$i+2] ."\t". $sitio->arSitio[$i+3]."\t". $sitio->arSitio[$i+4]."\t". $sitio->arSitio[$i+5] ."\n");
		}
	} 
	else 
	{
		$error_message = "Error Fatal, No es posible acceder la direccion especificada.";
	}
	unset( $sitio );


}
?>