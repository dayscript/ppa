<? 
require_once("include/functions.inc.php");
if( isset($_GET['date'] ))
	$date = $_GET['date'];
else
	$date = date("Y-m-d");
?>
	<TABLE class="cat_table" width="100%">
      <TR>
        <TD class="cat_td_line_1" align="right"><div align="left"><a href="<?=$_GET['url']?>?date=<?=date("Y-m-d", strtotime( $date ) - (60*60*24) )?>&channel=<?=($_GET['channel'])?>" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('anterior1','','images/anterior_over.jpg',1)"><img src="images/anterior.jpg" name="anterior1" border="0"> </a></div></TD>
       <TD class="cat_td_line_1" align="left"><div align="right"><a href="<?=$_GET['url']?>?date=<?=date("Y-m-d", strtotime( $date ) + (60*60*24) )?>&channel=<?=($_GET['channel'])?>" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('siguiente1','','images/siguiente_over.jpg',1)"><img src="images/siguiente.jpg" name="siguiente1" border="0"></a></div></TD>
      </TR>
      <TR class="cat_table_title">
<?
$sql = "SELECT * ".
       "FROM channel ".
       "WHERE id = ". $_GET['channel'];

$row = db_fetch_array( db_query( $sql ) );

?>
        <TD background="images/TipoProg.jpg" width="100" ><?=$row['name']?></TD>
        <TD background="images/Hora.jpg" align="center"><?=$date?></TD>
      </TR>
<?

$sql = "SELECT * ".
       "FROM slot ".
       "WHERE channel = '". $_GET['channel'] ."' " .
       "AND date = '" . $date . "' " .
       "ORDER BY time";

$result = db_query( $sql );

while( $row = db_fetch_array($result) )
{
?>
      <TR height="20">
        <TD align="center" class="cat_td_title_1" ><?=to12H( $row['time'] )?></TD>
        <TD align="center" class="cat_td_border" ><a href="#" onClick="PopUp('<?=str_replace("'", "\'", $row['title'])?>')" class="cat_td_line_1"><?=$row['title']?></a></TD>
      </TR>
<? } ?>
    </TABLE>
