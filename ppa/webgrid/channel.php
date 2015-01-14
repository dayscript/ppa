<?
include( "ppa/class/Channel.class.php" );
	$time = isset( $_GET['date'] ) ? strtotime( $_GET['date'] ) : strtotime( date("Y-m-d") );
	
	$date = date("Y-m-d", $time + $_GET['offset']*DAY);

	$sql = "SELECT channel.name, channel.logo, slot.* " .
		"FROM channel, slot " .
		"WHERE channel.id =  slot.channel AND " .
		"channel.id = " . $_GET['channel'] . " AND " .
		"slot.date = '" . $date . "'";
	$channel = new Channel ( $_GET['channel'] );
		
	$result = db_query( $sql );
	$output = '<table class="cat_table">';
	$output .= '<tr class="cat_table_title"><td colspan="2">' . ( ( $channel->getLogo() != "" ) ? ( '<img src="http://200.71.33.249/~ppa/ppa/' .  $channel->getLogo() . '">' ) : ( $channel->getName() ) ) . '</td></tr>';
	$output .= '<tr class="cat_table_title"><td width="70">Hora</td><td>' . $date . '</td></tr>';
	$line=1;
	while( $row = db_fetch_array( $result ) )
	{
		$classNameTitle = ( ($line%2) == 0 ? 'cat_td_title_1' : 'cat_td_title_2' );
		$classNameLine  = ( ($line%2) == 0 ? 'cat_td_line_1' : 'cat_td_line_2' );
//		$output .= '<tr><td class="' . $classNameTitle . '">' . $row['time'] . '</td><td class="' . $classNameLine . '">' . $row['title'] . '</td></tr>';
		$output .= '<tr><td class="' . $classNameTitle . '">' . $row['time'] . '</td><td class="' . $classNameLine . '"><a href="#;" class="link" onmouseup="GridApp.synopsisPopUp(' . $row['id'] . ', event);">' . $row['title'] . '</a></td></tr>';
		$line++;
	}
	$output .= '</table>';
	echo $output;
?>