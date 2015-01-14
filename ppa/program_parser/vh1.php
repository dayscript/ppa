<?

require_once ("class/HtmlParser.class.php");
require_once ("../include/util.inc.php");
		


//$dia[1]="Monday";
//$dia[2]="Tuesday";
//$dia[3]="Wednesday";
//$dia[4]="Thursday";
//$dia[5]="Friday";
//$dia[6]="Saturday";
//$dia[7]="Sunday";



for( $j=1; $j<=16; $j+=1){ 
	print ( "dia "      . $j . "\n" ); 
	$url_site = "http://www.vh1.com/shows/schedule/vh1/daily.jhtml?channels=none&day=04%2F" . ( $j<10 ? "0" : "" ) .  $j . "%2F06&shows=none";
	$sitio = new htmlParser($url_site);

	if (!empty($sitio->arSitio))
	{
		
		$sitio->seleccion( "All Times Eastern" );
		$sitio->arStripTags();
		$sitio->arSitio = $sitio->seleccion;
//			print_r($sitio->arSitio);

		for( $i=1; $i<=count( $sitio->arSitio ); $i++)
		{
			if( ereg(":", $sitio->arSitio[$i] ) )
				print($sitio->arSitio[$i] . "\t" . $sitio->arSitio[++$i] ."\n");
				flush();
		}
	} 
	else 
	{
		$error_message = "Error Fatal, No es posible acceder la direccion especificada.";
	}
	unset( $sitio );


}
?>