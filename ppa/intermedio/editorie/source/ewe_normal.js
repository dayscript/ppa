/* FILE HEADER **************************************************
** Easy Web Editor
** Author: Karl Seguin (karl@openmymind.net)
** Homepage: http://www.openmymind.net/editor/
** Version: 0.62
** Copyright 2003 Karl Seguin

    This file is part of Easy Web Editor (EWE).

    EWE is free software; you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    EWE is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Lesser General Public License
    along with Foobar; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
**
** END HEADER ***************************************************/

document.write('<link REL="StyleSheet" TYPE="text/css" HREF="' + editorPath + 'source/style.css" \/>');
document.write('<script language="JScript" src="' + editorPath + 'source/button.js"><\/script>');
document.write('<script language="JScript" src="' + editorPath + 'source/popup.js"><\/script>');

var toggledButtons = new Array();
var popupZindex = new Array();
var parentNodes = new Array();
var ViewCurrent = 1;
var BorderCurrent = 1;
var ZoomCurrent = 1;
var ColorCurrent = "#000000";
var prevRange;
var prevRangeType = "None";
var StyleCurrent = 0;
var td = null;
var table = null;
var tr = null;
var img = null;
var a = null;
var imgDrag = null;
var setBorders = 0;

function EWE(lang, ss){
  this.created = false;
  this.udButtons = new Array();
  switch (arguments.length){
  case 2:
    this.ss = ss;
    document.createStyleSheet(ss);
  case 1:
    if (lang){
      this.lang = lang;
    }
  }
}

EWE.prototype.addButton = function(btn){
  if (this.created){
    alert('Could not add button: ' + btn.ImageId + '.  Edit already created');
    return false;
  }
  this.udButtons.push(btn);
  return true;
}

EWE.prototype.setStyleSheet = function(ss){
  if (this.created){
    alert('Could not load stylesheet: ' + ss + '.  Editor already created');
    return false;
  }
  if (this.ss){
    alert('Could not load stylesheet: ' + ss + '.  A stylesheet was already loaded');
    return false;
  }
  this.ss = ss;
  document.createStyleSheet(ss);
  return true;
}

EWE.prototype.setLanguageFile = function(lang){
  if (this.created){
    alert('Could not load language file : ' + ss + '.  Editor already created');
    return false;
  }
  if (this.lang){
    alert('Could not load language file: ' + ss + '.  A language file was already loaded');
    return false;
  }
  this.lang = lang;
  return true;
}

EWE.prototype.load = function (container, content){
  if (!container || !container.canHaveChildren){
    alert("The load() function expects an object which supports the appendChild() method (div/td..)");
    return;
  }
  this.container = container;
  this.content = (arguments.length == 2)?content:'';  
  this.createXMLIsland();
  this.created = true;
}

EWE.prototype.createXMLIsland = function(){
  if (!this.lang){
    this.lang = editorPath + 'source/ewe_langEN.xml';
  }
  var _this = this
  var ne = document.createElement("XML");
  ne.id = 'lang';
  ne.onreadystatechange = function() {_this.checkXMLIsland()};
  document.body.appendChild(ne);
  setTimeout("setSource('" + _this.lang + "')", 200);

}
function setSource(lang){
  document.all.lang.src = lang;
}
EWE.prototype.checkXMLIsland = function(){
 if (lang.readyState == "interactive"){
    this.loadEWE();
  } 
}

