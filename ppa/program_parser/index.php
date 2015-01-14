<?
if ($handle = opendir('.')) {

   while (false !== ( $file = readdir($handle) ) ) 
   {
   		if( !is_dir( $file ) )
       print ("<a href=\"" . $file . "\">". $file ."</a><br>");
   }
   closedir($handle);
}
?>