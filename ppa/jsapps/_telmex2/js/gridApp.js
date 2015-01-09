function Table( rows, cols )
{
	this.rows = rows;
	this.cols = cols;
	this.table = document.createElement( "table" );
	this.table.setAttribute( "border", "1" );
	
	for( var i=0; i<rows; i++ )
	{
		var row = this.table.insertRow(i);
		for( var j=0; j<cols; j++ )
		{
			col = row.insertCell(j);
//			col.appendChild( document.createTextNode("\xA0") );
		}
	}
	
	this.appendInBody = function ( )
	{
		document.body.appendChild( this.table ) 
	}

	this.appendInId = function ( id )
	{
		document.getElementById( id ).appendChild( this.table ) 
	}

	this.deleteRow = function ( rowPos )
	{
		this.table.deleteRow( rowPos );
	}

	this.getCell = function ( rowPos, colPos )
	{
		return this.table.firstChild.childNodes.item( rowPos ).childNodes.item( colPos );
	}

	this.getLastRow = function ( )
	{
		return this.table.firstChild.childNodes.item( ( this.numRows() - 1 ) );
	}
	
	this.getRow = function ( rowPos )
	{
		return this.table.firstChild.childNodes.item( rowPos );
	}
	
	this.getRowPos = function ( rowElement )
	{
		for( var i=0; rowElement; i++)
		{
			rowElement = rowElement.previousSibling;
		}
		return (i-1);
	}
	
	this.getTable = function ( )
	{
		return this.table;
	}

	this.insertInCell = function ( row, col, el )
	{
		this.getCell(row,col).appendChild( el );
	}
	
	this.insertRow = function ( row )
	{
		if( row != undefined )
		{
			row = this.table.insertRow( row );
			for( var j=0; j<this.cols; j++ )
			{
				col = row.insertCell(j);
				col.appendChild( document.createTextNode("\xA0") );
			}
		}
		else
		{
			row = this.insertRow( this.numRows() );
		}
		return row;
	}
	
	this.numRows = function ( )
	{
		if( this.table.firstChild )
			return this.table.firstChild.childNodes.length;
		else
			return 0;
	}	

	this.setColSpan = function ( row, col, span )
	{
		cell    = this.getCell( row, col );
		_parent = cell.parentNode;
		for(var i=1; i<span; i++ )
		{
			_parent.removeChild( cell.nextSibling );
		}
		this.getCell(row,col).setAttribute( "colSpan", span );
	}	

	this.setAttribute = function ( _name, _value )
	{
		this.table.setAttribute( _name, _value );
	}	
}

//
// Copyright 2006 Nelson Daza. nelson.daza@gmail.com. All rights reserved.
// DOM HTML 
// ----------------------------------------------------
//
// HTTPRequest.js
//
//	version 1.0.5.2 major.minor[.revision[.build]]
//

// CONSTRUCTOR FUNCTION
//

function HTTPRequest ( )	{
	/* PROPERTIES */
	this.xmlHttpRequest = null;

	this.timeout = 0;
		//	Timeout period ( in ms ) until an async request will be aborted, and the ontimeout function will be called.
	this.cache = false;
		//	¿A unique numeric value is appended so that the requested URL will not be cached?
	this.url = null;
		//	The url that the request will be made to, which defaults to the current url of the window.
	this.method = 'GET';
		//	The method of the request, either GET (default), POST, or HEAD.
	this.async = true;
		//	¿The request will be asynchronous?
	this.username = null;
		//	The username used to access the URL.
	this.password = null;
		//	The password used to access the URL.
	this.parameters = new Object( );
		//	Hashtable holding name/value pairs which will be added to the url for a GET request or the request content for a POST request.
	this.requestIndex = HTTPRequest.length ++;
		//	The sequential index number of this request, updated internally.
	this.responseReceived = false;
		//	Indicates whether a response has been received yet from the server.
	this.groupName = null;
		//	The name of the group that this request belongs to.
	this.queryString = '';
		//	The query string to be added to the end of a GET request, in proper URIEncoded format.
	this.responseText = null;
		//	This will hold the text contents of the response - even in case of error.
	this.responseXML = null;
		//	This will hold the XML content.
	this.status = null;
		//	This will hold the status code of the response as returned by the server.
	this.statusText = null;
		//	This will hold the text description of the response code.
	this.aborted = false;
		//	Flag to indicate whether the request has been aborted.

	/* Event Handlers */
	this.onTimeout = null; 
		//	If a timeout period is set, and it is reached before a response is received ...
	this.onChangeState = null;
		//	When readyState changes and it passed the readyState.
	this.onLoading = null;
		//	When readyState = 1 and after onChangeState.
	this.onLoaded = null;
		//	When readyState = 2 and after onChangeState.
	this.onInteractive = null;
		//	When readyState = 3 and after onChangeState.
	this.onComplete = null;
		//	When readyState = 4 and after onChangeState.
	this.onSuccess = null;
		//	When statusCode = 200 and after onComplete.
	this.onCached = null;
		//	When statusCode = 304 and after onComplete.
	this.onError = null;
		//	When statusCode != 200 and != 304 and after onComplete.
	this.onGroupBegin = null;
		//	If has a group name and if this is the first request in the group to become active it passed the group name.
	this.onGroupEnd = null;
		//	If has a group name and this request is the last request in the group to complete, this function reference will be called.

	/* START THE REAL CREATION */
	this.xmlHttpRequest = HTTPRequest.CreateXMLHTTPRequest( );
	if ( !this.xmlHttpRequest )
		return null;
	
	/* INTERNAL EVENT HANDLERS */
	this.onLoadingInternalHandled = false;
	this.onLoadedInternalHandled = false;
	this.onInteractiveInternalHandled = false;
	this.onCompleteInternalHandled = false;

	this.onLoadingInternal = function( )	{
		if ( this.onLoadingInternalHandled )
			return;
		HTTPRequest.activeRequests ++;
		if ( HTTPRequest.activeRequests == 1 && typeof( window['HTTPRequestBegin'] ) == 'function' )
			HTTPRequestBegin( );
		if ( this.groupName != null )	{
			if ( typeof( HTTPRequest.ActiveHTTPGroupRequests[this.groupName] ) == 'undefined' )
				HTTPRequest.activeHTTPGroupRequests[this.groupName] = 0;
			HTTPRequest.activeHTTPGroupRequests[this.groupName]++;
			if ( HTTPRequest.activeHTTPGroupRequests[this.groupName]==1 && typeof( this.onGroupBegin ) == 'function' )
				this.onGroupBegin( this.groupName );
		}
		if ( typeof( this.onChangeState ) == 'function' )
			this.onChangeState( this );
		if ( typeof( this.onLoading ) == 'function' )
			this.onLoading( this );
		this.onLoadingInternalHandled = true;
	};
	this.onLoadedInternal = function( ) {
		if ( this.onLoadedInternalHandled )
			return;
		if ( typeof( this.onChangeState ) == 'function' )
			this.onChangeState( this );
		if ( typeof( this.onLoaded ) == 'function' )
			this.onLoaded( this );
		this.onLoadedInternalHandled = true;
	};
	this.onInteractiveInternal = function( )	{
		if ( this.onInteractiveInternalHandled )
			return;
		if ( typeof( this.onChangeState ) == 'function' )
			this.onChangeState( this );
		if ( typeof( this.onInteractive ) == 'function' )
			this.onInteractive( this );
		this.onInteractiveInternalHandled = true;
	};
	this.onCompleteInternal = function( )	{
		if ( this.onCompleteInternalHandled || this.aborted )
			return;
		this.onCompleteInternalHandled = true;
		HTTPRequest.activeHTTPRequests--;
		if ( HTTPRequest.activeHTTPRequests == 0 && typeof( window['HTTPRequestEnd'] ) == 'function' )
			HTTPRequestEnd( );
		if ( this.groupName != null )	{
			HTTPRequest.activeHTTPGroupRequests[this.groupName]--;
			if ( HTTPRequest.activeHTTPGroupRequests[this.groupName]==0 && typeof( this.onGroupEnd ) == 'function' )
				this.onGroupEnd( this.groupName );
		}
		this.responseReceived = true;
		this.status = this.xmlHttpRequest.status;
		this.statusText = this.xmlHttpRequest.statusText;
		this.responseText = this.xmlHttpRequest.responseText;
		this.responseXML = this.xmlHttpRequest.responseXML;
		if ( typeof( this.onChangeState ) == 'function' )
			this.onChangeState( this );
		if ( typeof( this.onComplete ) == 'function' )
			this.onComplete( this );
		if ( this.xmlHttpRequest.status == 200 )	{
			if ( typeof( this.onSuccess ) == 'function' )
				this.onSuccess( this );
		}
		else if ( this.xmlHttpRequest.status == 304 )	{
			if ( typeof( this.onCached ) == 'function' )
				this.onCached( this );
			else if ( typeof( this.onSuccess ) == 'function' )
				this.onSuccess( this );
		}				
		else if ( typeof( this.onError ) == 'function' )
			this.onError( this );
		
		// Clean up so IE doesn't leak memory
		delete this.xmlHttpRequest['onreadystatechange'];
		this.xmlHttpRequest = null;
	};
	this.onTimeoutInternal = function( )	{
		if ( this.xmlHttpRequest != null && !this.onCompleteInternalHandled )	{
			this.aborted = true;
			this.xmlHttpRequest.abort( );
			HTTPRequest.activeHTTPRequests--;
			if ( HTTPRequest.activeHTTPRequests == 0 && typeof( window['HTTPRequestEnd'] ) == 'function' )
				HTTPRequestEnd( );
			if ( this.groupName != null )	{
				HTTPRequest.activeHTTPGroupRequests[this.groupName]--;
				if ( HTTPRequest.activeHTTPGroupRequests[this.groupName]==0 && typeof( this.onGroupEnd ) == 'function' )
					this.onGroupEnd( this.groupName );
			}
			if ( typeof( this.onTimeout ) == 'function' )
				this.onTimeout( this );
		// Opera won't fire onreadystatechange after abort, but other browsers do. 
		// So I can't rely on the onreadystate function getting called. Clean up here!
			delete this.xmlHttpRequest['onreadystatechange'];
			this.xmlHttpRequest = null;
		}
	};

	var tmp = this;
	this.onreadystatechange = this.xmlHttpRequest.onreadystatechange = function ( )	{
		if ( tmp.xmlHttpRequest == null )
			return;
		tmp.readyState = tmp.xmlHttpRequest.readyState;
		switch ( tmp.readyState )	{
			case 0:
				tmp.statusText = '¡Page NOT Found!';
				if ( typeof( tmp.onError ) == 'function' )
					tmp.onError( tmp.xmlHttpRequest );
				break;
			case 1:
				tmp.onLoadingInternal( tmp.xmlHttpRequest );
				break;
			case 2:
				tmp.onLoadedInternal( tmp.xmlHttpRequest );
				break;
			case 3:
				tmp.onInteractiveInternal( tmp.xmlHttpRequest );
				break;
			case 4:
				tmp.onCompleteInternal( tmp.xmlHttpRequest );
		}
	};
	
	return this;
}
/* PROPERTIES */

/* METHODS */
	/**
	 *	The process method is called to actually make the request. It builds the
	 *	querystring for GET requests ( the content for POST requests ), sets the
	 *	appropriate headers if necessary, and calls the XMLHttpRequest.send() method
	**/