EWE.prototype.loadEWE = function(){
  var ne;
  var container = this.container;
  container.onmousedown =  new Function("contextToggle('hide')");
  //File commmand

  //clipboard commands
  ne = document.createElement('DIV');
  ne.className = 'toolBar';
  ne.id = 'tlbClipboard';
  ne.unselectable = "on";
  ne.style.width = '25px';
  ne.style.width = '70px';
  ne.style.styleFloat = 'left';
  container.appendChild(ne);
  var templateDIV = ne.cloneNode();
  createFromTemplateImg('Cut', 'true', null, 0, ne);
  createFromTemplateImg('Copy', 'true', null, 0, ne);
  createFromTemplateImg('Paste', 'true', "basicCmd(this);borderToggle(false);", 0, ne);


  //Format commands
  ne = templateDIV.cloneNode();
  ne.id = 'tlbFormat';
  ne.style.width = '80px';
  container.appendChild(ne);
  createFromTemplateImg('Bold', 'false', null, 1, ne);
  createFromTemplateImg('Italic', 'false', null, 1, ne);
  createFromTemplateImg('Underline', 'false', null, 1, ne);

  //Positional elements
  ne = templateDIV.cloneNode();
  ne.id = 'tlbPositioning';
  ne.style.width = '100px';
  container.appendChild(ne)
  createFromTemplateImg('Outdent', 'false', null, 0, ne);
  createFromTemplateImg('Indent', 'false', null, 0, ne);
  createFromTemplateImg('InsertOrderedList', 'false', null, 0, ne);
  createFromTemplateImg('InsertUnorderedList', 'false', null, 0, ne);

  //Font element
  ne = templateDIV.cloneNode();
  ne.id = 'tlbFont';
  ne.style.width = '100%';
  container.appendChild(ne);
  ne.style.position = 'relative';
  ne.style.styleFloat = 'none';

  ne = document.createElement("SELECT");
  ne.unselectable = 'on';
  ne.id = 'FontStyle'
  ne.className = 'dropdown';
  ne.onchange = new Function("changeStyle(this)");
  document.all.tlbFont.appendChild(ne);
  createFromTemplateImg('ForeColor', 'false', "popupShowForeColor(0);", 0, document.all.tlbFont)
  createFromTemplateImg('InsertTable', 'false', "popupToggle(document.all.table)", 0, ne);
  createFromTemplateImg('InsertImage', 'false', "popupToggle(document.all.image)", 0, document.all.tlbFont);
  createFromTemplateImg('CreateLink', 'false', "popupShowHREF()", 0, ne);
  createFromTemplateImg('InsertHR', 'false', "showWindow('hline')", 0, ne);



  //the actual editor box
  ne = document.createElement("DIV");
  ne.id = 'ewe';
  ne.contentEditable = 'true';
  ne.onmouseup = new Function("setRange()");
  ne.onkeyup = new Function("setRange()");
  ne.onkeydown = new Function("checkPaste();");
  ne.ondragend = new Function("borderToggle(false)");
  ne.oncontextmenu = new Function("contextToggle()");
  ne.style.backgroundColor = '#FFFFFF';
  ne.style.border = 'thin inset';
  ne.style.width = '100%';
  ne.style.height = '300px';
  ne.style.overflow = 'auto';
  ne.style.wordWrap = 'break-word';
  container.appendChild(ne);
  document.all.ewe.innerHTML = this.content;

  //footer
  ne = document.createElement("DIV");
  ne.id = 'tlbwyter'; 
  ne.unselectable = 'on'
  ne.className = 'wyter';
  ne.style.width = '475px';
  container.appendChild(ne);
 
  ne = document.createElement("DIV");
  ne.id = 'tlbCopyright';
  ne.unselectable = 'on';
  ne.className = 'wyter';
  ne.style.width = '100%';
  ne.style.position = 'relative';
  ne.style.left = '-3px';
  ne.innerHTML = '<a href="http://www.empresariolatino.com.co" style="color:#002569;font-weight:700"> Amway </a>';
  ne.style.styleFloat = 'none';
  container.appendChild(ne);


  container.appendChild(createPopup('EditTable', '300px', '250px'));
  container.appendChild(createPopup('editTD', '350px', '320px'));
  if (document.all.InsertImage.isDefaultFunc) container.appendChild(createPopup('image', '329px', '92px')); 
  container.appendChild(createPopup('editImage', '350px', '275px'))
  container.appendChild(createPopup('editHyperlink', '300px', '225px'));
  if (document.all.ForeColor.isDefaultFunc) container.appendChild(createPopup('forecolorPopup', '250px', '250px'));


  //context menu
  ne = document.createElement("DIV");
  ne.id = 'contextmenu';
  ne.unselectable = 'on';
  ne.style.position = 'absolute';
  ne.style.visibility = 'hidden';
  ne.style.border = '1px solid #7C7C7C';
  ne.style.backgroundColor = '#F4F4F4';
  ne.style.width = '130px';
  ne.style.fontFamily = 'tahoma';
  ne.style.fontSize = '8pt';
  ne.oncontextmenu = new Function("event.returnValue=false");
  ne.onmousedown =  new Function("event.cancelBubble = true;");
  container.appendChild(ne);

  ne = document.createElement("DIV")
  ne.id = 'positioner';
  ne.style.position = 'absolute';
  ne.style.visibility = 'hidden';
  ne.style.fontSize = '1pt';
  container.appendChild(ne);


  ne = contextItem('contextCopy', 0, "basicCmd(document.all.Copy);contextToggle();");
  ne.isDynamic = false;
  document.all.contextmenu.appendChild(ne);
  ne = contextItem('contextCut',0, "basicCmd(document.all.Cut);contextToggle();");
  ne.isDynamic = false;
  document.all.contextmenu.appendChild(ne);
  ne = contextItem('contextPaste', 0, "basicCmd(document.all.Paste);borderToggle(false);contextToggle();");
  ne.isDynamic = false;
  document.all.contextmenu.appendChild(ne);
  ne = contextItem('contextItemView', 0, "toggleView(null);");
  ne.isDynamic = false;
  document.all.contextmenu.appendChild(ne);


  //load the editor and stuff
  updatewyter();
  borderToggle(false)
  document.all.ewe.disabled = true;
  if (document.all.frmtable) document.all.frmtable.src = editorPath + 'popup/table.html';
  if (document.all.frmimage) document.all.frmimage.src = editorPath + 'popup/image.html';
  if (document.all.frmhyperlink) document.all.frmhyperlink.src = editorPath + 'popup/hyperlink.html';
  document.all.frmeditTD.src = editorPath + 'popup/editTD.html';
  document.all.frmEditTable.src = editorPath + 'popup/editTable.html';
  document.all.frmeditImage.src = editorPath + 'popup/editIMG.html';
  document.all.frmeditHyperlink.src = editorPath + 'popup/editA.html';


  if (document.all.frmforecolorPopup) document.all.frmforecolorPopup.src = editorPath + 'popup/colorchooser.html';
  if (this.ss){
    setTimeout(populateStyleSheet, 50);
  }
  document.all.ewe.disabled = false;
}

