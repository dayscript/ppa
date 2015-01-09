<?
if(isset($_POST['query']))$query = $_POST['query'];
else if(isset($_GET['query']))$query = $_GET['query'];
else	$query = "";

if(isset($_POST['start']))$start = $_POST['start'];
else if(isset($_GET['start']))$start= $_GET['start'];
else	$start = 0;

if($query!= ""){
	$sql = "select * from day_imagefile where keywords like '%$query%' ";
	$sql .= " or source like '%$query%'";
	$sql .= " or name like '%$query%'";
	$sql .= " or description like '%$query%'";
	$results = db_query($sql);
	while($row = db_fetch_array($results)){
		$paths[] = "/" . $row['path'] . $row['realname'];
	}
} else {
	$paths = Array();
}

?>

<html>
<head>
<title>Image Repository</title>
<script language="javascript">
function hideImgProp(){
  parent.document.all.frmimage.style.height = '250px';
  document.all.imgProperties.style.visibility = 'hidden';
}
function loadImgProp(img, path){
  var tds = document.all.tblContainer.getElementsByTagName("TD");
  for (var i = 0; i < tds.length; ++i){
     tds[i].style.border = 'none';
     tds[i].style.backgroundColor = '#FFFFFF';
  }
  var td = img.parentElement;
  td.style.border = '1px solid #7B9BBA';
  td.style.backgroundColor = '#334488';
  document.frmProp.filepath.value = path;
  parent.document.all.frmimage.style.height = '430px';
  document.all.imgProperties.style.visibility = 'visible';
}
function insertImage(alt, width, height){
  var frm = document.frmProp;
  var path = frm.filepath.value;
  var width = frm.width.value;
  var height = frm.height.value;
  var border = frm.border.value;
  var alt = frm.alt.value;
  var align = frm.align.value;

  var img = '<img src="' + path + '"';
  if (alt)
   img += ' alt="' + alt + '" title="' + alt + '"';
  if (align)
   img += ' align="' + align + '"';
  if (border)
   img += ' border="' + border + '"';
  if (width)
    img += ' width="' + width + '"';
  if (height)
    img += ' height="' + height + '"';
  img += '>';
  frm.filepath.value = '';
  frm.width.value = '';
  frm.height.value = '';
  frm.border.value = '';
  frm.alt.value = '';
  frm.align.value = '';
  hideImgProp()

  if (parent.prevRangeType == 'Control'){
    var ne = parent.document.createElement(img);
    parent.prevRange(0).replaceNode(ne);
  }else{
     parent.prevRange.pasteHTML(img);
  }
  parent.popupToggle(parent.document.all.image);
  parent.document.all.ewe.focus();
}
</script>
<style>
img.image{
  cursor: pointer;
}
</style>
<link href="../../../include/estilos_text_explorer.css" type=text/css rel=stylesheet>
<link REL="StyleSheet" TYPE="text/css" HREF="popup.css">
</head>
<body style="background-color:#FFFFFF;overflow:hidden;" onClick="parent.popupClick(parent.document.all.image);" unselectable="on">
<table width="100%" align="center">
<tr><td><a href="image.php" onClick="parent.document.all.frmimage.style.height = '250px';">Atrás</a></td></tr>
</table>
 <form name="searchFrm" action="image.php" method="post">
	<input type="text" name="query" value="<?=$query?>" size="20">
	<input type="submit" name="enviar" value="Buscar">
	<input type="hidden" name="option" value="<?=$option?>">
 </form>
  <table  border=0 cellpadding="2" cellspacing="0" unselectable="on" width="100%">
  	<tr bgcolor="#CCCCCC">
	<? if( $start >= 4 ){ ?>
	<th align="left"> <a href="image.php?option=<?=$option?>&query=<?=$query?>&start=<?=$start-4?>">Anterior</a> </td>
	<? }?>
   	<? if( $start + 4 <  count( $paths ) ){ ?>
	<th align="right"> <a href="image.php?option=<?=$option?>&query=<?=$query?>&start=<?=$start+4?>">Siguiente</a> </td>
	<? }?>
	</tr>
