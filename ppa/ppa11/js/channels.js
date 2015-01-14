function viewprogram( id )
{
	window.open( "ppa.php?show_program=1&paso=1&id=" + id );
}

function channelProperties( id )
{
	window.open( "ppa.php?edit=1&id=" + id );
}

function feedProgram( id )
{
	window.open( "ppa11.php?location=feedprogram&id=" + id );
}

function feedProgram2( id )
{
	window.open( "ppa11.php?location=feedprogram2&id=" + id );
}

function deleteProgram( id )
{
	window.open( "ppa.php?delete_programs=1&id=" + id );
}

function viewSynopsis( id )
{
	window.open( "ppa11.php?location=check_synopsis&id=" + id + "&date=" + _date);
}

function assignSynopsis( id )
{
	window.open( "ppa11.php?location=assign_synopsis&id=" + id + "&date=" + _date);
}

function showProgram( arr )
{
		document.write ( "<table width=\"700\" class=\"titulo\">" );
		document.write ( "<tr><td colspan=\"6\" class=\"titulo\"><a href=\"ppa.php?edit=1\" target=\"nueva\">:::Agregar Canal</a></td></tr>" );
		document.write ( "<tr bgcolor=\"#99EEFF\">" );
		document.write ( "<td align=\"center\" width=\"50\">Id</td>" );
		document.write ( "<td align=\"center\" >Nombre</td>" );
		document.write ( "<td align=\"center\" width=\"50\">Props.</td>" );
		document.write ( "<td align=\"center\" width=\"50\">Ver</td>" );
		document.write ( "<td align=\"center\" width=\"50\">Asignar</td>" );
		document.write ( "<td align=\"center\" width=\"50\">Asignar</td>" );
		document.write ( "<td align=\"center\" width=\"50\">Borrar</td></tr>" );
	for( i=0; i<arr.length; i++)
	{
		reg = arr[i];
		document.write ( "<tr bgcolor=\"#DDEEFF\" onMouseOver=\"this.setAttribute('bgcolor', '#99EEFF', 0)\" onMouseOut=\"this.setAttribute('bgcolor', '#DDEEFF', 0)\" height=\"30\">");
		document.write ( "<td valign=\"top\">" + reg[0] + "</td>");
		document.write ( "<td valign=\"top\">" + reg[1] + "<br>");
		document.write ( "<div class=\"description\">" + reg[2] + "</div></td>");
		document.write ( "<td align=\"center\" onClick=\"channelProperties(" + reg[0] + ");\" style=\"cursor: pointer;\"><img src=\"ppa11/images/props.gif\" /></td>");
		document.write ( "<td align=\"center\" onClick=\"viewprogram(" + reg[0] + ");\" style=\"cursor: pointer;\"><img src=\"ppa11/images/view.gif\" /></td>");
		document.write ( "<td align=\"center\" onClick=\"feedProgram(" + reg[0] + ");\" style=\"cursor: pointer;\"><img src=\"ppa11/images/insert.gif\" /></td>");
		document.write ( "<td align=\"center\" onClick=\"feedProgram2(" + reg[0] + ");\" style=\"cursor: pointer;\"><img src=\"ppa11/images/build.gif\" /></td>");
		document.write ( "<td align=\"center\" onClick=\"deleteProgram(" + reg[0] + ");\" style=\"cursor: pointer;\"><img src=\"ppa11/images/remove.gif\" /></td>");
		document.write ( "</tr>");
	}
	document.write ( "</table>");
}

function showSynopsis( arr )
{
	document.write ( "<table width=\"700\" class=\"titulo\">" );
		document.write ( "<tr bgcolor=\"#99EEFF\">" );
		document.write ( "<td align=\"center\" >Id</td>" );
		document.write ( "<td align=\"center\" >Nombre</td>" );
		document.write ( "<td align=\"center\" width=\"50\">Ver</td>" );
		document.write ( "<td align=\"center\" width=\"50\">Asignar</td>" );
	for( i=0; i<arr.length; i++)
	{
		reg = arr[i];
		document.write ( "<tr bgcolor=\"#DDEEFF\" onMouseOver=\"this.setAttribute('bgcolor', '#99EEFF', 0)\" onMouseOut=\"this.setAttribute('bgcolor', '#DDEEFF', 0)\" height=\"30\" onclick=\"markChannel('" + reg[0] + "')\" >");
		document.write ( "<td valign=\"top\"><input type=\"radio\" id=\"" + reg[0] + "\" name=\"checked\" ></td>");
		document.write ( "<td valign=\"top\">" + reg[1] + "<br>");
		document.write ( "<div class=\"description\">" + reg[2] + "</div></td>");
		document.write ( "<td align=\"center\" onClick=\"viewSynopsis(" + reg[0] + ");\" style=\"cursor: pointer;\"><img src=\"ppa11/images/view.gif\" /></td>");
		document.write ( "<td align=\"center\" onClick=\"assignSynopsis(" + reg[0] + ");\" style=\"cursor: pointer;\"><img src=\"ppa11/images/insert.gif\" /></td>");
		document.write ( "</tr>");
	}
	document.write ( "</table>");
}