function shrinkBar(bar){
  var newWidth = parseInt(bar.style.width) - 25;
  bar.style.width = newWidth + 'px';
  if (!bar.hasChildNodes()){
    bar.style.visibility = 'hidden';
    bar.style.position = 'absolute';
  }
}
function createPopup(id, w, h){
  var f = document.createElement("DIV");
  f.enableInHtml = 'false';
  f.id = id;
  f.className = 'popup';
  f.style.width = w;
  f.unselectable = 'on';
  f.onmousedown = new Function("setDragPos(this);");
  f.onclick = new Function("popupClick(document.all." + id + ")");

  var ne = document.createElement("DIV");
  ne.innerHTML = '&nbsp;' + xmlGetPopupTitle(id);
  ne.style.cursor = 'auto';
  ne.style.color = '#FFFFFF';
  ne.style.fontWeight = '700';
  ne.style.styleFloat = 'left';
  f.appendChild(ne);
 
  ne = document.createElement("DIV");
  ne.id = id + 'Close';
  ne.unselectable = 'on';
  ne.onclick = new Function("popupHide(document.all." + id + ")");
  ne.onmouseover = new Function("this.style.border='1px outset'; event.cancelBubble = true;");
  ne.onmouseout = new Function("this.style.border='1px solid'; event.cancelBubble = true;");
  ne.className = 'close';
  ne.innerText = 'X';
  f.appendChild(ne);

  ne = document.createElement("IFRAME");
  ne.unselectable = 'on';
  ne.id = ne.name = 'frm' + id;
  ne.style.width = w;
  ne.frameBorder = 0;
  ne.style.height = h;
  f.appendChild(ne);  
  return (f);
}
function createFromTemplateImg(id, enabled, clickFunc, canToggle, container){
  var alt = xmlGetAlt(id);
  if (alt == ''){
    shrinkBar(container)
    return false;
  }
  var ne = document.createElement("IMG")
  ne.id = id;
  ne.align = 'absbottom';
  ne.src = editorPath + 'images/' + id + '.gif';
  ne.enableInHTML = enabled;
  ne.unselectable = 'on';
  ne.isDefaultFunc = 1;
  var xmlFunc = xmlGetImgFunc(id);
  if (xmlFunc){
    ne.onclick = new Function(xmlFunc); 
    ne.isDefaultFunc = 0;
  }else if (clickFunc == null) ne.onclick = new Function("basicCmd(this);");
  else ne.onclick = new Function(clickFunc);
  ne.alt = alt;
  ne.canToggle = canToggle;
  ne.onmousedown = new Function("testdown(this)"); ne.onmouseover = new Function("testover(this)"); ne.onmouseup = new Function("testover(this)"); ne.onmouseout = new Function("testout(this)");
  ne.className = 'imageButton';
  container.appendChild(ne);
  return true;
}
function populateStyleSheet(){
  var i, cnt;
  var rle = document.styleSheets[document.styleSheets.length - 1].rules
  var sel = document.all.FontStyle;
  sel.options[0] = new Option(xmlGetSingle("fontstyle"), "ewenone");
  for (i = cnt = 0;i < rle.length;++i){
    var nme = String(rle[i].selectorText);
    var dot_pos = nme.indexOf(".");
    if (dot_pos == 0 ){
      nme = nme.substring(dot_pos+1);
      sel.options[++cnt] = new Option(nme, nme);
    }
  }
}

