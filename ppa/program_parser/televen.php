<?

	require_once ("class/HtmlParser.class.php");
	require_once ("../include/util.inc.php");
			
		$search_key = $data[0];
		$command = $data[1];
		$_link = $data[2];
		
		$url_site = "http://www.televen.com/programacion.asp";
		$sitio = new htmlParser ($url_site );
		$content = "";
		
		if (!empty($sitio->arSitio)){
			
			//$content = $search_key . " " . $sitio->getMonthName(date("n")) . " "	. date("d") . " de " . date("Y") . ", ";			
			$content = $search_key . ", Condiciones actuales: ";			
			$copy_site = $sitio->arSitio;										
			$sitio->seleccion( "Hora" , "Leyenda" );
			$sitio->arSitio = $sitio->seleccion;
			$sitio->arStripTags();
			
//			print_r($sitio->seleccion);
			foreach($sitio->seleccion as $reg)
			{
				if( trim( $reg ) == chr(160) ) print( "\n" );
				else print( $reg . "\t" );
			}
		
			exit;

//					$part1 = new formatoContenido( array_slice ( $sitio->seleccion , 1 , 3 ) );					
//					$content .= $part1->noticias("  ");					
			
//					unset( $sitio->seleccion );
								
			$sitio->arSitio = $copy_site;										
			$sitio->seleccion( "day/date here" , "</table>" );					
			$sitio->arSitio = $sitio->seleccion;
			$sitio->arStripTags();				
			if( $sitio->seleccion[3] != "" )	
				$content .= "  Temp Max: " . $sitio->seleccion[3];
			//$content .= "  Temp Min: " . $sitio->seleccion[4];									
			if( count( $sitio->seleccion ) == 0 ){
				$error_message = "Posible error de parseo.";
//						$sitio->notifyError(  $error_message, $command, $url_site );										
			}else{
				echo $content . "<br>";
//						new nuevoDoc(  $content . "." , $command );								
			}					
		} else {
			$error_message = "Error Fatal, No es posible acceder la direccion especificada.";
//					$sitio->notifyError( $error_message, $command, $url_site );	
		}
		unset( $sitio );
	

?>