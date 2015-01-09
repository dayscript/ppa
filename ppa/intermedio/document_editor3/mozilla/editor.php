<?
	session_start();
	if( isset($_GET['id']) ){
		$_POST['id']=$_GET['id'];
	} else {
		if(!isset($_POST['id'])){
			$_POST['id']="";
		}
	}
?>
<html>
<head>
<title>Editor</title>
<style>
	#toolbar{
		margin: 0;
		padding: 0;
		width: 100%;
		background: buttonface;
		border-top: 1px solid buttonhighlight;
		border-left: 1px solid buttonhighlight;
		border-bottom: 1px solid buttonshadow;
		border-right: 1px solid buttonshadow;
		text-align:left;
	}
	.button{
		background: buttonface;
		border: 1px solid buttonface;
		margin: 1;
	}
	.raised{
		border-top: 1px solid buttonhighlight;
		border-left: 1px solid buttonhighlight;
		border-bottom: 1px solid buttonshadow;
		border-right: 1px solid buttonshadow;
		background: buttonface;
		margin: 1;
	}
	.pressed{
		border-top: 1px solid buttonshadow;
		border-left: 1px solid buttonshadow;
		border-bottom: 1px solid buttonhighlight;
		border-right: 1px solid buttonhighlight;
		background: buttonface;
		margin: 1;
	}
	.skin0{
		position:absolute;
		width:120px;
		background: buttonface;
		font-family:Verdana;
		line-height:2 px;
		cursor:hand;
		font-size:11px;
		z-index:100;
		border-top: 1px solid buttonhighlight;
		border-left: 1px solid buttonhighlight;
		border-bottom: 1px solid buttonshadow;
		border-right: 1px solid buttonshadow;
		visibility:hidden;
		margin: 3px;
		padding: 3px;
	}
</style>
<script language="javascript">

var menu_abierto=false;

function enviarFormulario(){
	document.forms[0].html.value=document.getElementById("edit").contentDocument.body.innerHTML;
	document.forms[0].submit();
}

function mouseover(el) {
  el.className = "raised";
}

function mouseout(el) {
  el.className = "button";
}

function mousedown(el) {
  el.className = "pressed";
}

function mouseup(el) {
  el.className = "raised";
}


function enableEdit(){
  document.getElementById("edit").contentDocument.designMode="on";
  		<?if(isset($_POST['id'])){
			$nombre_directorio="../document_editor2/html";
			$ruta_archivo=$nombre_directorio."/" . $_POST['id'] . ".dat";
			if(isset($_POST['html'])){
				$archivo=fopen($ruta_archivo,"w+");
				fputs($archivo,stripslashes($_POST['html']));
				fclose($archivo);
			}else{
				$_POST['html'] = implode("\n",file($ruta_archivo));
			}
			$parte=strtok($_POST['html'],"\n");
			if($parte)
				while($parte){
					$cadena.=trim($parte)." ";
					$parte=strtok("\n");
				}
				else
					$cadena=$_POST['html'];
			session_register('ruta_archivo');
			?>
			document.getElementById("edit").contentDocument.body.innerHTML="<?= addslashes(stripslashes($cadena)) ?>";
			<?
		}?>
}

function cambiarEstilo(estilo){
  editableDocument = document.getElementById("edit").contentDocument;
  editableDocument.execCommand(estilo, false, null);
}
function url(){
  var ventana=open('/document_editor3/mozilla/link.php','','width=400,height=200,scroll=no');
}
function image( text ){
  var ventana=open('/document_editor3/mozilla/imagen.php','','width=400,height=300,scroll=no');
}
function source(){
  alert( document.getElementById("edit").contentDocument.body.innerHTML );

}
function tabla(){
  var ventana=open('/document_editor3/mozilla/tabla.php','','width=450,height=300,scroll=no');
}

function mostrarMenu(numero_menu){
	switch(numero_menu){
		case 0:
			var menu_archivo=document.getElementById("menu_archivo");
			var barra_herramientas=document.getElementById("barra_herramientas");
			var archivo=document.getElementById("archivo");
			menu_archivo.style.left=5;
			menu_archivo.style.top=23;
			menu_archivo.style.visibility="visible";
			break;
	}
	menu_abierto=true;
}

function cambiarFondo(div){
	div.style.background= '#000099';
	div.style.color= '#FFFFFF';
}

function retornarFondo(div){
	div.style.background= '#e6e6e6';
	div.style.color= '#000000';
}