function xmlGetAlt(id){  
  if (!id) return '';
  id = id.toLowerCase();
  var node = document.all.lang.XMLDocument.selectSingleNode("language/img[@id='" + id + "']");
  return (node)?node.selectSingleNode("alt").text:'';
}

function xmlGetSingle(id){  
  if (!id) return '';
  var node = document.all.lang.XMLDocument.selectSingleNode("language/" + id);
  return (node)?node.text:'';
}

function xmlGetPopupTitle(id){
  if (!id) return '';
  id = id.toLowerCase();
  var node = document.all.lang.XMLDocument.selectSingleNode("language/popup[@id='" + id + "']");
  return (node)?node.selectSingleNode("title").text: '';
}

function xmlGetImgFunc(id){
  if (!id) return '';
  id = id.toLowerCase();
  var node = document.all.lang.XMLDocument.selectSingleNode("language/img[@id='" + id + "']");
  return (node.selectSingleNode("function"))?node.selectSingleNode("function").text:'';
}

function xmlGetSelect(select, val){
  if (!select || !val) return;
  ival = val.toLowerCase();
  var node = document.all.lang.XMLDocument.selectSingleNode("language/select[@id='" + val + "']");
  if (node && node.hasChildNodes){
    var childNode = node.firstChild;
    for (var attr = 0; attr < childNode.attributes.length; ++attr){
      switch (childNode.attributes.item(attr).name){
        case 'value':
          select.options[0] = new Option(childNode.text, childNode.attributes.item(attr).text);
          break;
        case 'default':
          if (childNode.attributes.item(attr).text == "true") select.selectedIndex = 0;      
          break;      
      }
    }
    for (var i = 1; childNode = childNode.nextSibling; ++i){
      for (var attr = 0; attr < childNode.attributes.length; ++attr){
        switch (childNode.attributes.item(attr).name){
          case 'value':
            select.options[i] = new Option(childNode.text, childNode.attributes.item(attr).text);
            break;
          case 'default':
            if (childNode.attributes.item(attr).text == "true") select.selectedIndex = i;      
            break;      
        }
      }
    }
  }
}

