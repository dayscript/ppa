<?
require_once("config1.php");
$channel = new Channel( $_GET['channel_id'] );
echo $sql;
if( $_GET['mes'] == 1 ){
   $mes = 12;
   $ano = $_GET['ano']-1;
}else{
   $mes = $_GET['mes']-1;
   $ano = $_GET['ano'];
}
$arry0 = $channel->createSlotsfromMonthYear( $mes, $ano, $_GET['mes'], $_GET['ano'] );
for( $i = 0 ; $i < count( $arry0 ); $i++ ){
   $arry0[$i]->commit();
}
$numdays = date("d", mktime(0, 0, 0, $_GET['mes']+1, 0, $_GET['ano'] ) );
$sql = "select s.id, c.spanishTitle, c.title from slot s, slot_chapter sc, chapter c where s.channel = ".$_GET['channel_id']." and s.date >= '".$_GET['ano']."-".$_GET['mes']."-01"."' and s.date <= '".$_GET['ano']."-".$_GET['mes']."-".$numdays."' and s.id = sc.slot and sc.chapter=c.id";
$query = db_query( $sql );
while( $row = db_fetch_array( $query ) ){
	if( trim($row['spanishTitle']) == "" ){
		$title = $row['title'];
	}else{
		$title = $row['spanishTitle'];
	}
	if( !strstr( $title, "'" ) ){
		$sql1 = "update slot set title = '".$title."' where id = ".$row['id'];
	}else{
		$sql1 = 'update slot set title = "'.$title.'" where id = '.$row['id'];	
	}
	db_query( $sql1 );
}
echo "<strong>Fin</strong>";
?>