HTTPRequest.prototype.process = function( )	{
	if ( this.xmlHttpRequest != null && this.url != null )	{
		// Some logic to get the real request URL
		if ( !this.cache && this.method == 'GET' )
			this.parameters['cache'] = new Date( ).getTime( ) + '' + this.requestIndex;
	
		var content = null;
			// For POST requests, to hold query string
		for ( var i in this.parameters )	{
			if ( this.queryString.length > 0 )
				this.queryString += '&amp;';
			this.queryString += encodeURIComponent( i ) + '=' + encodeURIComponent( this.parameters[i] );
		}
		if ( this.method == 'GET' )	{
			if ( this.queryString.length > 0 )
				this.url += ( ( this.url.indexOf( '?' ) > -1 ) ? '&amp;' : '?' ) + this.queryString;
		}
		this.xmlHttpRequest.open( this.method, this.url, this.async, this.username, this.password );
		if ( this.method == 'POST' )	{
			if ( typeof( this.xmlHttpRequest.setRequestHeader ) != 'undefined' )
				this.xmlHttpRequest.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' );
			content = this.queryString;
		}
		this.xmlHttpRequest.send( content );
		if ( !this.async )
			this.onreadystatechange( );
		else if ( this.timeout > 0 )
			setTimeout( this.onTimeoutInternal , this.timeout );
	}
};

	/**
	 *	An internal function to handle an Object argument, which may contain
	 *	either HTTPRequest field values or parameter name/values
	**/
HTTPRequest.prototype.handleArguments = function( args )	{
	for ( var i in args )	{
		// If the HTTPRequest object doesn't have a property which was passed, treat it as a url parameter
		if ( typeof( this[i] ) == 'undefined' )	{
			//alert( 'parameter ' + i + " = " + args[i] );
			this.parameters[i] = args[i];
		}
		else	{
			//alert( 'arg ' + i + " = " + args[i] );
			this[i] = args[i];
		}
	}
};
	/**
	 *	Returns the results of XMLHttpRequest.getAllResponseHeaders().
	 *	Only available after a response has been returned
	**/
HTTPRequest.prototype.getResponseHeaders = function( )	{
	if ( this.xmlHttpRequest != null && this.responseReceived )	{
		return this.xmlHttpRequest.getAllResponseHeaders( );
	}
	else
		return null;
};

	/**
	 *	Returns the the value of a response header as returned by XMLHttpRequest,getResponseHeader().
	 *	Only available after a response has been returned
	**/
HTTPRequest.prototype.getResponseHeader = function( headerName )	{
	if ( this.xmlHttpRequest != null && req.responseReceived )
		return this.xmlHttpRequest.getResponseHeader( headerName );
	else
		return null;
};
// STATIC PROPERTIES
HTTPRequest.requests = 0;
	//	Total number of HTTPRequest objects instantiated
HTTPRequest.activeRequests = 0;
	//	The number of total HTTPRequest objects currently active and running
HTTPRequest.ActiveHTTPGroupRequests = new Object( );
	//	An object holding the number of active requests for each group

//	STATIC METHODS
	/**
	 *	Returns an XMLHttpRequest object, either as a core object or an ActiveX 
	 *	implementation. If an object cannot be instantiated, it will return null;
	**/
HTTPRequest.CreateXMLHTTPRequest = function( )	{
	var request = null;
	if ( window.XMLHttpRequest )
		request = new XMLHttpRequest( );
	else if ( window.ActiveXObject )	{
		try	{
			request = new ActiveXObject( 'Msxml2.XMLHTTP' );
		}
		catch ( e )	{}
		if ( !request )	{
			try	{
				request = new ActiveXObject( 'Microsoft.XMLHTTP' );
			}
			catch ( e ) {}
		}
	}
	return request;
};
	/**
	 *	See if any request is active in the background
	**/
HTTPRequest.isActive = function( )	{
	return ( HTTPRequest.activeHTTPRequests > 0 );
};
	/**
	 *	Make a GET request. Pass an object containing parameters and arguments as the second argument.
	 *	These arguments may be either HTTPRequest properties to set on the request 
	 *	object or name/values to set in the request querystring.
	**/
HTTPRequest.get = function( args )	{
	return HTTPRequest.doRequest( 'GET', args );
};
	/**
	 *	Make a POST request. Pass an object containing parameters and arguments as the second argument.
	 *	These areguments may be either HTTPRequest properties to set on the request 
	 *	object or name/values to set in the request querystring.
	**/
HTTPRequest.post = function( args ) {
	return HTTPRequest.doRequest( 'POST', args );
};

	/**
	 *	The internal method used by the .get() and .post() methods
	**/
HTTPRequest.doRequest = function( method, args )	{
	if ( typeof( args ) != 'undefined' && args != null )	{
		var myRequest = new HTTPRequest( );
		if ( myRequest != null )	{
			myRequest.method = method;
			myRequest.handleArguments( args );
			myRequest.process( );
			return myRequest;
		}
	}
	return null;
};

	/**
	 *	Submit a form. The requested URL will be the form's ACTION, and the request method will be the form's METHOD.
	 *	Returns true if the submittal was handled successfully, else false so it 
	 *	can easily be used with an onSubmit event for a form, and fallback to submitting the form normally.
	**/
HTTPRequest.submit = function( theform, args )	{
	var myRequest = new HTTPRequest( );
	if ( myRequest != null )	{
		var serializedForm = HTTPRequest.serializeForm( theform );
		myRequest.method = theform.method.toUpperCase( );
		myRequest.url = theform.action;
		myRequest.handleArguments( args );
		myRequest.queryString = serializedForm;
		myRequest.process( );
	}
	return myRequest;
};

	/**
	 *	Serialize a form into a format which can be sent as a GET string or a POST 
	 *	content.It correctly ignores disabled fields, maintains order of the fields 
	 *	as in the elements[] array. The 'file' input type is not supported, as 
	 *	its content is not available to javascript. This method is used internally by the submit class method.
	**/
HTTPRequest.serializeForm = function( theform )	{
	var els = theform.elements;
	var len = els.length;
	var queryString = '';
	this.addField = function( name, value )	{ 
		if ( queryString.length > 0 )
			queryString += '&amp;';
		queryString += encodeURIComponent( name ) + '=' + encodeURIComponent( value );
	};
	for ( var c = 0; c < len; c ++ )	{
		var el = els[c];
		if ( !el.disabled )	{
			switch( el.type )	{
				case 'text': case 'password': case 'hidden': case 'textarea': 
					this.addField( el.name, el.value );
				break;
			case 'select-one':
				if ( el.selectedIndex >= 0 )
					this.addField( el.name, el.options[el.selectedIndex].value );
				break;
			case 'select-multiple':
				for (var i = 0; i < el.options.length; i ++ )
					if ( el.options[i].selected )
						this.addField( el.name, el.options[j].value );
				break;
			case 'checkbox': case 'radio':
				if ( el.checked )
					this.addField( el.name, el.value );
				break;
			}
		}
	}
	return queryString;
};

/**
 *	GENERAL INCLUDE FUNCTION
 *
**/

function fatalError ( )	{
	if ( confirm( '¿Peticion no procesada!\nIntentar recargar la pagina?' ) )
		document.location.href = document.location;
}

function validateInJS ( keyword, filename )	{
	if ( eval( 'typeof ( ' + keyword + ' )' ) == 'undefined' )	{
		var file = HTTPRequest.get ( { 'url' : 'include/' + filename + '.js', 'async' : false, 'cache' : true, 'onError' : fatalError } );
		return file.responseText;
	}
	return '';
}

/*  Copyright Mihai Bazon, 2002-2005  |  www.bazon.net/mishoo
 * -----------------------------------------------------------
 *
 * The DHTML Calendar, version 1.0 "It is happening again"
 *
 * Details and latest version at:
 * www.dynarch.com/projects/calendar
 *
 * This script is developed by Dynarch.com.  Visit us at www.dynarch.com.
 *
 * This script is distributed under the GNU Lesser General Public License.
 * Read the entire license text here: http://www.gnu.org/licenses/lgpl.html
 */

// $Id: calendar.js,v 1.51 2005/03/07 16:44:31 mishoo Exp $

/** The Calendar object constructor. */
Calendar = function (firstDayOfWeek, dateStr, onSelected, onClose) {
	// member variables
	this.activeDiv = null;
	this.currentDateEl = null;
	this.getDateStatus = null;
	this.getDateToolTip = null;
	this.getDateText = null;
	this.timeout = null;
	this.onSelected = onSelected || null;
	this.onClose = onClose || null;
	this.dragging = false;
	this.hidden = false;
	this.minYear = 1970;
	this.maxYear = 2050;
	this.dateFormat = Calendar._TT["DEF_DATE_FORMAT"];
	this.ttDateFormat = Calendar._TT["TT_DATE_FORMAT"];
	this.isPopup = true;
	this.weekNumbers = true;
	this.firstDayOfWeek = typeof firstDayOfWeek == "number" ? firstDayOfWeek : Calendar._FD; // 0 for Sunday, 1 for Monday, etc.
	this.showsOtherMonths = false;
	this.dateStr = dateStr;
	this.ar_days = null;
	this.showsTime = false;
	this.showTfoot = false;
	this.time24 = false;
	this.yearStep = 2;
	this.hiliteToday = true;
	this.multiple = null;
	// HTML elements
	this.table = null;
	this.element = null;
	this.tbody = null;
	this.firstdayname = null;
	// Combo boxes
	this.monthsCombo = null;
	this.yearsCombo = null;
	this.hilitedMonth = null;
	this.activeMonth = null;
	this.hilitedYear = null;
	this.activeYear = null;
	// Information
	this.dateClicked = false;

	// one-time initializations
	if (typeof Calendar._SDN == "undefined") {
		// table of short day names
		if (typeof Calendar._SDN_len == "undefined")
			Calendar._SDN_len = 3;
		var ar = new Array();
		for (var i = 8; i > 0;) {
			ar[--i] = Calendar._DN[i].substr(0, Calendar._SDN_len);
		}
		Calendar._SDN = ar;
		// table of short month names
		if (typeof Calendar._SMN_len == "undefined")
			Calendar._SMN_len = 3;
		ar = new Array();
		for (var i = 12; i > 0;) {
			ar[--i] = Calendar._MN[i].substr(0, Calendar._SMN_len);
		}
		Calendar._SMN = ar;
	}
};

// ** constants

/// "static", needed for event handlers.
Calendar._C = null;

/// detect a special case of "web browser"
Calendar.is_ie = ( /msie/i.test(navigator.userAgent) &&
		   !/opera/i.test(navigator.userAgent) );

Calendar.is_ie5 = ( Calendar.is_ie && /msie 5\.0/i.test(navigator.userAgent) );

/// detect Opera browser
Calendar.is_opera = /opera/i.test(navigator.userAgent);

/// detect KHTML-based browsers
Calendar.is_khtml = /Konqueror|Safari|KHTML/i.test(navigator.userAgent);

Calendar._DN = new Array
("Domingo",
 "Lunes",
 "Martes",
 "Miércoles",
 "Jueves",
 "Viernes",
 "Sábado",
 "Domingo");

Calendar._SDN = new Array
("D",
 "L",
 "M",
 "M",
 "J",
 "V",
 "S",
 "D");

Calendar._FD = 1;

Calendar._MN = new Array
("Enero",
 "Febrero",
 "Marzo",
 "Abril",
 "Mayo",
 "Junio",
 "Julio",
 "Agosto",
 "Septiembre",
 "Octubre",
 "Noviembre",
 "Diciembre");

Calendar._SMN = new Array
("Ene",
 "Feb",
 "Mar",
 "Abr",
 "May",
 "Jun",
 "Jul",
 "Ago",
 "Sep",
 "Oct",
 "Nov",
 "Dic");

Calendar._TT = {};
Calendar._TT["INFO"] = "Acerca del calendario";

