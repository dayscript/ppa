<?
require_once("config.php");
$sql = "select * from chapter";
$query = db_query( $sql );
/*Series*/
/*
while( $row = db_fetch_array( $query ) ){
  if( $row['serie'] > 0 ){
    $sql1 = "select * from serie where id =".$row['serie'];
    $query1 = db_query( $sql1 );
    if( db_numrows( $query1 ) == 0 ){
      $sql2 = "INSERT INTO `serie` ( `id` , `title` , `spanishTitle` , `gender` , `rated` , `year` , `starring` , `description` , `ppa` , `season` )VALUES (";

      if( strstr( $row['serie'], "'" ) ){
	$sql2 .= '"'.$row['serie'].'", ';
      }else{
	$sql2 .= "'".$row['serie']."', ";
      }

      if( strstr( $row['title'], "'" ) ){
	$sql2 .= '"'.$row['title'].'", ';
      }else{
	$sql2 .= "'".$row['title']."', ";
      }

      if( strstr( $row['spanishTitle'], "'" ) ){
	$sql2 .= '"'.$row['spanishTitle'].'", ';
      }else{
	$sql2 .= "'".$row['spanishTitle']."', ";
      }

      $sql2 .= "'Acción', 'TVY', '2000', NULL, ";

      if( strstr( $row['description'], "'" ) ){
	$sql2 .= '"'.$row['description'].'", ';
      }else{
	$sql2 .= "'".$row['description']."', ";
      }

      $sql2 .= "'1', '')";
      db_query($sql2);
      echo $row['title']."<br>";
      

    }
  }
}
*/
/*Movies*/
/*
while( $row = db_fetch_array( $query ) ){
  if( $row['movie'] > 0 ){
    $sql1 = "select * from movie where id =".$row['movie'];
    $query1 = db_query( $sql1 );
    if( db_numrows( $query1 ) == 0 ){
      
      $sql2 = "INSERT INTO `movie` ( `id` , `title` , `spanishTitle` , `englishTitle`,`gender` , `rated` , `tvRated` , `year` , `duration` , `actors` , `director`, `description`, `country`,  `language`,  `ppa`  )VALUES (";

      if( strstr( $row['movie'], "'" ) ){
	$sql2 .= '"'.$row['movie'].'", ';
      }else{
	$sql2 .= "'".$row['movie']."', ";
      }

      if( strstr( $row['title'], "'" ) ){
	$sql2 .= '"'.$row['title'].'", ';
      }else{
	$sql2 .= "'".$row['title']."', ";
      }

      if( strstr( $row['spanishTitle'], "'" ) ){
	$sql2 .= '"'.$row['spanishTitle'].'", ';
      }else{
	$sql2 .= "'".$row['spanishTitle']."', ";
      }
      
      if( strstr( $row['englishTitle'], "'" ) ){
	$sql2 .= '"'.$row['englishTitle'].'", ';
      }else{
	$sql2 .= "'".$row['englishTitle']."', ";
      }

      $sql2 .= "'Acción', '','', '2000', '', '', '', ";

      if( strstr( $row['description'], "'" ) ){
	$sql2 .= '"'.$row['description'].'", ';
      }else{
	$sql2 .= "'".$row['description']."', ";
      }

      $sql2 .= "'', '', '1')";
      db_query($sql2);
      
      echo $row['title']."<br>";
      

    }
  }
}
*/
/*Specials*/

while( $row = db_fetch_array( $query ) ){
  if( $row['special'] > 0 ){
    $sql1 = "select * from special where id =".$row['special'];
    $query1 = db_query( $sql1 );
    if( db_numrows( $query1 ) == 0 ){
      
      $sql2 = "INSERT INTO `special` ( `id` , `title` , `spanishTitle` ,`gender` , `rated` , `starring`, `description`, `ppa`  )VALUES (";

      if( strstr( $row['special'], "'" ) ){
	$sql2 .= '"'.$row['special'].'", ';
      }else{
	$sql2 .= "'".$row['special']."', ";
      }

      if( strstr( $row['title'], "'" ) ){
	$sql2 .= '"'.$row['title'].'", ';
      }else{
	$sql2 .= "'".$row['title']."', ";
      }

      if( strstr( $row['spanishTitle'], "'" ) ){
	$sql2 .= '"'.$row['spanishTitle'].'", ';
      }else{
	$sql2 .= "'".$row['spanishTitle']."', ";
      }
      

      $sql2 .= "'Acción','TVY', '', ";

      if( strstr( $row['description'], "'" ) ){
	$sql2 .= '"'.$row['description'].'", ';
      }else{
	$sql2 .= "'".$row['description']."', ";
      }

      $sql2 .= " '1')";
      db_query($sql2);
      
      echo $row['title']."<br>";
      

    }
  }
}

?>