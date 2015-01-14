<?
function client_channel()
{
	$sql = "SELECT id " .
		"FROM channel, client_channel " .
		"WHERE " .
		" channel.id = client_channel.channel AND " .
		" client_channel.client = " . ID_CLIENT . " ";
		"ORDER BY id";
	$result = db_query( $sql );
	
	while( $row = db_fetch_array( $result ) )
	{
		$id_channel[] = $row['id'];
	}
	return $id_channel;
}
?>