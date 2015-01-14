<?
$sql = "SELECT movie, serie, special from chapter WHERE id = " . $_GET["chapter"];
$row = db_fetch_array( db_query($sql) );
if( $row["movie"] > 0 )
	header("Location: ppa.php?edit_movie=1&cit=" . $_GET["cit"] . "&id=" . $row["movie"] );
else if( $row["serie"] > 0 )
	header("Location: ppa.php?edit_series=1&cit=" . $_GET["cit"] . "&id=" . $row["serie"] );
else if( $row["special"] > 0 )
	header("Location: ppa.php?edit_special=1&cit=" . $_GET["cit"] . "&id=" . $row["special"] );
?>