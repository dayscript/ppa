<?
	require_once("class/Application.class.php");
	require_once("class/document_editor/DocElement.class.php");
	session_start();
	//$idtemp = $_GET['id']
	
	if ( isset($_POST['var']) )
		$_GET['var'] = $_POST['var'];

	if ( isset($_POST['id']) )
		$_GET['id'] = $_POST['id'];

	if ( isset($_POST['close']) )
		$_GET['close'] = $_POST['close'];

	if ( isset($_POST['update']) )
		$_GET['update'] = $_POST['update'];

	if ( isset($_POST['description']) )
		$_GET['description'] = $_POST['description'];

	if ( isset($_POST['author']) )
		$_GET['author'] = $_POST['author'];

	if ( isset($_POST['name']) )
		$_GET['name'] = $_POST['name'];
		
	if (isset($_POST['action']))
		$_GET['action'] = $_POST['action'];

	if ( isset($_GET['var']) ){
		eval ("\$docvar2" .  $_GET['id'] . " = \$_GET['var'];");
		eval ("\$docid2" .  $_GET['id'] . " = \$_GET['id'];");
		session_register('docid2' . $_GET['id']);
		session_register('docvar2' . $_GET['id']);
		eval("\$_SESSION['docid2" . $_GET['id'] . "'] = \$docid2" . $_GET['id'] . ";");
		eval("\$_SESSION['docvar2" . $_GET['id'] . "'] = \$docvar2" . $_GET['id'] . ";");
		eval ("\$doc2" . $_GET['id'] . " = \$_SESSION['".$_GET['var']."']->loadObject('".$_SESSION['docid2'.$_GET['id']]."');");
		session_register('doc2' . $_GET['id']);
		eval("\$_SESSION['doc2" . $_GET['id'] . "'] = \$doc2" . $_GET['id'] . ";");
	}

	if (isset($_GET['update']) || isset($_GET['close'])){
		$_GET['description'] = stripslashes($_GET['description']);
		$_GET['author'] = stripslashes($_GET['author']);
		//$_GET['name'] = stripslashes($_GET['name']);
		$_SESSION['doc2' . $_GET['id']]->setName($_GET['name']);
		$_SESSION['doc2' . $_GET['id']]->setDescription($_GET['description']);
		$_SESSION['doc2' . $_GET['id']]->setAuthor($_GET['author']);
		if(is_uploaded_file($_FILES['documento_html']['tmp_name'])){
			copy( $_FILES['documento_html']['tmp_name'], "document_editor2/html/".$_SESSION['doc2' . $_GET['id']]->getId().".dat" );
			$texto=new Text();
			$texto->setText( "@document_editor2/html/".$_SESSION['doc2' . $_GET['id']]->getId().".dat" );
			$_SESSION['doc2'.$_GET['id']]->elements->elements[0]=$texto;
		} else if ( count($_SESSION['doc2'.$_GET['id']]->elements->elements) == 0 ){
			fopen("document_editor2/html/".$_SESSION['doc2' . $_GET['id']]->getId().".dat", "w" );
			$texto=new Text();
			$texto->setText( "@document_editor2/html/".$_SESSION['doc2' . $_GET['id']]->getId().".dat" );
			$_SESSION['doc2'.$_GET['id']]->elements->elements[0]=$texto;
		}
		if( is_uploaded_file($_FILES['imagen']['tmp_name'])){
			copy( $_FILES['imagen']['tmp_name'], "dimages/".$_FILES['imagen']['name'] );
			$archivo = fopen("document_editor2/html/".$_SESSION['doc2' . $_GET['id']]->getId().".images","a");
			fputs($archivo,$_FILES['imagen']['name']."^");
			fclose($archivo);
		}
        $generar = false;
		$sql = "select folders from day_virtualobject v where v.page = 1 ";
        $query = db_query( $sql );
		while( $row = db_fetch_array( $query ) ){
            $temp = $row['folders'];
			$temp = explode( ";", $temp );
			for( $l = 0 ; $l < count( $temp ); $l++ )
			   $folders_home[] = new Fileref( $temp[$l] )  ;	
		}
		
		$file = new File( $_SESSION[ 'doc2' . $_GET['id'] ]->getId() );
		$file = $file->getParent();
		for( $m = 0 ; $m < count( $folders_home ); $m++ ){
		   $children = $folders_home[$m]->getChildren();
		   for( $n = 0 ; $n < count( $children ); $n++ ){
		        $children_id = $children[$n]->getId();
			   if( $file == $children_id ){
			     $generar = true;
			   }   
		   }
		 }
		 
		if(isset($_GET['close'])){
			eval ("\$_SESSION['".$_SESSION['docvar2' . $_GET['id']]."']->closeObject(".$_SESSION['docid2' . $_GET['id']].", \$_SESSION['doc2" . $_GET['id'] . "']);");
			session_unregister('doc2' . $_GET['id']);
			unset($_SESSION['doc2' . $_GET['id']]);
			session_unregister('docid2' . $_GET['id']);
			unset($_SESSION['docid2' . $_GET['id']]);
			session_unregister('docvar2' . $_GET['id']);
			unset($_SESSION['docvar2' . $_GET['id']]);
			if( $generar ){
		      if( !session_is_registered('site') ){
		         $site = new Site( 32 );
                 session_register('site');
                 $_SESSION['site'] = $site;
		      }
		      $texto = $_SESSION['site']->getHTMLpage( 1, "dayTemplates/images/" );
		      $in = fopen("index.txt", "w");
              fputs($in, $texto);
	          fclose($in);
	        }
			echo "<script>window.close();</script>";
		} else {
		if( $generar ){
		     if( !session_is_registered('site') ){
		         $site = new Site( 32 );
                 session_register('site');
                 $_SESSION['site'] = $site;
		      }
		      $texto = $_SESSION['site']->getHTMLpage( 1, "dayTemplates/images/" );
		      $in = fopen("index.txt", "w");
              fputs($in, $texto);
	          fclose($in);
	        }
			eval ("\$_SESSION['".$_SESSION['docvar2' . $_GET['id']]."']->updateObject(". $_GET['id'] .", \$_SESSION['doc2" . $_GET['id'] . "']);");
		}
	}
