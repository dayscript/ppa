<?
if(isset($_FILES) && isset($_POST['date'])){
	echo "Archivo Alimentado : " . $_FILES['userfile']['name'] . "<br>";
	$in = file($_FILES['userfile']['tmp_name']);
	echo "Número de Lineas : " . (count($in)-1) . "<br>";
	$vars = explode("\t",$in[0]);
	echo "Número de Columnas : " . (count($vars)-1) . "<br>";
	for($i = 1; $i<count($in); $i++){
		$vars = explode("\t",$in[$i]);
		$hora = $vars[0];
		for($j = 1; $j<count($vars); $j++){
			if(trim($vars[$j]) != ""){
				if($j < 10) $dia = "0" . $j;
				else $dia = $j;
				$fecha = $_POST['date'] . "-" . $dia;
				if($hora < $_POST['hora_inicio']){
					$fecha = date("Y-m-d", strtotime($fecha)+ (60*60*24));
				}
				$title = trim($vars[$j]);
				$title = strtolower($title);
				$title = ucwords($title);
				$sql = "insert into p_program (channel, title, date, starthour) values ('" . $_POST['canal'] . "','" . addslashes($title) . "','$fecha','$hora')";
				//				echo $sql . "<br>";
				db_query($sql);
			}
		}
	}
}
?>