function toggleView(mode){
  var vn = document.all.ViewNormal;
  var vh = document.all.ViewHTML;
  if ((mode == 'HTML' && ViewCurrent == 2) || (mode == 'NORM' && ViewCurrent == 1))
    return;

  if (ViewCurrent == 2){                                                           
    ViewCurrent = 1;
    document.all.ewe.style.removeAttribute("fontFamily");
    document.all.ewe.style.removeAttribute("fontSize");
    document.all.ewe.style.removeAttribute("whiteSpace");
    document.all.ewe.innerHTML = document.all.ewe.innerText;
    vn.style.cursor = 'default';
    vh.style.cursor = 'hand';
    vn.style.backgroundColor = '#7B9BBA';
    vh.style.backgroundColor = '#3B457A';
    borderToggle(0);
    setRange();
  }else{                                                                           
    ViewCurrent = 2;
    document.all.ewe.style.fontFamily = "Courier New";
    document.all.ewe.style.fontSize = "10pt";
    document.all.ewe.style.whiteSpace = "nowrap";
    document.all.ewe.innerText = cleanup(document.all.ewe.innerHTML);
    vh.style.cursor = 'default';
    vn.style.cursor = 'hand';
    vh.style.backgroundColor = '#7B9BBA';
    vn.style.backgroundColor = '#3B457A';
  } 
  toggleButtonState();
  updatewyter();
  document.all.ewe.focus();
}

function toggleButtonState(){
  var cursor = (ViewCurrent == 2)?"default":"hand";
  for (var i = 0;i < document.images.length;++i){
    var image = document.images[i];
    if (image.enableInHTML == "false") image.style.cursor = cursor;
  }
}

function basicCmd(image, val){    
  if (val == "-1" || (ViewCurrent == 2 && image.enableInHTML == "false")) return;
  var btn = image.id;
  switch (btn){
  case "FontName":
  case "FontSize":
    document.execCommand(btn, false, val);
    break;
  default:
    document.execCommand(btn, false, null);
    break;
  }
  document.all.ewe.focus();
}

function setForeColor(color, loc){
  if (loc == 1){
    document.frames["frmeditTD"].document.all.bgcolor.value = color;
    return;
  }
  document.execCommand("ForeColor", false, color)
  ColorCurrent = color;
  updatewyter();
	document.all.ewe.focus();
}

function updatewyter(){
  document.all.tlbwyter.innerHTML = "<b>" + xmlGetSingle('modeTitle') + ":</b> ";
  document.all.tlbwyter.innerHTML +=  (ViewCurrent == 1)?xmlGetSingle('modeNormal'):xmlGetSingle('modeHTML');
  document.all.tlbwyter.innerHTML += "&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<b>" + xmlGetSingle('zoomTitle') + ":</b> " + ZoomCurrent + "x";
  document.all.tlbwyter.innerHTML += "&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<b>" + xmlGetSingle('colorTitle') + ":</b> ";
  document.all.tlbwyter.innerHTML += "<font color=" + ColorCurrent + ">" + ColorCurrent + "</font>";
  document.all.tlbwyter.innerHTML += "&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<b>" + xmlGetSingle('borderStatus') + ":</b> " + ((BorderCurrent)?xmlGetSingle('borderStatusShow'):xmlGetSingle('borderStatusHide'));
}
function dispatcher(){
  if (!prevRange) return;
  if (event.keyCode == 13 && !event.shiftKey){
    if (ViewCurrent != 2){
      if (!inNode('LI')){
        prevRange.pasteHTML("<br><div></div>") 
        event.returnValue = false;
      }
    }
  }
} 

