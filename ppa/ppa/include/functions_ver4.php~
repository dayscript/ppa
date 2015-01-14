<?
function loadFileDiscovery ( $file, $date, $dif_hour = false ){
   $file = file( $file );
  $dif_hour = 0;
  for ( $i = 0; $i < count( $file ); $i++ ){
    if ( trim( $file[$i] ) != "" ){
      if( $obj ){
	$matrix[ date( "Y-m-d", mktime( 0, 0, 0, $date['mes'], $day + $hour[1], $date['ano'] ) ) ][$hour[0]] = trim( $title ); 
	$obj = $description = false;
      }
      if ( eregi( "[0-9]{1,2}[,][\ ]200[0-9]", $file[$i] ) ){
	strtok ( $file[$i], "," );
	strtok ( " " );
	$day = trim( strtok ( "," ) );
      }elseif ( ereg( "^[0-9]{1,2}[:][0-9]{2}", trim( $file[$i] ) ) ){
	$row = strtok( trim( $file[$i] ), " " );
	$title = strtok( "\n" );
	$hour = giveHour( $row, $dif_hour );
      }elseif( $title != "" ){
	if ( $description == "" )
	  $description = trim ( $file[$i] ) . ". ";
	else 
	  $description .= trim ( $file[$i] ) . " ";
	if ( eregi( "[0-9]{1,2}[:][0-9]{2}", $file[$i+1] ) )
	  $obj = true;
      }
    } 
  }
  $matrix[ date( "Y-m-d", mktime( 0, 0, 0, $date['mes'], $day + $hour[1], $date['ano'] ) ) ][$hour[0]] = $title; 
  return $matrix;
}
function giveHour( $hour, $dif_hour ){
  $hours = strtok( $hour, ":" ); 
  $minutes = strtok( ":" ); 
  if ( stristr(  $minutes, "am" ) ){
    ( $hours == 12 )? ( $hours = 0 ) : "";
    $minutes = str_replace( "am", "", $minutes );
  }elseif ( stristr(  $minutes, "pm" ) ){
    ( $hours < 12 )? ( $hours += 12 ) : ""; 
    $minutes = str_replace( "pm", "", $minutes );
  }
  $hours += $dif_hour;
  ( $hours < 0 )? ( $change_day = -1 ): ( ( $hours >= 24 )? ( $change_day = 1 ) : ( $change_day = 0 ) ) ;
  return array_merge( ( ($hours + 24) % 24 ) . ":" . $minutes, $change_day );
}
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
/*Convierte el archo de programacion version 3 en una matriz, dado un número de mes y año*/
?>