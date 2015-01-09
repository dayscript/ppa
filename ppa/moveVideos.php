<?
set_time_limit(-1);
$path = "ppa/";
require_once("ppa/config1.php");
function ftp_disconnect($connection){
	if (function_exists("ftp_close")) {
		ftp_close($connection);
	} else {
		ftp_exec($connection,"QUIT");
	}
}
$year = date( "Y" );
$month = date( "m" );
$day = date( "d" );
$date = date( "Y-m-d" );
$path = "tvguianew";
$numpages = 40;
$uploaded_videos = array();
$uploaded_images = array();
$start_time = date("H:i");
$ppa = new PPA(1);
$client_array = $ppa->getClients();

//Por si hay menos de 40 videos en el dia
for( $i = 0 ; $i < count( $client_array ); $i++ ){
  $file = file( $path."/output_videos/".$client_array[$i]->getId()."/".$year."/".$month."/".$day."/videos.txt" );
  $num_videos = 0;
  for( $k = 0 ; $k < count( $file ); $k++ ){
  	if( trim( $file[$k] ) != "" ){
		$num_videos++;
	}
  }
   if( $client_array[$i]->getId() == 46 || $client_array[$i]->getId() == 26 || $client_array[$i]->getId() == 52 || $client_array[$i]->getId() == 57 || $client_array[$i]->getId() == 44  || $client_array[$i]->getId() == 48 ){

  //if( $client_array[$i]->getId() == 48 ){
    if( $num_videos < 40 ){
	 	echo $client_array[$i]->getName()."<br>";
      if( $num_videos > 0 ){
	$newfile = implode( "", $file )."\n";
      }else{
	$newfile = implode( "", $file );
      }
      $newdate = date( "Y-m-d", strtotime( $year."-".$month."-".$day )-(60*60*24) );
      $newyear = date( "Y", strtotime( $newdate ) );
      $newmonth = date( "m", strtotime( $newdate ) );
      $newday = date( "d", strtotime( $newdate ) );
      $centi = false;
      $count = 0;
      while( !$centi ){
			$file1 = file( $path."/output_videos/".$client_array[$i]->getId()."/".$newyear."/".$newmonth."/".$newday."/videos.txt" );
  			$num_videos1 = 0;
  			for( $k = 0 ; $k < count( $file1 ); $k++ ){
  				if( trim( $file1[$k] ) != "" ){
					$num_videos1++;
				}
  			}
			if( $num_videos1 == 40 || $count > 50 ){
	  			$centi = true;
	  			if( $count <= 50 ){
				    for( $j = count( $file ) ; $j < $num_videos1; $j++ ){
	      			$video = new Video( $file1[$j] );
				      if( strtotime( $date ) > strtotime( $video->getDateSup() ) ){
							$video->setDateSup( $date );
							$video->commit();
				      }
				    }
	   			 $temp = implode( "", array_slice( $file1, $num_videos ) );
	  			}else{
					if( $num_videos > 0 ){
						$dup_videos = array();
						for( $k = 0 ; $k < (40 - $num_videos); $k++ ){
							$video1 = new Video( $file[$k] );
							$image1_id = $video1->getImage();
							$video = new Video();
							$image = new Image();
							$image->commit();
							$image->copyImage( $path."/image_bank/".$image1_id.".jpg" , "tvguianew/image_bank" );
							$video->setImage( $image->getId() );
							$video->setName( "(Copy)".$video1->getName() );
							$video->setDescription( $video1->getDescription() );
							$video->setLength( $video1->getLength() );
							$video->setDateInf( $year."/".$month."/".$day );
							$video->setDateSup( $year."/".$month."/".$day );
							$video->commit();
							$video->copyVideo( $path."/video_bank/".$video1->getId().".mpg", "tvguianew/video_bank" );
							$video->addClient( $client_array[$i]->getId() );
							$dup_videos[] = $video->getId()."\n";				
						}
	   			   $temp = implode( "", $dup_videos );
				   }
				}
			    $newfile .= $temp;
			    unlink( $path."/output_videos/".$client_array[$i]->getId()."/".$year."/".$month."/".$day."/videos.txt" );
			    $handle = fopen( $path."/output_videos/".$client_array[$i]->getId()."/".$year."/".$month."/".$day."/videos.txt", "w+" );
				 fwrite( $handle, $newfile );
			}else{
	  			$newdate = date( "Y-m-d", strtotime( $newdate )-(60*60*24) );
				$newyear = date( "Y", strtotime( $newdate ) );
			   $newmonth = date( "m", strtotime( $newdate ) );
		  	   $newday = date( "d", strtotime( $newdate ) );
			   $count++;
			}
  		 }
    }
  }
 
}