Calendar._TT["ABOUT"] =
"Selector DHTML de Fecha/Hora\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"Para conseguir la última versión visite: http://www.dynarch.com/projects/calendar/\n" +
"Distribuido bajo licencia GNU LGPL. Visite http://gnu.org/licenses/lgpl.html para más detalles." +
"\n\n" +
"Selección de fecha:\n" +
"- Use los botones \xab, \xbb para seleccionar el año\n" +
"- Use los botones " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " para seleccionar el mes\n" +
"- Mantenga pulsado el ratón en cualquiera de estos botones para una selección rápida.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Selección de hora:\n" +
"- Pulse en cualquiera de las partes de la hora para incrementarla\n" +
"- o pulse las mayúsculas mientras hace clic para decrementarla\n" +
"- o haga clic y arrastre el ratón para una selección más rápida.";

Calendar._TT["PREV_YEAR"] = "Año anterior (mantener para menú)";
Calendar._TT["PREV_MONTH"] = "Mes anterior (mantener para menú)";
Calendar._TT["GO_TODAY"] = "Ir a hoy";
Calendar._TT["NEXT_MONTH"] = "Mes siguiente (mantener para menú)";
Calendar._TT["NEXT_YEAR"] = "Año siguiente (mantener para menú)";
Calendar._TT["SEL_DATE"] = "Seleccionar fecha";
Calendar._TT["DRAG_TO_MOVE"] = "Arrastrar para mover";
Calendar._TT["PART_TODAY"] = " (hoy)";

Calendar._TT["DAY_FIRST"] = "Hacer %s primer día de la semana";

Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Cerrar";
Calendar._TT["TODAY"] = "Hoy";
Calendar._TT["TIME_PART"] = "(Mayúscula-)Clic o arrastre para cambiar valor";

Calendar._TT["DEF_DATE_FORMAT"] = "%d/%m/%Y";
Calendar._TT["TT_DATE_FORMAT"] = "%A, %e de %B de %Y";

Calendar._TT["WK"] = "sem";
Calendar._TT["TIME"] = "Hora:";

// BEGIN: UTILITY FUNCTIONS; beware that these might be moved into a separate
//        library, at some point.

Calendar.getAbsolutePos = function(el) {
	var SL = 0, ST = 0;
	var is_div = /^div$/i.test(el.tagName);
	if (is_div && el.scrollLeft)
		SL = el.scrollLeft;
	if (is_div && el.scrollTop)
		ST = el.scrollTop;
	var r = { x: el.offsetLeft - SL, y: el.offsetTop - ST };
	if (el.offsetParent) {
		var tmp = this.getAbsolutePos(el.offsetParent);
		r.x += tmp.x;
		r.y += tmp.y;
	}
	return r;
};

Calendar.isRelated = function (el, evt) {
	var related = evt.relatedTarget;
	if (!related) {
		var type = evt.type;
		if (type == "mouseover") {
			related = evt.fromElement;
		} else if (type == "mouseout") {
			related = evt.toElement;
		}
	}
	while (related) {
		if (related == el) {
			return true;
		}
		related = related.parentNode;
	}
	return false;
};

Calendar.removeClass = function(el, className) {
	if (!(el && el.className)) {
		return;
	}
	var cls = el.className.split(" ");
	var ar = new Array();
	for (var i = cls.length; i > 0;) {
		if (cls[--i] != className) {
			ar[ar.length] = cls[i];
		}
	}
	el.className = ar.join(" ");
};

Calendar.addClass = function(el, className) {
	Calendar.removeClass(el, className);
	el.className += " " + className;
};

// FIXME: the following 2 functions totally suck, are useless and should be replaced immediately.
Calendar.getElement = function(ev) {
	var f = Calendar.is_ie ? window.event.srcElement : ev.currentTarget;
	while (f.nodeType != 1 || /^div$/i.test(f.tagName))
		f = f.parentNode;
	return f;
};

Calendar.getTargetElement = function(ev) {
	var f = Calendar.is_ie ? window.event.srcElement : ev.target;
	while (f.nodeType != 1)
		f = f.parentNode;
	return f;
};

Calendar.stopEvent = function(ev) {
	ev || (ev = window.event);
	if (Calendar.is_ie) {
		ev.cancelBubble = true;
		ev.returnValue = false;
	} else {
		ev.preventDefault();
		ev.stopPropagation();
	}
	return false;
};

Calendar.addEvent = function(el, evname, func) {
	if (el.attachEvent) { // IE
		el.attachEvent("on" + evname, func);
	} else if (el.addEventListener) { // Gecko / W3C
		el.addEventListener(evname, func, true);
	} else {
		el["on" + evname] = func;
	}
};

Calendar.removeEvent = function(el, evname, func) {
	if (el.detachEvent) { // IE
		el.detachEvent("on" + evname, func);
	} else if (el.removeEventListener) { // Gecko / W3C
		el.removeEventListener(evname, func, true);
	} else {
		el["on" + evname] = null;
	}
};

Calendar.createElement = function(type, parent) {
	var el = null;
	if (document.createElementNS) {
		// use the XHTML namespace; IE won't normally get here unless
		// _they_ "fix" the DOM2 implementation.
		el = document.createElementNS("http://www.w3.org/1999/xhtml", type);
	} else {
		el = document.createElement(type);
	}
	if (typeof parent != "undefined") {
		parent.appendChild(el);
	}
	return el;
};

// END: UTILITY FUNCTIONS

// BEGIN: CALENDAR STATIC FUNCTIONS

/** Internal -- adds a set of events to make some element behave like a button. */
Calendar._add_evs = function(el) {
	with (Calendar) {
		addEvent(el, "mouseover", dayMouseOver);
		addEvent(el, "mousedown", dayMouseDown);
		addEvent(el, "mouseout", dayMouseOut);
		if (is_ie) {
			addEvent(el, "dblclick", dayMouseDblClick);
			el.setAttribute("unselectable", true);
		}
	}
};

Calendar.findMonth = function(el) {
	try{
	if (typeof el.month != "undefined") {
		return el;
	} else if (typeof el.parentNode.month != "undefined") {
		return el.parentNode;
	}
	return null;
	}catch(e){}
};

Calendar.findYear = function(el) {
	try{
	if (typeof el.year != "undefined") {
		return el;
	} else if (typeof el.parentNode.year != "undefined") {
		return el.parentNode;
	}
	return null;
	}catch(e){}
};

Calendar.showMonthsCombo = function () {
	var cal = Calendar._C;
	if (!cal) {
		return false;
	}
	var cal = cal;
	var cd = cal.activeDiv;
	var mc = cal.monthsCombo;
	if (cal.hilitedMonth) {
		Calendar.removeClass(cal.hilitedMonth, "hilite");
	}
	if (cal.activeMonth) {
		Calendar.removeClass(cal.activeMonth, "active");
	}
	var mon = cal.monthsCombo.getElementsByTagName("div")[cal.date.getMonth()];
	Calendar.addClass(mon, "active");
	cal.activeMonth = mon;
	var s = mc.style;
	s.display = "block";
	if (cd.navtype < 0)
		s.left = cd.offsetLeft + "px";
	else {
		var mcw = mc.offsetWidth;
		if (typeof mcw == "undefined")
			// Konqueror brain-dead techniques
			mcw = 50;
		s.left = (cd.offsetLeft + cd.offsetWidth - mcw) + "px";
	}
	s.top = (cd.offsetTop + cd.offsetHeight) + "px";
};

Calendar.showYearsCombo = function (fwd) {
	try{
	var cal = Calendar._C;
	if (!cal) {
		return false;
	}
	var cal = cal;
	var cd = cal.activeDiv;
	var yc = cal.yearsCombo;
	if (cal.hilitedYear) {
		Calendar.removeClass(cal.hilitedYear, "hilite");
	}
	if (cal.activeYear) {
		Calendar.removeClass(cal.activeYear, "active");
	}
	cal.activeYear = null;
	var Y = cal.date.getFullYear() + (fwd ? 1 : -1);
	var yr = yc.firstChild;
	var show = false;
	for (var i = 12; i > 0; --i) {
		if (Y >= cal.minYear && Y <= cal.maxYear) {
			yr.innerHTML = Y;
			yr.year = Y;
			yr.style.display = "block";
			show = true;
		} else {
			yr.style.display = "none";
		}
		yr = yr.nextSibling;
		Y += fwd ? cal.yearStep : -cal.yearStep;
	}
	if (show) {
		var s = yc.style;
		s.display = "block";
		if (cd.navtype < 0)
			s.left = cd.offsetLeft + "px";
		else {
			var ycw = yc.offsetWidth;
			if (typeof ycw == "undefined")
				// Konqueror brain-dead techniques
				ycw = 50;
			s.left = (cd.offsetLeft + cd.offsetWidth - ycw) + "px";
		}
		s.top = (cd.offsetTop + cd.offsetHeight) + "px";
	}
	}catch(e){}
};

// event handlers

Calendar.tableMouseUp = function(ev) {
	try{
	var cal = Calendar._C;
	if (!cal) {
		return false;
	}
	if (cal.timeout) {
		clearTimeout(cal.timeout);
	}
	var el = cal.activeDiv;
	if (!el) {
		return false;
	}
	var target = Calendar.getTargetElement(ev);
	ev || (ev = window.event);
	Calendar.removeClass(el, "active");
	if (target == el || target.parentNode == el) {
		Calendar.cellClick(el, ev);
	}
	var mon = Calendar.findMonth(target);
	var date = null;
	if (mon) {
		date = new Date(cal.date);
		if (mon.month != date.getMonth()) {
			date.setMonth(mon.month);
			cal.setDate(date);
			cal.dateClicked = false;
			cal.callHandler();
		}
	} else {
		var year = Calendar.findYear(target);
		if (year) {
			date = new Date(cal.date);
			if (year.year != date.getFullYear()) {
				date.setFullYear(year.year);
				cal.setDate(date);
				cal.dateClicked = false;
				cal.callHandler();
			}
		}
	}
	with (Calendar) {
		removeEvent(document, "mouseup", tableMouseUp);
		removeEvent(document, "mouseover", tableMouseOver);
		removeEvent(document, "mousemove", tableMouseOver);
		cal._hideCombos();
		_C = null;
		return stopEvent(ev);
	}
	}catch(e){}
};

Calendar.tableMouseOver = function (ev) {
	try{
	var cal = Calendar._C;
	if (!cal) {
		return;
	}
	var el = cal.activeDiv;
	var target = Calendar.getTargetElement(ev);
	if (target == el || target.parentNode == el) {
		Calendar.addClass(el, "hilite active");
		Calendar.addClass(el.parentNode, "rowhilite");
	} else {
		if (typeof el.navtype == "undefined" || (el.navtype != 50 && (el.navtype == 0 || Math.abs(el.navtype) > 2)))
			Calendar.removeClass(el, "active");
		Calendar.removeClass(el, "hilite");
		Calendar.removeClass(el.parentNode, "rowhilite");
	}
	ev || (ev = window.event);
	if (el.navtype == 50 && target != el) {
		var pos = Calendar.getAbsolutePos(el);
		var w = el.offsetWidth;
		var x = ev.clientX;
		var dx;
		var decrease = true;
		if (x > pos.x + w) {
			dx = x - pos.x - w;
			decrease = false;
		} else
			dx = pos.x - x;

		if (dx < 0) dx = 0;
		var range = el._range;
		var current = el._current;
		var count = Math.floor(dx / 10) % range.length;
		for (var i = range.length; --i >= 0;)
			if (range[i] == current)
				break;
		while (count-- > 0)
			if (decrease) {
				if (--i < 0)
					i = range.length - 1;
			} else if ( ++i >= range.length )
				i = 0;
		var newval = range[i];
		el.innerHTML = newval;

		cal.onUpdateTime();
	}
	var mon = Calendar.findMonth(target);
	if (mon) {
		if (mon.month != cal.date.getMonth()) {
			if (cal.hilitedMonth) {
				Calendar.removeClass(cal.hilitedMonth, "hilite");
			}
			Calendar.addClass(mon, "hilite");
			cal.hilitedMonth = mon;
		} else if (cal.hilitedMonth) {
			Calendar.removeClass(cal.hilitedMonth, "hilite");
		}
	} else {
		if (cal.hilitedMonth) {
			Calendar.removeClass(cal.hilitedMonth, "hilite");
		}
		var year = Calendar.findYear(target);
		if (year) {
			if (year.year != cal.date.getFullYear()) {
				if (cal.hilitedYear) {
					Calendar.removeClass(cal.hilitedYear, "hilite");
				}
				Calendar.addClass(year, "hilite");
				cal.hilitedYear = year;
			} else if (cal.hilitedYear) {
				Calendar.removeClass(cal.hilitedYear, "hilite");
			}
		} else if (cal.hilitedYear) {
			Calendar.removeClass(cal.hilitedYear, "hilite");
		}
	}
	return Calendar.stopEvent(ev);
	}catch(e){}
};

