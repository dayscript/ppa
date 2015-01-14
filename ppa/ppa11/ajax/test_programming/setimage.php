<?php
	error_reporting( 0 );

	$url = ( isset( $_REQUEST['url'] ) && $_REQUEST['url'] ? $_REQUEST['url'] : '' );
	$chapter = ( isset( $_REQUEST['chapter'] ) && (int)$_REQUEST['chapter'] > 0 ? (int)$_REQUEST['chapter'] : 0 );


	if( !$url || !$chapter ) {
		echo '{"added":0, "error":"url/chapter"}';
		exit();
	}

	$url = preg_replace( '/https:/i', 'http:', $url );


	$i260 = file_get_contents( 'http://localhost/ppa/ppa11/class/timthumb.php?src=' . $url . '&w=260&h=260' );
	if( $i260 ) {
		if( !file_put_contents( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/chapter_images/260x260/' . $chapter . '.jpg', $i260 ) )    {
			echo '{"added":0, "error":"260x260"}';
			exit();
		}
		chmod( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/chapter_images/260x260/' . $chapter . '.jpg', 0777 );
	}

	$i70 = file_get_contents( 'http://localhost/ppa/ppa11/class/timthumb.php?src=' . $url . '&w=70&h=70' );
	if( $i70 ) {
		if( !file_put_contents( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/chapter_images/70x70/' . $chapter . '.jpg', $i70 ) )    {
			echo '{"added":0, "error":"70x70"}';
			exit();
		}
		chmod( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/chapter_images/70x70/' . $chapter . '.jpg', 0777 );
	}


	$mysqli = new mysqli('localhost', 'ppa', 'kfc3*9mn', 'ppa');
	if ($mysqli->connect_error) {
		die('Error de Conexion (' . $mysqli->connect_errno . ') '
			. $mysqli->connect_error);
	}

	$sql = "
		REPLACE INTO chapter_images ( id_chapter_image, id_chapter, id_chapter_image_type ) VALUES
		( NULL, " . $chapter . ", 1 ),
		( NULL, " . $chapter . ", 2 )";
	$result_sql = $mysqli->query( $sql );

	echo '{"added":1}';