$videos = $ppa->getVideos();
//Arregla los segundos de los archivos de programación
for( $i = 0 ; $i < count($videos);$i++ ){
 	echo $videos[$i]->getName()."<br>";
	$clients = $videos[$i]->getClients();					
	$videos[$i]->changeProgramDirectoriesDate( $path."/output", $path."/output_videos", $clients, date("Y-m-d"), date("Y-m-d", strtotime( date("Y-m-d") ) + (60*60*24) ) );	
}

//Alimenta videos , programación e imágenes
for( $i = 0 ; $i < count( $client_array ); $i++ ){
	$rename_array = array();
		if( $client_array[$i]->getId() == 46  || $client_array[$i]->getId() == 26 || $client_array[$i]->getId() == 52 || $client_array[$i]->getId() == 57 || $client_array[$i]->getId() == 44 || $client_array[$i]->getId() == 48 ){
	//if( $client_array[$i]->getId() == 48){
	if( trim( $client_array[$i]->getIp() ) != "" ){
		$ip = $client_array[$i]->getIp();	
		$file = file( $path."/output_videos/".$client_array[$i]->getId()."/".$year."/".$month."/".$day."/videos.txt" );
		if( $ftp = ftp_connect($ip) ){
			if( ftp_login($ftp, "dayscript", "krxo4578") ){
				echo $ip."<br>";
				unlink("videos.txt");
				if( ftp_get($ftp, "videos.txt", "InstalledContent/videos.txt", FTP_BINARY) ){
					$file1 = file( "videos.txt" );
					$diff = false;
					if( count( $file ) != count( $file1 ) ){
						$diff = true;
					}else{
						for( $j = 0; $j < count( $file ); $j++ ){
							if( trim( $file[$j] ) != trim( $file1[$j] ) ){
								$diff = true;	
							}
						}
					}
					if( $diff ){
						ftp_delete($ftp, "InstalledContent/videos.txt" );
						ftp_put($ftp, "InstalledContent/videos.txt", $path."/output_videos/".$client_array[$i]->getId()."/".$year."/".$month."/".$day."/videos.txt", FTP_ASCII );
						for( $j = 0; $j < count( $file ); $j++ ){
							$file[$j] = str_replace( "\n", "", $file[$j]);
					    	$file[$j] = str_replace( "\r", "", $file[$j]);								
							$file[$j] = str_replace( chr(10), "", $file[$j]);
							$file[$j] = str_replace( chr(13), "", $file[$j]);
							
							$file1[$j] = str_replace( "\n", "", $file1[$j]);
					    	$file1[$j] = str_replace( "\r", "", $file1[$j]);								
							$file1[$j] = str_replace( chr(10), "", $file1[$j]);
							$file1[$j] = str_replace( chr(13), "", $file1[$j]);

							if( trim( $file[$j] ) != trim( $file1[$j] ) ){
								echo $path."/video_bank/".trim( $file[$j] ).".mpg"."<br>";
								if( ftp_put($ftp, "InstalledContent/videos/".($j+1)."_temp.mpg", $path."/video_bank/".trim( $file[$j] ).".mpg", FTP_BINARY ) ){
									$uploaded_videos[$client_array[$i]->getName()][] = $file[$j];
									$rename_array[] = ($j+1);
								}
							}
						}
						for( $j = 0; $j < count( $rename_array ); $j++ ){
							ftp_delete($ftp, "InstalledContent/videos/".$rename_array[$j].".mpg" );
							ftp_rename($ftp, "InstalledContent/videos/".$rename_array[$j]."_temp.mpg", "InstalledContent/videos/".$rename_array[$j].".mpg" );
						}		
					}
				}else{
					if( ftp_put($ftp, "InstalledContent/videos.txt", $path."/output_videos/".$client_array[$i]->getId()."/".$year."/".$month."/".$day."/videos.txt", FTP_ASCII ) ){
						for( $j = 0; $j < count( $file ); $j++ ){
							echo $path."/video_bank/".trim( $file[$j] ).".mpg"."<br>";
							$video = new Video( trim( $file[$j] ) );
							$clients = $video->getClients();
							$video->changeProgramDirectoriesDate( $path."/output", $path."/output_videos", $clients, date("Y-m-d"), date("Y-m-d") );
							ftp_mkdir( $ftp, "InstalledContent/videos/");
							if( ftp_put($ftp, "InstalledContent/videos/".($j+1)."_temp.mpg", $path."/video_bank/".trim( $file[$j] ).".mpg", FTP_BINARY ) ){
								$uploaded_videos[$client_array[$i]->getName()][] = $file[$j];
								$rename_array[] = ($j+1);
							}
						}
						for( $j = 0; $j < count( $rename_array ); $j++ ){
							ftp_delete($ftp, "InstalledContent/videos/".$rename_array[$j].".mpg" );
							ftp_rename($ftp, "InstalledContent/videos/".$rename_array[$j]."_temp.mpg", "InstalledContent/videos/".$rename_array[$j].".mpg" );
						}
					}
				}
			}
/*			
			$hour = "00_00";
			ftp_mkdir($ftp, "InstalledContent/output/" );
			ftp_mkdir($ftp, "InstalledContent/output/".$client_array[$i]->getId()."/" );
			ftp_mkdir($ftp, "InstalledContent/output/".$client_array[$i]->getId()."/".$year."/" );
			ftp_mkdir($ftp, "InstalledContent/output/".$client_array[$i]->getId()."/".$year."/".$month."/" );
			ftp_mkdir($ftp, "InstalledContent/output/".$client_array[$i]->getId()."/".$year."/".$month."/".$day."/" );

			for( $j = 0; $j < 48; $j++ ){
				for( $k = 1 ; $k <= $numpages; $k++ ){
					ftp_mkdir($ftp, "InstalledContent/output/".$client_array[$i]->getId()."/".$year."/".$month."/".$day."/".$hour);
					ftp_delete($ftp, "InstalledContent/output/".$client_array[$i]->getId()."/".$year."/".$month."/".$day."/".$hour."/p".$k.".txt" );
					ftp_put($ftp, "InstalledContent/output/".$client_array[$i]->getId()."/".$year."/".$month."/".$day."/".$hour."/p".$k.".txt", $path."/output/".$client_array[$i]->getId()."/".$year."/".$month."/".$day."/".$hour."/p".$k.".txt", FTP_ASCII );
					echo "InstalledContent/output/".$client_array[$i]->getId()."/".$year."/".$month."/".$day."/".$hour."/p".$k.".txt"."<br>";
				}
				$hour = date( "H_i", strtotime( str_replace( "_", ":", $hour ) ) + (60*30) );				
			}
			*/
			ftp_mkdir($ftp, "InstalledContent/images/");
			for( $j = 0; $j < count( $file ); $j++ ){
				$video = new Video( $file[$j] );
				echo $path."/image_bank/".$video->getImage().".jpg<br>";
				ftp_put($ftp, "InstalledContent/images/".($j+1)."_temp.jpg", $path."/image_bank/".$video->getImage().".jpg", FTP_BINARY);
				ftp_delete($ftp, "InstalledContent/images/".($j+1).".jpg" );
				if( ftp_rename($ftp, "InstalledContent/images/".($j+1)."_temp.jpg", "InstalledContent/images/".($j+1).".jpg" ) ){
					$uploaded_images[$client_array[$i]->getName()][] = $file[$j];
				}

			}				
			ftp_disconnect($ftp);
		}//if
	}//if	
}
}//for
$end_time = date("H:i");


