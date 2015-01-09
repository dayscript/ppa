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

	function insertarLink(){
		var direccion=document.forms[0].url.value;
		var propiedades=document.forms[0].propiedades.options[document.forms[0].propiedades.selectedIndex].value;

		editableDocument = window.opener.document.getElementById("edit").contentDocument;
		selection=window.opener.frames[0].getSelection();

		rango=selection.getRangeAt(selection.rangeCount-1);
		nodo=selection.anchorNode;
		
		while( true ){
			nodo=nodo.parentNode;
			if( nodo.nodeName == "BODY" ){
				nodo=selection.anchorNode;
				padre=nodo.parentNode;
				break;
			}
			if( nodo.nodeName == "A" ){
				padre=nodo;
				nodo=selection.anchorNode;
				break;
			}
		}

		if(padre.nodeName=="A"){
			try{
				atributos=padre.attributes;

				atributo=editableDocument.createAttribute("href");
				atributo.value=direccion;
				atributos.setNamedItem(atributo);
				
				atributo=editableDocument.createAttribute("target");
				atributo.value=propiedades;
				atributos.setNamedItem(atributo);

			}catch(error){
				alert(error);
			}
		}else{
			try{
				window.opener.document.body.display = "hidden";

				cadena=nodo.nodeValue;

				if( !rango.collapsed ){
					link = editableDocument.createElement("a");
					link.setAttribute("href",direccion);
					link.setAttribute("target",propiedades);
					cadena1=editableDocument.createTextNode(cadena.substring(0,rango.startOffset));
					cadena2=editableDocument.createTextNode(cadena.substring(rango.endOffset,cadena.length));
					link.appendChild(rango.extractContents());
					padre=nodo.parentNode;

					padre.insertBefore(cadena1,nodo);
					padre.insertBefore(link,nodo);
					padre.insertBefore(cadena2,nodo);
					padre.removeChild(nodo);
				}

				window.opener.document.body.display = "";

			}catch(error){
				alert(error);
			}

		}
		window.close();
	}
</script>
<body bgcolor="#EEEEFF">
	<form name="propiedades" >
		<table width="90%" border=0 align=center>
			<th colspan=2 bgcolor="9999FF">
				Propiedades Vinculo
			<th>
			<tr>
				<td bgcolor="9999FF">
					Direccion Url
				</td>
				<td bgcolor="DDDDFF" align=center>
					<input type="text" name="url" value="http://" size=40>
				</td>
			</tr>
			<tr>
				<td bgcolor="9999FF">
					Abrir Vinculo
				</td>
				<td align=center bgcolor="DDDDFF">
					<select name="propiedades">
						<option value="_blank">Página Nueva
						<option value="_parent">Primer Pop Up
						<option value="_self">Misma Página
						<option value="_top">Frame
					</select>
				</td>
			</tr>
			<tr>
				<td colspan=2 align=center bgcolor="9999FF">
					<input type="button" onClick="insertarLink()" value="aceptar">
				</td>
			</tr>
		</table>
	</form>
</body>
<script>
	editableDocument = window.opener.document.getElementById("edit").contentDocument;
	selection=window.opener.frames[0].getSelection();

	rango=selection.getRangeAt(selection.rangeCount-1);
	nodo=selection.anchorNode;

	padre=nodo.parentNode;
	if(padre.nodeName=="A"){
		atributos=padre.attributes;
		numeroAtributos=atributos.length;
		for(i=0;i<numeroAtributos;i++){
			if(atributos.item(i).name=="href")
				document.forms[0].url.value=atributos.item(i).value;
			if(atributos.item(i).name=="target"){
				switch(atributos.item(i).value){
					case "_blank":
						document.forms[0].propiedades.selectedIndex=0;
						break;
					case "_parent":
						document.forms[0].propiedades.selectedIndex=1;
						break;
					case "_self":
						document.forms[0].propiedades.selectedIndex=2;
						break;
					case "_top":
						document.forms[0].propiedades.selectedIndex=3;
						break;
				}
			}
		}
	}
</script>
</html>
