<?

require_once ("class/HtmlParser.class.php");
require_once ("../include/util.inc.php");
		
for( $j=1; $j<=10; $j+=1){ 
	print ( "dia "      . $j . "\n" ); 
	$url_site = "http://w3.infinito.com/scripts/gl/programacion/index.php3?grilla=A&id_pais=CO&idioma=ESP&param=1&hora=$j&dia=6&mes=10/2005";
	$sitio = new htmlParser($url_site);

	if (!empty($sitio->arSitio))
	{
		
		$sitio->seleccion( ": : : I N F I N I T O : : :"  );
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