?>
<html>
<head>
<title>Editor de Documentos</title>
<script language="JavaScript">
<!--
<? if(count($_SESSION['doc2'.$_GET['id']]->elements->elements) == 1){ ?>
function openPreview(){
	window.open('document_editor2/preview.php?name=<?= $_SESSION['doc2'.$_GET['id']]->elements->elements[0]->getText() ?>','Preliminar','width=500,height=500,scrollbars=yes');
}
function openEdit(){
	window.open('document_editor3/editor.php?id=<?=$_SESSION['doc2' . $_GET['id']]->getId()?>','Editar','width=630,height=500,scrollbars=auto');
}
<? } ?>
function popup(pagina,ancho,alto,name){
	newWindow=open(pagina,name,'resizable=yes, status=no,scrollbars=no,statusbar=no,personalbar=no,locationbar=no,menubar=no,toolbar=no,outerHeight=' + alto + ',outerWidth=' + ancho);
//outerHeight=' + screen.availHeight + ',outerWidth=' + screen.availWidth
}

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
//-->
</script>
<link href="include/estilos_editores.css" type=text/css rel=stylesheet>
<link href="DayAdmin/estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('../images/editar_f2.gif')" onUnload="">
<table width="100" border="0" align="center" cellpadding="0" cellspacing="0">
	<form action="module.php" method="post" enctype="multipart/form-data">
  <tr>
    <td colspan="2">
	  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td><div align="left"><img src="../DayAdmin/images/interna_1_r1_c1.gif" width="682" height="68"></div></td>
        </tr>
        <tr>
          <td><div align="left"><img src="../DayAdmin/images/documento.gif" width="677" height="38"></div></td>
        </tr>
      </table>
	</td>
  </tr>
  <tr>
    <td colspan="2">
	  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="10">
		  <tr> 
			<td width="9%">&nbsp;</td>
			<td width="26%">&nbsp;</td>
			<td width="65%">&nbsp;</td>
		  </tr>
		  <tr> 
			<td width="9%">&nbsp;</td>
			<td width="26%" class="subtitulos">Nombre del Documento</td>
			<td width="65%"><input type="text" name="name" size="60" value="<?=$_SESSION['doc2' . $_GET['id']]->getName()?>"></td>
		  </tr>
		  <tr> 
			<td>&nbsp;</td>
			<td class="subtitulos">Resumen del Documento</td>
			<td><textarea name="description" cols="55" rows="3"><?=$_SESSION['doc2' . $_GET['id']]->getDescription()?></textarea></td>
		  </tr>
		  <tr> 
			<td>&nbsp;</td>
			<td class="subtitulos">Autor</td>
			<td><input name="author" type="text" value="<?=$_SESSION['doc2' . $_GET['id']]->getAuthor()?>" size="60"></td>
		  </tr>
		  <tr> 
			<td colspan="3"><div align="center"><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image7','','DayAdmin/images/editar_f2.gif',1)"><img src="DayAdmin/images/editar.gif" name="Image7" width="96" height="19" border="0" onClick="javascript:openEdit();"></a> 
			  </div></td>
		  </tr>
			  <tr align="left" valign="top"> 
				<td colspan="3"><table width="100%" border="0" cellspacing="8" cellpadding="0">
					<tr align="center" valign="top"> 
					  
				  <td width="42%" align="right">
					<input type="image" src="DayAdmin/images/actual.gif" name="update[]"></td>
					  <td width="16%">&nbsp;</td>
					  <td width="42%" align="left">
					  <input type="image" src="DayAdmin/images/cerrar.gif" name="close[]">
				  </td>
					</tr>
				  </table></td>
			  </tr>
		</table>
	</td>
  </tr>
  <tr>
    <td colspan="2">
	  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td bgcolor="#666666"><div align="center" class="tit1">Contenido Html</font></div></td>
        </tr>
      </table>
	</td>
  </tr>
  <tr align="center">
    <td colspan="2" >
	  <table width="100%" border="1" align="center" cellpadding="0" cellspacing="5" bordercolor="#000033" >
		  <tr> 
			<td align="center" bgcolor="#CCCCCC" height=45 > <p> Si desea alimentar 
				el contenido de este documento desde un archivo html, puede hacerlo 
				a continuaci&oacute;n :<br>
				<input name="documento_html" type="file" size="50" >
			  </p>
			<td> </tr>
		</table>
	</td>
  </tr>
  <tr>
    <td colspan="2">
	  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td align="center">
						<input type="hidden" name="command" value="document_editor3/">
						<input type="hidden" name="id" value="<?=$_GET['id']?>">
			</td>
        </tr>
      </table>
	</td>
  </tr>
	</form>
</table>
</html>
