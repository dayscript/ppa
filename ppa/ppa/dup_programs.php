<?
session_start();

require_once("ppa/config.php");
require_once("ppa/header.php");

$dateerror = false;
$sucess = false;

if( isset( $_GET['frommonth'] ) && isset( $_GET['fromyear'] ) && isset( $_GET['tomonth'] ) && isset( $_GET['toyear'] ) ){
	if( strtotime( $_GET['fromyear']."-".$_GET['frommonth']."-01" ) > strtotime( $_GET['toyear']."-".$_GET['tomonth']."-01" )  ){
		$dateerror = true;	
	}else{
		if( strtotime( $_GET['fromyear']."-".$_GET['frommonth']."-01" ) == strtotime( $_GET['toyear']."-".$_GET['tomonth']."-01" )  ){
			$dateerror = true;	
		}	
	}
	if( !$dateerror ){
		$channel = new Channel( $_GET['id'] );
		$arry0 = $channel->createSlotsfromMonthYear( $_GET['frommonth'], $_GET['fromyear'], $_GET['tomonth'], $_GET['toyear'] );
		for( $i = 0 ; $i < count( $arry0 ); $i++ ){
		   $arry0[$i]->commit();
		}
		$numdays = date("d", mktime(0, 0, 0, $_GET['tomonth']+1, 0, $_GET['toyear'] ) );
		$sql = "select s.id, c.spanishTitle, c.title from slot s, slot_chapter sc, chapter c where s.channel = ".$_GET['id']." and s.date >= '".$_GET['toyear']."-".$_GET['tomonth']."-01"."' and s.date <= '".$_GET['toyear']."-".$_GET['tomonth']."-".$numdays."' and s.id = sc.slot and sc.chapter=c.id";
		$query = db_query( $sql );
		while( $row = db_fetch_array( $query ) ){
			if( trim($row['spanishTitle']) == "" ){
				$title = $row['title'];
			}else{
				$title = $row['spanishTitle'];
			}
			if( !strstr( $title, "'" ) ){
				$sql1 = "update slot set title = '".$title."' where id = ".$row['id'];
			}else{
				$sql1 = 'update slot set title = "'.$title.'" where id = '.$row['id'];	
			}
			db_query( $sql1 );
			$sucess = true;
		}		
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PPA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
body,table {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #444444;
}
</style>
</head>
<body>
<?
if( $dateerror ){
?>
<script>alert("La fecha inferior debe ser igual que la superior.")</script>
<?
}else{
	if( $sucess ){
?>
<script>alert("La programación fue dumplicada.")</script>
<?	
	}
}
?>
<form name="f1" action="ppa.php" method="GET">
<table>
<tr>
<td>&nbsp;

</td>
</tr>
<tr>
<td>
<strong>De: </strong>
</td>
<td>
<select name="frommonth" style="width:70px" >
<option value="10">Octubre</option>
<option value="01">Enero</option>
<option value="02">Febrero</option>
<option value="03">Marzo</option>
<option value="04">Abril</option>
<option value="05">Mayo</option>
<option value="06">Junio</option>
<option value="07">Julio</option>
<option value="08">Agosto</option>
<option value="09">Septiembre</option>
<option value="10">Octubre</option>
<option value="11">Noviembre</option>
<option value="12">Diciembre</option>
</select>
</td>
<td>
<select name="fromyear" style="width:50px">
<option value="2010">2010</option>
<option value="2007">2007</option>
<option value="2008">2008</option>
<option value="2009">2009</option>
</select>
</td>
</tr>
<tr>
<td>
<strong>A: </strong>
</td>
<td>
<select name="tomonth" style="width:70px" >
<option value="11">Noviembre</option>
<option value="01">Enero</option>
<option value="02">Febrero</option>
<option value="03">Marzo</option>
<option value="04">Abril</option>
<option value="05">Mayo</option>
<option value="06">Junio</option>
<option value="07">Julio</option>
<option value="08">Agosto</option>
<option value="09">Septiembre</option>
<option value="10">Octubre</option>
<option value="11">Noviembre</option>
<option value="12">Diciembre</option>
</select>
</td>
<td>
<select name="toyear" style="width:50px">
<option value="2010">2010</option>
<option value="2005">2005</option>
<option value="2006">2006</option>
<option value="2007">2007</option>
<option value="2008">2008</option>
<option value="2009">2009</option>
</select>
</td>
</tr>
<tr>
<td colspan="2" align="center">
<input type="hidden" name="dup_programs" value="1">
<input type="hidden" name="id" value="<?=$_GET['id']?>">
<input type="submit" name="submit" value="duplicar">
</td>
</tr>
</table>
</form>
</body>
</html>
