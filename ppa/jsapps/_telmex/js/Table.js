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