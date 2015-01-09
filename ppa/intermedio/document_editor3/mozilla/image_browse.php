
<html>
<style>
	TH {
		FONT-WEIGHT: normal;
		FONT-SIZE: 11px;
		COLOR: #FFFFFF;
		FONT-FAMILY: Verdana, Helvetica, sans-serif;
	}
	TD, A {
		FONT-WEIGHT: normal;
		FONT-SIZE: 11px;
		COLOR: #FFFFFF;
		FONT-FAMILY: Verdana, Helvetica, sans-serif;
		TEXT-DECORATION: none
	}
</style>
<script>

<?
	if(isset($_POST['aceptar'])){
		if(is_uploaded_file($_FILES['imagen']['tmp_name'])){
			copy($_FILES['imagen']['tmp_name'],"../../dimages/".$_FILES['imagen']['name']);
			$imagen="http://".$_SERVER['HTTP_HOST']."/dimages/".$_FILES['imagen']['name'];
		} else
			$imagen="";
		?>

		var ruta_imagen="<?= $imagen ?>";
		var alineacion="<?= $_POST['align'] ?>";
		var border=<?= ($_POST['border']!=""?$_POST['border']:"1") ?>;
		var width=<?= ($_POST['width']!=""?$_POST['width']:"\"\"") ?>;
		var height=<?= ($_POST['height']!=""?$_POST['height']:"\"\"") ?>;
		var vspace=<?= ($_POST['vspace']!=""?$_POST['vspace']:"\"\"") ?>;
		var hspace=<?= ($_POST['hspace']!=""?$_POST['hspace']:"\"\"") ?>;

		var selection=window.opener.frames[0].getSelection();
		editableDocument = window.opener.document.getElementById("edit").contentDocument;

		var rango=selection.getRangeAt(selection.rangeCount-1);
		nodo=selection.anchorNode;

		try{

			window.opener.document.body.display = "hidden";
			
			if(rango.cloneContents().hasChildNodes() && rango.cloneContents().firstChild.nodeName=="IMG"){
				nodo=rango.cloneContents().firstChild;
				atributos=nodo.attributes;

				if( ruta_imagen != "" ){
					atributo=editableDocument.createAttribute("src");
					atributo.value=ruta_imagen;
					atributos.setNamedItem(atributo);
				}

				atributo=editableDocument.createAttribute("align");
				atributo.value=alineacion;
				atributos.setNamedItem(atributo);
				
				if( border != "" ){
					atributo=editableDocument.createAttribute("border");
					atributo.value=border;
					atributos.setNamedItem(atributo);
				}
				
				atributo=editableDocument.createAttribute("width");
				atributo.value=width;
				atributos.setNamedItem(atributo);
								
				atributo=editableDocument.createAttribute("height");
				atributo.value=height;
				atributos.setNamedItem(atributo);

				if( vspace != "" ){
					atributo=editableDocument.createAttribute("vspace");
					atributo.value=vspace;
					atributos.setNamedItem(atributo);
				}
				
				if( hspace != "" ){
					atributo=editableDocument.createAttribute("hspace");
					atributo.value=hspace;
					atributos.setNamedItem(atributo);
				}
				rango.deleteContents();
				rango.insertNode(nodo);
			}else{
				cadena=nodo.nodeValue;

				imagen = editableDocument.createElement("img");

				if(ruta_imagen!="")
					imagen.setAttribute("src",ruta_imagen);
				
				if(border!="")
					imagen.setAttribute("border",border);

				imagen.setAttribute("align",alineacion);

				imagen.setAttribute("width",width);

				imagen.setAttribute("height",height);

				if(vspace!="")
					imagen.setAttribute("vspace",vspace);

				if(hspace!="")
					imagen.setAttribute("hspace",hspace);

				if(nodo.nodeType==3){
					cadena1=editableDocument.createTextNode(cadena.substring(0,rango.startOffset));
					cadena2=editableDocument.createTextNode(cadena.substring(rango.endOffset,cadena.length));
					padre=nodo.parentNode;
					padre.removeChild(nodo);
					padre.appendChild(cadena1);
					padre.appendChild(imagen);
					padre.appendChild(cadena2);
				}else{
					nodo.appendChild(imagen);
				}
			}

				window.opener.document.body.display = "";

		}catch(error){
			alert(error);
		}
		window.close();
	<?

	}
