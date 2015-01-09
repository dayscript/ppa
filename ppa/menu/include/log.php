<?
function toLog( $aUser, $aOp){
	$sql = "insert into userLog(id_usuario, operacion, fecha) values ($aUser, '$aOp', now())";
	db_query($sql);
}
?>