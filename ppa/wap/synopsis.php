<?
include( "util/client_channel.php" );

$sql = "SELECT slot.title, chapter.description, channel.name, slot.date, slot.time FROM slot " .
  "LEFT JOIN slot_chapter " .
  "ON slot.id = slot_chapter.slot " .
  "LEFT JOIN chapter ON slot_chapter.chapter = chapter.id " .
  "LEFT JOIN channel ON slot.channel = channel.id " .
  "WHERE ( ( date >= '" . date("Y-m-d", NOW) . "' AND time > '" . date("H:i:00", NOW) . "') OR " .
  " date >= '" . date("Y-m-d", NOW + 60*60*24) . "' ) AND " .
  "slot.title = '" . $_SESSION['title'][$_GET['t']] . "' AND " .
  "slot.channel IN(" . implode(",", client_channel() ) . ") " .
  "ORDER BY slot.date ASC " .
  "LIMIT 0, 1";

$row   = db_fetch_array( db_query ( $sql ) );
$date  = date("m-d h:i a", ( strtotime( $row['date'] . " " . $row['time'] ) + ( GMT*60*60 ) ) );
$today = date("m-d", NOW + ( GMT*60*60 ) );
$date = ( substr($date,0,5) == $today) ? "Hoy " . substr($date, -8) : $date;
?>
<p>
	<b><?=$row['title']?></b>. <?=$date?>. <?=$row['name']?><br />
	<?=remove_special_chars( substr( $row['description'], 0, 200) ) . ( strlen( $row['description'] ) < 200 ? "" : "..." )?>
</p>