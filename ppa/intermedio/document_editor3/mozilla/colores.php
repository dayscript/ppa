<style>
	.skin0{
	position:absolute;
	width:100px;
	font-family:Verdana;
	line-height:20px;
	cursor:default;
	font-size:14px;
	z-index:1;
	visibility:hidden;
	}
</style>
<script language="JavaScript">
	document.onclick=mostrartabla;
	
	function mostrartabla(e){
		document.getElementById('tabla_colores').style.visibility="visible";
	}
	
	function cualColor(Elemento){
		atributos=Elemento.attributes;
		document.forms['colores'].color.value=atributos.getNamedItem("bgcolor").value;
	}
	
	function ocultar(){
		document.getElementById('tabla_colores').style.visibility="hidden";
	}
</script>
<div class="skin0" id="tabla_colores" onClick="ocultar()">
<form name="colores">
<table width="100" height="108" border="2" cellpadding="0" cellspacing="0" bordercolor="" >
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
