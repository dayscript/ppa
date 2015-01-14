<?

require_once ("class/HtmlParser.class.php");
require_once ("../include/util.inc.php");
		
for( $j=16; $j<=30; $j+=1){ print ( "dia "      . $j . "\n" ); $url_site = 
"http://www.arirang.co.kr/Tv/Tv_Schedule_Today.asp?F_DATE=2006-10-" . ( $j <10 ? "0" : "" ) . $j .  "&today_nation=Mexico&C_Week=FRI&today_city=Mexico%20City";
$sitio = new 
htmlParser($url_site);

	if (!empty($sitio->arSitio))
	{
		
		$sitio->seleccion( "Regional Time" );
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