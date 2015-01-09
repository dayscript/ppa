// JavaScript Document
function MM_swapImgRestore ( ) {	//v3.0
	var i, x, a = document.MM_sr;
	for ( i = 0; a && i < a.length && ( x = a[i] ) && x.oSrc; i++ )
		x.src = x.oSrc;
}

function MM_preloadImages ( ) {	//v3.0
	var d = document;
	if ( d.images )	{
		if ( !d.MM_p )
			d.MM_p = new Array();
		var i, j = d.MM_p.length, a = MM_preloadImages.arguments;
		for ( i = 0; i < a.length; i++ )
			if ( a[i].indexOf("#") != 0 )	{
				d.MM_p[j] = new Image;
				d.MM_p[j++].src = a[i];
			}
	}
}

function MM_findObj ( n, d )	{	//v4.01
	var p, i, x;
	if ( !d )
		d = document;
	if ( ( p = n.indexOf("?") ) > 0 && parent.frames.length )	{
		d = parent.frames[n.substring( p + 1 )].document;
		n = n.substring( 0, p );
	}
	if ( !( x = d[n] ) && d.all )
		x = d.all[n];
		for ( i = 0; !x && i < d.forms.length; i++ )
			x = d.forms[i][n];
	for( i = 0; !x && d.layers && i < d.layers.length; i++ )
		x = MM_findObj( n,d.layers[i].document );
	if( !x && d.getElementById )
		x = d.getElementById(n);
	
	return x;
}

function MM_swapImage ( )	{	//v3.0
	var i, j = 0, x, a = MM_swapImage.arguments;
	document.MM_sr = new Array;
	for( i = 0; i < ( a.length - 2 ); i += 3 )
		if ( ( x = MM_findObj( a[i] ) ) != null )	{
			document.MM_sr[j++] = x;
		if( !x.oSrc )
			x.oSrc = x.src;
		x.src = a[i+2];
	}
}

function MM_jumpMenu( targ, selObj, restore)	{	//v3.0
	eval ( targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
	if ( restore )
		selObj.selectedIndex = 0;
}

function hide ( id )	{
	document.getElementById(id).style.visibility = "hidden";
}

function show ( id )	{
     document.getElementById(id).style.visibility = "visible";
}

function changeBgColor( color, id ){
	try	{
		document.getElementById( id ).style.background = color;
		document.getElementById( id + '1' ).style.background = color;				
		document.getElementById( id ).firstChild.style.color = ( color == "" )?"#FFFFFF" : "#333333";
} catch( err )	{
		;
	}
}

function changeTdBgColor( td, color ){
	td.style.background = color;
}

function changeColor( td, bgColor, fontColor ){
	td.style.background = bgColor;
	td.style.color = fontColor;
}

function changeBorder( id, type ){
	document.getElementById( id ).style.borderTop = ( type == 1 )?"1px solid #DDDDDD":"";
	document.getElementById( id ).style.borderLeft = ( type == 1 )?"1px solid #DDDDDD":"";
	document.getElementById( id ).style.borderBottom = ( type == 1 )?"1px solid #333333":"";
	document.getElementById( id ).style.borderRight = ( type == 1 )?"1px solid #333333":"";
}
			
function changeStateRow( id_row ){
	TR = document.getElementById( id_row );
	TR.style.display = ( TR.style.display == "none" )?"":"none";
	document.getElementById( id_row + "_image" ).src = ( TR.style.display == "none" )? URLBaseLive + "images/arriba_des.gif" : URLBaseLive + "images/abajo_des.gif";
}