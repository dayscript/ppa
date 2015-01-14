<?
class ProgramBlock
{
	var $id;
	var $day;
	var $start;
	var $duration;
	var $days;
	var $title;
	var $previous;
	var $next;
	
	function ProgramBlock( )
	{
		$this->id       = 0;
		$this->day      = 0;
		$this->start    = 0;
		$this->duration = 0;
		$this->days     = 1;
		$this->title    = $id;
	}
	
	function getId()      { return $this->id; }
	function getDay()     { return $this->day; }
	function getStart()   { return $this->start; }
	function getDuration(){ return $this->duration; }
	function getDays()    { return $this->days; }
	function getTitle()   { return $this->title; }
	function getPrevious(){ return $this->previous; }
	function getNext()    { return $this->next; }

	function getElementByStart( $start )
	{ 
		while( $this->next )
		{
			if( $this->start == $start ) return true;
			$this = $this->next;
		}
		return false;
	}
	
	function setId($id)            { $this->id       = $id; }
	function setDay($day)          { $this->day      = $day; }
	function setStart($start)      { $this->start    = $start; }
	function setDuration($duration){ $this->duration = $duration; }
	function setDays($days)        { $this->days     = $days; }
	function setTitle($title)      { $this->title    = $title; }
	function setPrevious(&$prev)   { $this->previous = &$prev; return $this; }
	function setNext(&$next)       { $this->next     = &$next;  return $this; } //www.unpaltda.com rocio molat
}
?>