function markChannel( id )
{
	var channel = document.getElementById( id );
	channel.checked = true;
}

function showTvcfl( arr ) //Lista grillas fijas revista TvCable
{
		document.write ( "<table width=\"500\" class=\"titulo\">" );
		document.write ( "<tr bgcolor=\"#99EEFF\">" );
		document.write ( "<td align=\"center\" >Id</td>" );
		document.write ( "<td align=\"center\" >Nombre</td>" );
	for( i=0; i<arr.length; i++)
	{
		reg = arr[i];
		document.write ( "<tr bgcolor=\"#DDEEFF\" onMouseOver=\"this.setAttribute('bgcolor', '#99EEFF', 0)\" onMouseOut=\"this.setAttribute('bgcolor', '#DDEEFF', 0)\" height=\"30\" onclick=\"tvcflChecklist('" + reg[0] + "')\" style=\"cursor:pointer;\">");
		document.write ( "<td valign=\"top\" width=\"40\">" + reg[0] + "</td>");
		document.write ( "<td valign=\"top\">" + reg[1] + "<br>");
		document.write ( "<div class=\"description\">" + reg[2] + "</div></td>");
		document.write ( "</tr>");
	}
	document.write ( "</table>");
}

function tvcflChecklist( id )
{
	window.open( "ppa11.php?location=tvcfl_checklist&id=" + id + "&date=" + _date);
}

function exportHgl( ) //Lista destacados
{
	if( id_client == 0 ) alert("Debe seleccionar una cabecera");
	else
	document.location = "ppa11.php?location=hgl_export&nohead=true&id_client=" + id_client + "&date=" + _date;
}

function showHgl( arr, type ) //Lista destacados
{
		document.write ( "<div onclick=\"exportHgl();\" style=\"cursor:pointer;width:500px;text-align:left;font-weight:bold\" class=\"textos\">Exportar</div>" );
		document.write ( "<table width=\"500\" class=\"titulo\">" );
		document.write ( "<tr bgcolor=\"#99EEFF\">" );
		document.write ( "<td align=\"center\" >Id</td>" );
		document.write ( "<td align=\"center\" >Nombre</td>" );
		document.write ( "<td align=\"center\" >Asignar</td>" );
		document.write ( "<td align=\"center\" style=\"width:46px\">Ver</td>" );
	for( i=0; i<arr.length; i++)
	{
		reg = arr[i];
		document.write ( "<tr bgcolor=\"#DDEEFF\" onMouseOver=\"this.setAttribute('bgcolor', '#99EEFF', 0)\" onMouseOut=\"this.setAttribute('bgcolor', '#DDEEFF', 0)\" height=\"30\" style=\"cursor:pointer;\">");
		document.write ( "<td valign=\"top\" width=\"40\">" + reg[0] + "</td>");
		document.write ( "<td valign=\"top\">" + reg[1] + "<br>");
		document.write ( "<div class=\"description\">" + reg[2] + "</div></td>");
		document.write ( "<td align=\"center\" onclick=\"hglChecklist('" + reg[0] + "', '" + type + "');\" style=\"cursor: pointer;\"><img src=\"ppa11/images/insert.gif\" /></td>");
		document.write ( "<td align=\"center\" onClick=\"hglView('" + reg[0] + "', '" + type + "');\" style=\"cursor: pointer;\"><img src=\"ppa11/images/view.gif\" /></td>");
		document.write ( "</tr>");
	}
	document.write ( "</table>");
}

function hglChecklist( id, type )
{
	window.open( "ppa11.php?location=hgl_checklist&id=" + id + "&date=" + _date + "&type=" + type);
}

function hglView( id, type )
{
	window.open( "ppa11.php?location=hgl_view&id=" + id + "&date=" + _date + "&type=" + type);
}

