<?
class WriteFile
{
	var $hd;
	static $LANG = "es";
	
	function WriteFile($name, $mode = "w+")
	{
		$this->hd = fopen(OUT_PATH . $name, $mode);
	}
	
	function write( $str, $len = "", $sep = "", $delimiter = "\"" )
	{
		$str = ereg_replace( "\"([^\"]*)\"", "``\\1''", $str);
		$str = ereg_replace( "\"", "", $str);
		$str = ereg_replace( "\n", "", $str);
		$str = ereg_replace( "\r", "", $str);
		$str = ereg_replace( "\t", "", $str);
		if(WriteFile::$LANG == "es")
		{
			$str = ereg_replace("[^".chr(32) . "-" . chr(125) ."ביםףתסÑÁÉÍÓÚüÜ]", "", $str);	
		}
		else
		{
			$str = str_replace( "$", "", $str);
			$str = strtr($str, 	"ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝ‗אבגדהוזחטיךכלםמןנסעףפץצרשתûü‎ÿ",
								"AAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
			$str = ereg_replace("[^".chr(32) . "-" . chr(125) ."]", "", $str);
		}
		
		if($len == "") 
			fwrite( $this->hd, $delimiter .$str . $delimiter . $sep );
		else
			fwrite( $this->hd, $delimiter . substr( $str, 0, $len) . $delimiter . $sep );
	}

	function writeLn( )
	{
		fwrite( $this->hd, chr(13) . chr(10));
	}
	
	function close( )
	{
		fclose( $this->hd );
	}
}

?>