Calendar.tableMouseDown = function (ev) {
	if (Calendar.getTargetElement(ev) == Calendar.getElement(ev)) {
		return Calendar.stopEvent(ev);
	}
};

Calendar.calDragIt = function (ev) {
	var cal = Calendar._C;
	if (!(cal && cal.dragging)) {
		return false;
	}
	var posX;
	var posY;
	if (Calendar.is_ie) {
		posY = window.event.clientY + document.body.scrollTop;
		posX = window.event.clientX + document.body.scrollLeft;
	} else {
		posX = ev.pageX;
		posY = ev.pageY;
	}
	cal.hideShowCovered();
	var st = cal.element.style;
	st.left = (posX - cal.xOffs) + "px";
	st.top = (posY - cal.yOffs) + "px";
	return Calendar.stopEvent(ev);
};

Calendar.calDragEnd = function (ev) {
	var cal = Calendar._C;
	if (!cal) {
		return false;
	}
	cal.dragging = false;
	with (Calendar) {
		removeEvent(document, "mousemove", calDragIt);
		removeEvent(document, "mouseup", calDragEnd);
		tableMouseUp(ev);
	}
	cal.hideShowCovered();
};

Calendar.dayMouseDown = function(ev) {
	try{
	var el = Calendar.getElement(ev);
	if (el.disabled) {
		return false;
	}
	var cal = el.calendar;
	cal.activeDiv = el;
	Calendar._C = cal;
	if (el.navtype != 300) with (Calendar) {
		if (el.navtype == 50) {
			el._current = el.innerHTML;
			addEvent(document, "mousemove", tableMouseOver);
		} else
			addEvent(document, Calendar.is_ie5 ? "mousemove" : "mouseover", tableMouseOver);
		addClass(el, "hilite active");
		addEvent(document, "mouseup", tableMouseUp);
	} else if (cal.isPopup) {
		cal._dragStart(ev);
	}
	if (el.navtype == -1 || el.navtype == 1) {
		if (cal.timeout) clearTimeout(cal.timeout);
		cal.timeout = setTimeout("Calendar.showMonthsCombo()", 250);
	} else if (el.navtype == -2 || el.navtype == 2) {
		if (cal.timeout) clearTimeout(cal.timeout);
		cal.timeout = setTimeout((el.navtype > 0) ? "Calendar.showYearsCombo(true)" : "Calendar.showYearsCombo(false)", 250);
	} else {
		cal.timeout = null;
	}
	return Calendar.stopEvent(ev);
	}catch(e){}
};

Calendar.dayMouseDblClick = function(ev) {
	Calendar.cellClick(Calendar.getElement(ev), ev || window.event);
	if (Calendar.is_ie) {
		document.selection.empty();
	}
};

Calendar.dayMouseOver = function(ev) {
	try{
	var el = Calendar.getElement(ev);
	if (Calendar.isRelated(el, ev) || Calendar._C || el.disabled) {
		return false;
	}
	if (el.ttip && this.showTfoot ) {
		if (el.ttip.substr(0, 1) == "_") {
			el.ttip = el.caldate.print(el.calendar.ttDateFormat) + el.ttip.substr(1);
		}
		el.calendar.tooltips.innerHTML = el.ttip;
	}
	if (el.navtype != 300) {
		Calendar.addClass(el, "hilite");
		if (el.caldate) {
			Calendar.addClass(el.parentNode, "rowhilite");
		}
	}
	return Calendar.stopEvent(ev);
	}catch(e){}
};

Calendar.dayMouseOut = function(ev) {
	try{
	with (Calendar) {
		var el = getElement(ev);
		if (isRelated(el, ev) || _C || el.disabled)
			return false;
		removeClass(el, "hilite");
		if (el.caldate)
			removeClass(el.parentNode, "rowhilite");
		if (el.calendar)
			el.calendar.tooltips.innerHTML = _TT["SEL_DATE"];
		return stopEvent(ev);
	}
	}catch(e){}
};

/**
 *  A generic "click" handler :) handles all types of buttons defined in this
 *  calendar.
 */
Calendar.cellClick = function(el, ev) {
	var cal = el.calendar;
	var closing = false;
	var newdate = false;
	var date = null;
	if (typeof el.navtype == "undefined") {
		if (cal.currentDateEl) {
			Calendar.removeClass(cal.currentDateEl, "selected");
			Calendar.addClass(el, "selected");
			closing = (cal.currentDateEl == el);
			if (!closing) {
				cal.currentDateEl = el;
			}
		}
		cal.date.setDateOnly(el.caldate);
		date = cal.date;
		var other_month = !(cal.dateClicked = !el.otherMonth);
		if (!other_month && !cal.currentDateEl)
			cal._toggleMultipleDate(new Date(date));
		else
			newdate = !el.disabled;
		// a date was clicked
		if (other_month)
			cal._init(cal.firstDayOfWeek, date);
	} else {
		if (el.navtype == 200) {
			Calendar.removeClass(el, "hilite");
			cal.callCloseHandler();
			return;
		}
		date = new Date(cal.date);
		if (el.navtype == 0)
			date.setDateOnly(new Date()); // TODAY
		// unless "today" was clicked, we assume no date was clicked so
		// the selected handler will know not to close the calenar when
		// in single-click mode.
		// cal.dateClicked = (el.navtype == 0);
		cal.dateClicked = false;
		var year = date.getFullYear();
		var mon = date.getMonth();
		function setMonth(m) {
			var day = date.getDate();
			var max = date.getMonthDays(m);
			if (day > max) {
				date.setDate(max);
			}
			date.setMonth(m);
		};
		switch (el.navtype) {
		    case 400:
			Calendar.removeClass(el, "hilite");
			var text = Calendar._TT["ABOUT"];
			if (typeof text != "undefined") {
				text += cal.showsTime ? Calendar._TT["ABOUT_TIME"] : "";
			} else {
				// FIXME: this should be removed as soon as lang files get updated!
				text = "Help and about box text is not translated into this language.\n" +
					"If you know this language and you feel generous please update\n" +
					"the corresponding file in \"lang\" subdir to match calendar-en.js\n" +
					"and send it back to <mihai_bazon@yahoo.com> to get it into the distribution  ;-)\n\n" +
					"Thank you!\n" +
					"http://dynarch.com/mishoo/calendar.epl\n";
			}
			alert(text);
			return;
		    case -2:
			if (year > cal.minYear) {
				date.setFullYear(year - 1);
			}
			break;
		    case -1:
			if (mon > 0) {
				setMonth(mon - 1);
			} else if (year-- > cal.minYear) {
				date.setFullYear(year);
				setMonth(11);
			}
			break;
		    case 1:
			if (mon < 11) {
				setMonth(mon + 1);
			} else if (year < cal.maxYear) {
				date.setFullYear(year + 1);
				setMonth(0);
			}
			break;
		    case 2:
			if (year < cal.maxYear) {
				date.setFullYear(year + 1);
			}
			break;
		    case 100:
			cal.setFirstDayOfWeek(el.fdow);
			return;
		    case 50:
			var range = el._range;
			var current = el.innerHTML;
			for (var i = range.length; --i >= 0;)
				if (range[i] == current)
					break;
			if (ev && ev.shiftKey) {
				if (--i < 0)
					i = range.length - 1;
			} else if ( ++i >= range.length )
				i = 0;
			var newval = range[i];
			el.innerHTML = newval;
			cal.onUpdateTime();
			return;
		    case 0:
			// TODAY will bring us here
			if ((typeof cal.getDateStatus == "function") &&
			    cal.getDateStatus(date, date.getFullYear(), date.getMonth(), date.getDate())) {
				return false;
			}
			break;
			case 99:
					ev && cal.callHandler();
			break;
		}
		if (!date.equalsTo(cal.date)) {
			cal.setDate(date);
			newdate = true;
		} else if (el.navtype == 0)
			newdate = closing = true;
	}
	if (closing) {
		Calendar.removeClass(el, "hilite");
		ev && cal.callCloseHandler();
	}
};

// END: CALENDAR STATIC FUNCTIONS

// BEGIN: CALENDAR OBJECT FUNCTIONS

/**
 *  This function creates the calendar inside the given parent.  If _par is
 *  null than it creates a popup calendar inside the BODY element.  If _par is
 *  an element, be it BODY, then it creates a non-popup calendar (still
 *  hidden).  Some properties need to be set before calling this function.
 */
