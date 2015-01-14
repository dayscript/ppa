<?
 class Ftp {

	var $ip;
	var $archLocal;
	var $archRemoto;
	var $idCon;
		
	function Ftp($ip)
	{
		$this->ip = $ip ? $ip : "" ;
		$this->idCon = ftp_connect($ip);
	}

	function login($usr, $pass)
	{
		if($return = @ftp_login($this->idCon, $usr, $pass))
			return $return;
		else
		{
			echo "Nombre o Usuario Incorrecto\n";
			return $return;
		}
	}
	
	function get($archLocal, $archRemoto)
	{
		return @ftp_get($this->idCon, $archLocal, $archRemoto , FTP_BINARY);
	}

	function put($archLocal, $archRemoto)
	{
		return @ftp_put($this->idCon, $archLocal, $archRemoto , FTP_BINARY);
	}
	
	function chDir($dir)
	{
		if($return = @ftp_chdir($this->idCon, $dir))
			return $return;
		else
		{
			echo "No se pudo cambiar de directorio\n";
			return $return;
		}
	}

	function ls()
	{
		return @ftp_nlist($this->idCon, ".");
	}
	
	function setTimeOut($int)
	{
		return ftp_set_option($this->idCon, FTP_TIMEOUT_SEC, $int);
	}

	function mdtm($arch)
	{
		return @ftp_mdtm($this->idCon, $arch);
	}

	function delete($arch)
	{
		return @ftp_delete($this->idCon, $arch);	
	}

	function close()
	{
		ftp_close($this->idCon);	
	}

	function setIp($ip)
	{
		$this->ip = $ip; 
	}

	function setPasv()
	{
		return ftp_pasv($this->idCon, true);
	}	
}	

?>