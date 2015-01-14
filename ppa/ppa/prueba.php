<?
require("config1.php");

$sql = "select * from chapter where serie > 0 and special > 0";
$query = db_query( $sql );
while( $row = db_fetch_array( $query ) ){
	echo $row['id']." - ".$row['title']."<br>";
	$sql1 = "select * from special where id = ".$row['special'];
	$query1 = db_query( $sql1 );
	$row1 = db_fetch_array( $query1 );
	$sql2 = "insert into chapter(title, spanishTitle, description, special ) values( '".$row1['title']."', '".$row1['spanishTitle']."', '".$row1['description']."', '".$row1['id']."'   )";	
	db_query( $sql2 );
	$sql3 = "update chapter set special = 0 where id = ".$row['id'];
	db_query( $sql3 );

}


$sql = "SELECT s.title, s.spanishTitle, s.description, c.id FROM chapter c, serie s WHERE c.title != s.title AND c.serie = s.id";
$query = db_query( $sql );
while( $row = db_fetch_array( $query ) ){
	$sql1 = "update chapter set title = '".$row['title']."', spanishTitle = '".$row['spanishTitle']."', description = '".$row['description']."' where id = ".$row['id'];		
	db_query( $sql1, true );
}


?>
