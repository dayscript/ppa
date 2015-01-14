<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ndaza
 * Date: 27/09/12
 * Time: 02:47 PM
 */

error_reporting( -1 );

$channel_copy = array();
$channel_copy['PPV1']  = '{"PP101": {"id":984, "price":1500},"PP102": {"id":985, "price":2000},"PP103": {"id":986, "price":2500}}';
$channel_copy['PPV7']  = '{"PP701": {"id":987, "price":1500},"PP702": {"id":988, "price":2000},"PP703": {"id":989, "price":2500}}';
$channel_copy['PPV8']  = '{"PP801": {"id":990, "price":1500},"PP802": {"id":991, "price":2000},"PP803": {"id":992, "price":2500}}';
$channel_copy['PPV9']  = '{"PP901": {"id":993, "price":1500},"PP902": {"id":994, "price":2000},"PP903": {"id":995, "price":2500}}';
$channel_copy['PPV10'] = '{"PP1001":{"id":996, "price":1500},"PP1002":{"id":997, "price":2000},"PP1003":{"id":998, "price":2500}}';
# PPV15?
$channel_copy['PMOV']  = '{"PP1501":{"id":1008,"price":1500},"PP1502":{"id":1009,"price":2000},"PP1503":{"id":1010,"price":2500}}';
# PPV16?
$channel_copy['2HOT']  = '{"PP1601":{"id":1002,"price":1500},"PP1602":{"id":1003,"price":2000},"PP1603":{"id":1004,"price":2500}}';
# PPV17?
$channel_copy['priv']  = '{"PP1701":{"id":1005,"price":1500},"PP1702":{"id":1006,"price":2000},"PP1703":{"id":1007,"price":2500}}';
# PPV18? es 11
$channel_copy['PPV11'] = '{"PP1801":{"id":999, "price":1500},"PP1802":{"id":1000,"price":2000},"PP1803":{"id":1001,"price":2500}}';
$channel_copy['PBOY']  = '{"PBOY1" :{"id":1011,"price":2200},"PBOY2": {"id":1012,"price":3000},"PBOY3": {"id":1013,"price":3600}}';
$channel_copy['VENU']  = '{"VENUS1":{"id":1014,"price":2200},"VENUS2":{"id":1015,"price":3000},"VENUS3":{"id":1016,"price":3600}}';

$copies = array( );

foreach( $channel_copy as $key => &$value ) {
	$value = json_decode( $value, true );
	foreach( $value as $key2 => &$value2 )
		$copies[$key2] = $value2;
}

$init_date = ( ( isset( $argv[0] ) && preg_match( "/^[0-9]{4}\\-[0-9]{2}\\-[0-9]{2}$/", $argv[0] ) ) ? strtotime( $argv[0] ) : ( ( isset( $argv[1] ) && preg_match( "/^[0-9]{4}\\-[0-9]{2}\\-[0-9]{2}$/", $argv[1] ) ? strtotime( $argv[1] ) : time( ) ) ) );

$date = date( "Y-m-d", strtotime( "-1 DAY", $init_date ) );

mysql_connect( "localhost", "root", "krxo4578" );
mysql_select_db( "ppa" );

$sql = "
DELETE
FROM slot
WHERE `date` > '" . $date . "'
AND channel IN (
	SELECT channel.id
	FROM channel, client_channel
	WHERE channel.id = client_channel.channel
	AND client_channel.client = 67
	AND channel.shortName IN ( '" . implode( "','", array_keys( $copies ) ) . "' )
)";
$result = mysql_query( $sql );

echo "\n\nLimpiando: " . mysql_affected_rows( ) . " filas eliminadas.\n\n";
exit();


foreach( $channel_copy as $channel => $copies ) {
	echo "\n\nCopiando: " . $channel . "";
	foreach( $copies as $copyName => $copy )    {
		echo "\n\nCopiando: " . $channel . " en " . $copyName;

        //echo $sql = " UPDATE channel SET name = ( SELECT name FROM ( SELECT * FROM channel WHERE shortname = '" . $channel . "' ) AS TMP  LIMIT 1 ) WHERE shortname = '" . $copyName . "'";
        //mysql_query( $sql );

		$sql = "
			SELECT *
			FROM slot
			WHERE `date` > '" . $date . "'
			AND channel = (
				SELECT channel.id
				FROM channel, client_channel
				WHERE channel.id = client_channel.channel
				AND client_channel.client = 67
				AND channel.shortName = '" . $channel . "'
				LIMIT 1
			)";

		$result = mysql_query( $sql );
		$cont = 0;
		while( ( $row = mysql_fetch_assoc( $result ) ) )    {
			$sql = "
				INSERT INTO slot( id, date, time, duration, channel, title, new_episode ) VALUES (
				NULL,
				'" . $row['date'] . "',
				'" . $row['time'] . "',
				'" . $row['duration'] . "',
				'" . $copy['id'] . "',
				'" . $row['title'] . "',
				'" . $row['new_episode'] . "' )";
			$result2 = mysql_query( $sql );
			$id = mysql_insert_id( );

			$sql = "
				SELECT *
				FROM slot_chapter
				WHERE slot = '" . $row['id'] . "'
				LIMIT 1";
			$result2 = mysql_query( $sql );
			$row2 = mysql_fetch_assoc( $result2 );

			if( $row2 && $id ) {
				$sql = "REPLACE INTO slot_chapter ( _order, slot, chapter ) VALUES ( 0, '" . $id . "', '" . $row2['chapter'] . "' );";
				mysql_query( $sql );
			}
			$cont ++;

		}
		echo " con " . $cont . " programas.";
	}
}




