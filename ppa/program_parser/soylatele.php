<?

require_once ("class/HtmlParser.class.php");
require_once ("../include/util.inc.php");
		


$dia[1]="Mon";
$dia[2]="Tue";
$dia[3]="Wed";
$dia[4]="Thu";
$dia[5]="Fri";
$dia[6]="Sat";
$dia[7]="Sun";



for( $j=1; $j<=7; $j+=1){ 
	print ( "dia "      . $j . "\n" ); 
	$url_site = "http://www.soylatele.com/programation.php?dayy=" . $dia[$j];
	$sitio = new htmlParser($url_site);

	if (!empty($sitio->arSitio))
	{
		
		$sitio->seleccion( "Todos los Días" );
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