?>

</script>
<body bgcolor="#EEEEFF">
	<form action="imagen.php" enctype="multipart/form-data" method="post">
		<table width="90%" align=center border=0>
			<th colspan="2" bgcolor="9999FF">
				Propiedades Imagen
			</th>
			<tr>
				<td bgcolor="9999FF">
					Seleccionar Imagen
				</td>
				<td align=center bgcolor="DDDDFF">
					<input type="file" name="imagen">
				</td>
			<tr>
				<td bgcolor="9999FF">
					Alineacion Imagen
				</td>
				<td align=center bgcolor="DDDDFF">
					<select name="align">
						<option>top
						<option>middle
						<option>bottom
						<option>left
						<option>right
					</select>
				</td>
			</tr>
			<tr>
				<td bgcolor="9999FF">
					Borde
				</td>
				<td align=center bgcolor="DDDDFF">
					<input type="text" name="border" value="1" size=5>
				</td>
			</tr>
			<tr>
				<td bgcolor="9999FF">
					Ancho
				</td>
				<td align=center bgcolor="DDDDFF">
					<input type="text" name="width" size=5>
				</td>
			</tr>
			<tr>
				<td bgcolor="9999FF">
					Alto
				</td>
				<td align=center bgcolor="DDDDFF">
					<input type="text" name="height" size=5>
				</td>
			</tr>
			<tr>
				<td bgcolor="9999FF">
					Espacio Vertical
				</td>
				<td align=center bgcolor="DDDDFF">
					<input type="text" name="vspace" size=5>
				</td>
			</tr>
			<tr>
				<td bgcolor="9999FF">
					Espacio Horizontal
				</td>
				<td align=center bgcolor="DDDDFF">
					<input type="text" name="hspace" size=5>
				</td>
			</tr>
			<tr bgcolor="9999FF">
				<td colspan=2 align=center>
					<input type="submit" name="aceptar" value="aceptar">
				</td>
			</tr>
		</table>
	</form>
</body>
<script>

	var selection=window.opener.frames[0].getSelection();
	editableDocument = window.opener.document.getElementById("edit").contentDocument;

	var rango=selection.getRangeAt(selection.rangeCount-1);
	nodo=selection.anchorNode;

	if(rango.cloneContents().hasChildNodes()&& rango.cloneContents().firstChild.nodeName=="IMG"){
		
		nodo=rango.cloneContents().firstChild;
		atributos=nodo.attributes;
		numeroAtributos=atributos.length;

		for(i=0;i<numeroAtributos;i++){
			
			if(atributos.item(i).name=="align"){
				switch(atributos.item(i).value){
					case "top":
						document.forms[0].align.selectedIndex=0;
						break;
					case "middle":
						document.forms[0].align.selectedIndex=1;
						break;
					case "botton":
						document.forms[0].align.selectedIndex=2;
						break;
					case "left":
						document.forms[0].align.selectedIndex=3;
						break;
					case "right":
						document.forms[0].align.selectedIndex=4;
						break;
				}
			}
			
			if(atributos.item(i).name=="width")
				document.forms[0].width.value=atributos.item(i).value;
				
			if(atributos.item(i).name=="height")
				document.forms[0].height.value=atributos.item(i).value;

			if(atributos.item(i).name=="vspace")
				document.forms[0].vspace.value=atributos.item(i).value;
				
			if(atributos.item(i).name=="hspace")
				document.forms[0].hspace.value=atributos.item(i).value;
		}
	}
</script>
</html>
