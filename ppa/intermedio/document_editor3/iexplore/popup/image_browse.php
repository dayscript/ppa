<?
if( isset( $_POST['send'] ) ){
	if( is_uploaded_file( $_FILES['image_file']['tmp_name'] ) ){
	   $filename =  $_FILES['image_file']['name'];
	   $ext = substr( $filename, strpos( $filename, "." ) );
	   $filename = md5( $filename );
	   $img_src = "../../../dimages/uploaded_images/".$filename.$ext;
	   copy( $_FILES['image_file']['tmp_name'], $img_src);
	}else{
	   $img_src = "";
	}
	$width = $_POST['width'];
	$height = $_POST['height'];
	$border_size = $_POST['border_size'];
	$alt_text = $_POST['alt_text'];
}
?>
<html>
<head>
<link href="../../../include/estilos_text_explorer.css" type=text/css rel=stylesheet>
<title>Choose Image File</title>
<script language="javascript">
function hideImgProp(){
  parent.document.all.frmimage.style.height = '250px';
  document.all.imgProperties1.style.visibility = 'visible';
   document.all.imgProperties.style.visibility = 'hidden';
}
function showImgProp(){
  parent.document.all.frmimage.style.height = '500px';
  document.all.imgProperties.style.visibility = 'visible';
  document.all.imgProperties1.style.visibility = 'hidden';
}

function insertImage(){
  var frm = document.frmProp;
  var path = '<?=$img_src?>';
  var alt = '<?=$alt_text?>';
  var border = '<?=$border_size?>';
  var width = '<?=$width?>';
  var height = '<?=$height?>';

  var img = '<img src="' + path + '"';
  if (alt)
   img += ' alt="' + alt + '" title="' + alt + '"';
  if (border)
   img += ' border="' + border + '"';
  if (width)
    img += ' width="' + width + '"';
  if (height)
    img += ' height="' + height + '"';
  img += '>';
  frm.width.value = '';
  frm.length.value = '';
  frm.border_size.value = '';
  frm.alt_text.value = '';
  if (parent.prevRangeType == 'Control'){
    var ne = parent.document.createElement(img);
    parent.prevRange(0).replaceNode(ne);
  }else{
     parent.prevRange.pasteHTML(img);
  }
  parent.document.all.frmimage.style.height = '250px';
  parent.popupToggle(parent.document.all.image);
  parent.document.all.ewe.focus();  
}


</script>
</head>
<body bgcolor="#FFFFFF" >
<table width="100%" align="center">
<tr><td><a href="image.php" onClick="parent.document.all.frmimage.style.height = '250px';">Atrás</a></td></tr>
</table>
<form name="frmProp" action="image_browse.php" method="post" enctype="multipart/form-data"  >
 <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
File: <input type="file" name="image_file" onChange="showImgProp()">
 <div id="imgProperties1" style="visibility:hidden;">
  <table width="350" border="0" cellspacing="0" cellpadding="1" unselectable="on">
   <tr unselectable="on">
   <td width="350" class="columnHeader" style="font-weight: 600;" unselectable="on" colspan="3">
   Image Properties - <a href="javascript:showImgProp();">Show</a> <hr size="1" noshad>
   </td>
  </tr>
 </table>
</div>
  <input type="hidden" name="filepath">
  <input type="hidden" name="option" value="<?=$option?>">
  <div id="imgProperties" style="visibility:hidden;">
  <table width="350" border="0" cellspacing="0" cellpadding="1" unselectable="on">
    <tr unselectable="on">
      <td width="350" class="columnHeader" style="font-weight: 600;" unselectable="on" colspan="3">Image Properties - <a href="javascript:hideImgProp();">hide</a> <hr size="1" noshad></td>
    </tr>
    <tr unselectable="on">
      <td width="15" unselectable="on">&nbsp;</td>
      <td width="135" class="columnHeader" unselectable="on">Width: </td>
      <td width="200" unselectable="on"><input type="text" size="2" maxlength="4" name="width">px</td>
    </tr>
    <tr unselectable="on">
      <td width="15" unselectable="on">&nbsp;</td>
      <td width="135" class="columnHeader" unselectable="on">Height: </td>
      <td width="200" unselectable="on"><input type="text" size="2" maxlength="4" name="height">px</td>
    </tr>
    <tr unselectable="on">
      <td width="15" unselectable="on">&nbsp;</td>
      <td width="135" class="columnHeader" unselectable="on">Border Size: </td>
      <td width="200" unselectable="on"><input type="text" size="2" maxlength="4" name="border_size" ></td>
    </tr>
    <tr unselectable="on">
      <td width="15" unselectable="on">&nbsp;</td>
      <td width="135" class="columnHeader" unselectable="on">Alternate Text: </td>
      <td width="200" unselectable="on"><input type="text" size="20" maxlength="100" name="alt_text"></td>
    </tr>
  </table>
  <input type="submit" name="send" value="Insert image">
  </div>
  </form>
  <?
  if( isset( $_POST['send'] ) ){
     echo '<script language="javascript"> insertImage(); </script>';
  }
?>

</body>

