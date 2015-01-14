<?
class SlotChapters
{
	var $slots;
	var $classNames;

	function SlotChapters( $slots )
	{
		$this->slots = array();
		if(!empty( $slots ) )
			$this->load( $slots );
			
		$this->classNames['Infantil'] = "children";
		$this->classNames['Deportes'] = "sports";
		$this->classNames['Movie']    = "movie";
	}
	
	function load( $slots )
	{
		$inStr = implode( ",", $slots );
		
		$sql = "SELECT slot_chapter.slot, serie.gender ".
		       "FROM chapter, slot_chapter, serie ".
		       "WHERE chapter.id = slot_chapter.chapter ". 
		       "AND serie.id = chapter.serie ".
		       "AND chapter.serie > 0 ".
		       "AND slot_chapter.slot in ( ". $inStr ." ) ".
		       "ORDER BY slot";

		$result = db_query($sql );
		
		while( $row = db_fetch_array( $result ) )
		{
			$this->slots[$row['slot']] = $row['gender'];
		}

		$sql = "SELECT slot_chapter.slot ".
		       "FROM chapter, slot_chapter ".
		       "WHERE chapter.id = slot_chapter.chapter ". 
		       "AND chapter.movie > 0 ".
		       "AND slot_chapter.slot in ( ". $inStr ." ) ".
		       "ORDER BY slot";

		$result = db_query($sql );
		
		while( $row = db_fetch_array( $result ) )
		{
			$this->slots[$row['slot']] = "Movie";
		}
		
		asort( $this->slots );
	}
	
	function getClassName($slot)
	{
		$gender = $this->slots[$slot];
		return $this->classNames[ $gender ];
	}

	function getGender($slot)
	{
		$gender = $this->slots[$slot];
		return $gender;
	}
}
?>