function ocultarMenu(){

	if(!menu_abierto){
		var menu=document.getElementById("menu_archivo");
		menu.style.visibility="hidden";
	}else
		menu_abierto=false;
}

function realizarEvento(accion){
	switch(accion){
		case 'nuevo':
			document.getElementById("edit").src="/document_editor3/mozilla/example.html";
			break;
		case 'abrir':
			window.open('/document_editor3/mozilla/archivos/index.php','','width=400,height=400,scroll=no');
			break;
		case 'enviar':
			window.open('/document_editor3/mozilla/enviar/index.php','','width=400,height=200,scroll=no');
			break;
		case 'guardar':
			enviarFormulario();
			break;
		case 'cerrar':
			document.location="editor.php?cerrar=";
			break;
		case 'imprimir':
			window.print();
			break;
		case 'salir':
			window.close();
			break;
	}
}

document.onclick=ocultarMenu;

</script>

</head>
<body onLoad="javascript:enableEdit();" >

  <table width="100%" border="0" align="center"  cellpadding="0" cellspacing="0" id="barra_herramientas">
  	<tr>
		<td style="background: buttonface;" >
			<img class="button"
 				onmouseover="mouseover(this);"
 				onmouseout="mouseout(this);"
 				onmousedown="mousedown(this);"
 				onclick="mostrarMenu(0);"
 				src="mozilla/images/archivo.bmp"
 				id="archivo"
 				align="middle"
 			>
		</td>
	</tr>
  </table>
  <div id="toolbar"> <img src="images/linea.gif" align="middle" > <img class="button"
 	onmouseover="mouseover(this);"
 	onmouseout="mouseout(this);"
 	onmousedown="mousedown(this);"
 	onmouseup="mouseup(this);"
 	onclick="cambiarEstilo('Bold');"
 	src="mozilla/images/bold.gif"
 	width="16" height="16"
 	align="middle"
 	> <img class="button"
 	onmouseover="mouseover(this);"
 	onmouseout="mouseout(this);"
 	onmousedown="mousedown(this);"
 	onmouseup="mouseup(this);"
 	onclick="cambiarEstilo('italic');"
 	src="mozilla/images/italic.gif"
 	width="16" height="16"
 	align="middle"
 	> <img class="button"
 	onmouseover="mouseover(this);"
 	onmouseout="mouseout(this);"
 	onmousedown="mousedown(this);"
 	onmouseup="mouseup(this);"
 	onclick="cambiarEstilo('underline');"
 	src="mozilla/images/under.gif"
 	width="24" height="24"
 	align="middle"
	> <img src="mozilla/images/linea.gif" align="middle"> <img class="button"
 	onmouseover="mouseover(this);"
 	onmouseout="mouseout(this);"
 	onmousedown="mousedown(this);"
 	onmouseup="mouseup(this);"
 	onclick="url();"
 	src="mozilla/images/link.gif"
 	width="32" height="16"
 	align="middle"
 	> <img class="button"
 	onmouseover="mouseover(this);"
 	onmouseout="mouseout(this);"
 	onmousedown="mousedown(this);"
 	onmouseup="mouseup(this);"
 	onclick="image();"
 	src="mozilla/images/image.gif"
 	width="24" height="24"
 	align="middle"
 	> <img src="mozilla/images/linea.gif" align="middle" > <img class="button"
 	onmouseover="mouseover(this);"
 	onmouseout="mouseout(this);"
 	onmousedown="mousedown(this);"
 	onmouseup="mouseup(this);"
 	onclick="cambiarEstilo('outdent');"
 	src="mozilla/images/deindent.gif"
 	width="24" height="24"
 	align="middle"
 	> <img class="button"
 	onmouseover="mouseover(this);"
 	onmouseout="mouseout(this);"
 	onmousedown="mousedown(this);"
 	onmouseup="mouseup(this);"
 	onclick="cambiarEstilo('indent');"
 	src="mozilla/images/inindent.gif"
 	width="24" height="24"
 	align="middle"
 	> <img src="mozilla/images/linea.gif" align="middle" > <img class="button"
 	onmouseover="mouseover(this);"
 	onmouseout="mouseout(this);"
 	onmousedown="mousedown(this);"
 	onmouseup="mouseup(this);"
 	onclick="cambiarEstilo('copy');"
 	src="mozilla/images/copy.gif"
 	width="24" height="24"
 	align="middle"
 	> <img class="button"
 	onmouseover="mouseover(this);"
 	onmouseout="mouseout(this);"
 	onmousedown="mousedown(this);"
 	onmouseup="mouseup(this);"
 	onclick="cambiarEstilo('cut');"
 	src="mozilla/images/cut.gif"
 	width="24" height="24"
 	align="middle"
 	> <img class="button"
 	onmouseover="mouseover(this);"
 	onmouseout="mouseout(this);"
 	onmousedown="mousedown(this);"
 	onmouseup="mouseup(this);"
 	onclick="cambiarEstilo('paste');"
 	src="mozilla/images/paste.gif"
 	width="24" height="24"
 	align="middle"
 	> <img src="mozilla/images/linea.gif" align="middle" > <img class="button"
 	onmouseover="mouseover(this);"
 	onmouseout="mouseout(this);"
 	onmousedown="mousedown(this);"
 	onmouseup="mouseup(this);"
 	onclick="cambiarEstilo('undo');"
 	src="mozilla/images/undo.gif"
 	width="24" height="24"
 	align="middle"
 	> <img class="button"
 	onmouseover="mouseover(this);"
 	onmouseout="mouseout(this);"
 	onmousedown="mousedown(this);"
 	onmouseup="mouseup(this);"
 	onclick="cambiarEstilo('redo');"
 	src="mozilla/images/redo.gif"
 	width="24" height="24"
 	align="middle"
 	> <img src="mozilla/images/linea.gif" align="middle" > <img class="button"
 	onmouseover="mouseover(this);"
 	onmouseout="mouseout(this);"
 	onmousedown="mousedown(this);"
 	onmouseup="mouseup(this);"
 	onclick="cambiarEstilo('justifyleft');"
 	src="mozilla/images/left.gif"
 	width="24" height="24"
 	align="middle"
 	> <img class="button"
 	onmouseover="mouseover(this);"
 	onmouseout="mouseout(this);"
 	onmousedown="mousedown(this);"
 	onmouseup="mouseup(this);"
 	onclick="cambiarEstilo('justifycenter');"
 	src="mozilla/images/center.gif"
 	width="24" height="24"
 	align="middle"
 	> <img class="button"
 	onmouseover="mouseover(this);"
 	onmouseout="mouseout(this);"
 	onmousedown="mousedown(this);"
 	onmouseup="mouseup(this);"
 	onclick="cambiarEstilo('justifyright');"
 	src="mozilla/images/right.gif"
 	width="24" height="24"
 	align="middle"
 	> <img src="mozilla/images/linea.gif" align="middle" > 
    <!--<input type="submit" name="view" value="Ver" onClick="javascript:source();">-->
    <input type="submit" name="view" value="tabla" onClick="javascript:tabla();">
  </div>
 
  <iframe border=1 id="edit" width="100%" height="425" name="floatframe" src="mozilla/example.html" frameborder="1"></iframe>

