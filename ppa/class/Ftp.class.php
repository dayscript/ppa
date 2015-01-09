<?
 class Ftp {

	var $ip;
	var $archLocal;
	var $archRemoto;
	var $idCon;
		
	function Ftp($ip, $usr, $pass)
	{
		$this->ip = $ip ? $ip : "" ;
		$this->usr 	= $usr;
		$this->pass	= $pass;
	}

	/*****************
	* repite cinco veces la operacion
	* si ésta no se realiza con éxito
	* (casi) todos los comandos pasan 
	* x aqui.
	******************/
	function justDoIt($funcion)
	{
		$return = false;
		for($i=1; $i<=5; $i++)
		{
			eval('$ok = '. $funcion .';');

			if($ok)
			{
				$return = true;
				break;
			}
			else
			{
				echo "REINTENTANDO......<BR>";
				$this->reconnect();		
			}
		}
		return $return;
	}

	/*****************
	* comandos de conexión
	******************/

	function open()
	{
		$this->idCon 	= ftp_connect($this->ip);
		return( $this->login($this->usr, $this->pass) );
	}

	function reconnect()
	{
		$this->close();
		$this->open();
	}

	function login()
	{
		if($return = ftp_login($this->idCon, $this->usr, $this->pass))
			return $return;
		else
		{
			echo "Nombre o Usuario Incorrecto\n";
			return $return;
		}
	}

	function close()
	{
		ftp_close($this->idCon);	
	}

	/*****************
	* comandos básicos
	******************/
	function get($archRemoto, $archLocal)
	{
//		return ftp_get($this->idCon, $archLocal, $archRemoto , FTP_BINARY);
		return $this->justDoIt('ftp_get($this->idCon, "'. $archLocal .'", "'. $archRemoto .'", FTP_BINARY)');
	}

	function put($archLocal, $archRemoto)
	{
		return $this->justDoIt('ftp_put(' . $this->idCon . ', "'. $archRemoto .'", "'. $archLocal .'", FTP_BINARY)');
	}

	function delete($arch)
	{
		return $this->justDoIt('ftp_delete($this->idCon, "'. $arch .'")');
	}

	function rename($archViejo, $archNuevo)
	{
		return $this->justDoIt('ftp_rename($this->idCon, "'. $archViejo .'", "'. $archNuevo .'")');
	}

	function mkDir( $archivo )
	{
		return ftp_mkdir($this->idCon, $archivo);
	}

	function chDir($dir)
	{
		if($return = ftp_chdir($this->idCon, $dir))
			return $return;
		else
		{
			echo "No se pudo cambiar de directorio\n";
			return $return;
		}
	}

	function ls($path = ".")
	{
		return ftp_nlist($this->idCon, $path);
	}
	
	function size($arch)
	{
		return ftp_size($this->idCon, $arch);
	}	
	
	/*****************
	* comandos de control
	******************/
	function setTimeOut($int)
	{
		return ftp_set_option($this->idCon, FTP_TIMEOUT_SEC, $int);
	}

	function mdtm($arch)
	{
		return ftp_mdtm($this->idCon, $arch);
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