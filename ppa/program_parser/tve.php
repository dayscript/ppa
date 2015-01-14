<?

require_once ("class/HtmlParser.class.php");
require_once ("../include/util.inc.php");
		
for( $j=1; $j<=31; $j++){ print ( "dia "      . $j . "\n" ); $url_site = 
"http://www.rtve.es/tve/programo/avan4/tv4s" . ( ($j<10) ? "0" . $j : $j ) . "09.html";
$sitio = new htmlParser($url_site);

	if (!empty($sitio->arSitio))
	{
		$sitio->seleccion( "SEPTIEMBRE" );
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