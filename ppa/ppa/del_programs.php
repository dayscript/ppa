<?
session_start();

include( "class/Log.class.php" );
require_once("ppa/config.php");
require_once("ppa/header.php");

$dateerror = false;
$emptydates = false;
$sucess = false;
$dateformaterror = false;

if( isset( $_GET['fromdate'] ) && isset( $_GET['todate'] ) && isset( $_GET['id'] ) ){
	if( strtotime( $_GET['fromdate'] ) > strtotime( $_GET['todate'] ) ){
		$dateerror = true;
	}
	if( trim( $_GET['fromdate'] ) == "" || trim( $_GET['todate'] ) == "" ){
		$emptydates = true;
	}
	if( !ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $_GET['fromdate'] ) || !ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $_GET['todate'] ) ){
		$dateformaterror = true;
	}
	if( !$dateerror && !$emptydates && !$dateformat ){
		$sql = "SELECT id FROM slot WHERE channel = ".$_GET['id']." AND date >= '".$_GET['fromdate']."' AND date <= '".$_GET['todate']."' ";
		$query = db_query( $sql );
		$i = 0;
		$str = "(";
		while( $row = db_fetch_array( $query ) ){
			if( $i+1 == db_numrows( $query ) ){
	   		$str .= $row['id'];
	  		}else{
   	 		$str .= $row['id'].",";
  			}
	  		$i++;
		}
		$str .= ")";
		$sql = "DELETE FROM slot_chapter WHERE slot IN ".$str;
		db_query( $sql );
		$sql = "DELETE from slot WHERE channel = ".$_GET['id']." AND date >= '".$_GET['fromdate']."' AND date <= '".$_GET['todate']."'";
		db_query( $sql );		
		$sucess = true;
		
		$days = (strtotime($_GET['todate'])-strtotime($_GET['fromdate']))/(60*60*24);
		Log::write("/home/ppa/backup/listingUpdates.log", 
			$_SESSION['user'] . " d " . $_GET['id'] . " " . $_GET['fromdate'] . ":00:00 " . $days . " " . db_affected_rows() . " ");
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
<script>alert("La fecha inferior debe ser menor que la superior.")</script>
<?
}else{
	if( $emptydates ){
?>
<script>alert("Las fechas no pueden estar vacias.")</script>
<?	
	}else{
		if( $dateformaterror ){
?>
<script>alert("Error en el formato de las fechas.")</script>		
<?
		}else{
			if( $sucess ){
?>
<script>alert("Los programación fue borrada.")</script>		
<?			
			}		
		}
	}
}

$ch = new Channel( $_GET['id'] );
?>
<form name="f1" action="ppa.php" method="GET">
<table>
<tr>
<td align="center"><h3><?=$ch->getName()?></h3><div style="description" ><?=$ch->getDescription() ?></div><br>

</td>
</tr>
<tr>
<td>
<strong>Desde (aaaa-mm-dd): </strong>
</td>
<td>
<input name="fromdate" type="text" size="8">
</td>
</tr>
<tr>
<td>
<strong>Hasta (aaaa-mm-dd): </strong>
</td>
<td>
<input type="text" name="todate" size="8">
</td>
</tr>
<tr>
<td colspan="2" align="center">
<input type="hidden" name="delete_programs" value="1">
<input type="hidden" name="id" value="<?=$_GET['id']?>">
<input type="submit" name="submit" value="borrar">
</td>
</tr>
</table>
</form>
</body>
</html>