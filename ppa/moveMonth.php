<?
$link = mysql_connect("localhost","dayscript","kfc3*9mn");
mysql_select_db("intermedio");
$db = "intermedio";
require_once( "include/db.inc.php" );
function numDaysmonth( $year, $month ){
  if((($year % 4)==0) && (($year % 100)!=0) || (($year % 400)==0)){
    $leap = true; 
  }else{
    $leap = false; 
  }
		  
  if ( $month=='1' || $month=='3' || $month=='5' || $month1=='7' || $month1=='8' || $month=='10' || $month=='12'){
	    $days=31;
  }else{
    if($month=='4'|| $month=='6' || $month=='9' || $month=='11'){
      $days=30;
    }else{ 
      if($month1=='2'){
		if($leap){ 
		  $days=29; 
		}else{ 
		  $days=28;
		} 
      } 
    }
  } 		   
  return $days;
}

function moveLast8DaysPrograms( $channel, $year ,$month ){
  $days = numDaysmonth( $year, $month );
  $endday = $days;
  $startday = $days-6;
  $sql = "select title, originaltitle, duration, gender, starthour, endhour, channel, year, director, actor, rated, description, id_sinopsis, original, series, date from p_program where channel = ".$channel.' and date >= "'.$year."-".$month."-".$startday.'" and date <= "'.$year."-".$month."-".$endday.'"';
  $query = db_query( $sql );
  while( $row = db_fetch_array( $query ) ){
    $str = "";
    for( $i = 0; $i <= 13 ; $i++ ){
	  $str .= $row[$i]."\t";
	}
	$programs[$row['date']][] = $str; 
  }
  if( $month == '12' ){
    $nextmonth = '01';
	$year += 1;
  }else{
    $nextmonth = $month + 1;
  }
  $firstday = date("w", strtotime( $year."-".$nextmonth."-01" ) );
  $keys = array_keys( $programs );
  for( $i = 0; $i < count( $programs ); $i++ ){
    if( date( "w", strtotime( $keys[$i] ) ) == $firstday ){
	  $startkey = $i;
	}
  }
  for( $i = $startkey; $i < count( $programs ); $i++ ){
     $programs1[] = $programs[$keys[$i]];
  }  
  for( $i = 0; $i < $startkey; $i++ ){
     $programs1[] = $programs[$keys[$i]];
  }
  $date = $year."-".$nextmonth."-01" ;
  $cont = 0;
  for( $i = 0 ; $i < numDaysMonth( $year, $nextmonth ) ; $i++ ){
    for( $j = 0 ; $j < count( $programs1[$cont] ); $j++ ){
	  $entry = explode( "\t", $programs1[$cont][$j] );
      $sql = "insert into p_program( title, originaltitle, duration, gender, starthour, endhour, channel, year, director, actor, rated, description, id_sinopsis, original, series, date) values(";
	  for( $k = 0; $k < count( $entry ); $k++ ){
   		$sql .= "'".$entry[$k]."', ";
	  }
	  $sql .= "'".$date."'";
	  $sql .= ")";
//	  echo $sql."<br>";
      db_query( $sql );
	}
	$cont++;
	if( $cont >= count( $programs1 ) ){
	  $cont = 0;
	}
    $date = date("Y-m-d", strtotime( $date ) + (60*60*24) );
  }
  echo "Fin<br>";
}
//moveLast8DaysPrograms(60, 2003, 12);
?>