<?
	if( isset($_GET['id']) ){
		$_POST['id']=$_GET['id'];
	} else {
		if(!isset($_POST['id'])){
			$_POST['id']="";
		}
	}
?>
<HTML>
<head>
<title>Editor 3.0</title>
<script language="JScript">
   function onLoad(){
    var ewe = new EWE(null, 'iexplore/sample.css');
 	<?
	if(isset($_POST['id'])){
		$nombre_directorio="../document_editor2/html";
		$ruta_archivo=$nombre_directorio."/" . $_POST['id'] . ".dat";
		if(isset($_POST['HTMLContent'])){
			$archivo=fopen($ruta_archivo,"w+");
			fputs($archivo,stripslashes($_POST['HTMLContent']));
			fclose($archivo);
		}else{
			$_POST['HTMLContent'] = implode("\n",file($ruta_archivo));
		}
		$parte=strtok($_POST['HTMLContent'],"\n");
		if($parte){
			while($parte){
				$cadena.=trim($parte)." ";
				$parte=strtok("\n");
			}
		} else {
			$cadena=$_POST['HTMLContent'];
		}
	}
	?>
    ewe.load(document.all.eweContainer,'<?= addslashes(stripslashes($cadena)) ?>');
  }
  function sampleSave(){
    document.forms['sampleSave'].HTMLContent.value = cleanup(document.all.ewe.innerHTML);
	document.forms['sampleSave'].submit();
    //document.forms['sampleSave'].submit();
  }
  var editorPath = "iexplore/";
</script>
<script language="JScript" src="iexplore/source/ewe.js"></script>
</head>

<body bgcolor="#FFFFFF" onLoad="onLoad();" onKeyDown="dispatcher();">
<div id="eweContainer" unselectable="on"></div>

<form action="editor.php" method="post" id="sampleSave">
  <input type="hidden" name="HTMLContent">
  <input type="hidden" name="id" value="<?=$_POST['id']?>">
</form>
</body>
</html>