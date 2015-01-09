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
	
	.skin0{
	position:absolute;
	width:100px;
	
	font-family:Verdana;
	line-height:2 px;
	cursor:hand;
	font-size:14px;
	z-index:100;
	visibility:hidden;
	margin: 0px;
	padding: 0px;
	}

</style>
<script>
	
	
	
	var ie5=document.all&&document.getElementById;
	var ns6=document.getElementById&&!document.all;
	
	var propiedades=0;
	
	function cambiarPropiedades(numero_propiedad){
		if(propiedades!=numero_propiedad){
			if(numero_propiedad==0){
				document.getElementById('imagen_1').src="images/botones/bot_1_a.gif";
				document.getElementById('imagen_2').src="images/botones/bot_2_a.gif";
			}else{
				document.getElementById('imagen_1').src="images/botones/bot_1.gif";
				document.getElementById('imagen_2').src="images/botones/bot_2.gif";
			}
			propiedades=numero_propiedad;
		}
	}

	function mostrartabla(e){

		var tabla_colores=document.getElementById("tabla_colores");
		
		var rightedge=ie5? document.body.clientWidth-event.clientX : window.innerWidth-e.clientX;
		var bottomedge=ie5? document.body.clientHeight-event.clientY : window.innerHeight-e.clientY;

		if (rightedge<tabla_colores.offsetWidth)
			tabla_colores.style.left=ie5? document.body.scrollLeft+event.clientX-tabla_colores.offsetWidth : window.pageXOffset+e.clientX-tabla_colores.offsetWidth;
		else
			tabla_colores.style.left=ie5? document.body.scrollLeft+event.clientX : window.pageXOffset+e.clientX;

		if (bottomedge<tabla_colores.offsetHeight)
			tabla_colores.style.top=ie5? document.body.scrollTop+event.clientY-tabla_colores.offsetHeight : window.pageYOffset+e.clientY-tabla_colores.offsetHeight;
		else
			tabla_colores.style.top=ie5? document.body.scrollTop+event.clientY : window.pageYOffset+e.clientY;

		tabla_colores.style.visibility="visible";
		return false;
	}
	
	function escondertabla(e){
		var tabla_colores=document.getElementById("tabla_colores");
		tabla_colores.style.visibility="hidden";
		
		document.forms['propiedades'].valor_color.value=document.forms['colores'].color.value;
		document.forms['colores'].color.value="";
	}
	
	if (ie5||ns6){
		//document.oncontextmenu=mostrartabla;
		//document.onclick=escondertabla;
	}

	function insertarTabla(){
		var tamano=document.forms[0].tam.value;
		var border=document.forms[0].border.value;
		var align=document.forms[0].align.options[document.forms[0].align.selectedIndex].value;
		var numrows=document.forms[0].numrows.value;
		var numcols=document.forms[0].numcols.value;
		var cellspacing=document.forms[0].cellspacing.value;
		var cellpadding=document.forms[0].cellpadding.value;
		var colorfondo=document.forms[0].valor_color.value;

		editableDocument = window.opener.document.getElementById("edit").contentDocument;
		var selection=window.opener.frames[0].getSelection();
		rango=selection.getRangeAt(selection.rangeCount-1);
		
		nodo=selection.anchorNode;

		try{
			window.opener.document.body.display = "hidden";
			
			if( nodo.nodeName != "BODY" ){
				while( true  ){
					nodo=nodo.parentNode;
					if( nodo.nodeName == "BODY" ){
						nodo=selection.anchorNode;
						padre=nodo.parentNode;
						break;
					}
					if( nodo.nodeName == "TD" ){
						padre=nodo;
						nodo=selection.anchorNode;
						break;
					}
				}
			}else{
				padre=nodo.parentNode;
			}
			
			if(padre.nodeName=="TD"){
				
				TR=padre.parentNode;
				TBODY=TR.parentNode;
				TABLE=TBODY.parentNode;
				atributos=TABLE.attributes;
				numeroAtributos=atributos.length;
				
				atributo=editableDocument.createAttribute("width");
				atributo.value=tamano;
				atributos.setNamedItem(atributo);
				
				atributo=editableDocument.createAttribute("border");
				atributo.value=border;
				atributos.setNamedItem(atributo);
				
				atributo=editableDocument.createAttribute("align");
				atributo.value=align;
				atributos.setNamedItem(atributo);
				
				atributo=editableDocument.createAttribute("cellspacing");
				atributo.value=cellspacing;
				atributos.setNamedItem(atributo);
				
				atributo=editableDocument.createAttribute("cellpadding");
				atributo.value=cellpadding;
				atributos.setNamedItem(atributo);
						
				atributo=editableDocument.createAttribute("bgcolor");
				atributo.value=colorfondo;
				atributos.setNamedItem(atributo);
		
			}else{
				tabla = editableDocument.createElement("table");
        		cuerpoTabla = editableDocument.createElement("tbody");
        		for(j=0;j<numrows;j++) {
           				fila=editableDocument.createElement("tr");
           				for(i=0;i<numcols;i++) {
               				celda=editableDocument.createElement("td");
							texto=editableDocument.createTextNode("-");
							celda.appendChild(texto);
               				fila.appendChild(celda);
           				}
           				cuerpoTabla.appendChild(fila);
        		}
        		tabla.appendChild(cuerpoTabla);
				tabla.setAttribute("border",border);
				tabla.setAttribute("align",align);
        		tabla.setAttribute("width",tamano);
				tabla.setAttribute("cellspacing",cellspacing);
				tabla.setAttribute("cellpadding",cellpadding);
				tabla.setAttribute("bgcolor",colorfondo);

				parrafo=editableDocument.createElement("p");
				parrafo.appendChild(tabla);

				if(nodo.nodeType==3){
					cadena=nodo.nodeValue;
					cadena1=editableDocument.createTextNode(cadena.substring(0,rango.startOffset));
					cadena2=editableDocument.createTextNode(cadena.substring(rango.endOffset,cadena.length));
					padre=nodo.parentNode;
					padre.removeChild(nodo);
					padre.appendChild(cadena1);
					padre.appendChild(parrafo);
					padre.appendChild(cadena2);
				}else{
					nodo.appendChild(parrafo);
				}
			}
		}catch(error){
			alert(error.message);
		}
		window.opener.document.body.display = "";
		window.close();
	}
	
	function cualColor(Elemento){
		atributos=Elemento.attributes;
		document.forms['colores'].color.value=atributos.getNamedItem("bgcolor").value;
	}
	
	function inicializar(){
	}