Calendar.prototype.create = function (_par) {
	var parent = null;
	if (! _par) {
		// default parent is the document body, in which case we create
		// a popup calendar.
		parent = document.getElementsByTagName("body")[0];
		this.isPopup = true;
	} else {
		parent = _par;
		this.isPopup = false;
	}
	this.date = this.dateStr ? new Date(this.dateStr) : new Date();

	var table = Calendar.createElement("table");
	this.table = table;
	table.cellSpacing = 0;
	table.cellPadding = 0;
	table.calendar = this;
	Calendar.addEvent(table, "mousedown", Calendar.tableMouseDown);

	var div = Calendar.createElement("div");
	this.element = div;
	div.className = "calendar";
	if (this.isPopup) {
		div.style.position = "absolute";
		div.style.display = "none";
	}
	div.appendChild(table);

	var thead = Calendar.createElement("thead", table);
	var cell = null;
	var row = null;

	var cal = this;
	var hh = function (text, cs, navtype) {
		cell = Calendar.createElement("td", row);
		cell.colSpan = cs;
		cell.className = "button";
		if (navtype != 0 && Math.abs(navtype) <= 2)
			cell.className += " nav";
		Calendar._add_evs(cell);
		cell.calendar = cal;
		cell.navtype = navtype;
		cell.innerHTML = "<div unselectable='on'>" + text + "</div>";
		return cell;
	};

	row = Calendar.createElement("tr", thead);
	var title_length = 6;
	(this.isPopup) && --title_length;
	(this.weekNumbers) && ++title_length;

//	hh("?", 1, 0).ttip = Calendar._TT["INFO"];
	this.title = hh("", title_length+1, 300);
	this.title.className = "title";
	if (this.isPopup) {
		this.title.ttip = Calendar._TT["DRAG_TO_MOVE"];
		this.title.style.cursor = "move";
		hh("&#x00d7;", 1, 200).ttip = Calendar._TT["CLOSE"];
	}

	row = Calendar.createElement("tr", thead);
	row.className = "headrow";

	this._nav_py = hh("&#x00ab;", 1, -2);
	this._nav_py.ttip = Calendar._TT["PREV_YEAR"];

	this._nav_pm = hh("&#x2039;", 1, -1);
	this._nav_pm.ttip = Calendar._TT["PREV_MONTH"];

	this._nav_now = hh(Calendar._TT["TODAY"], this.weekNumbers ? 4 : 3, 0);
	this._nav_now.ttip = Calendar._TT["GO_TODAY"];

	this._nav_nm = hh("&#x203a;", 1, 1);
	this._nav_nm.ttip = Calendar._TT["NEXT_MONTH"];

	this._nav_ny = hh("&#x00bb;", 1, 2);
	this._nav_ny.ttip = Calendar._TT["NEXT_YEAR"];

	// day names
	row = Calendar.createElement("tr", thead);
	row.className = "daynames";
	if (this.weekNumbers) {
		cell = Calendar.createElement("td", row);
		cell.className = "name wn";
		cell.innerHTML = Calendar._TT["WK"];
	}
	for (var i = 7; i > 0; --i) {
		cell = Calendar.createElement("td", row);
		if (!i) {
			cell.navtype = 100;
			cell.calendar = this;
			Calendar._add_evs(cell);
		}
	}
	this.firstdayname = (this.weekNumbers) ? row.firstChild.nextSibling : row.firstChild;
	this._displayWeekdays();

	var tbody = Calendar.createElement("tbody", table);
	this.tbody = tbody;

	for (i = 6; i > 0; --i) {
		row = Calendar.createElement("tr", tbody);
		if (this.weekNumbers) {
			cell = Calendar.createElement("td", row);
		}
		for (var j = 7; j > 0; --j) {
			cell = Calendar.createElement("td", row);
			cell.calendar = this;
			Calendar._add_evs(cell);
		}
	}

	if (this.showsTime) {
		row = Calendar.createElement("tr", tbody);
		row.className = "time";

		cell = Calendar.createElement("td", row);
		cell.className = "time";
		cell.colSpan = this.weekNumbers ? 4 : 3;

		(function(){
			function makeTimePart(className, init, range_start, range_end) {
				var part = Calendar.createElement("span", cell);
				part.className = className;
				part.innerHTML = init;
				part.calendar = cal;
				part.ttip = Calendar._TT["TIME_PART"];
				part.navtype = 50;
				part._range = [];

				if (className == "minute")
					addNumber = 30;
				else
					addNumber = 1;

				if (typeof range_start != "number")
					part._range = range_start;
				else {
					for (var i = range_start; i <= range_end; i+=addNumber) {
						var txt;
						if (i < 10 && range_end >= 10) txt = '0' + i;
						else txt = '' + i;
						part._range[part._range.length] = txt;
					}
				}
				Calendar._add_evs(part);
				return part;
			};
			var hrs = cal.date.getHours();
			var mins = cal.date.getMinutes();
			var t12 = !cal.time24;
			var pm = (hrs > 12);
			if (t12 && pm) hrs -= 12;
			var H = makeTimePart("hour", hrs, t12 ? 1 : 0, t12 ? 12 : 23);
			var span = Calendar.createElement("span", cell);
			span.innerHTML = ":";
			span.className = "colon";
			var M = makeTimePart("minute", mins, 0, 59);
			var AP = null;
			cell = Calendar.createElement("td", row);
			cell.className = "time";
			cell.colSpan = 2;
			if (t12)
				AP = makeTimePart("ampm", pm ? "pm" : "am", ["am", "pm"]);
			else
				cell.innerHTML = "&nbsp;";

			cal.onSetTime = function() {
				var pm, hrs = this.date.getHours(),
					mins = this.date.getMinutes();
				if (t12) {
					pm = (hrs >= 12);
					if (pm) hrs -= 12;
					if (hrs == 0) hrs = 12;
					AP.innerHTML = pm ? "pm" : "am";
				}
				H.innerHTML = (hrs < 10) ? ("0" + hrs) : hrs;
				M.innerHTML = (mins < 10) ? ("0" + mins) : mins;
			};

			cal.onUpdateTime = function() {
				var date = this.date;
				var h = parseInt(H.innerHTML, 10);
				if (t12) {
					if (/pm/i.test(AP.innerHTML) && h < 12)
						h += 12;
					else if (/am/i.test(AP.innerHTML) && h == 12)
						h = 0;
				}
				var d = date.getDate();
				var m = date.getMonth();
				var y = date.getFullYear();
				date.setHours(h);
				date.setMinutes(parseInt(M.innerHTML, 10));
				date.setFullYear(y);
				date.setMonth(m);
				date.setDate(d);
				this.dateClicked = false;
			};
		})();
		cell = hh("Buscar",2,99);
	} else {
		this.onSetTime = this.onUpdateTime = function() {};
	}
	
	if( this.showTfoot )
	{
		var tfoot = Calendar.createElement("tfoot", table);
		row = Calendar.createElement("tr", tfoot);
		row.className = "footrow";

		cell = hh(Calendar._TT["SEL_DATE"], this.weekNumbers ? 8 : 7, 300);
		cell.className = "ttip";
		if (this.isPopup) {
			cell.ttip = Calendar._TT["DRAG_TO_MOVE"];
			cell.style.cursor = "move";
		}
		this.tooltips = cell;
	}

	div = Calendar.createElement("div", this.element);
	this.monthsCombo = div;
	div.className = "combo";
	for (i = 0; i < Calendar._MN.length; ++i) {
		var mn = Calendar.createElement("div");
		mn.className = Calendar.is_ie ? "label-IEfix" : "label";
		mn.month = i;
		mn.innerHTML = Calendar._SMN[i];
		div.appendChild(mn);
	}

	div = Calendar.createElement("div", this.element);
	this.yearsCombo = div;
	div.className = "combo";
	for (i = 12; i > 0; --i) {
		var yr = Calendar.createElement("div");
		yr.className = Calendar.is_ie ? "label-IEfix" : "label";
		div.appendChild(yr);
	}

	this._init(this.firstDayOfWeek, this.date);
	parent.appendChild(this.element);
};

/** keyboard navigation, only for popup calendars */
Calendar._keyEvent = function(ev) {
	
	var cal = window._dynarch_popupCalendar;
	if (!cal || cal.multiple)
		return false;
	(Calendar.is_ie) && (ev = window.event);
	var act = (Calendar.is_ie || ev.type == "keypress"),
		K = ev.keyCode;
	if (ev.ctrlKey) {
		switch (K) {
		    case 37: // KEY left
			act && Calendar.cellClick(cal._nav_pm);
			break;
		    case 38: // KEY up
			act && Calendar.cellClick(cal._nav_py);
			break;
		    case 39: // KEY right
			act && Calendar.cellClick(cal._nav_nm);
			break;
		    case 40: // KEY down
			act && Calendar.cellClick(cal._nav_ny);
			break;
		    default:
			return false;
		}
	} else switch (K) {
	    case 32: // KEY space (now)
		Calendar.cellClick(cal._nav_now);
		break;
	    case 27: // KEY esc
		act && cal.callCloseHandler();
		break;
	    case 37: // KEY left
	    case 38: // KEY up
	    case 39: // KEY right
	    case 40: // KEY down
		if (act) {
			var prev, x, y, ne, el, step;
			prev = K == 37 || K == 38;
			step = (K == 37 || K == 39) ? 1 : 7;
			function setVars() {
				el = cal.currentDateEl;
				var p = el.pos;
				x = p & 15;
				y = p >> 4;
				ne = cal.ar_days[y][x];
			};setVars();
			function prevMonth() {
				var date = new Date(cal.date);
				date.setDate(date.getDate() - step);
				cal.setDate(date);
			};
			function nextMonth() {
				var date = new Date(cal.date);
				date.setDate(date.getDate() + step);
				cal.setDate(date);
			};
			while (1) {
				switch (K) {
				    case 37: // KEY left
					if (--x >= 0)
						ne = cal.ar_days[y][x];
					else {
						x = 6;
						K = 38;
						continue;
					}
					break;
				    case 38: // KEY up
					if (--y >= 0)
						ne = cal.ar_days[y][x];
					else {
						prevMonth();
						setVars();
					}
					break;
				    case 39: // KEY right
					if (++x < 7)
						ne = cal.ar_days[y][x];
					else {
						x = 0;
						K = 40;
						continue;
					}
					break;
				    case 40: // KEY down
					if (++y < cal.ar_days.length)
						ne = cal.ar_days[y][x];
					else {
						nextMonth();
						setVars();
					}
					break;
				}
				break;
			}
			if (ne) {
				if (!ne.disabled)
					Calendar.cellClick(ne);
				else if (prev)
					prevMonth();
				else
					nextMonth();
			}
		}
		break;
	    case 13: // KEY enter
		if (act)
			Calendar.cellClick(cal.currentDateEl, ev);
		break;
	    default:
		return false;
	}
	return Calendar.stopEvent(ev);
};

/**
 *  (RE)Initializes the calendar to the given date and firstDayOfWeek
 */
Calendar.prototype._init = function (firstDayOfWeek, date) {
	var today = new Date(),
		TY = today.getFullYear(),
		TM = today.getMonth(),
		TD = today.getDate();
	this.table.style.visibility = "hidden";
	var year = date.getFullYear();
	if (year < this.minYear) {
		year = this.minYear;
		date.setFullYear(year);
	} else if (year > this.maxYear) {
		year = this.maxYear;
		date.setFullYear(year);
	}
	this.firstDayOfWeek = firstDayOfWeek;
	this.date = new Date(date);
	var month = date.getMonth();
	var mday = date.getDate();
	var no_days = date.getMonthDays();

	// calendar voodoo for computing the first day that would actually be
	// displayed in the calendar, even if it's from the previous month.
	// WARNING: this is magic. ;-)
	date.setDate(1);
	var day1 = (date.getDay() - this.firstDayOfWeek) % 7;
	if (day1 < 0)
		day1 += 7;
	date.setDate(-day1);
	date.setDate(date.getDate() + 1);

	var row = this.tbody.firstChild;
	var MN = Calendar._SMN[month];
	var ar_days = this.ar_days = new Array();
	var weekend = Calendar._TT["WEEKEND"];
	var dates = this.multiple ? (this.datesCells = {}) : null;
	for (var i = 0; i < 6; ++i, row = row.nextSibling) {
		var cell = row.firstChild;
		if (this.weekNumbers) {
			cell.className = "day wn";
			cell.innerHTML = date.getWeekNumber();
			cell = cell.nextSibling;
		}
		row.className = "daysrow";
		var hasdays = false, iday, dpos = ar_days[i] = [];
		for (var j = 0; j < 7; ++j, cell = cell.nextSibling, date.setDate(iday + 1)) {
			iday = date.getDate();
			var wday = date.getDay();
			cell.className = "day";
			cell.pos = i << 4 | j;
			dpos[j] = cell;
			var current_month = (date.getMonth() == month);
			if (!current_month) {
				if (this.showsOtherMonths) {
					cell.className += " othermonth";
					cell.otherMonth = true;
				} else {
					cell.className = "day";
					cell.innerHTML = "&nbsp;";
					cell.disabled = true;
					continue;
				}
			} else {
				cell.otherMonth = false;
				hasdays = true;
			}
			cell.disabled = false;
			cell.innerHTML = this.getDateText ? this.getDateText(date, iday) : iday;
			if (dates)
				dates[date.print("%Y%m%d")] = cell;
			if (this.getDateStatus) {
				var status = this.getDateStatus(date, year, month, iday);
				if (this.getDateToolTip) {
					var toolTip = this.getDateToolTip(date, year, month, iday);
					if (toolTip)
						cell.title = toolTip;
				}
				if (status === true) {
					cell.className += " disabled";
					cell.disabled = true;
				} else {
					if (/disabled/i.test(status))
						cell.disabled = true;
					cell.className += " " + status;
				}
			}
			if (!cell.disabled) {
				cell.caldate = new Date(date);
				cell.ttip = "_";
				if (!this.multiple && current_month
				    && iday == mday && this.hiliteToday) {
					cell.className += " selected";
					this.currentDateEl = cell;
				}
				if (date.getFullYear() == TY &&
				    date.getMonth() == TM &&
				    iday == TD) {
					cell.className += " today";
					cell.ttip += Calendar._TT["PART_TODAY"];
				}
				if (weekend.indexOf(wday.toString()) != -1)
					cell.className += cell.otherMonth ? " oweekend" : " weekend";
			}
		}
		if (!(hasdays || this.showsOtherMonths))
			row.className = "emptyrow";
	}
	this.title.innerHTML = Calendar._MN[month] + ", " + year;
	this.onSetTime();
	this.table.style.visibility = "visible";
	this._initMultipleDates();
	// PROFILE
	// this.tooltips.innerHTML = "Generated in " + ((new Date()) - today) + " ms";
};

