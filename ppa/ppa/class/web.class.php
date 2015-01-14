<?

class Web{

  function Web( ){
    
  }
  
  function getTitlePrograms( $date, $hour, $group ){
    $result = db_query( "select a.channel, a.date, a.time, c.spanishTitle from slot a, slot_chapter b, chapter c where channel in ( $group ) and a.date = '$date' an a.time >= '" . date( "H:i:00", strtotime( $hour ) ) . "' and a.time <='" . date( "H:i:00", strtotime( $hour ) + 90 * 60 ) . "' and a.id = b.slot and b.chapter = c.id order by time" );
    while( $row = db_fetch_array( $result ) )
      $programs[ $row['channel'] ][ strtok( $row['time'], ":" ) % 24 ]
  }
}

?>