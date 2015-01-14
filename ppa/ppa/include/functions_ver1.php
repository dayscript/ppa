<?

///////////////////////////////////////// FUNCIONES //////////////////////////////////////////////////////////////////////////
/*Retorna la fecha de una fila del archivo de programacion versión 1*/
function getDatefromRow( $file_array_row ){
   $pos = strpos( $file_array_row, "\t" );
   return substr( $file_array_row, 0, $pos );
}
/*Retorna la hora de una fila del archivo de programacion versión 1*/
function getHourfromRow( $file_array_row ){
   $pos = strpos( $file_array_row, "\t" );
   $substr = substr( $file_array_row, $pos+1 );
   $pos = strpos( $substr, "\t" );
   return substr( $substr, 0, $pos );
   
}
/*Retorna el programa de una fila del archivo de programacion versión 1*/
function getProgramfromRow( $file_array_row ){
   $pos = strpos( $file_array_row, "\t" );
   $substr = substr( $file_array_row, $pos+1 );
   $pos = strpos( $substr, "\t" );
   $substr = substr( $substr, $pos+1 );
   $pos = strpos( $substr, "\n" );   
   return substr( $substr, 0, $pos-1 );
}

/*date debe estar en formato aaaa-mm-dd*/
/*Retorna un array ordenado con todas las horas y programas de una fecha dada, ejemplo: array['horas'], array['programa']*/
function getHoursProgramsfromDate( $date, $file_array ){  
  $arr = array();
  $arr0 = array();
  $arr1 = array();
  $arr0[$date] = "";
  $arr1[$date] = "";
  for( $i = 0 ; $i < count( $file_array ); $i++ ){
    $date1 = getDatefromRow( $file_array[$i] );
    if( strstr( $date, $date1 ) ){
      $arr0[$date] .= getHourfromRow( $file_array[$i] )."\t"; 
      $arr1[$date] .= getProgramfromRow( $file_array[$i] )."\t"; 
    }
  }
  $temp = $arr0[$date];
  
  $temp = orderHourString( $arr0[$date], $arr1[$date] );
  $arr[0] = $temp[0];
  $arr[1] = $temp[1];
  return $arr;
}
/*El string de horas debe tener un tabulador entre las horas , ejemplo: "07:00\t10:00", *El string de programas debe tener un tabulador entre los programas , ejemplo: "Programa 1\tPrograma2 " */ 
/*Retorna un arreglo cuya primera posicion corresponde al string de horas ordenado por horas y la segunda posicion el string de programas que se muestran en las horas ya ordenadas*/
function orderHourString( $hour_string, $program_string ){
  $arr2 = array();
  $arr = explode("\t", $hour_string);
  $arr1 = explode( "\t", $program_string );
  for( $i = count( $arr ); $i > 0; $i-- ){
    for( $j = 1; $j < count( $arr ); $j++ ){
      if( strtotime( $arr[$j-1] ) > strtotime( $arr[$j] ) && trim( $arr[$j] ) != "" ){
	$temp = $arr[$j];
	$temp1 = $arr1[$j];
	$arr[$j] = $arr[$j-1];
	$arr1[$j] = $arr1[$j-1];
	$arr[$j-1] = $temp;
	$arr1[$j-1] = $temp1;
      }
    }
  }  
  $str = implode( "\t", $arr );
  $str1 = implode( "\t", $arr1 );
  $arr2[0] = $str;
  $arr2[1] = $str1;
  return $arr2;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


?>