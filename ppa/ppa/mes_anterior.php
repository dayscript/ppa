<?
require_once("config.php");
session_start();
if( isset( $_SESSION['arry0'] ) ){
  $arry0 = $_SESSION['arry0'];
}
?>
<html>
<head>
<title>
PPA
</title>
</head>
<body>
<form name="chapters" method="post" action="mes_anterior.php">
<table>
<?
for( $i = 0; $i < count( $arry0 ); $i++ ){
?>
<tr>
<td>
<? 
 $chapters = $arry0[$i]->getChapters(); 
 $chapter = $chapters[0];
 $title = $chapter->getTitle();
?>
   <?=$title?>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chapter" value="<?=$chapter->getId()?>">
</td>
</tr>
<?
}
?>
<?
if( count( $arry0 ) == 0 ){
  echo "<tr><td><b>No existe programación del mes anterior.</b></td></tr>";
}
?>
</table>
</from>
</body>
</html>