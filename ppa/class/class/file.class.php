<?

	require_once("globals.php");

	class File	{
		var $name;		//	File name
		var $mode;		//	open mode
		var $handle;	//	File pointer
		var $open;		//	Is open (true/false)

		function File ($aName = false, $aMode = "a+")	{
			if ($aName)
				$this->name = $aName;
			if ($aMode)
				$this->mode = $aMode;
		}

		function Set ($aName, $aMode = "a+")	{
			if ($this->open)
				$this->Close();
			$this->name = $aName;
			$this->mode = $aMode;
		}

		function Open ()	{
			if ($this->open)
				$this->Close();
			$h = fopen($this->name,$this->mode);
			if ($h !== false)	{
				$this->open = true;
				$this->handle = $h;
			} else	{
				$this->open = false;
			}
			return $this->open;
		}

		function Close ()	{
			if ($this->open)	{
				$this->open = !fclose($this->handle);
			}
			return $this->open;
		}

		function Write ($what)	{
			if (!$this->open)
				$this->Open();
			$tam = strlen($what);
			return ($tam == fwrite($this->handle,$what));
		}

		function Read ($howM)	{
			if (!$this->open)
				$this->Open();
			return fread($this->handle, $howM);
		}

		function ReadLine ($howM = false)	{
			if (!$this->open)
				$this->Open();
			$str = "";
			if ($howM)
				$str = fgets($this->handle, $howM);
			else	{
				$str = fgets($this->handle);
				$str = substr($str,0,strlen($str) - 1);
			}
			return $str;
		}

		function Exist ($aName = false)  {
			if (!$aName)
				$aName = $this->name;
			return file_exists ($this->name);
		}

		function Truncate ()  {
			if (!$this->open)
				$this->Open();
			return ftruncate($this->handle,0);
		}

		function Size ($aName = false)  {
			if (!$aName)
				$aName = $this->name;
			return filesize($aName);
		}

		function AsString ()	{
			if (!$this->open)
				$this->Open();
			return $this->Read($this->Size());
		}

		function End ()	{
			return $this->Pos($this->Size());
		}

		function Begin ()	{
			return $this->Pos(0);
		}

		function Pos ($where)	{
			if (!$this->open)
				$this->Open();
			if ($where < 0 || $where > $this->Size())
				return false;
			else
				return (fseek($this->handle,$where) == 0);
		}

		function Eof ()	{
			return feof($this->handle);
		}

		function getName ()	{
			return $this->name;
		}
	}
?>
