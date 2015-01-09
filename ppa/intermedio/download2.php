<?
$link2 = mysql_connect("dayscript.com","dayscript","kfc3*9mn");
mysql_select_db("tvcable",$link2);
if($_POST['fecha']== $_POST['fecha2']){
	$sql = "select * from p_program where channel=" . $_POST['canal'] . " and date like '" . $_POST['fecha'] . "%'";
	//echo $sql;
	$data = mysql_db_query("tvcable",$sql,$link2);
	echo "Registros Obtenidos: " . mysql_num_rows($data) . "<br>";
	while ($row = mysql_fetch_array($data)){
		$sql = "insert into p_program (channel, title, date, starthour) values ('" . $_POST['canal2'] . "','" . addslashes($row['title']) . "','". $row['date'] . "','" . $row['starthour'] . "')";
		db_query($sql);
	}
} else {
	$inidate = $_POST['fecha'] . "-15";
	for($i = 0; $i<7; $i++){
		$dia = date("w",strtotime($inidate)+($i*60*60*24));
		$date = date("Y-m-d",strtotime($inidate)+($i*60*60*24));
		$sql[$dia] = "select * from p_program where channel =" . $_POST['canal'] . " and date = '" . $date . "'";
	}
	$maxdias = date("t",strtotime($_POST['fecha2']));
	for($i=0;$i<$maxdias;$i++){
		$dia = date("w",strtotime($_POST['fecha2'] . "-01")+($i*60*60*24));
		$date = date("Y-m-d",strtotime($_POST['fecha2'] . "-01")+($i*60*60*24));
//		echo $sql[$dia] . "<br>";
		$data = mysql_db_query("tvcable",$sql[$dia],$link2);
		echo "Registros Obtenidos ($date): " . mysql_num_rows($data) . "<br>";
		while ($row = mysql_fetch_array($data)){
			$sql2 = "insert into p_program (channel, title, date, starthour) values ('" . $_POST['canal2'] . "','" . addslashes($row['title']) . "','". $date . "','" . $row['starthour'] . "')";
			db_query($sql2);
		}
	}
}

?>