//MAIL
$to  = "gafanador@dayscript.com" ;
$subject = "Resultado Alimentación de Videos";
$message = '
<html>
<head>
 <title>Resultado Alimentación de Videos</title>
</head>
<body>
';
$message .= "\n";
$message = '<center><h3>Videos</h3></center>
<br>
<table width="600" border="1" align="center">
<tr>
<th>Id</th>
<th>Nombre</th>
<th>Ciudad</th>
</tr>';
$message .= "\n";
$keys = array_keys( $uploaded_videos );
for( $i = 0; $i < count($keys); $i++ ){
	$keys1 = array_keys( $uploaded_videos[$keys[$i]] );
	print_r( $uploaded_videos );
	$sql = "SELECT id, name FROM video WHERE id IN (";
	for( $j =0; $j < count($keys1); $j++ ){
		if( $j+1 == count($keys1) ){
			$sql .= $uploaded_videos[$keys[$i]][$keys1[$j]];
		}else{
			$sql .= $uploaded_videos[$keys[$i]][$keys1[$j]].", ";		
		}
	}
	$sql .= ")";
	$query = db_query( $sql );
	while( $row = db_fetch_array( $query ) ){
	  $message .= "<tr>";
	  $message .= "<td>";
	  $message .= $row['id'];
	  $message .= "</td>";
	  $message .= "<td>";
	  $message .= $row['name'];
	  $message .= "</td>";
	  $message .= "<td>";
	  $message .= $keys[$i];
	  $message .= "</td>";
	  $message .= "</tr>";
	  $message .= "\n";
	}
	
}
$message .= '</table>';
$message .= '<br>
<center><h3>Imagenes</h3></center>
<br>
<table width="600" border="1" align="center">
  <tr>
    <th>Id</th>
    <th>Video</th>
    <th>Ciudad</th>
  </tr>';
