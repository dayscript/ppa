<?
class Log
{
	public static function write($path, $data)
	{
		$fp = fopen($path, "a");
		$retries = 0;
		$max_retries = 100;
		
		if (!$fp){return false; Log::writeData("tmp/logerr.log","no se pudo abrir el archivo");}
		
		do 
		{
			if ($retries > 0){ usleep(rand(1, 10000)); }
			$retries += 1;
		} while (!flock($fp, LOCK_EX) and $retries <= $max_retries);
		
		if ($retries == $max_retries) { return false;Log::write("tmp/logerr.log","no se pudo abrir el archivo");}
		
		fwrite($fp, date("Y-m-d:H:i ") . $data ."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
		return true;
	}
}
?>