</table>
  <table id="tblContainer" border=0 cellpadding="2" cellspacing="0" unselectable="on" width="100%">
    <tr height="110" unselectable="on" >
	<?
	for($i=$start; ($i < $start + 4) && ($i < count($paths));$i++){
		$size = getimagesize("../../..".$paths[$i]);
		if($size[0]>80 || $size[1]>100){
			if($size[0]>$size[1]) $size_text = "width=80";
			else $size_text = "height=100";
		}
		?><td style="border:1px solid #FFFFFF;" unselectable="on" width="80" valign="middle" align="center"><img src="<?=$paths[$i]?>" <?=$size_text?> onClick="loadImgProp(this,'<?=$paths[$i]?>');" class="image"></td><?		
	}
	?>
    </tr>
  </table>
  <table  border=0 cellpadding="2" cellspacing="0" unselectable="on" width="100%">
  	<tr bgcolor="#CCCCCC">
	<? if( $start >= 4 ){ ?>
	<th align="left"> <a href="image.php?option=<?=$option?>&query=<?=$query?>&start=<?=$start-4?>">Anterior</a> </td>
	<? }?>
   	<? if( $start + 4 <  count( $paths ) ){ ?>
	<th align="right"> <a href="image.php?option=<?=$option?>&query=<?=$query?>&start=<?=$start+4?>">Siguiente</a> </td>
	<? }?>
	</tr>
</table>
  <div id="imgProperties" style="visibility:hidden;">
  <form name="frmProp" onSubmit="return false;" unselectable="on">
  <input type="hidden" name="filepath">
  <input type="hidden" name="option" value="<?=$option?>">
  <table width="350" border="0" cellspacing="0" cellpadding="1" unselectable="on">
    <tr unselectable="on">
      <td width="350" class="columnHeader" style="font-weight: 600;" unselectable="on" colspan="3">Propiedades de Imagen - <a href="#" onClick="hideImgProp();return false;">ocultar</a> <hr size="1" noshad></td>
    </tr>
    <tr unselectable="on">
      <td width="15" unselectable="on">&nbsp;</td>
      <td width="135" class="columnHeader" unselectable="on">Ancho: </td>
      <td width="200" unselectable="on"><input type="text" size="2" maxlength="4" name="width">px</td>
    </tr>
    <tr unselectable="on">
      <td width="15" unselectable="on">&nbsp;</td>
      <td width="135" class="columnHeader" unselectable="on">Alto: </td>
      <td width="200" unselectable="on"><input type="text" size="2" maxlength="4" name="height">px</td>
    </tr>
    <tr unselectable="on">
      <td width="15" unselectable="on">&nbsp;</td>
      <td width="135" class="columnHeader" unselectable="on">Alineación: </td>
      <td width="200" unselectable="on">
	  	<select name="align">
			<option value="left">Izquierda</option>
			<option value="right">Derecha</option>
			<option value="center">Centro</option>
		</select>
	  </td>
    </tr>
    <tr unselectable="on">
      <td width="15" unselectable="on">&nbsp;</td>
      <td width="135" class="columnHeader" unselectable="on">Borde: </td>
      <td width="200" unselectable="on"><input type="text" size="2" maxlength="4" name="border" value="0"></td>
    </tr>
    <tr unselectable="on">
      <td width="15" unselectable="on">&nbsp;</td>
        <td width="135" class="columnHeader" unselectable="on">Texto Alternativo: 
		</td>
      <td width="200" unselectable="on"><input type="text" size="20" maxlength="100" name="alt"></td>
    </tr>
    <tr unselectable="on">
      <td width="15" unselectable="on">&nbsp;</td>
      <td width="100" class="columnHeader" unselectable="on"><input name="button" type="button" onClick="insertImage();" value="Insertar Imagen"></td>
        <td width="160" unselectable="on">&nbsp;</td>
    </tr>
  </table>
  </form>
  </div>
</body>
</html>