<?
class FamilyTree
{
	var $name;
	var $length;
	var $item;
	var $attributes;
	var $parent;
	var $firstChild;
	var $nextSibling;
	var $previousSibling;
	var $children;
	var $lastChild;
	
	function FamilyTree( )
	{
		$this->length          = 0;
		$this->item            = 0;
		$this->parent          = null;
		$this->firstChild      = null;
		$this->nextSibling     = null;
		$this->previousSibling = null;
		$this->name            = "none";
		$this->lastChild       = null;
		$this->attributes      = array();
		$this->children        = array();
	}	
	
	function getAttribute( $name )           { return $this->attributes[$name]; }
	function getName( )                      { return $this->name; }

	function setAttribute( $name, $value )   { $this->attributes[$name] = $value; }
	function setName( $name )                { $this->name = $name; }
	
	function appendChild( &$child )
	{ 
		$this->children[$this->length]         = &$child;
		$this->lastChild                       = &$child;
		$this->children[$this->length]->item   = $this->length;
		$this->children[$this->length]->parent = &$this;
		if($this->length === 0)
		{
			$this->firstChild = &$child;
		}
		else
		{
			$this->children[$this->length - 1]->nextSibling = &$child; 
			$this->children[$this->length]->previousSibling = &$this->children[$this->length - 1]; 
		}
		$this->length++;
	} 
	
	function &createBranch( $pos = false )
	{
		if( $pos === false )
		{
			unset( $tmpTree );
			$tmpTree = new FamilyTree();
			$this->appendChild( &$tmpTree );
			return  $tmpTree;
		}
		else return $this->insertInPos( $pos );
	}
	
	function &insertInPos( $pos )
	{
		$child            = &$this->createBranch();
		unset( $child->previousSibling->nextSibling );
		$child->previousSibling->nextSibling = null;

		$this->children[] = null;
		
		for( $i=$this->length-1; $i >= $pos ; $i--)
		{
			$this->children[$i+1] = &$this->children[$i];
			$this->children[$i+1]->item++;
		}
		
		$this->children[$pos]              = &$child;
		$this->children[$pos]->item        = $pos;

		array_pop( $this->children );
		
		$this->children[$pos]->nextSibling                  = &$this->children[$pos+1];
		$this->children[$pos]->nextSibling->previousSibling = &$this->children[$pos];
		
		if( $pos != 0 )
		{
			$this->children[$pos]->previousSibling              = &$this->children[$pos-1];
			$this->children[$pos]->previousSibling->nextSibling = &$this->children[$pos];
		}
		
		$this->firstChild = &$this->children[0];
		$this->lastChild  = &$this->children[$this->length - 1];
		
		return $this->children[$pos];
	}
	
	function toString( $spaces = "" )
	{
		$return = $spaces . "(" . $this->item . ") " . $this->name . ": ";
		foreach( $this->attributes as $key => $value )
		{
			$return .= "[" . $key . " => " . $value . "] ";
		}
		$return .=  "\n";
		
		$spaces .= "\t";
		foreach( $this->children as $child )
		{
			$return .= $child->toString( $spaces );
		}
		$spaces = substr( $spaces, 0,-1);
	
		return $return;
	}

	function destroy()
	{ 
		for($i=0; $i< $this->length; $i++)
		{
			if($this->children[$i]) 
			{
				$this->children[$i]->destroy();
				$this->children[$i] = null;
				unset($this->children[$i]);
			}
		}
	}

	function __destruct()
	{ 
//			$this->destroy();
	}
}
?>