Calendar.prototype._initMultipleDates = function() {
	if (this.multiple) {
		for (var i in this.multiple) {
			var cell = this.datesCells[i];
			var d = this.multiple[i];
			if (!d)
				continue;
			if (cell)
				cell.className += " selected";
		}
	}
};

Calendar.prototype._toggleMultipleDate = function(date) {
	if (this.multiple) {
		var ds = date.print("%Y%m%d");
		var cell = this.datesCells[ds];
		if (cell) {
			var d = this.multiple[ds];
			if (!d) {
				Calendar.addClass(cell, "selected");
				this.multiple[ds] = date;
			} else {
				Calendar.removeClass(cell, "selected");
				delete this.multiple[ds];
			}
		}
	}
};

Calendar.prototype.setDateToolTipHandler = function (unaryFunction) {
	this.getDateToolTip = unaryFunction;
};

/**
 *  Calls _init function above for going to a certain date (but only if the
 *  date is different than the currently selected one).
 */
Calendar.prototype.setDate = function (date) {
	if (!date.equalsTo(this.date)) {
		this._init(this.firstDayOfWeek, date);
	}
};

/**
 *  Refreshes the calendar.  Useful if the "disabledHandler" function is
 *  dynamic, meaning that the list of disabled date can change at runtime.
 *  Just * call this function if you think that the list of disabled dates
 *  should * change.
 */
Calendar.prototype.refresh = function () {
	this._init(this.firstDayOfWeek, this.date);
};

/** Modifies the "firstDayOfWeek" parameter (pass 0 for Synday, 1 for Monday, etc.). */
Calendar.prototype.setFirstDayOfWeek = function (firstDayOfWeek) {
	this._init(firstDayOfWeek, this.date);
	this._displayWeekdays();
};

/**
 *  Allows customization of what dates are enabled.  The "unaryFunction"
 *  parameter must be a function object that receives the date (as a JS Date
 *  object) and returns a boolean value.  If the returned value is true then
 *  the passed date will be marked as disabled.
 */
Calendar.prototype.setDateStatusHandler = Calendar.prototype.setDisabledHandler = function (unaryFunction) {
	this.getDateStatus = unaryFunction;
};

/** Customization of allowed year range for the calendar. */
Calendar.prototype.setRange = function (a, z) {
	this.minYear = a;
	this.maxYear = z;
};

/** Calls the first user handler (selectedHandler). */
Calendar.prototype.callHandler = function () {
	if (this.onSelected) {
		this.onSelected(this, this.date.print(this.dateFormat));
	}
};

/** Calls the second user handler (closeHandler). */
Calendar.prototype.callCloseHandler = function () {
	if (this.onClose) {
		this.onClose(this);
	}
	this.hideShowCovered();
};

/** Removes the calendar object from the DOM tree and destroys it. */
Calendar.prototype.destroy = function () {
	var el = this.element.parentNode;
	el.removeChild(this.element);
	Calendar._C = null;
	window._dynarch_popupCalendar = null;
};

/**
 *  Moves the calendar element to a different section in the DOM tree (changes
 *  its parent).
 */
Calendar.prototype.reparent = function (new_parent) {
	var el = this.element;
	el.parentNode.removeChild(el);
	new_parent.appendChild(el);
};

// This gets called when the user presses a mouse button anywhere in the
// document, if the calendar is shown.  If the click was outside the open
// calendar this function closes it.
Calendar._checkCalendar = function(ev) {
	var calendar = window._dynarch_popupCalendar;
	if (!calendar) {
		return false;
	}
	var el = Calendar.is_ie ? Calendar.getElement(ev) : Calendar.getTargetElement(ev);
	for (; el != null && el != calendar.element; el = el.parentNode);
	if (el == null) {
		// calls closeHandler which should hide the calendar.
		window._dynarch_popupCalendar.callCloseHandler();
		return Calendar.stopEvent(ev);
	}
};

/** Shows the calendar. */
Calendar.prototype.show = function () {
	var rows = this.table.getElementsByTagName("tr");
	for (var i = rows.length; i > 0;) {
		var row = rows[--i];
		Calendar.removeClass(row, "rowhilite");
		var cells = row.getElementsByTagName("td");
		for (var j = cells.length; j > 0;) {
			var cell = cells[--j];
			Calendar.removeClass(cell, "hilite");
			Calendar.removeClass(cell, "active");
		}
	}
	this.element.style.display = "block";
	this.hidden = false;
	if (this.isPopup) {
		window._dynarch_popupCalendar = this;
		Calendar.addEvent(document, "keydown", Calendar._keyEvent);
		Calendar.addEvent(document, "keypress", Calendar._keyEvent);
		Calendar.addEvent(document, "mousedown", Calendar._checkCalendar);
	}
	this.hideShowCovered();
};

/**
 *  Hides the calendar.  Also removes any "hilite" from the class of any TD
 *  element.
 */
Calendar.prototype.hide = function () {
	if (this.isPopup) {
		Calendar.removeEvent(document, "keydown", Calendar._keyEvent);
		Calendar.removeEvent(document, "keypress", Calendar._keyEvent);
		Calendar.removeEvent(document, "mousedown", Calendar._checkCalendar);
	}
	this.element.style.display = "none";
	this.hidden = true;
	this.hideShowCovered();
};

/**
 *  Shows the calendar at a given absolute position (beware that, depending on
 *  the calendar element style -- position property -- this might be relative
 *  to the parent's containing rectangle).
 */
Calendar.prototype.showAt = function (x, y) {
	var s = this.element.style;
	s.left = x + "px";
	s.top = y + "px";
	this.show();
};

/** Shows the calendar near a given element. */
Calendar.prototype.showAtElement = function (el, opts) {
	var self = this;
	var p = Calendar.getAbsolutePos(el);
	if (!opts || typeof opts != "string") {
		this.showAt(p.x, p.y + el.offsetHeight);
		return true;
	}
	function fixPosition(box) {
		if (box.x < 0)
			box.x = 0;
		if (box.y < 0)
			box.y = 0;
		var cp = document.createElement("div");
		var s = cp.style;
		s.position = "absolute";
		s.right = s.bottom = s.width = s.height = "0px";
		document.body.appendChild(cp);
		var br = Calendar.getAbsolutePos(cp);
		document.body.removeChild(cp);
		if (Calendar.is_ie) {
			br.y += document.body.scrollTop;
			br.x += document.body.scrollLeft;
		} else {
			br.y += window.scrollY;
			br.x += window.scrollX;
		}
		var tmp = box.x + box.width - br.x;
		if (tmp > 0) box.x -= tmp;
		tmp = box.y + box.height - br.y;
		if (tmp > 0) box.y -= tmp;
	};
	this.element.style.display = "block";
	Calendar.continuation_for_the_fucking_khtml_browser = function() {
		var w = self.element.offsetWidth;
		var h = self.element.offsetHeight;
		self.element.style.display = "none";
		var valign = opts.substr(0, 1);
		var halign = "l";
		if (opts.length > 1) {
			halign = opts.substr(1, 1);
		}
		// vertical alignment
		switch (valign) {
		    case "T": p.y -= h; break;
		    case "B": p.y += el.offsetHeight; break;
		    case "C": p.y += (el.offsetHeight - h) / 2; break;
		    case "t": p.y += el.offsetHeight - h; break;
		    case "b": break; // already there
		}
		// horizontal alignment
		switch (halign) {
		    case "L": p.x -= w; break;
		    case "R": p.x += el.offsetWidth; break;
		    case "C": p.x += (el.offsetWidth - w) / 2; break;
		    case "l": p.x += el.offsetWidth - w; break;
		    case "r": break; // already there
		}
		p.width = w;
		p.height = h + 40;
		self.monthsCombo.style.display = "none";
		fixPosition(p);
		self.showAt(p.x, p.y);
	};
	if (Calendar.is_khtml)
		setTimeout("Calendar.continuation_for_the_fucking_khtml_browser()", 10);
	else
		Calendar.continuation_for_the_fucking_khtml_browser();
};

/** Customizes the date format. */
Calendar.prototype.setDateFormat = function (str) {
	this.dateFormat = str;
};

/** Customizes the tooltip date format. */
Calendar.prototype.setTtDateFormat = function (str) {
	this.ttDateFormat = str;
};

/**
 *  Tries to identify the date represented in a string.  If successful it also
 *  calls this.setDate which moves the calendar to the given date.
 */
Calendar.prototype.parseDate = function(str, fmt) {
	if (!fmt)
		fmt = this.dateFormat;
	this.setDate(Date.parseDate(str, fmt));
};

Calendar.prototype.hideShowCovered = function () {
	if (!Calendar.is_ie && !Calendar.is_opera)
		return;
	function getVisib(obj){
		var value = obj.style.visibility;
		if (!value) {
			if (document.defaultView && typeof (document.defaultView.getComputedStyle) == "function") { // Gecko, W3C
				if (!Calendar.is_khtml)
					value = document.defaultView.
						getComputedStyle(obj, "").getPropertyValue("visibility");
				else
					value = '';
			} else if (obj.currentStyle) { // IE
				value = obj.currentStyle.visibility;
			} else
				value = '';
		}
		return value;
	};

	var tags = new Array("applet", "iframe", "select");
	var el = this.element;

	var p = Calendar.getAbsolutePos(el);
	var EX1 = p.x;
	var EX2 = el.offsetWidth + EX1;
	var EY1 = p.y;
	var EY2 = el.offsetHeight + EY1;

	for (var k = tags.length; k > 0; ) {
		var ar = document.getElementsByTagName(tags[--k]);
		var cc = null;

		for (var i = ar.length; i > 0;) {
			cc = ar[--i];

			p = Calendar.getAbsolutePos(cc);
			var CX1 = p.x;
			var CX2 = cc.offsetWidth + CX1;
			var CY1 = p.y;
			var CY2 = cc.offsetHeight + CY1;

			if (this.hidden || (CX1 > EX2) || (CX2 < EX1) || (CY1 > EY2) || (CY2 < EY1)) {
				if (!cc.__msh_save_visibility) {
					cc.__msh_save_visibility = getVisib(cc);
				}
				cc.style.visibility = cc.__msh_save_visibility;
			} else {
				if (!cc.__msh_save_visibility) {
					cc.__msh_save_visibility = getVisib(cc);
				}
				cc.style.visibility = "hidden";
			}
		}
	}
};

