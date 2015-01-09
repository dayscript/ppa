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
