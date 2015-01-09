
<?

	require_once("class/globals.php");
	require_once(CLASS_PATH."table.class.php");
	require_once(CLASS_PATH."file.class.php");

	function CreateFile ($tbl)	{
		$F = new File("CreatedClass/" . ucwords( $tbl->getName() ) . ".class.php");
		$comment = array();
		if ($F->Open())	{
			$F->Truncate();
			$comment[] = $tbl->getName() . " Object definition";
			$comment[] = "author:\t\tGerman Afanador";
			$comment[] = "created:\t\t" . date("F - d - Y - g:i:s");
			$comment[] = "modified:\t" . date("F - d - Y - g:i:s");
			$comment[] = "version:\t1.0";
			
			$str = "\n<?\n\n";
			$str .= Tabs(1) . "require_once('globals.com');\n";
			$fields = $tbl->getFields();
			if ($fields !== false)	{
				for ($c = 0; $c < count($fields); $c ++)	{
					if ($fields[$c]->isForeign())
						$str .= Tabs(1) . "require_once('" . $fields[$c]->foreignTable->getName() . ".class.php');\n";					
				}			
			}

			$str .= "\n\n";
			$str .= asComment($comment, 1, true, true) . "\n\n";
			$str .= $tbl->strDefinition(1);
			$str .= "\n?>\n";
			
			$F->Write($str);
			$F->Close();
		} else	{
			echo "<br> ERROR: al abrir :". "CreatedClass/" . $tbl->getName() . ".class.php";
		}
	}

	$tipo= new Table();

//	$pagina = new Table();

	 /***********************************
	 *	
	***********************************/
	//	function addField ($name, $desc, $type = false, $obj = false, $link = false, $list = false, $primary = false, $foreignTable = false, $default = false)	{
	$tipo->setName ("Chapter");
	$tipo->setDescription ("CLASS CHAPTER");

	$tipo->addField("stereo", "Stereo" , "bool");
	$tipo->addField("surround", "Surround" , "bool");
	$tipo->addField("sap", "SAP" , "bool");
	$tipo->addField("closeCaption", "Close Captioned" , "bool");
	$tipo->addField("animated", "Animated" , "bool");
	$tipo->addField("blackAndWhite", "Black & White" , "bool");
	$tipo->addField("repetition", "Repetition" , "bool");
	$tipo->addField("onLive", "On Live" , "bool");
	$tipo->addField("nudity", "Nude Content" , "bool");
	$tipo->addField("ofensiveLanguage", "Ofensive Language" , "bool");
	$tipo->addField("violence", "Violence" , "bool");
	$tipo->addField("adultContent", "Adult Content" , "bool");
	
	
/*
	$pagina->setName ("pagina");
	$pagina->setDescription ("CLASS PAGINA");

	$pagina->addField("id", "LLAVE PRIMARIA" , "int" , false, false, true, true);
	$pagina->addField("preview", "" , "string");
	$pagina->addField("fecha_aprobacion", "FECHA AP" , "string");
	$pagina->addField("comentario", "" , "string");
	$pagina->addField("revista", "FORANEA - RE..." , "int", false, false, false, false, &$revista);
	
	CreateFile($pagina);
*/
	CreateFile($tipo);
	
	

?>