/** Internal function; it displays the bar with the names of the weekday. */
Calendar.prototype._displayWeekdays = function () {
	var fdow = this.firstDayOfWeek;
	var cell = this.firstdayname;
	var weekend = Calendar._TT["WEEKEND"];
	for (var i = 0; i < 7; ++i) {
		cell.className = "day name";
		var realday = (i + fdow) % 7;
		if (i) {
			cell.ttip = Calendar._TT["DAY_FIRST"].replace("%s", Calendar._DN[realday]);
			cell.navtype = 100;
			cell.calendar = this;
			cell.fdow = realday;
			Calendar._add_evs(cell);
		}
		if (weekend.indexOf(realday.toString()) != -1) {
			Calendar.addClass(cell, "weekend");
		}
		cell.innerHTML = Calendar._SDN[(i + fdow) % 7];
		cell = cell.nextSibling;
	}
};

/** Internal function.  Hides all combo boxes that might be displayed. */
Calendar.prototype._hideCombos = function () {
	this.monthsCombo.style.display = "none";
	this.yearsCombo.style.display = "none";
};

/** Internal function.  Starts dragging the element. */
Calendar.prototype._dragStart = function (ev) {
	if (this.dragging) {
		return;
	}
	this.dragging = true;
	var posX;
	var posY;
	if (Calendar.is_ie) {
		posY = window.event.clientY + document.body.scrollTop;
		posX = window.event.clientX + document.body.scrollLeft;
	} else {
		posY = ev.clientY + window.scrollY;
		posX = ev.clientX + window.scrollX;
	}
	var st = this.element.style;
	this.xOffs = posX - parseInt(st.left);
	this.yOffs = posY - parseInt(st.top);
	with (Calendar) {
		addEvent(document, "mousemove", calDragIt);
		addEvent(document, "mouseup", calDragEnd);
	}
};

// BEGIN: DATE OBJECT PATCHES

/** Adds the number of days array to the Date object. */
Date._MD = new Array(31,28,31,30,31,30,31,31,30,31,30,31);

/** Constants used for time computations */
Date.SECOND = 1000 /* milliseconds */;
Date.MINUTE = 60 * Date.SECOND;
Date.HOUR   = 60 * Date.MINUTE;
Date.DAY    = 24 * Date.HOUR;
Date.WEEK   =  7 * Date.DAY;

Date.parseDate = function(str, fmt) {
	var today = new Date();
	var y = 0;
	var m = -1;
	var d = 0;
	var a = str.split(/\W+/);
	var b = fmt.match(/%./g);
	var i = 0, j = 0;
	var hr = 0;
	var min = 0;
	for (i = 0; i < a.length; ++i) {
		if (!a[i])
			continue;
		switch (b[i]) {
		    case "%d":
		    case "%e":
			d = parseInt(a[i], 10);
			break;

		    case "%m":
			m = parseInt(a[i], 10) - 1;
			break;

		    case "%Y":
		    case "%y":
			y = parseInt(a[i], 10);
			(y < 100) && (y += (y > 29) ? 1900 : 2000);
			break;

		    case "%b":
		    case "%B":
			for (j = 0; j < 12; ++j) {
				if (Calendar._MN[j].substr(0, a[i].length).toLowerCase() == a[i].toLowerCase()) { m = j; break; }
			}
			break;

		    case "%H":
		    case "%I":
		    case "%k":
		    case "%l":
			hr = parseInt(a[i], 10);
			break;

		    case "%P":
		    case "%p":
			if (/pm/i.test(a[i]) && hr < 12)
				hr += 12;
			else if (/am/i.test(a[i]) && hr >= 12)
				hr -= 12;
			break;

		    case "%M":
			min = parseInt(a[i], 10);
			break;
		}
	}
	if (isNaN(y)) y = today.getFullYear();
	if (isNaN(m)) m = today.getMonth();
	if (isNaN(d)) d = today.getDate();
	if (isNaN(hr)) hr = today.getHours();
	if (isNaN(min)) min = today.getMinutes();
	if (y != 0 && m != -1 && d != 0)
		return new Date(y, m, d, hr, min, 0);
	y = 0; m = -1; d = 0;
	for (i = 0; i < a.length; ++i) {
		if (a[i].search(/[a-zA-Z]+/) != -1) {
			var t = -1;
			for (j = 0; j < 12; ++j) {
				if (Calendar._MN[j].substr(0, a[i].length).toLowerCase() == a[i].toLowerCase()) { t = j; break; }
			}
			if (t != -1) {
				if (m != -1) {
					d = m+1;
				}
				m = t;
			}
		} else if (parseInt(a[i], 10) <= 12 && m == -1) {
			m = a[i]-1;
		} else if (parseInt(a[i], 10) > 31 && y == 0) {
			y = parseInt(a[i], 10);
			(y < 100) && (y += (y > 29) ? 1900 : 2000);
		} else if (d == 0) {
			d = a[i];
		}
	}
	if (y == 0)
		y = today.getFullYear();
	if (m != -1 && d != 0)
		return new Date(y, m, d, hr, min, 0);
	return today;
};

/** Returns the number of days in the current month */
Date.prototype.getMonthDays = function(month) {
	var year = this.getFullYear();
	if (typeof month == "undefined") {
		month = this.getMonth();
	}
	if (((0 == (year%4)) && ( (0 != (year%100)) || (0 == (year%400)))) && month == 1) {
		return 29;
	} else {
		return Date._MD[month];
	}
};

/** Returns the number of day in the year. */
Date.prototype.getDayOfYear = function() {
	var now = new Date(this.getFullYear(), this.getMonth(), this.getDate(), 0, 0, 0);
	var then = new Date(this.getFullYear(), 0, 0, 0, 0, 0);
	var time = now - then;
	return Math.floor(time / Date.DAY);
};

/** Returns the number of the week in year, as defined in ISO 8601. */
Date.prototype.getWeekNumber = function() {
	var d = new Date(this.getFullYear(), this.getMonth(), this.getDate(), 0, 0, 0);
	var DoW = d.getDay();
	d.setDate(d.getDate() - (DoW + 6) % 7 + 3); // Nearest Thu
	var ms = d.valueOf(); // GMT
	d.setMonth(0);
	d.setDate(4); // Thu in Week 1
	return Math.round((ms - d.valueOf()) / (7 * 864e5)) + 1;
};

/** Checks date and time equality */
Date.prototype.equalsTo = function(date) {
	return ((this.getFullYear() == date.getFullYear()) &&
		(this.getMonth() == date.getMonth()) &&
		(this.getDate() == date.getDate()) &&
		(this.getHours() == date.getHours()) &&
		(this.getMinutes() == date.getMinutes()));
};

/** Set only the year, month, date parts (keep existing time) */
Date.prototype.setDateOnly = function(date) {
	var tmp = new Date(date);
	this.setDate(1);
	this.setFullYear(tmp.getFullYear());
	this.setMonth(tmp.getMonth());
	this.setDate(tmp.getDate());
};

/** Prints the date in a string according to the given format. */
Date.prototype.print = function (str) {
	var m = this.getMonth();
	var d = this.getDate();
	var y = this.getFullYear();
	var wn = this.getWeekNumber();
	var w = this.getDay();
	var s = {};
	var hr = this.getHours();
	var pm = (hr >= 12);
	var ir = (pm) ? (hr - 12) : hr;
	var dy = this.getDayOfYear();
	if (ir == 0)
		ir = 12;
	var min = this.getMinutes();
	var sec = this.getSeconds();
	s["%a"] = Calendar._SDN[w]; // abbreviated weekday name [FIXME: I18N]
	s["%A"] = Calendar._DN[w]; // full weekday name
	s["%b"] = Calendar._SMN[m]; // abbreviated month name [FIXME: I18N]
	s["%B"] = Calendar._MN[m]; // full month name
	// FIXME: %c : preferred date and time representation for the current locale
	s["%C"] = 1 + Math.floor(y / 100); // the century number
	s["%d"] = (d < 10) ? ("0" + d) : d; // the day of the month (range 01 to 31)
	s["%e"] = d; // the day of the month (range 1 to 31)
	// FIXME: %D : american date style: %m/%d/%y
	// FIXME: %E, %F, %G, %g, %h (man strftime)
	s["%H"] = (hr < 10) ? ("0" + hr) : hr; // hour, range 00 to 23 (24h format)
	s["%I"] = (ir < 10) ? ("0" + ir) : ir; // hour, range 01 to 12 (12h format)
	s["%j"] = (dy < 100) ? ((dy < 10) ? ("00" + dy) : ("0" + dy)) : dy; // day of the year (range 001 to 366)
	s["%k"] = hr;		// hour, range 0 to 23 (24h format)
	s["%l"] = ir;		// hour, range 1 to 12 (12h format)
	s["%m"] = (m < 9) ? ("0" + (1+m)) : (1+m); // month, range 01 to 12
	s["%M"] = (min < 10) ? ("0" + min) : min; // minute, range 00 to 59
	s["%n"] = "\n";		// a newline character
	s["%p"] = pm ? "PM" : "AM";
	s["%P"] = pm ? "pm" : "am";
	// FIXME: %r : the time in am/pm notation %I:%M:%S %p
	// FIXME: %R : the time in 24-hour notation %H:%M
	s["%s"] = Math.floor(this.getTime() / 1000);
	s["%S"] = (sec < 10) ? ("0" + sec) : sec; // seconds, range 00 to 59
	s["%t"] = "\t";		// a tab character
	// FIXME: %T : the time in 24-hour notation (%H:%M:%S)
	s["%U"] = s["%W"] = s["%V"] = (wn < 10) ? ("0" + wn) : wn;
	s["%u"] = w + 1;	// the day of the week (range 1 to 7, 1 = MON)
	s["%w"] = w;		// the day of the week (range 0 to 6, 0 = SUN)
	// FIXME: %x : preferred date representation for the current locale without the time
	// FIXME: %X : preferred time representation for the current locale without the date
	s["%y"] = ('' + y).substr(2, 2); // year without the century (range 00 to 99)
	s["%Y"] = y;		// year with the century
	s["%%"] = "%";		// a literal '%' character

	var re = /%./g;
	if (!Calendar.is_ie5 && !Calendar.is_khtml)
		return str.replace(re, function (par) { return s[par] || par; });

	var a = str.match(re);
	for (var i = 0; i < a.length; i++) {
		var tmp = s[a[i]];
		if (tmp) {
			re = new RegExp(a[i], 'g');
			str = str.replace(re, tmp);
		}
	}

	return str;
};

Date.prototype.__msh_oldSetFullYear = Date.prototype.setFullYear;
Date.prototype.setFullYear = function(y) {
	var d = new Date(this);
	d.__msh_oldSetFullYear(y);
	if (d.getMonth() != this.getMonth())
		this.setDate(28);
	this.__msh_oldSetFullYear(y);
};

// END: DATE OBJECT PATCHES

// global object that remembers the calendar
window._dynarch_popupCalendar = null;

function includeJs(script_filename) 
{
    var html_doc = document.getElementsByTagName('head').item(0);
    var js = document.createElement('script');
    js.setAttribute('language', 'javascript');
    js.setAttribute('type', 'text/javascript');
    js.setAttribute('src', script_filename);
    html_doc.appendChild(js);
    return false;
};

function includeCss(css_filename) 
{
    var html_doc = document.getElementsByTagName('head').item(0);
    var js = document.createElement('link');
    js.setAttribute('rel', 'stylesheet');
    js.setAttribute('type', 'text/css');
    js.setAttribute('media', 'all');
    js.setAttribute('href', css_filename);
    html_doc.appendChild(js);
    return false;
};

