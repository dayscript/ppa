<?
$arry1 = array();
$arry2 = array();
$link = mysql_pconnect("64.116.130.4", "dayscript", "kfc3*9mn");
mysql_select_db("intermedio",$link);

$sql = "select * from p_program where date like '%2003-11%' and channel='203' order by starthour";
$data = mysql_db_query("intermedio",$sql,$link);
while( $row = mysql_fetch_array( $data ) ){
  $arry1[$row['date']] .= $row['starthour']."\t";
  $arry2[$row['date']] .= $row['title']."\t";
}

?>