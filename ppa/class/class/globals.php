<?

	/**
	 * definition of global properties
	 * implemented for generator program only
	 *
	 * @author   Nelson Daza
	 * @created  July 08 2003
	 * @modified July 08 2003
	 * @version  1.0
	**/

	/**
	 * location of objects definitions
	**/
		define(CLASS_PATH, "./class/");

	/**
	 * Return a str with n tabs
	**/
		function Tabs ($n = 0)	{
			return str_repeat("\t",$n);
		}

	/**
	 * Return a virtual name (for easy work)
	**/
		function toVirtualName ($txt)	{
			$sol = eregi_replace ('(_)|(-)|(&)',' ',$txt);
			$sol = ucwords($sol);
			$sol[0] = strtolower($sol[0]);
			return eregi_replace (' ','',$sol);
		}

	/**
	 * Return name for function list of a link filed
	**/
		function functionLinkListName ($tableName)	{
			if ($tableName[strlen($tableName) - 1] == y)	{
				$tableName[strlen($tableName) - 1] = "i";
				return $tableName . "esList";
			}
			return $tableName."sList";
		}

	/**
	 * Return text as comment
	**/
			function asComment ($txt, $tabs = 0, $doc = false, $title = false) {
			if ($title)
				$str = Tabs ($tabs) . "/*************************************************";
			else
				$str = Tabs ($tabs) . "/**";
			$ini = "\n" . Tabs ($tabs) . "* ";
			if ($doc)
				$ini .= "@ ";
			if (is_array($txt))
				for ($c = 0; $c < count ($txt); $c++)
					$str .= $ini . $txt[$c];
			else	
				$str .= $ini . $txt;
			if ($title)
				$str .= "\n" . Tabs ($tabs) . "*************************************************/";
			else
				$str .= "\n" . Tabs ($tabs) . "**/";
			$str .= "\n";
			return $str;
		}
?>
