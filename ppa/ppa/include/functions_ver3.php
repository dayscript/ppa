<?
/*
function loadFileByRows( $date, $hourDiff ){
  $file = file( "tipo3.txt" );
  //$pos = typeRows( explode( "\t", $file[0] ) );
  $pos['title'] = 1;
  $pos['hour'] = 0;  
  $date = explode( "-", $date );
  for ( $i = 1; $i < count( $file ); $i++ ){
    if ( trim( $file[$i] ) ){
      $temp = explode( "\t", addslashes( $file[$i] ) );
      if ( ereg( "[0-9]{1,2}(:)[0-9]{2}", $temp[ $pos['hour'] ] ) ){
        $hour = changeHour( $temp[ $pos['hour'] ], $hourDiff );
        $day += ( strtok( $hour, ":" ) <  strtok( $hourCopy , ":" ) ? 1 : 0 );
        $matrix[$date[0] . "-" . $date[1] . "-" . ( $day < 10 ? "0" . $day: $day )][$hour] = $temp[ $pos['title'] ];
      	$hourcopy = $hour;
	  }
    }
  }
  return $matrix;
}

function changeHour( $hour, $hourDiff ){
  return  ( ( strtok( $hour, ":" ) + 24 + $hourDiff ) %24 ) . ":" . 
strtok( ":" );;
}
*/
/*Convierte el archo de programacion version 3 en una matriz, dado un n�mero de mes y a�o*/
function convertToMatrix1( $file ){
  $matrix = array();
  $in = file( $file );
  for( $i = 0 ; $i < count( $in ); $i++ ){
    $vars = explode("\t",$in[$i]);
    $vars[1] = str_replace("am", "", strtolower( $vars[1] ) );
    $vars[1] = str_replace("pm", "", strtolower( $vars[1] ) );
    $vars[1] = str_replace("a.m.", "", strtolower( $vars[1] ) );
    $vars[1] = str_replace("p.m.", "", strtolower( $vars[1] ) );
    $fecha = $vars[0];
    $temp = explode(":", $vars[1] );
    if( $temp[0] > 23 ){
      $temp1 = $temp[0] - 24;
      $vars[1] = $temp1.":".$temp[1];
    }
    if( strstr( $temp[0], "-" )  ){
        $temp1 = 24 + $temp[0];
        $vars[1] = $temp1.":".$temp[1];
    }
    $hora = trim($vars[1]);
    $program = trim($vars[2]);
    $program=ereg_replace (' +', ' ', trim($program));
    $program = str_replace( "\n", "", $program );
    $program = str_replace( "\r", "", $program );
    $program = str_replace( chr(10), "", $program );
    $program = str_replace( chr(13), "", $program );			
    $program = str_replace( chr(160), "", $program );			
    if( strstr($hora,":") ){
      $matrix[$fecha][$hora] = $program;
    }
  }
  return $matrix;
}

?>