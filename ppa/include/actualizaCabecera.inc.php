<?
require_once("/home/intercable/public_html/class/Ftp.class.php");

function ftp_disconnect($connection){
	if (function_exists("ftp_close")) {
		ftp_close($connection);
	} else {
		ftp_exec($connection,"QUIT");
	}
}

function array_diff_keys($a, $b)
{
	$out = array();
	foreach($a as $key_a => $_a)
	{
		if(!isset($b[$key_a])) 
			$out[$key_a] = $_a;
	}
	return $out;
}

function array_mix($a,$b)
{
	$diff_b_a = array_diff($b,$a);
	$out = array();
	for($i=0;$i<count($b);$i++)
	{
		if(array_search($a[$i], $b) === false)
		{
			$a[$i] = current($diff_b_a);
			next($diff_b_a);
		}
	}
	array_splice($a, count($b));
	return $a;
}

function array_strip_nl($array)
{
	$new = array();
	$i = 0;
	foreach($array as $ln)
	{
		$new[$i] = str_replace(chr(13), "" ,$ln);
		$new[$i] = str_replace(chr(10), "" ,$ln);
		$new[$i] = trim($new[$i]);
		$i++;
	}
	return $new;
}

function arraySearch($aguja, $array)
{
	foreach($array as $elem)
	{
		if(strstr($aguja, $elem))
			return true;
	}
	return false;
}

