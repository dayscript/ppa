<?

require_once ("class/HtmlParser.class.php");
require_once ("../include/util.inc.php");
		


$dia[1]="lunes";
$dia[2]="martes";
$dia[3]="miercoles";
$dia[4]="jueves";
$dia[5]="viernes";
$dia[6]="sabado";
$dia[7]="domingo";



for( $j=1; $j<=8; $j+=1){ 
	print ( "dia "      . $j . "\n" ); 
	$url_site = "http://www.vive.gob.ve/prog_". $dia[$j] . ".php";
	$sitio = new htmlParser($url_site);

	if (!empty($sitio->arSitio))
	{
		
		$sitio->seleccion( "Programación" );
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