</script>
<body bgcolor="#EEEEFF" onLoad="inicializar()">
	<form name="propiedades" >
		<table width="90%" border=0 align=center id="tabla_principal">
			<tbody>
			<tr>
				<td colspan=2 bgcolor="DDDDFF">
					<a href="#" onClick="cambiarPropiedades(0)" ><img src="images/botones/bot_1_a.gif" border=0 id="imagen_1"></a><a href="#" onClick="cambiarPropiedades(1)" ><img src="images/botones/bot_2_a.gif" border=0 id="imagen_2"></a>

				</td>
			</tr>
			<tr>
				<td bgcolor="9999FF">
					Tamaño tabla
				</td>
				<td align=center bgcolor="DDDDFF">
					<input type="text" name="tam" value="100%" size=5>
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
					Alineacion Tabla
				</td>
				<td align=center bgcolor="DDDDFF">
					<select name="align">
						<option>left
						<option>center
						<option>right
					</select>
				</td>
			</tr>
			<tr>
				<td bgcolor="9999FF">
					Numero de Filas
				</td>
				<td align=center bgcolor="DDDDFF">
					<input type="text" name="numrows" id="numrows" value="2" size=5>
				</td>
			</tr>
			<tr>
				<td bgcolor="9999FF">
					Numero de Columnas
				</td>
				<td align=center bgcolor="DDDDFF">
					<input type="text" name="numcols" id="numcols" value="2" size=5>
				</td>
			</tr>
			
    			<tr>
      			<td bgcolor="9999FF">Espacio Entre Celdas</td>
				<td align=center bgcolor="DDDDFF">
					<input type="text" name="cellspacing" value="0" size=5>
				</td>
			</tr>
			<tr> 
      			<td bgcolor="9999FF">Espacio Dentro de las Celdas</td>
				<td align=center bgcolor="DDDDFF">
					<input type="text" name="cellpadding" value="0" size=5>
				</td>
			</tr>
			<tr>
				<td bgcolor="9999FF">
					Color Fondo
				</td>
				<td align=center bgcolor="DDDDFF">
					<input type="text" id="valor_color" name="valor_color" value="#FFFFFF" size=15 >
				</td>
			</tr>
			<tr>
				<td colspan=2 align=center bgcolor="9999FF">
					<input type="button" onClick="insertarTabla()" value="aceptar">
				</td>
			</tr>
			</tbody>
		</table>
	</form>
