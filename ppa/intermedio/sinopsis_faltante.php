<?
require_once( "ppa/config.php" );
//Retorna un arreglo de los id de canal al cual pertenece el título de programa
function channel_date_program( $id_chapter, $ano, $mes, $timediff ){
  $chapter = new Chapter( $id_chapter );
  $sub_str_arr = array();
  $day_array = array( 'Dom', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa' );
  $date = $ano."-".$mes;
  $return_str = "";
  $slots = $chapter->getSlotsfromYearMonth( $ano, $mes );
  $return_str = "";

  for( $i = 0; $i < count( $slots ); $i++ ){
    $channel = new Channel($slots[$i]->getChannel());
    $date = $slots[$i]->getDate();
    $date .= " ".$slots[$i]->getTime();
    $date = date( "Y-m-d H:i:s", strtotime( $date )+((60*60)+$timediff) );
    $sub_str_arr[$channel->getName()][] = $date;
  }
  $keys = array_keys( $sub_str_arr );
  for( $i = 0; $i < count( $sub_str_arr ); $i++ ){
    $return_str .= "Por ".$keys[$i].'&nbsp;';
    for( $j = 0; $j < count( $sub_str_arr[$keys[$i]] ); $j++ ){
      $date = $sub_str_arr[$keys[$i]][$j];
      $day = date( "w", strtotime( $date ) );
      $day = $day_array[$day];
      $day_month = date( 'j', strtotime( $date ) );
      $hour = date( 'g:i a', strtotime( $date ) );
      if( strstr( $hour, 'am' ) ){
	$hour = str_replace( 'am', 'a.m.', $hour );
      }else{
	if( strstr( $hour, 'pm' ) ){
	  $hour = str_replace( 'pm', 'p.m.', $hour );
	}
      }
      $return_str .= $day.". ".$day_month." - ".$hour;
      /*
      if( $j+1 > count( $sub_str_arr[$keys[$i]] )-1 && $i+1 < count( $sub_str_arr )+1  ){
	$return_str .= $day.". ".$day_month." - ".$hour." / ";
      }else{
	$return_str .= $day.". ".$day_month." - ".$hour;
      }
      */
      /*
      if( count( $sub_str_arr ) == 1 || $i+1 > count( $sub_str_arr )-1 ){
	$return_str .= $day.". ".$day_month." - ".$hour;
      }else{
      $return_str .= $day.". ".$day_month." - ".$hour." / ";
      }
      */
    }
    if( $i+1 < count( $sub_str_arr ) ){
      $return_str .= " / ";
    }
  }

  return $return_str;
}
 $temp =  trim( $_POST['mes1'] );
 if(  $_POST['mes1'] < 10 && $temp[0] != '0' ){
    $_POST['mes1'] = "0". $_POST['mes1'];
 }
$sql = "SELECT s.title, s.chapter FROM sinopsis s, chapter c where s.year = '".$_POST['ano1']."' and s.month = '".$_POST['mes1']."' and s.chapter = c.id and c.description = '' order by s.title";
$query = db_query( $sql );
 echo db_numrows( $query );
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="textos">
<? while( $row = db_fetch_array( $query ) ){?>
<tr>
<td align="center">
<?
$chapter = new Chapter( $row['chapter'] );
  //$title = strtolower( trim( $chapter->getTitle() ) ); 
 //$title = strtolower( trim( $chapter->getSpanishTitle() ) ); 
 $title = strtolower( trim( $row['title'] ) );
   if( $title == "" ){
     $title = strtolower( trim( $chapter->getTitle() ) ); 
   }
$row['title'] = $title;
$type = $chapter->getType();
eval("$"."id_obj = "."$"."chapter->get".$type."();");
eval( "$"."obj = new ".$type."(".$id_obj.");" );
$row['description'] = $chapter->getDescription();
$row['gender'] = $obj->getGender();
if( $type == "Movie" ){
  $row['actor'] = $obj->getActors();
  $row['director'] = $obj->getDirector();
}else{
 $row['actor'] = $obj->getStarring();
 $row['director'] = "";
}
$row['description'] = trim( $row['description'] );
if( $row['description'][strlen($row['description'])-1] != '.' ){
  $row['description'] .= '.';
}
switch( $title ){
 case ( $title[0] == 'a' ):{
   if( !$entre_a ){
     echo '<b><font size="5" color="#FFCC00">- A -</font></b><br>';
     $entre_a = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
	 
   }
   break;
 }
 case ( $title[0] == 'b' ):{
   if( !$entre_b ){
     echo '<b><font size="5" color="#FFCC00">- B -</font></b><br>';
     $entre_b = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'c' ):{
   if( !$entre_c ){
     echo '<b><font size="5" color="#FFCC00">- C -</font></b><br>';
     $entre_c = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'd' ):{
   if( !$entre_d ){
     echo '<b><font size="5" color="#FFCC00">- D -</font></b><br>';
     $entre_d = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'e' ):{
   if( !$entre_e ){
     echo '<b><font size="5" color="#FFCC00">- E -</font></b><br>';
     $entre_e = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'f' ):{
   if( !$entre_f ){
     echo '<b><font size="5" color="#FFCC00">- F -</font></b><br>';
     $entre_f = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'g' ):{
   if( !$entre_g ){
     echo '<b><font size="5" color="#FFCC00">- G -</font></b><br>';
     $entre_g = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'h' ):{
   if( !$entre_h ){
     echo '<b><font size="5" color="#FFCC00">- H -</font></b><br>';
     $entre_h = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'i' ):{
   if( !$entre_i ){
     echo '<b><font size="5" color="#FFCC00">- I -</font></b><br>';
     $entre_i = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'j' ):{
   if( !$entre_j ){
     echo '<b><font size="5" color="#FFCC00">- J -</font></b><br>';
     $entre_j = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'k' ):{
   if( !$entre_k ){
     echo '<b><font size="5" color="#FFCC00">- K -</font></b><br>';
     $entre_k = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'l' ):{
   if( !$entre_l ){
     echo '<b><font size="5" color="#FFCC00">- L -</font></b><br>';
     $entre_l = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'm' ):{
   if( !$entre_m ){
     echo '<b><font size="5" color="#FFCC00">- M -</font></b><br>';
     $entre_m = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'n' ):{
   if( !$entre_n ){
     echo '<b><font size="5" color="#FFCC00">- N -</font></b><br>';
     $entre_n = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'o' ):{
   if( !$entre_o ){
     echo '<b><font size="5" color="#FFCC00">- O -</font></b><br>';
     $entre_o = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'p' ):{
   if( !$entre_p ){
     echo '<b><font size="5" color="#FFCC00">- P -</font></b><br>';
     $entre_p = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'q' ):{
   if( !$entre_q ){
     echo '<b><font size="5" color="#FFCC00">- Q -</font></b><br>';
     $entre_q = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'r' ):{
   if( !$entre_r ){
     echo '<b><font size="5" color="#FFCC00">- R -</font></b><br>';
     $entre_r = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 's' ):{
   if( !$entre_s ){
     echo '<b><font size="5" color="#FFCC00">- S -</font></b><br>';
     $entre_s = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 't' ):{
   if( !$entre_t ){
     echo '<b><font size="5" color="#FFCC00">- T -</font></b><br>';
     $entre_t = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'u' ):{
   if( !$entre_u ){
     echo '<b><font size="5" color="#FFCC00">- U -</font></b><br>';
     $entre_u = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'v' ):{
   if( !$entre_v ){
     echo '<b><font size="5" color="#FFCC00">- V -</font></b><br>';
     $entre_v = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'w' ):{
   if( !$entre_w ){
     echo '<b><font size="5" color="#FFCC00">- W -</font></b><br>';
     $entre_w = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'x' ):{
   if( !$entre_x ){
     echo '<b><font size="5" color="#FFCC00">- X -</font></b><br>';
     $entre_x = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'y' ):{
   if( !$entre_y ){
     echo '<b><font size="5" color="#FFCC00">- Y -</font></b><br>';
     $entre_y = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 case ( $title[0] == 'z' ):{
   if( !$entre_z ){
     echo '<b><font size="5" color="#FFCC00">- Z -</font></b><br>';
     $entre_z = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
 default:{
   if( !$entre_otro ){
     echo '<b><font size="5" color="#FFCC00">- # -</font></b><br>';
     $entre_otro = true;
   }
   if( !@in_array( $title, $inserted_titles) ){
     $inserted_titles[] = $title;
     echo "<font class='titulo_sinop'>".ucwords( strtolower( $row['title'] ) )."</font><br>";
	 if( trim( $row['gender'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop' >".$row['gender']."</font><br>";
	 }
	 if( trim( $row['actor'] ) != "" ){	 
       echo "<font class='gender_actor_dir_sinop'> Con ".$row['actor']."</font><br>";
	 }
	 if( trim( $row['director'] ) != "" ){
       echo "<font class='gender_actor_dir_sinop'> Dir. ".$row['director']."</font><br>";
	 }
	 if( trim( $row['description'] ) != "" ){
       echo "<font class='description_sinop'>".str_replace( "<br>", " ", $row['description'] )."</font><br>";
	 }
     echo "<font class='horario_sinop'>".channel_date_program( $chapter->getId(), $_POST['ano1'], $_POST['mes1'], 1 )."</font>";
   }
   break;
 }
}
?>
</td>
</tr>

<? }?>
</table>
<?=count($inserted_titles)?>
