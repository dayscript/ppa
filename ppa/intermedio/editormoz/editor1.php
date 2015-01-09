<html>
<head>
<title>Mozilla Rich Text Editing Demo</title>

<style type="text/css">
.imagebutton {height: 22; width: 23; border: solid 2px #C0C0C0; background-color: #C0C0C0}
.image {position: relative; left: 1; top: 1; height:20; width:21; border:none;}
.toolbar {height: 30; background-color: #C0C0C0;}
</style>

<script>

var command = "";

function InitToolbarButtons() {
  kids = document.getElementsByTagName('DIV');

  for (var i=0; i < kids.length; i++) {
    if (kids[i].className == "imagebutton") {
      kids[i].onmouseover = tbmouseover;
      kids[i].onmouseout = tbmouseout;
      kids[i].onmousedown = tbmousedown;
      kids[i].onmouseup = tbmouseup;
      kids[i].onclick = tbclick;
    }
  }
}

function tbmousedown(e)
{
  this.firstChild.style.left = 2;
  this.firstChild.style.top = 2;
  this.style.border="inset 2px";
  e.preventDefault();
}

function tbmouseup()
{
  this.firstChild.style.left = 1;
  this.firstChild.style.top = 1;
  this.style.border="outset 2px";
}

function tbmouseout()
{
  this.style.border="solid 2px #C0C0C0";
}

function tbmouseover()
{
  this.style.border="outset 2px";
}

  function insertNodeAtSelection(win, insertNode)
  {
      // get current selection
      var sel = win.getSelection();

      // get the first range of the selection
      // (there's almost always only one range)
      var range = sel.getRangeAt(0);

      // deselect everything
      sel.removeAllRanges();

      // remove content of current selection from document
      range.deleteContents();

      // get location of current selection
      var container = range.startContainer;
      var pos = range.startOffset;

      // make a new range for the new selection
      range=document.createRange();

      if (container.nodeType==3 && insertNode.nodeType==3) {

        // if we insert text in a textnode, do optimized insertion
        container.insertData(pos, insertNode.nodeValue);

        // put cursor after inserted text
        range.setEnd(container, pos+insertNode.length);
        range.setStart(container, pos+insertNode.length);

      } else {


        var afterNode;
        if (container.nodeType==3) {

          // when inserting into a textnode
          // we create 2 new textnodes
          // and put the insertNode in between

          var textNode = container;
          container = textNode.parentNode;
          var text = textNode.nodeValue;

          // text before the split
          var textBefore = text.substr(0,pos);
          // text after the split
          var textAfter = text.substr(pos);

          var beforeNode = document.createTextNode(textBefore);
          var afterNode = document.createTextNode(textAfter);

          // insert the 3 new nodes before the old one
          container.insertBefore(afterNode, textNode);
          container.insertBefore(insertNode, afterNode);
          container.insertBefore(beforeNode, insertNode);

          // remove the old node
          container.removeChild(textNode);

        } else {

          // else simply insert the node
          afterNode = container.childNodes[pos];
          container.insertBefore(insertNode, afterNode);
        }

        range.setEnd(afterNode, 0);
        range.setStart(afterNode, 0);
      }

      sel.addRange(range);
  };

function getOffsetTop(elm) {

  var mOffsetTop = elm.offsetTop;
  var mOffsetParent = elm.offsetParent;

  while(mOffsetParent){
    mOffsetTop += mOffsetParent.offsetTop;
    mOffsetParent = mOffsetParent.offsetParent;
  }
 
  return mOffsetTop;
}

function getOffsetLeft(elm) {

  var mOffsetLeft = elm.offsetLeft;
  var mOffsetParent = elm.offsetParent;

  while(mOffsetParent){
    mOffsetLeft += mOffsetParent.offsetLeft;
    mOffsetParent = mOffsetParent.offsetParent;
  }
 
  return mOffsetLeft;
}

function tbclick()
{

  if ((this.id == "forecolor") || (this.id == "hilitecolor")) {
    parent.command = this.id;
    buttonElement = document.getElementById(this.id);
    document.getElementById("colorpalette").style.left = getOffsetLeft(buttonElement);
    document.getElementById("colorpalette").style.top = getOffsetTop(buttonElement) + buttonElement.offsetHeight;
    document.getElementById("colorpalette").style.visibility="visible";
  } else if (this.id == "createlink") {
    var szURL = prompt("Enter a URL:", "");
    document.getElementById('edit').contentWindow.document.execCommand("CreateLink",false,szURL)
  } else if (this.id == "createtable") {
    e = document.getElementById("edit");
    rowstext = prompt("enter rows");
    colstext = prompt("enter cols");
    rows = parseInt(rowstext);
    cols = parseInt(colstext);
    if ((rows > 0) && (cols > 0)) {
      table = e.contentWindow.document.createElement("table");
      table.setAttribute("border", "1");
      table.setAttribute("cellpadding", "2");
      table.setAttribute("cellspacing", "2");
      tbody = e.contentWindow.document.createElement("tbody");
      for (var i=0; i < rows; i++) {
        tr =e.contentWindow.document.createElement("tr");
        for (var j=0; j < cols; j++) {
          td =e.contentWindow.document.createElement("td");
          br =e.contentWindow.document.createElement("br");
          td.appendChild(br);
          tr.appendChild(td);
        }
        tbody.appendChild(tr);
      }
      table.appendChild(tbody);      
      insertNodeAtSelection(e.contentWindow, table);
    }
  } else {
     document.getElementById('edit').contentWindow.document.execCommand(this.id, false, null);
  }
}

function Select(selectname)
{
  var cursel = document.getElementById(selectname).selectedIndex;
  /* First one is always a label */
  if (cursel != 0) {
    var selected = document.getElementById(selectname).options[cursel].value;
    document.getElementById('edit').contentWindow.document.execCommand(selectname, false, selected);
    document.getElementById(selectname).selectedIndex = 0;
  }
  document.getElementById("edit").contentWindow.focus();
}

function dismisscolorpalette()
{
  document.getElementById("colorpalette").style.visibility="hidden";
}

function Start() {
  document.getElementById('edit').contentWindow.document.designMode = "on";
  document.getElementById('edit').contentWindow.document.body.innerHTML = '<?=$contenido?>';
  try {
    document.getElementById('edit').contentWindow.document.execCommand("undo", false, null);
  }  catch (e) {
    alert("This demo is not supported on your level of Mozilla.");
  }

  InitToolbarButtons();
  document.addEventListener("mousedown", dismisscolorpalette, true);
  document.getElementById("edit").contentWindow.document.addEventListener("mousedown", dismisscolorpalette, true);
  document.addEventListener("keypress", dismisscolorpalette, true);
  document.getElementById("edit").contentWindow.document.addEventListener("keypress", dismisscolorpalette, true);
}
</script>
</head>
<body onLoad="Start()">
<table width="100%"  bgcolor="#C0C0C0" id="toolbar1">
<!--
<tr>
<td>
<div class="imagebutton" id="cut"><img class="image" src="../editormoz/images/cut.gif" alt="Cut" title="Cut"></div>
</td>
<td>
<div class="imagebutton" id="copy"><img class="image" src="copy.gif" alt="Copy" title="Copy"></div>
</td>
<td>
<div class="imagebutton" id="paste"><img class="image" src="paste.gif" alt="Paste" title="Paste"></div>
<td>
</td>
<td>
</td>
-->
<td>
<div class="imagebutton" id="undo"><img class="image" src="../editormoz/images/undo.gif" alt="Undo" title="Undo"></div>

</td>
<td>
<div class="imagebutton" id="redo"><img class="image" src="../editormoz/images/redo.gif" alt="Redo" title="Redo"></div>
</td>
<td>
<div style="left: 10;" class="imagebutton" id="createlink"><img class="image" src="../editormoz/images/createlink.gif" alt="Insert Link" title="Insert Link"></div>
</td>
<td>
<div style="left: 10;" class="imagebutton" id="createtable"><img class="image" src="../editormoz/images/inserttable.gif" alt="Insert Table" title="Insert Table"></div>
</td>
<td>

<select unselectable="on" id="fontsize" onchange="Select(this.id);">
  <option value="Size">Tama�o</option>
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option value="5">5</option>

  <option value="6">6</option>
  <option value="7">7</option>  
</select>
</td>
<td>
<div class="imagebutton" id="bold"><img class="image" src="../editormoz/images/bold.gif" alt="Bold" title="Bold"></div>
</td>
<td>
<div class="imagebutton" id="italic"><img class="image" src="../editormoz/images/italic.gif" alt="Italic" title="Italic"></div>
</td>
<td>
<div class="imagebutton" id="underline"><img class="image" src="../editormoz/images/underline.gif" alt="Underline" title="Underline"></div>
</td>
<td>
<div style="left: 10;" class="imagebutton" id="justifyleft"><img class="image" src="../editormoz/images/justifyleft.gif" alt="Align Left" title="Align Left"></div>
</td>
<td>
<div style="left: 40;" class="imagebutton" id="justifycenter"><img class="image" src="../editormoz/images/justifycenter.gif" alt="Center" title="Center"></div>
</td>
<td>

<div style="left: 70;" class="imagebutton" id="justifyright"><img class="image" src="../editormoz/images/justifyright.gif" alt="Align Right" title="Align Right"></div>
</td>
</tr>
<tr>
<td>
<div style="left: 10;" class="imagebutton" id="insertorderedlist"><img class="image" src="../editormoz/images/insertorderedlist.gif" alt="Ordered List" title="Ordered List"></div>
</td>
<td>
<div style="left: 40;" class="imagebutton" id="insertunorderedlist"><img class="image" src="../editormoz/images/insertunorderedlist.gif" alt="Unordered List" title="Unordered List"></div>
</td>
<td>
<div style="left: 10;" class="imagebutton" id="outdent"><img class="image" src="../editormoz/images/outdent.gif" alt="Outdent" title="Outdent"></div>
</td>
<td>
<div style="left: 40;" class="imagebutton" id="indent"><img class="image" src="../editormoz/images/inindent.gif" alt="Indent" title="Indent"></div>
</td>

</table>
<!--
<table bgcolor="#C0C0C0" id="toolbar2">
<tr>
<td>
<select id="formatblock" onchange="Select(this.id);">
  <option value="<p>">Normal</option>
  <option value="<p>">Paragraph</option>
  <option value="<h1>">Heading 1 <h1></option>
  <option value="<h2>">Heading 2 <h2></option>
  <option value="<h3>">Heading 3 <h3></option>

  <option value="<h4>">Heading 4 <h4></option>
  <option value="<h5>">Heading 5 <h5></option>
  <option value="<h6>">Heading 6 <h6></option>
  <option value="<address>">Address <ADDR></option>
  <option value="<pre>">Formatted <pre></option>
</select>
</td>
<td>
<select id="fontname" onchange="Select(this.id);">
  <option value="Font">Font</option>
  <option value="Arial">Arial</option>
  <option value="Courier">Courier</option>
  <option value="Times New Roman">Times New Roman</option>
</select>
</td>
<td>

<select unselectable="on" id="fontsize" onchange="Select(this.id);">
  <option value="Size">Size</option>
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option value="5">5</option>

  <option value="6">6</option>
  <option value="7">7</option>  
</select>
</td>
<td>
<div class="imagebutton" id="bold"><img class="image" src="bold.gif" alt="Bold" title="Bold"></div>
</td>
<td>
<div class="imagebutton" id="italic"><img class="image" src="italic.gif" alt="Italic" title="Italic"></div>
</td>
<td>
<div class="imagebutton" id="underline"><img class="image" src="underline.gif" alt="Underline" title="Underline"></div>
</td>

<td>
</td>
<td>
<div style="left: 10;" class="imagebutton" id="forecolor"><img class="image" src="forecolor.gif" alt="Text Color" title="Text Color"></div>
</td>
<td>
<div style="left: 40;" class="imagebutton" id="hilitecolor"><img class="image" src="backcolor.gif" alt="Background Color" title="Background Color"></div>
</td>
<td>
</td>
<td>
<div style="left: 10;" class="imagebutton" id="justifyleft"><img class="image" src="justifyleft.gif" alt="Align Left" title="Align Left"></div>
</td>
<td>
<div style="left: 40;" class="imagebutton" id="justifycenter"><img class="image" src="justifycenter.gif" alt="Center" title="Center"></div>
</td>
<td>

<div style="left: 70;" class="imagebutton" id="justifyright"><img class="image" src="justifyright.gif" alt="Align Right" title="Align Right"></div>
</td>
<td>
</td>
<td>
<div style="left: 10;" class="imagebutton" id="insertorderedlist"><img class="image" src="orderedlist.gif" alt="Ordered List" title="Ordered List"></div>
</td>
<td>
<div style="left: 40;" class="imagebutton" id="insertunorderedlist"><img class="image" src="unorderedlist.gif" alt="Unordered List" title="Unordered List"></div>
</td>
<td>
</td>
<td>
<div style="left: 10;" class="imagebutton" id="outdent"><img class="image" src="outdent.gif" alt="Outdent" title="Outdent"></div>
</td>
<td>
<div style="left: 40;" class="imagebutton" id="indent"><img class="image" src="indent.gif" alt="Indent" title="Indent"></div>

</td>
</tr>
</table>
-->
<iframe id="edit" width="395" height="200px"></iframe>
<iframe width="50" height="170" id="colorpalette" src="colors.html" style="visibility:hidden; position: absolute;"></iframe></iframe>
<script>
function viewsource(source)
{
  if (source) {
    var html = document.createTextNode(document.getElementById('edit').contentWindow.document.body.innerHTML);
    document.getElementById('edit').contentWindow.document.body.innerHTML = "";
    document.getElementById('edit').contentWindow.document.body.appendChild(html);
    document.getElementById("toolbar1").style.visibility="hidden";
    document.getElementById("toolbar2").style.visibility="hidden";  
  } else {
    var html = document.getElementById('edit').contentWindow.document.body.ownerDocument.createRange();
    html.selectNodeContents(document.getElementById('edit').contentWindow.document.body);
    document.getElementById('edit').contentWindow.document.body.innerHTML = html.toString();
    document.getElementById("toolbar1").style.visibility="visible";
    document.getElementById("toolbar2").style.visibility="visible";  
  }
}

function usecss(source)
{
  document.getElementById('edit').contentWindow.document.execCommand("useCSS", false, !(source));  
}

function readonly(source)
{
    document.getElementById('edit').contentWindow.document.execCommand("readonly", false, !(source));  
}
</script>
</body>
</html>