<?
require("config1.php");


/*
$sql = "select id from slot where channel = 299 and date >= '2004-06-01' ";
$query = db_query( $sql, true );
$i = 0;
$str = "(";
while( $row = db_fetch_array( $query ) ){
  if( $i+1 == db_numrows( $query ) ){
    $str .= $row['id'];
  }else{
    $str .= $row['id'].",";
  }
  $i++;
}
$str .= ")";
$sql = "delete from slot_chapter where slot in ".$str;
db_query( $sql );
$sql = "delete from slot where channel = 299 and date >= '2004-06-01'";
db_query( $sql, true );
*/

/*Elimina slots repetidos*/
/*
$sql = "SELECT id, count(*) as col2, time, date, channel  FROM slot GROUP  BY channel, date, time order by col2 desc";
$query = db_query( $sql );
$cont = 0;
while( $row = db_fetch_array( $query ) ){
  if( $row['1'] >= 2 ){
     $sql1 = "delete from slot where time = '".$row['time']."' and date = '".$row['date']."' and channel = ".$row['channel']." and id != ".$row['id'];
     db_query( $sql1 );
  }
}
*/
/*
$sql = "select * from slot";
$query = db_query( $sql );
$i = 0;
$str = "(";
while( $row = db_fetch_array( $query ) ){
  if( $i+1 == db_numrows( $query ) ){
    $str .= $row['id'];
  }else{
    $str .= $row['id'].",";
  }
  $i++;
}
$str .= ")";
$sql = "delete from slot_chapter where slot not ".$str;
db_query( $sql );
*/
?>