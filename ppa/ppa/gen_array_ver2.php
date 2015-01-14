<?
///////////////////////////////////////// FUNCIONES //////////////////////////////////////////////////////////////////////////
/*Retorna una arreglo cuya primera posicion tiene las horas y la segunda tiene los programas que correponden a las horas, dada la matriz de programacion transpuesta y una fecha*/
function getHoursProgramsfromMatrix( $matrix_row , $date ){
  $arr = array();
  $arr[0] = "";
  $arr[1] = "";
  
  $keys = array_keys( $matrix_row[$date] );
  for( $i = 0; $i < count( $keys ); $i++ ){
    if( trim( $matrix_row[$date][$keys[$i]] ) != "" ){
      $arr[0] .= $keys[$i]."\t";
      $arr[1] .= $matrix_row[$date][$keys[$i]]."\t";
    }
  }
  return $arr;
}


/*Convierte el archo de programacion version 2 en una matriz, dado un número de mes y año*/
function convertToMatrix( $file, $year, $month ){
  $matrix = array();
  $in = file( $file );
  $vars = explode("\t",$in[2]);
  $hora_corte = $vars[0];
  for($i = 1; $i<count($in); $i++){
    $vars = explode("\t",$in[$i]);
    $hora = $vars[0];
    for($j = 1; $j<count($vars); $j++){
      if(trim($vars[$j]) != ""){
	if($j < 10) $dia = "0" . $j;
	else $dia = $j;
	$fecha = date($year."-".$month."-") . $dia;
	
	if(strtotime($hora) < strtotime($hora_corte) ){
	  $fecha = date("Y-m-d", strtotime($fecha)+ (60*60*24));
	}
	
	$title = trim($vars[$j]);
	$title = strtolower($title);
	$title = ucwords($title);
	$matrix[$hora][$fecha] = $title;
      }
    }
  }   
  $matrix = orderMatrix( $matrix );
  $matrix = transMatrix( $matrix );
  return $matrix;
}
/*Ordena un arreglo de horas*/
function orderHourArray( $hour_array ){

  for( $i = count( $hour_array ); $i > 0; $i-- ){
    for( $j = 1; $j < count( $hour_array ); $j++ ){
      if( strtotime( $hour_array[$j-1] ) > strtotime( $hour_array[$j] ) && trim( $hour_array[$j] ) != "" ){
	$temp = $hour_array[$j];
	$hour_array[$j] = $hour_array[$j-1];
	$hour_array[$j-1] = $temp;
      }
    }
  }  
  return $hour_array;
}
/*Ordena la matriz de programación por horas*/
function orderMatrix( $matrix ){
  $new_matrix = array();
  $keys = array_keys( $matrix  );
  $keys1 = array_keys( $matrix[$keys[0]] );
  $keys = orderHourArray( $keys );

  for( $i = 0 ; $i < count( $keys ); $i++ ){
    for( $j = 0 ; $j < count( $matrix[$keys[$i]] ); $j++ ){
      $new_matrix[$keys[$i]][$keys1[$j]] = $matrix[$keys[$i]][$keys1[$j]];
    }
  }
  return $new_matrix;
}
/*Transpone la matriz de programación*/
function transMatrix( $matrix ){
  $trans_matrix = array();
  $keys = array_keys( $matrix  );
  $keys1 = array_keys( $matrix[$keys[0]] );
  for( $i = 0; $i < count( $keys ); $i++ ){
    for( $j = 0; $j < count( $matrix[$keys[$i]] ); $j++  ){
      $trans_matrix[$keys1[$j]][$keys[$i]] = $matrix[$keys[$i]][$keys1[$j]];
    }
  }
  return $trans_matrix;
}
/*Imprime la matriz de programacion version 2*/
function printMatrix( $matrix ){
  $keys = array_keys( $matrix  );
  $keys1 = array_keys( $matrix[$keys[0]] );
  echo "<table cellspacing='1' cellpadding='10' border='1'>";
  echo "<tr>";
  echo "<td>&nbsp;</td>";
  for( $i = 0; $i < count( $keys1 ); $i++ ){
    echo "<td>";
    echo "<b>".$keys1[$i]."</b>";
    echo "</td>";
  }
  echo "<tr><td>&nbsp;</td></tr>";
  echo "</tr>"; 
  for( $i = 0 ; $i < count( $keys ); $i++ ){
    echo "<tr>";
    echo "<td>";
    echo "<b>".$keys[$i]."</b>";
    echo "</td>";
    for( $j = 0 ; $j < count( $matrix[$keys[$i]] ); $j++ ){
      echo "<td>";
      echo $matrix[$keys[$i]][$keys1[$j]];
      echo "</td>";
    }
    echo "</tr>";
  }

  echo "</table>";
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
$mat = convertToMatrix( 'version2.txt', '2003', '11' );
//printMatrix( $mat );
$arr = array();
$arry0 = array();
$arry1 = array();
$keys = array_keys( $mat );
for( $i = 0; $i < count( $mat ); $i++ ){
  $arr = getHoursProgramsfromMatrix( $mat, $keys[$i] );
  $arry0[ $keys[$i] ] = $arr[0];
  $arry1[ $keys[$i] ] = $arr[1];
}

print_r( $arry0 );
print_r( $arry1 );
*/
?>