<?
include('../ppa/include/config.inc.php');
include('../include/db.inc.php');

define("GRID", 3);
define("CLIENT", 64);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Costavision</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" src="js/calendar.js"></script>
<script type="text/javascript" src="js/lang/calendar-es.js"></script>
<script type="text/javascript" src="js/calendar-setup.js"></script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function changeSearch(name, val)
{
	document.location.href = "?" + name + "=" + val
}

function PopUp( title )
{
	window.open('prgproperties.php?title=' + title, 'popup', 'width=350, height=320, scrollbars=yes')
}
//-->
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="calendar-blue.css" rel="stylesheet" type="text/css">

<body onLoad="MM_preloadImages('images/button_over.jpg')">
<form method="post" name="f1" >
<table width="580" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td><img src="images/Titulo.jpg" alt="Consulta de Programaci&oacute;n" width="580" height="29"></td>
</tr>
<tr>
	<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/MenuSup.jpg">
		<tr valign="middle">
			<td width="91" class="canal">&nbsp;&nbsp;Por Categoria: 
			</td>
			<td width="111" class="canal"><select name="select" style="width:100px;" onChange="changeSearch('category', this.value)">
              <option>- Categorías -</option>
              <? 
$sql = "SELECT distinct(_group) FROM `client_channel` WHERE  client='" . CLIENT . "'";
$result = db_query($sql);

while( $row = db_fetch_array($result) )
{
?>
              <option value="<?=$row['_group']?>">
              <?=ucwords($row['_group'])?>
              </option>
              <? } ?>
            </select></td>
			<td width="207" class="canal">&nbsp;&nbsp;Por Canal: 
				<select style="width:130px;" onChange="changeSearch('channel', this.value)" >
					<option>- Canales -</option>
<?
$sql = "SELECT channel.name, channel.id ".
       "FROM channel, client, client_channel ".
       "WHERE client.id = client_channel.client ". 
       "AND channel.id=client_channel.channel ".
       "AND client.id = ". CLIENT ." ".
       "ORDER BY channel.name";

$result = db_query( $sql );

while( $row = db_fetch_array($result) )
{
?>
					<option value="<?=$row['id']?>"><?=$row['name']?></option>
<? } ?>
				</select>
			</td>
		  </tr>
		</table>
	</td>
</tr>
<tr>
	<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/MenuInf.jpg">
		<tr>
			<td width="132" class="canal">
				&nbsp;&nbsp;Por Calendario: 
			</td>
			<td width="303" class="canal"><div align="center" style="width:250px; cursor: default; border-color:000000; border-style:solid; border-width:thin; background-color: rgb(255, 255, 255);" onmouseover="this.style.backgroundColor='#ffffff';" onmouseout="this.style.backgroundColor='#ffffff';" id="show_date">Fecha</div>
              <input id="date" name="date"  value="" size="25" type="hidden">
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "date",
        daFormat       :    "%A, %B %d, %Y %I:%M %p",
        ifFormat       :    "%m/%d/%Y %H:%M",
        displayArea    :    "show_date", 
        showsTime      :    true,
        timeFormat     :    "12",
        singleClick    :    false
    });
</script></td>
			<td width="155" class="canal"><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('BuscarActor1','','images/button_over.jpg',1)" onClick="changeSearch('date', document.f1.date.value)"><img src="images/button.jpg" alt="Buscar" name="BuscarActor1" width="51" height="15" border="0" align="absmiddle" id="BuscarActor1"></a></td>
		  </tr>
		</table>
	    <table width="100%" border="0" cellpadding="0" cellspacing="0" background="images/MenuInf.jpg">
          <tr>
            <td width="44%"><span class="canal">&nbsp;&nbsp;Por T&iacute;tulo:
                <input name="title" type="text" style="width:110px;">
                <a href="#" onClick="changeSearch('title', document.f1.title.value)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Buscar','','images/button_over.jpg',1)"><img src="images/button.jpg" alt="Buscar" name="Buscar" width="51" height="15" border="0" align="absmiddle"></a> </span></td>
            <td width="56%"><span class="canal">&nbsp;Por Actor:
                <input name="actor" type="text" style="width:110px;">
                <a href="#" onClick="changeSearch('actor', document.f1.actor.value)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('BuscarActor','','images/button_over.jpg',1)"><img src="images/button.jpg" alt="Buscar" name="BuscarActor" width="51" height="15" border="0" align="absmiddle"></a></span></td>
          </tr>
        </table></td>
</tr>
<tr>
	<td height="10"></td>
</tr>
<tr>
	<td>
<? 
	if( isset($_GET['category'] ) )
		include("category.php");
	elseif( isset( $_GET['channel'] ) )
		include("channel.php");
	elseif( isset( $_GET['date'] ) )
		include("date.php");
	elseif( isset( $_GET['title'] ) && $_GET['title'] !="" )
		include("title.php");
	elseif( isset( $_GET['actor'] ) && $_GET['actor'] !="" )
		include("actor.php");
	else
		include("date.php");
?>
	</td>
</tr>
</table>
</form>
<table width="580" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td align="center"><a target="_blank" href="http://www.dayscript.com"><img src="images/logo_dayscript_big.jpg" border="0"></a></td>
  </tr>
</table>
</body>
</html>