<div class="skin0" id="tabla_colores"  onClick="escondertabla()">
<form name="colores">
<table width="100" border="2" cellpadding="0" cellspacing="0" bordercolor="" >
  <tr bordercolor="#999999">
  	<td colspan=4 bgcolor="#999999">
		<input type="text" name="color" size="10">
	</td>
  </tr>
  <tr>
    <td width="20" bgcolor="#000000" onMouseOver="cualColor(this)">&nbsp;</td>
    <td width="20" bgcolor="#006600" onMouseOver="cualColor(this)">&nbsp;</td>
    <td width="20" bgcolor="#009900" onMouseOver="cualColor(this)">&nbsp;</td>
    <td width="20" bgcolor="#00FF00" onMouseOver="cualColor(this)">&nbsp;</td>
  </tr>
  <tr>
    <td onMouseOver="cualColor(this)" bgcolor="#000033">&nbsp;</td>
    <td onMouseOver="cualColor(this)" bgcolor="#666666">&nbsp;</td>
    <td onMouseOver="cualColor(this)" bgcolor="#990000">&nbsp;</td>
    <td onMouseOver="cualColor(this)" bgcolor="#FF0000">&nbsp;</td>
  </tr>
  <tr>
    <td onMouseOver="cualColor(this)" bgcolor="#000099">&nbsp;</td>
    <td onMouseOver="cualColor(this)" bgcolor="#CC9933">&nbsp;</td>
    <td onMouseOver="cualColor(this)" bgcolor="#CCCCCC">&nbsp;</td>
    <td onMouseOver="cualColor(this)" bgcolor="#663300">&nbsp;</td>
  </tr>
  <tr>
    <td onMouseOver="cualColor(this)" bgcolor="#0000FF">&nbsp;</td>
    <td onMouseOver="cualColor(this)" bgcolor="#FFFF00">&nbsp;</td>
    <td onMouseOver="cualColor(this)" bgcolor="#FFFFCC">&nbsp;</td>
    <td onMouseOver="cualColor(this)" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
</table>
</form>
</div>
<script>
	document.getElementById("valor_color").onmousedown=mostrartabla;
	
	editableDocument = window.opener.document.getElementById("edit").contentDocument;
	var selection=window.opener.frames[0].getSelection();
	rango=selection.getRangeAt(selection.rangeCount-1);
	
	nodo=selection.anchorNode;
	
	while( true ){
			nodo=nodo.parentNode;
			if( nodo.nodeName == "BODY" ){
				nodo=selection.anchorNode;
				padre=nodo.parentNode;
				break;
			}
			if( nodo.nodeName == "TD" ){
				padre=nodo;
				nodo=selection.anchorNode;
				break;
			}
	}
	
	if(padre.nodeName=="TD"){
		elemento=document.getElementById('numcols');
		elemento.value="";
		elemento.disabled=true;
		
		elemento=document.getElementById('numrows');
		elemento.value="";
		elemento.disabled=true;
		
		TR=padre.parentNode;
		TBODY=TR.parentNode;
		TABLE=TBODY.parentNode;
		atributos=TABLE.attributes;
		numeroAtributos=atributos.length;
		
		for(i=0;i<numeroAtributos;i++){
			if(atributos.item(i).name=="width")
				document.forms[0].tam.value=atributos.item(i).value;
				
			if(atributos.item(i).name=="border")
				document.forms[0].border.value=atributos.item(i).value;
				
			if(atributos.item(i).name=="cellspacing")
				document.forms[0].cellspacing.value=atributos.item(i).value;
				
			if(atributos.item(i).name=="cellpadding")
				document.forms[0].cellpadding.value=atributos.item(i).value;
				
			if(atributos.item(i).name=="bgcolor")
				document.forms[0].valor_color.value=atributos.item(i).value;
				
			if(atributos.item(i).name=="align"){
				switch(atributos.item(i).value){
					case "left":
						document.forms[0].align.selectedIndex=0;
						break;
					case "center":
						document.forms[0].align.selectedIndex=1;
						break;
					case "right":
						document.forms[0].align.selectedIndex=2;
						break;
				}
			}
			
		}
	}

</script>
</body>
</html>

