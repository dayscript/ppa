<?
function resampimagejpg( $forcedwidth, $forcedheight, $g_srcfile, $g_dstfile ) {
	if(file_exists($g_srcfile)) {
		$g_is=getimagesize($g_srcfile);
		$img_src=imagecreatefrompng($g_srcfile);
		$img_dst=imagecreate($forcedwidth,$forcedheight);
		imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $forcewidth, $forceheight, $g_is[0], $g_is[1]);
		imagejpeg($img_dst, $g_dstfile, 100);
		imagedestroy($img_dst);
		return true;
	} else {
		return false;
	}
} 
   
function thumbs($source, $dest, $dest2){
	$image = imagecreatefrompng($source);
	$image2 = imagecreate(100,130);
	$image3 = imagecreate(100,130);
	imagecopyresized($image2,$image,0,0,0,0,100,130,600,780);
	imagecopyresized($image3,$image,0,0,0,0,100,130,600,780);
	$total = ImageColorsTotal($image3);
	if($total<=0){
		imagepng($image,$source);
		$image = imagecreatefrompng($source);
		imagecopyresized($image2,$image,0,0,0,0,100,130,600,780);
		imagecopyresized($image3,$image,0,0,0,0,100,130,600,780);
	}
	for( $i=0; $i<$total; $i++){ 
		$old = ImageColorsForIndex($image3, $i); 
		$commongrey = (int)(($old[red] + $old[green] + $old[blue]) / 3); 
		ImageColorSet($image3, $i, $commongrey, $commongrey, $commongrey); 
	}
	imagejpeg($image2, $dest, 80);
	imagejpeg($image3, $dest2, 80);
}

$resource = 'portada.png';
$resource2 = 'portada2.jpg';
$resource3 = 'portada3.jpg';

echo "Imagen Original: <br>";
?><img src="<?=$resource?>" border="0"><br><?
//resampimagejpg(100, 130, $resource, $resource2, 100);
toGray($resource, $resource2, $resource3);
echo "Imagen Redimensionada: <br>";
?><img src="<?=$resource2?>" border="0"><br><?
echo "Imagen Redimensionada en Gris: <br>";
?><img src="<?=$resource3?>" border="0"><br>