function actualizaCabecera($id)
{
	$year = date( "Y" );
	$month = date( "m" );
	$day = date( "d" );
	$date = date( "Y-m-d" );
	$numpages = 40;
	$uploaded_videos = array();
	$uploaded_images = array();
	$path = "tvguianew";
	$log = new Log("log_sinc.log");


	$ppa = new PPA(1);
	$client_obj = $ppa->getClientById($id);

	$log->write("Inicia: Actualización de cabecera: ". $client_obj->getName());
	$ip = $client_obj->getIp();	
	$videos_srv = file( $path."/output_videos/".$client_obj->getId()."/".$year."/".$month."/".$day."/videos.txt" );
	$videos_srv = array_strip_nl($videos_srv);
	$ftp = new FTP($ip, "dayscript", "krxo4578");
	if( $ftp->open() )
	{
		echo $client_obj->getName()."<br>";

		@unlink("/tmp/videos.txt");
		$videos_cli = array();
		if( $ftp->get("InstalledContent/videos.txt", "/tmp/videos.txt") )
			$videos_cli = file( "/tmp/videos.txt" );
		$videos_cli = array_strip_nl($videos_cli);

		//agrega videos faltantes
		$videos_srv = array_mix($videos_cli, $videos_srv);
		$videos_dif = array_diff($videos_srv, $videos_cli);
		foreach($videos_dif as $pos => $video)
		{
			$log->write("Subiendo Video: ". $videos . "=>". ($pos+1));
			if( $ftp->put( $path."/video_bank/".trim( $video ).".mpg", "InstalledContent/videos/".($pos+1)."_temp.mpg" ) )
			{
				$ftp->delete( "InstalledContent/videos/".($pos+1).".mpg" );
				$log->write("Subida imagen: ". $videos ."=>". ($pos+1));
				if( !$ftp->rename( "InstalledContent/videos/".($pos+1)."_temp.mpg", "InstalledContent/vdeos/". ($pos+1) .".mpg" ) )
				{
					$log->write("Error renombrando Video: ". $videos ."=>". ($pos+1));
				}
			}
			else
			{
				$log->write("Error Subiendo Video: ". $videos ."=>". ($pos+1));
			}			
		}

		//elimina videos sobrantes
		$videos_dif_keys = array_diff_keys($videos_cli, $videos_srv);
		foreach($videos_dif_keys as $pos => $video)
		{
			$log->write("Eliminando video: ". $video ." => ". ($pos+1));
			$ftp->delete("InstalledContent/videos/". ($pos+1) .".jpg");
		}

		$hd = fopen("/tmp/videos.txt", "w");
		fwrite($hd, implode("\n", $videos_srv));
		fclose($hd);
		$ftp->put("/tmp/videos.txt", "InstalledContent/videos.txt");
		unlink("/tmp/videos.txt");
/*		$hd = fopen("/tmp/videos.txt", "w");
		for( $j = 0; $j < count( $videos_srv ); $j++ )
		{						
			fwrite($hd, $videos_srv[$j] ."\n");
			if( !arraySearch(trim( $videos_srv[$j] ), $videos_cli ) )
			{
				echo $path."/video_bank/". trim( $videos_srv[$j] ) .".mpg <br>";
				$log->write("Subiendo video: ". $videos_srv[$j]);
				if( $ftp->put( $path."/video_bank/".trim( $videos_srv[$j] ).".mpg", "InstalledContent/videos/".($j+1)."_temp.mpg" ) )
				{
					$log->write("Subido video: ". trim($videos_srv[$j]) ." -> ". ($j+1));
					if(!$ftp->delete("InstalledContent/videos/". ($j+1) .".mpg" ))
						$log->write("Error Eliminando:". ($j+1));
					if(!$ftp->rename("InstalledContent/videos/". ($j+1) ."_temp.mpg", "InstalledContent/videos/". ($j+1) .".mpg" ))
						$log->write("Error Renombrando:". ($j+1) );
				}
				else
				{
					$log->write("ERROR subiendo video: ". trim($videos_srv[$j]));
				}
			}
		}
		fclose($hd);
		$ftp->put("/tmp/videos.txt", "InstalledContent/videos.txt");
		unlink("/tmp/videos.txt");
*/
		$log->write("Comprimiendo archivos de programación");
		$makezip = file("http://localhost/tvguianew/makezip.php?client_id=".$client_obj->getId()."&year=".$year."&month=".$month."&day=".$day);
		$ftp->put($path."/".$client_obj->getId().".zip", "InstalledContent/programacion.zip");

		@unlink("/tmp/imagenes.txt");
		$imgs_cli = array(); //imagenes ya cargadas en el cliente
		if($ftp->get("InstalledContent/imagenes.txt", "/tmp/imagenes.txt"))
			$imgs_cli = file("/tmp/imagenes.txt");
		else
			echo "error";
			
		$imgs_cli = array_strip_nl($imgs_cli);
		foreach($videos_srv as $video)
		{
			$video_obj = new Video( $video );
			$imgs_srv[] = trim($video_obj->getImage());
		}
		
		$imgs_srv = array_mix($imgs_cli, $imgs_srv);
		$imgs_dif = array_diff($imgs_srv, $imgs_cli);

		foreach($imgs_dif as $pos => $img)
		{
			$log->write("Subiendo imagen: ". $img. "=>". ($pos+1));
			if($ftp->put( $path."/image_bank/". $img .".jpg", "InstalledContent/images/".($pos+1)."_temp.jpg"))
			{
				$ftp->delete( "InstalledContent/images/".($pos+1).".jpg" );
				$log->write("Subida imagen: ". $img ."=>". ($pos+1));
				if( !$ftp->rename( "InstalledContent/images/".($pos+1)."_temp.jpg", "InstalledContent/images/".($pos+1).".jpg" ) )
				{
					$log->write("Error renombrando Imagen: ". $img ."=>". ($pos+1));
				}
			}
			else
			{
				$log->write("Error Subiendo Imagen: ". $img ."=>". ($pos+1));
			}			
		}

		//elimina las imagenes sobrantes
		$imgs_dif_keys = array_diff_keys($imgs_cli, $imgs_srv);
		foreach($imgs_dif_keys as $pos => $img)
		{
			$log->write("Eliminando imagen: ". $img ." => ". ($pos+1));
			$ftp->delete("InstalledContent/imagenes/". ($pos+1) .".jpg");
		}

/*		for( $j = 0; $j < count( $videos_srv ); $j++ )
		{
			$video = new Video( $videos_srv[$j] );
			fwrite($hd, trim($video->getImage())."\n");
			if(!arraySearch(trim($video->getImage()), $imgs_cli))
			{
				echo $path."/image_bank/". $video->getImage() .".jpg<br>";
				$log->write("Subiendo imagen: ". $video->getImage());
				if($ftp->put( $path."/image_bank/".$video->getImage().".jpg", "InstalledContent/images/".($j+1)."_temp.jpg"))
				{
					$ftp->delete( "InstalledContent/images/".($j+1).".jpg" );
					$log->write("Subida imagen: ". $video->getImage());
					if( $ftp->rename( "InstalledContent/images/".($j+1)."_temp.jpg", "InstalledContent/images/".($j+1).".jpg" ) )
					{
						$uploaded_images[$client_obj->getName()][] = $videos_srv[$j];
					}
					else
					{
						$log->write("Error renombrando Imagen: ". $video->getImage());
					}
				}
				else
				{
					$log->write("Error Subiendo Imagen: ". $video->getImage());
				}
			}
		}*/
		$hd = fopen("/tmp/imagenes.txt", "w");
		fwrite($hd, implode("\n", $imgs_srv));
		fclose($hd);
		$ftp->put("/tmp/imagenes.txt", "InstalledContent/imagenes.txt");
		$ftp->close();
		unlink("/tmp/imagenes.txt");
	}
	else
	{
		$log->write("Error en conexion FTP hacia: ". $client_obj->getName());
	}
	file( "http://".$client_obj->getIp()."/unzip.php" );
	$log->write("Finaliza: Actualización de cabecera: ". $client_obj->getName());
}
?>