GridApp.frm = null;
GridApp.setGridPage = function (str) {
    GridApp.gridPage = str;
};
GridApp.resetSelect = function (id) {
    var sel = document.getElementById(id);
    sel.selectedIndex = 0;
};
GridApp.rechargeSelect = function (id) {
    GridApp.category = document.getElementById("ppa_gender").value;
    var sel = document.getElementById(id);
    var fc = sel.parentNode;
    var ch = document.createElement("select");
    ch.appendChild(document.createElement("option"));
    ch.firstChild.appendChild(document.createTextNode("Cargando..."));
    fc.replaceChild(ch, sel);
    ch.id = id;
    if (id == "ppa_gender") var _url = SERVICES_PATH + "?action=filters" + ((GridApp.header != 0) ? ("&header=" + GridApp.header) : "");
    else var _url = SERVICES_PATH + "?action=filters" + ((GridApp.category != 0) ? ("&category=" + GridApp.category) : "") + ((GridApp.header != 0) ? ("&header=" + GridApp.header) : "");
    var ht = new HTTPRequest();
    ht.timeout = 2000;
    ht.url = _url;
    ht.onError = showError;
    ht.async = false;
    ht.cache = false;
    ht.process();
    var root_el = ppaGetMainNode(ht.responseXML);
    if (id == "ppa_gender") var newsel = GridApp.createGenderSel(root_el.getElementsByTagName("categories").item(0));
    else var newsel = GridApp.createChannelSel(root_el.getElementsByTagName("channels").item(0));
    fc.replaceChild(newsel, ch);
};
GridApp.createGenderSel = function (xml) {
    var sel = document.createElement("select");
    sel.name = "gender";
    sel.id = "ppa_gender";
    if (GridApp.isLockGender) sel.disabled = true;
    sel.onchange = function () {
        GridApp.rechargeSelect("ppa_channel");
    };
    var fs = xml.childNodes;
    var opt = document.createElement("option");
    opt.value = "0";
    opt.appendChild(document.createTextNode("Todos"));
    sel.appendChild(opt);
    for (var i = 0; i < fs.length; i++) {
        var opt = document.createElement("option");
        opt.value = fs[i].getAttribute("id");
        try {
            opt.appendChild(document.createTextNode(fs[i].firstChild.nodeValue));
            sel.appendChild(opt);
        } catch (e) {}
    }
    if (GridApp.category != "") sel.value = GridApp.category;
    return sel;
};
GridApp.createCitySel = function () {
    var sel = document.createElement("select");
    if (GridApp.isLockHeadend) sel.disabled = true;
    sel.name = "city";
    sel.id = "ppa_city";
    sel.onchange = function () {
        GridApp.header = getIdFromCityName(this.value);
        GridApp.headerName = this.value;
        GridApp.rechargeSelect("ppa_gender");
        GridApp.rechargeSelect("ppa_channel");
    };
    if (GridApp.headerName != "") for (var i in ppaCitiesId) {
        var opt = document.createElement("option");
        opt.value = ppaCitiesName[i];
        try {
            opt.appendChild(document.createTextNode(ppaCitiesName[i]));
            if (ppaCitiesName[i].toLowerCase() == GridApp.headerName) {
                opt.selected = "true";
                GridApp.header = ppaCitiesId[i];
            }
            sel.appendChild(opt);
        } catch (e) {}
    } else for (var i in ppaCitiesId) {
        var opt = document.createElement("option");
        opt.value = ppaCitiesName[i];
        try {
            opt.appendChild(document.createTextNode(ppaCitiesName[i]));
            sel.appendChild(opt);
        } catch (e) {}
    }
    if (GridApp.category != "") sel.value = GridApp.category;
    return sel;
};
GridApp.createCityField = function (lbl) {
    var div = document.createElement("div");
    div.className = "cities_fld";
    if (lbl) {
        var p = document.createElement("p");
        p.appendChild(document.createTextNode(lbl));
        div.appendChild(p);
    }
    div.appendChild(GridApp.createCitySel());
    return div;
};
GridApp.createChannelSel = function (xml) {
    var div = document.createElement("div");
    div.className = "channel_container";
    div.id = "ppa_channel";
    var dl = document.createElement("div");
    dl.className = "select";
    dl.innerHTML = "Seleccione";
    div.appendChild(dl);
    var dh = document.createElement("div");
    dh.className = "opts";
    dh.s = dh;
    dh.onmouseout = function () {
        this.s.style.display = "none";
    };
    dh.onmouseover = function () {
        this.s.style.display = "block";
    };
    dl.s = dh;
    dl.onclick = function () {
        this.s.style.display = "block";
    };
    dl.onmouseout = function () {
        this.s.style.display = "none";
    };
    var opt = document.createElement("div");
    var c = document.createElement("input");
    c.type = "checkbox";
    c.name = "ppa_channels_select_all";
    c.id = "ppa_chnId_select_all";
    c.chk = true;
    c.checked = true;
    c.onclick = function () {
        var frmEls = document.getElementById("ppa_filterForm").elements;
        if (this.checked) {
            for (var i = 0; i < frmEls.length; i++) if (frmEls[i].name == "ppa_channels[]") frmEls[i].checked = true;
        } else {
            for (var i = 0; i < frmEls.length; i++) if (frmEls[i].name == "ppa_channels[]") frmEls[i].checked = false;
        }
    };
    var l = document.createElement("label");
    l.htmlFor = "ppa_chnId_select_all";
    l.appendChild(document.createTextNode("Todos"));
    opt.appendChild(l);
    dh.appendChild(c);
    dh.appendChild(opt);
    var fs = xml.childNodes;
    for (var i = 0; i < fs.length; i++) {
        var opt = document.createElement("div");
        var c = document.createElement("input");
        var cid = fs[i].getAttribute("id");
        c.type = "checkbox";
        c.name = "ppa_channels[]";
        c.value = cid;
        c.id = "ppa_chnId_" + cid;
        c.checked = true;
        c.onclick = function () {
            document.getElementById("ppa_chnId_select_all").checked = false;
        };
        var l = document.createElement("label");
        l.htmlFor = "ppa_chnId_" + cid;
        l.appendChild(document.createTextNode(fs[i].firstChild.nodeValue));
        opt.appendChild(l);
        dh.appendChild(c);
        dh.appendChild(opt);
    }
    div.appendChild(dh);
    return div;
    var sel = document.createElement("select");
    sel.name = "channel";
    sel.id = "ppa_channel";
    if (GridApp.isLockChannel) sel.disabled = true;
    var fs = xml.childNodes;
    var opt = document.createElement("option");
    opt.value = "0";
    opt.appendChild(document.createTextNode("Todos"));
    sel.appendChild(opt);
    if (GridApp.channel != "") {
        for (var i = 0; i < fs.length; i++) {
            opt = document.createElement("option");
            opt.value = fs[i].getAttribute("id");
            var chn = fs[i].firstChild.nodeValue;
            opt.appendChild(document.createTextNode(chn));
            if (chn == GridApp.channel || opt.value == GridApp.channel) opt.selected = "true";
            sel.appendChild(opt);
        }
    } else {
        for (var i = 0; i < fs.length; i++) {
            opt = document.createElement("option");
            opt.value = fs[i].getAttribute("id");
            opt.appendChild(document.createTextNode(fs[i].firstChild.nodeValue));
            sel.appendChild(opt);
        }
        return sel;
    }
};
GridApp.createGenderField = function (xml, lbl) {
    var div = document.createElement("div");
    if (lbl) {
        var p = document.createElement("p");
        p.appendChild(document.createTextNode(lbl));
        div.appendChild(p);
    }
    div.appendChild(GridApp.createGenderSel(xml));
    return div;
};
GridApp.createChannelField = function (xml, lbl) {
    var div = document.createElement("div");
    if (lbl) {
        var p = document.createElement("p");
        p.appendChild(document.createTextNode(lbl));
        div.appendChild(p);
    }
    div.appendChild(GridApp.createChannelSel(xml));
    return div;
};
GridApp.showStartCal = function () {
    startCal.showAtElement(document.getElementById("ppa_startDate"));
};
GridApp.showEndCal = function () {
    endCal.showAtElement(document.getElementById("ppa_endDate"));
};
GridApp.onSelectDate = function (cal, date) {
    cal.field.value = date;
    if (cal.dateClicked) {
        cal.hide();
    }
};
GridApp.closeHandler = function (cal) {
    cal.hide();
    _dynarch_popupCalendar = null;
};
GridApp.createCalendar = function (n, cal) {
    cal.showTime = false;
    var i = document.createElement("input");
    i.id = n;
    i.name = n;
    cal.field = i;
    cal.create(document.getElementById("ppa_filterForm"));
    cal.setDateFormat("%d/%m/%y");
    return i;
};
GridApp.createField = function (tit, cn) {
    var div = document.createElement("div");
    if (cn) div.className = cn;
    var txt = document.createElement("p");
    txt.appendChild(document.createTextNode(tit));
    div.appendChild(txt);
    return div;
};
GridApp.createSearchByField = function () {
    var div = GridApp.createField("Inicial");
};
GridApp.createStartDateField = function () {
    var div = document.createElement("div");
    div.appendChild(GridApp.createField("Inicio"));
    var i = GridApp.createCalendar("ppa_startDate", startCal);
    i.onclick = GridApp.showStartCal;
    var d = new Date();
    var m = (d.getMonth() < 9 ? ("0" + (d.getMonth() + 1)) : d.getMonth() + 1);
    var dt = (d.getDate() <= 9 ? ("0" + d.getDate()) : d.getDate());
    if (d.getHours() > 12) {
        var h = d.getHours() - 12;
        var mer = "pm";
    } else {
        var h = d.getHours() == 0 ? 12 : d.getHours();
        var mer = "am";
    }
    h = (h > 9) ? h : "0" + h;
    if (d.getMinutes() > 30) var mins = "30";
    else var mins = "00";
    if (GridApp.startDate != "") i.value = GridApp.startDate;
    else i.value = dt + "/" + m + "/" + d.getFullYear().toString().substr(2, 2);
    i.readOnly = "readonly";
    div.appendChild(i);
    var sd = new Date();
    startCal.setDate(sd);
    div.appendChild(GridApp.createField(""));
    div.appendChild(GridApp.createTime("ppa_start_", h, mins, mer));
    return div;
};
GridApp.createTime = function (n, h, m, a) {
    h = (h ? h : "");
    m = (m ? m : "");
    a = (a ? a : "");
    var t = new timeField(n);
    t.startTime(h, m, a);
    var d = t.createTime();
    return d;
}
GridApp.createEndDateField = function () {
    var div = GridApp.createField("Fin");
    var i = GridApp.createCalendar("ppa_endDate", endCal);
    i.onclick = GridApp.showEndCal;
    if (GridApp.endDate != "") i.value = GridApp.endDate;
    else i.value = "Calendario";
    i.readOnly = true;
    div.appendChild(i);
    div.appendChild(GridApp.createField(""));
    div.appendChild(GridApp.createTime("ppa_end_"));
    return div;
};
GridApp.createInputField = function (tit, nam, val) {
    var div = GridApp.createField(tit);
    div.className = "ppa_left";
    var i = document.createElement("input");
    i.name = nam;
    i.id = nam;
    if (val != "") i.value = val;
    div.appendChild(i);
    return div;
};
GridApp.createSearchButton = function () {
    var div = document.createElement("div");
    var b = document.createElement("input");
    b.className = "ppa_btnSearch";
    b.type = "submit";
    b.value = "Buscar"; /*b.onclick = function(){try{ppaG.consultGrid(0);}catch(e){alert("La instancia principal no existe, revise la documentación! " + e)}};*/
    div.appendChild(b);
    return div;
};
GridApp.createFiltersLine = function (el) {
    var ln = document.createElement("div");
    ln.className = "filter_line";
    ln.appendChild(el);
    return ln;
};
GridApp.createFilters = function () {
    var httpstr = '<form id="ppa_filterForm" name="ppa_filterForm" method="post" action="" onsubmit="ppaConsultGrid(0);return false"><div id="ppa_filterContainerTop"><\/div><\/form>';
    document.write(httpstr);
    var t = document.getElementById("ppa_filterContainerTop");
    GridApp.frm = document.getElementById("ppa_filterForm");
    var fc = document.createElement("div");
    t.appendChild(fc);
    fc.id = "ppa_filterContainer";
    var ln; /*city, gender, channel*/
    ln = GridApp.createFiltersLine(GridApp.createCityField("Ciudad"));
    var _url = SERVICES_PATH + "?action=filters" + ((GridApp.header != 0) ? ("&header=" + GridApp.header) : "");
    var ht = new HTTPRequest();
    ht.timeout = 2000;
    ht.url = _url;
    ht.onError = showError;
    ht.async = false;
    ht.cache = false;
    ht.process();
    var root_el = ppaGetMainNode(ht.responseXML);
    ln.appendChild(GridApp.createGenderField(root_el.getElementsByTagName("categories").item(0), "Género"));
    ln.appendChild(GridApp.createChannelField(root_el.getElementsByTagName("channels").item(0), "Canal"));
    fc.appendChild(ln); /*date*/
    fs = document.createElement("fieldset");
    lg = document.createElement("legend");
    lg.appendChild(document.createTextNode("Fecha y hora"));
    fs.appendChild(lg);
    ln = GridApp.createFiltersLine(GridApp.createStartDateField());
    fs.appendChild(ln);
    ln = GridApp.createFiltersLine(GridApp.createEndDateField());
    fs.appendChild(ln);
    fc.appendChild(fs);
    ln = GridApp.createFiltersLine(GridApp.createInputField("Título", "ppa_title", GridApp.title));
    ln.appendChild(GridApp.createInputField("Actor/Director", "ppa_actor", GridApp.actor));
    ln.appendChild(GridApp.createSearchButton());
    fc.appendChild(ln);
    if (GridApp.category != "") {
        GridApp.rechargeSelect("ppa_channel");
    }
};
var startCal = new Calendar(0, null, GridApp.onSelectDate, GridApp.closeHandler);
var endCal = new Calendar(0, null, GridApp.onSelectDate, GridApp.closeHandler);
GridApp.setGridPage(PPA_GRID_PAGE);