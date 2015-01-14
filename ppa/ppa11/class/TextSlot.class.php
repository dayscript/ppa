<?
Class TextSlot
{

	var $result;
	var $current;
	var $channel;
	var $next;
	var $HOUR;
	var $file;
	var $properties;
	
	function TextSlot( $channel, $file_name )
	{
		if( $channel == "") die( "Error en el id del canal" );
		$this->HOUR    = 60*60;
		$this->channel = $channel;
		$this->file    = array();
		$this->current = array();
		$this->next    = array();
		$this->result  = array();

		@$this->file = file( $file_name );

		if( empty( $this->file ) ) echo ("Error leyendo el archivo<br>");
		$this->setProperties();
		
		if( $this->properties["id"] == $channel )
		{
			switch( $this->properties["type"] )
			{
				case 2:
					$this->processType2( $file );
				break;
				case 3:
					$this->processType3( $file );
				break;
				default;
					print( "Formato Desconocido" );
				break;
			}
		}
		else
			print( "El ID del canal no coincide" );
	}
	
	function getProperties( $name )
	{
		return $this->properties[$name];
	}
	
	function getTime( $pointer="current" )
	{
		if($pointer == "current")
			return $this->current['time'] + ( ( -5 - $this->properties["gmt"] ) * $this->HOUR ); //el - 5 es para compensar la hora colombia
		else if($pointer == "next" && $this->next['time'] !== false )
			return $this->next['time'] + ( ( -5 - $this->properties["gmt"] ) * $this->HOUR ); //el - 5 es para compensar la hora colombia
		else return false;
	}

	function getTitle( )
	{
		return $this->current['title'];
	}
	
	function getChannel( )
	{
		return $this->channel;
	}

	function getDuration( )
	{
		$return = ($this->next['time'] - $this->current['time']) / 60;
		return $return > 0 ? $return : 0;
	}
	
	function getId( )
	{
		return $this->current['id'];
	}
	
	function setId( $id )
	{
		$this->current['id'] = $id;
	}
	
	function setProperties()
	{
		while( ereg("^#", $this->file[0] ) )
		{
			if( ereg("=", $this->file[0] ) )
			{
				$tmp = ereg_replace("[^\.A-Za-z0-9=-]", "", $this->file[0] );
				$tmp = explode("=", $tmp);
				$this->properties[strtolower( $tmp[0] )] = $tmp[1];
			}
			$this->file = array_slice( $this->file, 1 );
		}
	}
	
	function processType2()
	{
		$tmp = explode("\t", $this->file[0] );
		for($i=1; $i<count($tmp); $i++)
		{
			$date[$i] = trim( $this->getProperties("date") ) . "-" . sprintf("%02d", ereg_replace( "[^0-9]", "", $tmp[$i] ) );
		}
		
		$this->file = array_slice( $this->file, 1 );
		
		$j=0;
		foreach( $this->file as $reg )
		{
			$tmp = explode("\t", $reg );
			for($i=1; $i<=count($tmp); $i++)
			{
				if( trim($tmp[$i]) != "" )
				{
					$tmpDate = explode( "-", trim( $date[$i] ) );
					$tmpHour = explode( ":", trim( $tmp[0] ) );
					$this->result[$j]['time']  = mktime( $tmpHour[0], $tmpHour[1], $tmpHour[3], $tmpDate[1], $tmpDate[2], $tmpDate[0] );
//					$this->result[$j]['time']  = strtotime( trim( $date[$i] ) . " " . trim( $tmp[0] ) );
					$this->result[$j]['title'] = trim( preg_replace("/ {2,}/", " ", $tmp[$i] ) );
					$j++;
				}
			}
		}
		unset($this->file);
		asort( $this->result );
	}

	function processType3()
	{
		$i=0;
		foreach( $this->file as $reg )
		{
			$tmp = explode("\t", $reg );
			if( ereg("([0-9]{4})-([0-9]{2})-([0-9]{2})", trim( $tmp[0] )) && 
				ereg("([0-9]{2}):([0-9]{2}):([0-9]{2})", trim( $tmp[1] )) &&
				trim( $tmp[2] ) != "" )
			{
				$this->result[$i]['time']  = strtotime( trim( $tmp[0] ) . " " . trim( $tmp[1] ) );
				$this->result[$i]['title'] = trim( preg_replace("/ {2,}/", " ", $tmp[2] ) );
				$i++;
			}
		}
		unset($this->file);
		asort( $this->result );
	}
	
	function next()
	{
		$this->current = current( $this->result );
		$this->next    = next( $this->result );
		if( $this->current === false ) return false;
		else return true;
	}

	function reset()
	{
		reset( $this->result );
	}
}
?>