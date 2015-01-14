<?
require_once "ppa11/class/FamilyTree.class.php";

class ProgramGrid extends FamilyTree
{

	function &createBranch( $pos = false )
	{
		if( $pos === false )
		{
			unset( $tmpTree );
			$tmpTree = new ProgramGrid();
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
				
	function &createDay( )
	{
		if( $this->name != "main" ) die( "The object is not a day ProgramGrid" );
		
		unset( $tmpBranch );
		$tmpBranch = &$this->createBranch( );
		$tmpBranch->setName( "day" );
		return $tmpBranch;
	}
	
	function &createTime( $time, $duration, $title, $opts = false )
	{
		if( $this->name != "day" ) die( "The object is not a day ProgramGrid" );
		
		unset( $tmpBranch );
		$tmpBranch = &$this->createBranch( );
		$tmpBranch->setName( "time" );
		$tmpBranch->setAttribute( "start", $this->hourToTime( $time ) );
		$tmpBranch->setAttribute( "time", substr( $time, 0, 5 ) );

/* este pedacito es x si la duración que está en la base de datos está mal */
		if( $tmpBranch->previousSibling )
		{
			$dur = $tmpBranch->getAttribute("start") - $tmpBranch->previousSibling->getAttribute("start");
			$tmpBranch->previousSibling->setAttribute( "duration", $dur );
		} 
		else if( $tmpBranch->parent->previousSibling && $opts )
		{
			$dur = (24*60) + $tmpBranch->getAttribute("start") - $tmpBranch->parent->previousSibling->lastChild->getAttribute("start");
			$tmpBranch->parent->previousSibling->lastChild->setAttribute( "duration", $dur );			
		}
		$tmpBranch->setAttribute( "duration", 1440 - $tmpBranch->getAttribute("start") );
/*hasta aquí*/
//		$tmpBranch->setAttribute( "duration", $duration );
		$tmpBranch->setAttribute( "title", $title );
		return $tmpBranch;
	}
	
	function fillFirstProgram()
	{
		if( $this->name != "main" ) die( "The object is not a main ProgramGrid" );
				
		if( $this->children[0]->firstChild->getAttribute("start") != 0 )
		{
			$child = &$this->children[0]->createBranch( 0 );
			$child->setName( "time" );
			$child->setAttribute( "start", "0" );
			$child->setAttribute( "time", "00:00" );
			$child->setAttribute( "title", $this->lastChild->lastChild->getAttribute( "title" ) );
			$child->setAttribute( "duration", $this->children[0]->children[1]->getAttribute("start") );
			$child->setAttribute( "cont", true );
		}

		unset($child);

		for( $i=1; $i< $this->length; $i++ )
		{
			if( $this->children[$i]->firstChild->getAttribute("start") != 0 )
			{
				$child = &$this->children[$i]->createBranch( 0 );
				$child->setName( "time" );
				$child->setAttribute( "start", "0" );
				$child->setAttribute( "time", "00:00" );
				$child->setAttribute( "title", $this->children[$i-1]->lastChild->getAttribute( "title" ) );
				$child->setAttribute( "duration", $this->children[$i]->children[1]->getAttribute("start") );
				$child->setAttribute( "cont", true );
			}
		}
	}
	
	function groupByDay()
	{
		if( $this->name != "main" ) die( "The object is not a main ProgramGrid" );

		for( $i=0; $i< $this->length; $i++ )
		{
			$time = &$this->children[$i]->firstChild;
			while( $time )
			{
				for( $j=$i+1; $j< $this->length; $j++ )
				{
					$nextDayTime = &$this->children[$j]->getElementByStart( $time->getAttribute("start") );
				
					if( $nextDayTime && 
					$time->getAttribute( "title" )  == $nextDayTime->getAttribute( "title" ) && 
					$time->getAttribute( "duration" ) == $nextDayTime->getAttribute( "duration" ) &&
					$time->getAttribute( "grouped" ) != "yes" )
					{
						$nextDayTime->setAttribute("grouped", "yes" );
					}
					else break;
				}
				if( $time->getAttribute( "grouped" ) == "" ) $time->setAttribute( "days", $j-$i);
				$time = &$time->nextSibling;
			}
		}
	}

	function groupByHour()
	{
		if( $this->name != "main" ) die( "The object is not a main ProgramGrid" );

		for( $i=0; $i< $this->length; $i++ )
		{
			$time = &$this->children[$i]->firstChild;
			while( $time->nextSibling )
			{
				$nextTime = &$time->nextSibling;
				while( ( $time->getAttribute("chapter") == $nextTime->getAttribute("chapter") ) 
					&& ( $time->getAttribute("grouped") != "yes" )
					&& $nextTime->nextSibling )
				{
						$thisDuration = $time->getAttribute( "duration" );
						$nextDuration = $nextTime->getAttribute( "duration" );

						$time->setAttribute( "duration", $thisDuration + $nextDuration );
						$nextTime->setAttribute( "grouped", "yes" );
						$nextTime = &$nextTime->nextSibling;		
				}
				$time = &$time->nextSibling;
			}
		}
	}
		
	function &getElementByStart( $start )
	{
		if( $this->name != "day" ) die( "The object is not a day ProgramGrid" );
		
		for( $i=0; $i< $this->length; $i++ )
		{
			if( $this->children[$i]->getAttribute("start") == $start ) return $this->children[$i];
		}
		return false;
	}

	function &getElementByDate( $date )
	{
		if( $this->name != "main" ) die( "The object is not a main ProgramGrid" );
		
		for( $i=0; $i< $this->length; $i++ )
		{
			if( $this->children[$i]->getAttribute("date") == $date ) return $this->children[$i];
		}
		return false;
	}

	function hourToTime( $str )
	{
			$tmp        = explode(":", $str);
			$time       = $tmp[0] * 60 + $tmp[1];
			return $time;
	}
}
?>