$keys = array_keys( $uploaded_images );
for( $i = 0; $i < count($keys); $i++ ){
	$keys1 = array_keys( $uploaded_images[$keys[$i]] );
	$sql = "SELECT i.id, v.name FROM video v, image i WHERE v.image = i.id AND i.id IN (";
	for( $j =0; $j < count($keys1); $j++ ){
		if( $j+1 == count($keys1) ){
			$sql .= $uploaded_images[$keys[$i]][$keys1[$j]];
		}else{
			$sql .= $uploaded_images[$keys[$i]][$keys1[$j]].", ";		
		}
	}
	$sql .= ")";
	$query = db_query( $sql );
	while( $row = db_fetch_array( $query ) ){
		$message .= "<tr>";
		$message .= "<td>";
		$message .= $row['id'];
		$message .= "</td>";
		$message .= "<td>";
		$message .= $row['name'];
		$message .= "</td>";
		$message .= "<td>";
		$message .= $keys[$i];
		$message .= "</td>";
		$message .= "</tr>";
		$message .= "\n";
	}
}
$message .= '</table><br>';
$message .= '<h4>Hora de Inicio: '.$start_time.'</h4>';
$message .= '<h4>Hora de Finalización: '.$end_time.'</h4>';
$message .= '</body></html>';

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$headers .= "From: PPA <ppa@spinebone2.dayscript.com>\r\n";


mail($to, $subject, $message, $headers);
?>