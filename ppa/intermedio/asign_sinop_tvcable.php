<?
$link1 = mysql_connect("64.116.130.2","dayscript","kfc3*9mn");
mysql_select_db("tvcable",$link1);
$link2 = mysql_connect("localhost", "intercable", "inter379");
mysql_select_db("intermedio",$link2);
$link3 = mysql_connect("localhost","intercable","inter379");
mysql_select_db("program", $link3);
    


$sql = "SELECT p.id, p.title, c.name, p.description FROM p_sinopsis s, p_program p, p_channel c WHERE p.channel = c.id AND s.program = p.id";
if( isset( $_GET['ano'] ) && isset( $_GET['mes'] ) ){
  $sql .= " AND s.ano = '".$_GET['ano']."' AND s.mes = '".$_GET['mes']."'";
}
$data = mysql_db_query("intermedio",$sql,$link2);
echo "Registros Obtenidos: " . mysql_num_rows($data) . "<br>";
$count = 1;

while ($row = mysql_fetch_array($data)){
  
  if( trim( $row['description'] ) == ""  ){
    //  $sql1 = 'SELECT s.actors, s.director, s.gender, s.description, c.name FROM p_program p, p_synopsis s, p_channel c WHERE p.title LIKE \'%'.addslashes( trim( $row['title'] ) ).'%\' AND p.id_sinopsis = s.id AND p.channel = c.id AND s.id != 1 AND c.name = \''.trim( $row['name'] ).'\' ';
    if( !strstr( $row['title'], "'") ){
  	  $sql1 = "SELECT actors, director, gender, description FROM p_synopsis WHERE title LIKE  '".trim( $row['title'] )."'";
      $data1 = mysql_db_query("tvcable", $sql1, $link1 );
	}else{
  	  $sql1 = 'SELECT actors, director, gender, description FROM p_synopsis WHERE title LIKE  "'.trim( $row['title'] ).'"';
      $data1 = mysql_db_query("tvcable", $sql1, $link1 );
	}
    if( !strstr( $row['title'], "'") ){	
      $sql3 = "SELECT gender, actors, director, description FROM movie WHERE spanishTitle like '".trim( $row['title'] )."' OR englishTitle like '".trim( $row['title'] )."'";
      $data3 = mysql_db_query("program", $sql3, $link3);
	}else{
      $sql3 = 'SELECT gender, actors, director, description FROM movie WHERE spanishTitle like "'.trim( $row['title'] ).'" OR englishTitle like "'.trim( $row['title'] ).'"';
      $data3 = mysql_db_query("program", $sql3, $link3);	
	}
    $description = " ";
    if( mysql_num_rows( $data3 ) > 0 ){
      echo "<b>".$count." PROGRAMACI�N</b><br>";
    }
    while( $row3 = mysql_fetch_array( $data3 ) ){
      $genero = $row3['gender'];
      $actores = $row3['actors'];
      $director = $row3['director'];
      if( !strstr( $description, $row3['description'] ) ){
	$description .= "<br><br>".$row3['description'];
      }
    }
	
    if( mysql_num_rows( $data3 ) == 0  ){
      echo "<b>".$count." TV CABLE</b><br>";
      while(  $row1 = mysql_fetch_array($data1) ){
	$genero = $row1['gender'];
	$actores = $row1['actors'];
	$director = $row1['director'];
        if( !strstr( $description, $row1['description'] ) ){
	  $description .= "<br><br>".$row1['description'];
	}
      }
    }
	
    echo "<b>Canal: </b>".$row['name']."<br>";
    echo "<b>T�tulo: </b>".$row['title']."<br>";
    echo "<b>G�nero: </b>".$genero."<br>";
    echo "<b>Actors: </b>".$actores."<br>";
    echo "<b>Director: </b>".$director."<br>";
    echo "<b>Sinopsis: </b>".$description."<br><br><br>";
  
    if( mysql_num_rows( $data1 ) > 0 || mysql_num_rows( $data3 ) > 0 ){
      $sql2 = 'update p_program set gender = "'.$genero.'", actor = "'.$actores.'", director = "'.$director.'", description = "'.addslashes( trim( $description ) ).'" where title ='."'".addslashes( $row['title'] )."'";
      mysql_db_query("intermedio", $sql2, $link2);
    }
    $count ++;
    $genero = " ";
    $actores = " ";
    $director = " ";
    $description = " ";
  }
}


?>