<form action="editor.php" method="post" onSubmit="enviarFormulario();return true;">
  <input type="hidden" name="id" value="<?=$_POST['id']?>">
  <TEXTAREA style="VISIBILITY: hidden; POSITION: absolute" name="html">
</TEXTAREA>
  <!--<table width="100%" border=0 align="center">
    <tr align="center"> 
      <td align="center"> <input type="hidden" value="<?= $_POST['id'] ?>" name="id"> 
        <input type="submit" value="Guardar" name="guardar"> </td>
    </tr>
  </table>-->
</form>
<div class="skin0" id="menu_archivo"   > 
  <div onMouseOver="cambiarFondo(this)" onMouseOut="retornarFondo(this)" onClick="realizarEvento('nuevo')">Nuevo</div>
  <div onMouseOver="cambiarFondo(this)" onMouseOut="retornarFondo(this)" onClick="realizarEvento('cerrar')">Cerrar</div>
  <div ><img src="mozilla/images/separador.gif" width="130" height="6"></div>
  <div onMouseOver="cambiarFondo(this)" onMouseOut="retornarFondo(this)" onClick="realizarEvento('guardar')">Guardar</div>
  <div onMouseOver="cambiarFondo(this)" onMouseOut="retornarFondo(this)">Guardar Como...</div>
  <div ><img src="mozilla/images/separador.gif" width="130" height="6"></div>
  <div onMouseOver="cambiarFondo(this)" onMouseOut="retornarFondo(this)" onClick="realizarEvento('imprimir')">Imprimir</div>
  <div ><img src="mozilla/images/separador.gif" width="130" height="6"></div>
  <div onMouseOver="cambiarFondo(this)" onMouseOut="retornarFondo(this)" onClick="realizarEvento('salir')">Salir</div>
</div>
</body>
</html>
<? @mysql_close($conexion); ?>