function setRange(){
  prevRange = document.selection.createRange();
  prevRangeType = document.selection.type;
  if (toggledButtons.length){
    var tmpRange = document.selection.createRange();
    var parentElement = tmpRange.parentElement().tagName;
    tmpRange.moveStart('character', -1);
    var parentElement2 = tmpRange.parentElement().tagName;
    if (parentElement != "STRONG" && parentElement2 != "STRONG" && parentElement != "EM" && parentElement2 != "EM" && parentElement != "U" && parentElement2 != "U" && parentElement != "SUP" && parentElement2 != "SUP" && parentElement != "SUB" && parentElement2 != "SUB"){
      if (isToggled('Bold')) testdown(document.all.Bold);
      if (isToggled('Italic')) testdown(document.all.Italic);
      if (isToggled('Underline')) testdown(document.all.Underline);
      if (isToggled('Superscript')) testdown(document.all.Superscript);
      if (isToggled('Subscript')) testdown(document.all.Subscript);
    }
  }
  getParentNodes();
  toggleActiveImages();
  if (setBorders){
    borderToggle(false);
  }
}

function checkPaste(){
  setBorders = (event.keyCode == 86 && event.ctrlKey);
}
function toggleActiveImages(){
  if (isToggled('Bold')) testdown(document.all.Bold);
  if (isToggled('Italic')) testdown(document.all.Italic);
  if (isToggled('Underline')) testdown(document.all.Underline);
  if (isToggled('Superscript')) testdown(document.all.Superscript);
  if (isToggled('Subscript')) testdown(document.all.Subscript);
  for (var i = 0; i < parentNodes.length; ++i){
    var tName = parentNodes[i].tagName.toUpperCase();
    if (tName == 'B' || tName == 'STRONG') testdown(document.all.Bold);
    if (tName == 'I' || tName == 'EM') testdown(document.all.Italic);
    if (tName == 'U') testdown(document.all.Underline);
    if (tName == 'SUP') testdown(document.all.Superscript);
    if (tName == 'SUB') testdown(document.all.Subscript);
  }
}

function getParentNodes(){
  var selType = document.selection.type;
  var rg = document.selection.createRange();
  var node = (selType == "Control")?rg(0):rg.parentElement();
  parentNodes.length = 0;
  if (!node || node.id == 'ewe') return;
  while(node && node.id != "ewe"){
    parentNodes.push(node); 
    node = node.parentElement;
  }
}

function inNode(nodeType){
  nodeType = nodeType.toUpperCase();
  var selType = document.selection.type;
  var rg = document.selection.createRange();
  var node = (selType == "Control")?rg(0):rg.parentElement();
  parentNodes.length = 0;
  if (!node || node.id == 'ewe') return;
  while(node && node.id != "ewe"){
    if (node.tagName.toUpperCase() == nodeType) return true;
    node = node.parentElement;
  }
  return false;
}

function contextToggle(mode){
  var cntx = document.all.contextmenu;
  if (!mode && mode != 'hide' && cntx.style.visibility == "hidden"){
    getParentNodes();
    for (var i = 0; i < parentNodes.length; ++i){
      var tName = parentNodes[i].tagName.toUpperCase();
      switch (tName){
        case 'TD':
          if (!td) td = parentNodes[i];
          break;
        case 'TABLE':
          if (!table) table = parentNodes[i];
          break;
        case 'TR':
          if (!tr) tr = parentNodes[i];
          break;
        case 'IMG':
          img = parentNodes[i];   
        case 'A':
           a = parentNodes[i] 
      }
    }
    var ne;
    var found = 0;
    var view = document.all.contextItemView;
    if (img){
      found = 1;
    }
    if (a){
      found = 1;
    }
    if (td){
      found = 1;
    }
    if (table){
      found = 1;
    }
    if (found){
      ne = contextItem('contextBreaker', '', 1);
      ne.style.borderBottom = '1px solid #9F9F9F'; 
      ne.style.paddingTop = '5px';
      cntx.insertBefore(ne, view);  
    } 
    cntx.style.left = event.x;
    cntx.style.top = event.y;
    cntx.style.visibility="visible";
    cntx.focus();
    event.returnValue = false;
  }else{
    var divs = cntx.getElementsByTagName("DIV")
    for (var i = 0; i < divs.length; ++i){
      if (divs[i].isDynamic){
        divs[i].removeNode(true);
        --i;
      }
    }
    cntx.style.visibility = "hidden";
    td = tr = table = img = a = null;
  }
}
function contextItemOver(item){
  item.style.backgroundColor='#7B9BBA'; 
  item.style.cursor = 'hand';
}
function contextItemOut(item){
 item.style.removeAttribute('backgroundColor');
}