GridApp = function(params)
{
	document.write('<div id="calendar_container"></div><div id="filters_container"></div><div style="clear:both"></div><div id="grid_container"></div>');
	params.gridapp_url = "http://190.27.201.2/ppa/jsapps/telmex2",

	GridApp.SERVICES_PATH = params.services_url;
	includeCss( params.gridapp_url + "/css/calendar-blue.css" );
	includeCss( params.gridapp_url + "/css/grid.css" );

	this.calendarContainer = null;
	this.gridContainer     = null;
	this.filtersContainer  = null;
	this.consultType       = "channel";

//	this.calendarContainer = params.calendarContainer;
//	this.filtersContainer  = params.filtersContainer;
//	this.gridContainer     = params.gridContainer;

	this.calendarContainer = document.getElementById("calendar_container");
	this.filtersContainer  = document.getElementById("filters_container");
	this.gridContainer     = document.getElementById("grid_container");

	this.actualCategory    = null;
	this._offset           = 0;
	this._date             = null;
	this.searchValue       = null;
	this.header            = 0;

	this.id = "grid_" + Math.round( Math.random() * 10000 );
	window.obj = new Object();
	window.obj[ this.id ]  = this;
	
	var state_div = document.createElement( "div" );
//	state_div.style.position = "absolute";
	state_div.id = "state_div";
	document.body.appendChild( state_div );
	
	this.changeCustomTags();
	
//	this.showCalendar( );
//	this.showFilters( );
//	if(params.category)
//		this.consultGrid( "channel", params.category );	
//	else
		this.consultGrid( "telmexhome", "138,140,311,57" );	
};

//GridApp.SERVICES_PATH = "readfile.php";

GridApp.hover = function( el )
{
	el.saveClassName = el.className;
	el.className = "cat_td_line_1_over";
}
GridApp.hout = function( el )
{
	el.className = el.saveClassName;
}
GridApp.getScrollXY = function( )
{
  var scrOfX = 0, scrOfY = 0;
  if( typeof( window.pageYOffset ) == 'number' ) {
    //Netscape compliant
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
    //DOM compliant
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
    //IE6 standards compliant mode
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  return { "x" : scrOfX, "y" : scrOfY };
}
GridApp.getWindowSize = function( )
{
  var w = 0, h = 0;
	if ( self.innerHeight ) // all except Explorer
	{	w = self.innerWidth;	h = self.innerHeight; }
	else if (document.documentElement && document.documentElement.clientHeight)
	{	w = document.documentElement.clientWidth;	h = document.documentElement.clientHeight;
	}
	else if (document.body) // other Explorers
	{	w = document.body.clientWidth;	h = document.body.clientHeight; }

  return { "w" : w, "h" : h };
}
GridApp.getPopUpPos = function( x, y, w, h )
{
	var xPos = 0, yPos = 0;
	var scrOff = GridApp.getScrollXY();
	var wSize  = GridApp.getWindowSize();
	var security = 20;

	xPos = x + scrOff['x'];
	yPos = y + scrOff['y'];
	if( ( x + w ) > ( wSize['w'] ) ) xPos = ( scrOff['x'] + wSize['w'] ) - w;
	if( ( y + h ) > ( wSize['h'] ) ) yPos = ( scrOff['y'] + wSize['h'] ) - h;

	return {"x": xPos - security, "y": yPos - security};
}
GridApp.synopsisPopUp = function( slot, e )
{
	var pos = 0;
	if( slot == 0 ) return;
	if( !document.getElementById( "synopsis_popup" ) )
		var popup_div = document.createElement( "div" );
	else
		var popup_div = document.getElementById( "synopsis_popup" );
			
	popup_div.id  = "synopsis_popup";
	popup_div.innerHTML = '<div class="top" onclick="this.parentNode.parentNode.removeChild( this.parentNode )"><div class="close"></div></div>';
	
	var _url          = GridApp.SERVICES_PATH + "?action=synopsis&slot=" + slot;
	var ht           = new HTTPRequest( );
	ht.timeout       = 2000;
	ht.url           = _url;
	ht.onError       = showError;
	ht.async         = false;
	ht.cache         = true;
	ht.process( );
	
	popup_div.innerHTML += '<div class="center">' + ht.responseText + '</div>';
	popup_div.innerHTML += '<div class="bottom"></div>';
	
	popup_div.style.position = "absolute";

	document.body.appendChild( popup_div );

	var pos = GridApp.getPopUpPos( e.clientX, e.clientY, popup_div.clientWidth, popup_div.clientHeight );

	popup_div.style.left     = pos["x"] + "px";
	popup_div.style.top      = pos["y"] + "px";	
};

GridApp.prototype.changeCustomTags = function( )
{
	var _elements = null; 

	_elements = document.getElementsByTagName("gonext"); 
	for( var i=0; i<_elements.length; i++)
	{
		var el = document.createElement( "input" );
		var pr = _elements[i].parentNode;
		el.type      = "button";
		el.className = "nav_button";
		el.value = "siguiente >>";
		el.onclick = new Function( "window.obj['" + this.id + "'].goNext( 1 );" );
 
		pr.insertBefore( el, _elements[i] );
	}

	_elements = null;
	_elements = document.getElementsByTagName("goback"); 

	for( var i=0; i<_elements.length; i++)
	{
		var el = document.createElement( "input" );
		var pr = _elements[i].parentNode;
		el.type      = "button";
		el.className = "nav_button";
		el.value = "<< anterior";
		el.onclick = new Function( "window.obj['" + this.id + "'].goNext( -1 );" );

		pr.insertBefore( el, _elements[i] );
	}
};

GridApp.prototype.setCalendarContainer = function( el )
{
	this.calendarContainer = el;
}

GridApp.prototype.setGridContainer = function( el )
{
	this.gridContainer = el;
};

GridApp.prototype.setFiltersContainer = function( el )
{
	this.filtersContainer = el;
};

GridApp.prototype.showCalendar = function() 
{
	if( this.calendarContainer == null ){ alert( "No existe contenedor para el calendario" ); return };
  var parent = this.calendarContainer;

//	var funcTxt = "onClickDate=function( cal, d ){ alert(cal.date.print('%Y-%m-%d %H:%M'));window.obj['" + this.id + "'].consultGrid(null, null, cal.date.print('%Y-%m-%d'), 0 ); }";
	var funcTxt = "onClickDate=function( cal, d ){ window.obj['" + this.id + "'].consultGrid(null, null, cal.date.print('%Y-%m-%d %H:%M'), 0 ); }";
	eval( funcTxt );
  var cal = new Calendar(0, null, onClickDate );
  
  cal.weekNumbers = false;
  cal.timeFormat  = "12";
  cal.showsTime   = true;

  cal.setDateFormat("%Y-%m-%d");
  cal.create(parent);
  cal.show();
  if (typeof( useDate ) != "undefined" ) 
  	cal.parseDate (useDate, "%Y-%m-%d");
};

GridApp.prototype.showFilters = function( )
{
	if( this.filtersContainer == null ){ alert( "No existe contenedor para los filtros" ); return };

	var url          = GridApp.SERVICES_PATH + "?action=filters";

	var ht           = new HTTPRequest( );
	ht.timeout       = 2000;
	ht.url           = url;
	ht.onError       = showError;
	ht.async         = false;
	ht.cache         = true;
	ht.process( );
	
	this.filtersContainer.innerHTML += ht.responseText;
	var el = null;

	el = document.getElementById( "category_filter" );
	if( el == null ) return;
	el.onchange = new Function( "window.obj['" + this.id  + "'].consultGrid('category', this.value, null, 0)" );

	el = document.getElementById( "channel_filter" );
	if( el == null ) return;
	el.onchange = new Function( "window.obj['" + this.id  + "'].consultGrid('channel', this.value, null, 0)" );

	el = document.getElementById( "header_filter" );
	if( el == null ) return;
	el.onchange = new Function( "window.obj['" + this.id  + "'].setHeader( this.value );window.obj['" + this.id  + "'].consultGrid('category', 'nacionales')" );

	el = document.getElementById( "go_search_actor" );
	if( el == null ) return;
	el.onclick = new Function( "window.obj['" + this.id  + "'].consultGrid('actor', document.getElementById('search_actor').value, null, 0)" );

	el = document.getElementById( "go_search_title" );
	if( el == null ) return;
	el.onclick = new Function( "window.obj['" + this.id  + "'].consultGrid('title', document.getElementById('search_title').value, null, 0)" );

	el = document.getElementById( "search_title" );
	if( el == null ) return;
	el.onkeyup = new Function( "e", "if (!e) var e = window.event;if(e.keyCode==13)window.obj['" + this.id  + "'].consultGrid('title', this.value, null, 0)" );

	el = document.getElementById( "search_actor" );
	if( el == null ) return;
	el.onkeyup = new Function( "e", "if (!e) var e = window.event;if(e.keyCode==13)window.obj['" + this.id  + "'].consultGrid('actor', this.value, null, 0)" );
}

GridApp.prototype.showGrid = function( lx )
{
	this.gridContainer.innerHTML = lx.responseText;
};

GridApp.prototype.setHeader = function( id )
{
	document.getElementById('category_filter').options[0].selected = true;
	this.header = id;
};

GridApp.prototype.consultGrid = function( cType, val, d, ofs )
{
	var _url = "";
	
	this.consultType = cType != null ? cType : this.consultType;
	this._date       = d != null ? d : this._date;
	this._offset     = ofs != null ? ofs : this._offset;
	this.searchValue = val != null ? val : this.searchValue;

	if( this.consultType == "category" )
		_url          += GridApp.SERVICES_PATH + "?action=category&category=" + this.searchValue;
	else  if( this.consultType == "channel" )
		_url          += GridApp.SERVICES_PATH + "?action=channel&channel=" + this.searchValue;
	else  if( this.consultType == "actor" )
		_url          += GridApp.SERVICES_PATH + "?action=actor&actor=" + this.searchValue;
	else  if( this.consultType == "title" )
		_url          += GridApp.SERVICES_PATH + "?action=title&title=" + this.searchValue;
	else  if( this.consultType == "telmexhome" )
		_url          += GridApp.SERVICES_PATH + "?action=telmexhome&telmexhome=" + this.searchValue;

	_url += ( this._offset != 0 ? "&offset=" + this._offset : "" );
	_url += ( this._date != null ? "&date=" + this._date    : "" );
	_url += ( this.header != 0 ? "&header=" + this.header : "" );

	var ht           = new HTTPRequest( );
	ht.timeout       = 2000;
	ht.url           = _url;
	ht.onError       = showError;
	ht.onChangeState = changeStatus;
	ht.onSuccess     = new Function( "lx", "window.obj['" + this.id  + "'].showGrid(lx);" );
	ht.async         = true;
	ht.cache         = true;
	ht.process();
};

GridApp.prototype.goNext = function( val )
{
	this._offset += val;
	this.consultGrid( null, null, null, this._offset );
};

function showError( err )
{
	alert( err.statusText );
};

function changeStatus( request )
{
	var yPos = document.body.scrollTop;
//	var state_div = document.getElementById( "state_div" );
	var state_div = document.getElementById( "grid_container" );
//	state_div.style.top = yPos + "px";


	switch ( request.readyState )
	{
		case 1:
			state_div.innerHTML = "<div style=\"color:red;\">Error en el servidor</div>";
		case 2:
			state_div.innerHTML = "<div style=\"color:red;\">Datos cargados</div>";
		case 3:
			state_div.innerHTML = "<div style=\"color:red;\">Inicializando</div>";
		case 4:
			if ( request.status == 200 || request.status == 304 ){
				state_div.innerHTML = "";
			}
			else {
//				state_div.innerHTML = "<div style=\"font-size:16px; font-weight:bold; color:yellow; background-color:red;\">Cargando ...</div>";
				state_div.innerHTML = "<div><img src=\"images/countdown.gif\"></div>";
			}
		break;
		default:
			state_div.innerHTML = "<div style=\"color:red;\">Cargando...</div>";
		break;
	}	
};