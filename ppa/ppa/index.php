<?
require_once("config.php");
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PPA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body,table {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #444444;
}
input, select, textarea, {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #444444;
	border: 1px solid #000000;
	background-color: #DDEEFF;
}
.large {
	width: 300;
}
-->
</style>
</head>

<body>
<?
//require_once("ppa/header.php"); 
$ppa = new PPA(1);
?>
<table width="600" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <th scope="col" bgcolor="#F0F0F0">Cabeceras Existentes </th>
  </tr>
	<?
	$clients = $ppa->getClients();
	for($i=0; $i<count($clients); $i++){
		?><tr>
			<td bgcolor="#DDEEFF"><?=$clients[$i]->getName()?></td>
		</tr><?
	}
	?>
</table>
</body>
</html>