function contextItem(id, isHead, clickFunc){
  var ne = document.createElement("DIV");
  ne.id = id;
  ne.innerText = xmlGetSingle(id);
  ne.isDynamic = true;
  ne.unselectable = 'on';
  if (isHead){
    ne.style.fontWeight = '700';
    ne.style.backgroundColor = '#E4E4E4';
  }else{
    ne.onmouseover = new Function("contextItemOver(this);");
    ne.onmouseout = new Function("contextItemOut(this);");
  }
  if (clickFunc) ne.onmousedown = new Function(clickFunc);
  ne.style.borderLeft = '15px solid #4C4C4C';
  ne.style.padding = '4px';
  return ne;
}
function cleanup(str){
  str = str.replace(/<(\/)?strong>/ig, '<$1b>');
  str = str.replace(/<(\/)?em>/ig, '<$1i>');
  str = str.replace(/<\s*font\s+[^>]*><\/font>/ig, '');
  str = str.replace(/<\s*a\s+href[^>]*><\/a>/ig, '');
  str = str.replace(/\s*<\/?TBODY>\s*/ig, '');
  str = str.replace(/<div>\s*<\/div>/ig,''); 
  str = str.replace(/<u>\s*<\/u>/ig,''); 
  str = str.replace(/<b>\s*<\/b>/ig,''); 
  str = str.replace(/<ul>\s*<\/ul>/ig,''); 
  str = str.replace(/<br>([^\r\n])/ig,"<br>\r$1"); 


  var domain = String(top.location);
  var currentPath = getPath(domain);
  switch (domain.substring(0,4).toLowerCase()){
    case 'file':
      domain = domain.substring(0, 8);
      break;
    case 'http':
      domain = domain.substring(0, domain.indexOf("/", 7));
      break;
  }
  var re = new RegExp('href="?' + currentPath + '\??.*?#(.*?)["\'> ]', 'gi');
  str = str.replace(re, 'href=#$1');
  re = null;
  re = new RegExp('href="?' + domain, 'gi');
  str = str.replace(re, 'href="');
  re = null;
  re = new RegExp('<\s*img(.*?)src="?' + domain, 'gi');
  str = str.replace(re, '<img$1src="'); 
  return str;
}

function getPath(str){
  var ndx = str.lastIndexOf("?");
  return (ndx == -1)?str:str.substring(0, ndx);
}


function changeStyle(sel){
  var style = sel.options[sel.selectedIndex].value;
  var selType = document.selection.type;
  var rg = document.selection.createRange();
  if (selType == "Text"){
    var newText = "<span class=\"" + style +"\">" + rg.htmlText + "</span>";
    rg.pasteHTML(newText);
  }
  sel.selectedIndex = 0;
  document.all.ewe.focus();
}

//Table functions
function borderToggle(toggle){
  if (toggle) BorderCurrent = !BorderCurrent;
  var tables = document.all.ewe.getElementsByTagName("TABLE");
  for (var i = 0; i < tables.length; ++i){
    if (tables[i].border == 0){
      var tds = tables(i).getElementsByTagName("td");
      for (var j = 0; j < tds.length; ++j){
        if (BorderCurrent) tds[j].runtimeStyle.border = '1 dashed #3C3C3C';
        else tds[j].runtimeStyle.cssText = '';
      }
    }
  }
  updatewyter();
}


function removeHyper(tag){
  if (tag){
    tag.removeNode(false);
  }
}

function eweButton(ImageID, Func, Resize){
  this.ImageID = ImageID;
  this.Func = Func;
  this.Resize = Resize;
}

function setLink(add){
  var lnk = document.createElement('a');
  lnk.href = add;
  prevRange(0).applyElement(lnk);
}
