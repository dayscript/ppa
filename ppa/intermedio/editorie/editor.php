<?
?>
<HTML>
<head>
<title>Easy Web Editor (EWE)</title>
<script language="JScript">
   function onLoad(){
    var ewe = new EWE(null, '');
    ewe.addButton(new eweButton('ZoomOut', "alert('This is a custom button, easily added in EWE.');", 0));
    ewe.load(document.all.eweContainer, '<?=$contenido?>');
  }
  var editorPath = "../editorie/";
</script>
<script language="JScript" src="../editorie/source/ewe.js"></script>
</head>
<body bgcolor="#FFFFFF" onLoad="onLoad();" onKeyDown="dispatcher();">
<div id="eweContainer" unselectable="on"></div>
<form method="post" id="sampleSave">
  <input type="hidden" name="HTMLContent">
</form>
</body>
</html>