function showSvchl( arr ) //Lista canales Superview
{
		document.write ( "<table width=\"500\" class=\"titulo\">" );
		document.write ( "<tr bgcolor=\"#99EEFF\">" );
		document.write ( "<td align=\"center\" >Id</td>" );
		document.write ( "<td align=\"center\" >Nombre</td>" );
	for( i=0; i<arr.length; i++)
	{
		reg = arr[i];
		document.write ( "<tr bgcolor=\"#DDEEFF\" onMouseOver=\"this.setAttribute('bgcolor', '#99EEFF', 0)\" onMouseOut=\"this.setAttribute('bgcolor', '#DDEEFF', 0)\" height=\"30\" onclick=\"svXls('" + reg[0] + "')\" style=\"cursor:pointer;\">");
		document.write ( "<td valign=\"top\" width=\"40\">" + reg[0] + "</td>");
		document.write ( "<td valign=\"top\">" + reg[1] + "<br>");
		document.write ( "<div class=\"description\">" + reg[2] + "</div></td>");
		document.write ( "</tr>");
	}
	document.write ( "</table>");
}

function svXls( id )
{
	document.location.href =  "ppa11.php?location=svfl_gxls&nohead=true&id=" + id + "&date=" + _date;
}

function showUnechl( arr ) //Lista canales UNE
{
		document.write ( "<table width=\"500\" class=\"titulo\">" );
		document.write ( "<tr bgcolor=\"#99EEFF\">" );
		document.write ( "<td align=\"center\" >Id</td>" );
		document.write ( "<td align=\"center\" >Nombre</td>" );
	for( i=0; i<arr.length; i++)
	{
		reg = arr[i];
		document.write ( "<tr bgcolor=\"#DDEEFF\" onMouseOver=\"this.setAttribute('bgcolor', '#99EEFF', 0)\" onMouseOut=\"this.setAttribute('bgcolor', '#DDEEFF', 0)\" height=\"30\" onclick=\"uneXml('" + reg[0] + "')\" style=\"cursor:pointer;\">");
		document.write ( "<td valign=\"top\" width=\"40\">" + reg[0] + "</td>");
		document.write ( "<td valign=\"top\">" + reg[1] + "<br>");
		document.write ( "<div class=\"description\">" + reg[2] + "</div></td>");
		document.write ( "</tr>");
	}
	document.write ( "</table>");
}

function uneXml( id, time_format )
{
	if( time_format )
		document.location.href =  "ppa11.php?location=unem_gxml&nohead=true&id=" + id + "&date=" + _date + "&time_format=" + time_format;
	else
		document.location.href =  "ppa11.php?location=unem_gxml&nohead=true&id=" + id + "&date=" + _date;
}

function showCcchl( arr ) //Lista canales Superview
{
	document.write ( "<table width=\"500\" class=\"titulo\">" );
	document.write ( "<tr bgcolor=\"#99EEFF\">" );
	document.write ( "<td align=\"center\" >Id</td>" );
	document.write ( "<td align=\"center\" >Nombre</td>" );
	document.write ( "<td align=\"center\" >Mes</td>" );
	document.write ( "<td align=\"center\" >Semana</td>" );
	for( i=0; i<arr.length; i++)
	{
		reg = arr[i];
		document.write ( "<tr bgcolor=\"#DDEEFF\" onMouseOver=\"this.setAttribute('bgcolor', '#99EEFF', 0)\" onMouseOut=\"this.setAttribute('bgcolor', '#DDEEFF', 0)\" height=\"30\" >");
		document.write ( "<td valign=\"top\" width=\"40\">" + reg[0] + "</td>");
		document.write ( "<td valign=\"top\">" + reg[1] + "<br>");
		document.write ( "<div class=\"description\">" + reg[2] + "</div></td>");
		document.write ( "<td align=\"center\" onclick=\"uneXml('" + reg[0] + "', '12H')\" style=\"cursor: pointer;width:50px;\"><img src=\"ppa11/images/month.gif\" /></td>");
		document.write ( "<td align=\"center\" onclick=\"ccChkl('" + reg[0] + "')\"  style=\"cursor: pointer;width:50px;\"><img src=\"ppa11/images/week.gif\" /></td>");
		document.write ( "</tr>");
	}
	document.write ( "</table>");
}

function ccTxt( id )
{
	document.location.href =  "ppa11.php?location=ccfl_gtxt&nohead=true&id=" + id + "&date=" + _date;
}

function ccChkl( id )
{
	document.location.href =  "ppa11.php?location=ccfl_chkl&id=" + id + "&date=" + _date;
}
