<?

require_once ("class/HtmlParser.class.php");
require_once ("../include/util.inc.php");
		


$dia[1]="Monday";
$dia[2]="Tuesday";
$dia[3]="Wednesday";
$dia[4]="Thursday";
$dia[5]="Friday";
$dia[6]="Saturday";
$dia[7]="Sunday";



for( $j=1; $j<=2; $j+=1){ 
	print ( "dia "      . $j . "\n" ); 
	$url_site = "http://outdoorchannel.com/_showschedule.cfm?site=1&DisplayPage=" . $dia[$j];
	$sitio = new htmlParser($url_site);

	if (!empty($sitio->arSitio))
	{
		$sitio->seleccion( "Click on a" );
		$sitio->arStripTags();
		$sitio->arSitio = $sitio->seleccion;
//		print_r( $sitio->arSitio ); exit;
		foreach( $sitio->arSitio as $ln )
		{
			$ln = ereg_replace( chr(160), "", $ln );
			if( ereg("[0-9]{2}:[0-9]{2}:[0-9]{2}", $ln ) )
			{
				echo substr( $ln, 0, 8 ) . "\t";
				echo ucwords( strtolower ( substr( $ln, 8 ) ) ) . "\n";
			}
			else if( ereg("[0-9]{2}:[0-9]{2}", $ln ) )
			{
				echo substr( $ln, 0, 5 ) . "\t";
				echo ucwords( strtolower ( substr( $ln, 5 ) ) ) . "\n";
			}
		}
	
	} 
	else 
	{
		echo "Error Fatal, No es posible acceder la direccion especificada.";
		$error_message = "Error Fatal, No es posible acceder la direccion especificada.";
	}
	unset( $sitio );

}
?>