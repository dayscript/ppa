<?
Class Properties
{
	var $props;
	
	function Properties( $file_name )
	{
		$this->props = array();
		
		@$file = file( $file_name );
		if( empty( $file ) ) die ( "Properties File Empty or not present\n\n" );
		$i = 0;
		foreach( $file as $ln )
		{
			$i++;
			if ( !ereg("^#", trim ($ln) ) && trim($ln) != "" )
			{
				$ln_arr = explode( "=", $ln );
				if ( count( $ln_arr ) != 0 )
				{
					if( count( $ln_arr ) != 2 ) die( "Sintaxis error in properties File on line " . $i . "\n\n" );
					$this->props[trim( $ln_arr[0] )] = trim( $ln_arr[1] );
				}
			}
		}
	}
	
	function getProperty( $name )
	{
		return $this->props[$name];
	}
}
?>