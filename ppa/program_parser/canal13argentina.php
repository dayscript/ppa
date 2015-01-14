<?

require_once ("class/HtmlParser.class.php");
require_once ("../include/util.inc.php");
		
$url_site = "http://www.canaltrece.ciudad.com.ar/grilla.asp"; 
$sitio = new htmlParser($url_site);
$iIndice=0;

if (!empty($sitio->arSitio))
{
	
	$sitio->seleccion( "class=\"txtbl\"" );
	
	$output = array();
	for( $i=0; $i<count( $sitio->seleccion); $i++ )
	{
		if( ereg( "rowspan=\"(.*)\"", $sitio->seleccion[$i] , $reg ) )
			$output[] = ereg_replace( "rowspan=\"(.*)\"", "rowspan=\"" . ( $reg[1] / 6 ) . "\"", $sitio->seleccion[$i] );		
		else
			$output[] = $sitio->seleccion[$i];
		if( eregi("<tr", $sitio->seleccion[$i] ) )
		{
			$i++;
			while( !eregi("</td", $sitio->seleccion[$i] ) && isset( $sitio->seleccion[$i] ) )
				$i++; 
		}
	}
	
	print_r( eregi_replace( "<tr></tr>", "", implode( "", $output ) ) );
} 
else 
{
	$error_message = "Error Fatal, No es posible acceder la direccion especificada.";
}
unset( $sitio );
?>