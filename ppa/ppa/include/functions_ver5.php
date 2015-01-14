<?
function loadFileTipo5 ( $file, $date, $dif_hour = false ){
   $file = file( $file );
	$regs = array();
	$regs1 = array();
	$first_time = false;
   for ( $i = 0; $i < count( $file ); $i++ ){	
    if ( trim( $file[$i] ) != "" ){
      if ( eregi( "(lunes|martes|miercoles|jueves|viernes|sabado|domingo)[\ ][0-9]{1,2}[\ ](de)[\ ](enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre)", $file[$i] ) ){
			$date1 = explode(" ", trim( $file[$i] ) );
			$day = $date1[1];
			$first_time = true;	
		}else{
			if( eregi( "^[0-9]{1,2}[:][0-9]{2}", trim( $file[$i] ), $regs ) ){
				$time = $regs[0];
				if( $first_time ){
					$time0 = $time;
				}
				if( strstr( $time, "24:00" ) ){
					$time = "00:00";
					$date2 = date("Y-m-d", strtotime( $date['ano']."-".$date['mes']."-".$day ) + (60*60*24) );
				}else{
					if( strtotime( $time ) < strtotime( $time0 ) ){
				  		$date2 = date("Y-m-d", strtotime( $date['ano']."-".$date['mes']."-".$day ) + (60*60*24) );
					}else{
						 $date2 = date("Y-m-d", strtotime( $date['ano']."-".$date['mes']."-".$day )  );
					}				
				}				
				$newdate = date( "Y-m-d", strtotime( $date2." ".$time ) + (60*60*$dif_hour) );
				$newtime = date( "H:i", strtotime( $date2." ".$time ) + (60*60*$dif_hour) );
				if( eregi( "^[0-9]{1,2}[:][0-9]{2}[:][0-9]{2}", $file[$i], $regs1 ) ){
					$title = trim( str_replace( $regs1[0], "", $file[$i] ) );				
				}else{
					$title = trim( str_replace( $regs[0], "", $file[$i] ) );
				}
				$first_time = false;
				$matrix[$newdate][$newtime] = ucwords(strtolower($title));
			}
		}
	 }	
	 $regs = array();
  }
  return $matrix;
}
?>