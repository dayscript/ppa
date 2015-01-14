<?
class Channel
{
	var $id;
	var $name;
	var $shortname;
	var $number;
	var $program;
	 
	function Channel()
	{
		$this->id = "";
		$this->name = "";
		$this->shortname = "";
		$this->program = Array();		
	}
	
	/***********************
	*  SETS
	***********************/
	
	function setId( $str )
	{
		$this->id = $str;
	}

	function setName( $str )
	{
		$this->name = $str;
	}

	function setNumber( $str )
	{
		$this->number = $str;
	}

	function setShortName( $str )
	{
		$this->shortname = $str;
	}

	/***********************
	*  GETS
	***********************/

	function getId()
	{
		return $this->id;
	}

	function getName()
	{
		return $this->name;
	}

	function getNumber()
	{
		return $this->number;
	}

	function getShortName()
	{
		return $this->shortname;
	}
	
	function getProgramBySchedule( $search_date, $search_hour )
	{
		$find = -1 ;
		if(is_array($this->program[$search_date]))
		{
			foreach($this->program[$search_date] as $hour => $title)
			{
				if( $hour > $search_hour )
					if($find == -1)
						return $this->getProgramBySchedule(date("Y-m-d", strtotime($search_date, -86400)), "23:59");
					else
						return $find;
				else
					$find = $title;
			}
			return $find;
		}
		return "Programación Sin Confirmar";
	}

	/***********************
	*  
	***********************/

	function appendProgram( $date, $hour, $title )
	{
		$this->program[$date][$hour] = $title;
	}
}
?>