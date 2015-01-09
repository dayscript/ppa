<?
	/**
	 * @Table Definition for Generator only
	 *
	 * @author     Nelson Daza
	 * @created    Julio 12 2003
	 * @modified   Julio 12 2003
	 * @version   1.0
	**/

	require_once("globals.php");
	require_once(CLASS_PATH."field.class.php");

	class Table	{
		/**
		 *	Properties for Table
		**/
		var $name;			//	name of table
		var $description;		//	desc ...
		var $nameShow;		//	name to show
		var $nameTmp;		//	temporary name
		var $fields;			//	array of fields

	 /**********************************************
	 * Constructor
	**********************************************/

		function Table ()	{
			$this->name = "";
			$this->description = "";
			$this->nameShow = "";
			$this->nameTmp = "";
		}

	 /**********************************************
	 * Analizers
	**********************************************/
		/**
		 * Prints HTML representation of this object.
		**/

		function _print(){
			echo "<b>(TABLE)</b><br>";
			echo "<ul>";
			echo "<li><b>Name:</b> '".$this->name."'</li>";
			echo "<li><b>Name to Show:</b> '".$this->nameShow."'</li>";
			echo "<li><b>Temporary Name:</b> '".$this->nameTmp."'</li>";
			echo "<li><b>Fields:</b>";
			for ($c = 0 ; $c < count ($this->fields); $c++)	{
				echo "<ul><li><b>FIELD [$c]:</b></li><ul>";
					$this->fields[$c]->_print();
				echo "</ul></ul>";
			}
			echo "</li></ul>";
		}
		/**
		 * Returns the list of "List Fields" of the table.
		 * Including primary Key
		**/
		function getListKeys()	{
			$Keys = array ();
			for ($c = 0; $c < count($this->fields); $c++)	{
				if ($this->fields[$c]->isListKey())
					$Keys[] = $this->fields[$c]->getName();
			}
			return $Keys;
		}
		/**
		 * Returns the foreign field to the table $tableName
		**/
		function getForeignTo($tableName)	{
			//echo "<b><i>TABLE: $tableName</i></b>";
			for ($c = 0; $c < count($this->fields); $c++)	{
				if ($this->fields[$c]->isForeign())	{
					//echo "<b><i> FIELD:  ". $this->fields[$c]->getName() . " - " .$this->fields[$c]->foreignTable->getName() . "</i></b>";
					if ($this->fields[$c]->foreignTable->getName() == $tableName)
						return $this->fields[$c];
				}
			}
			return false;
		}
		/**
		 * Returns the field
		**/
		function FieldByName($Name)	{
			for ($c = 0; $c < count($this->fields); $c++)	{
				if (strcasecmp($this->fields[$c]->getName(), $Name) == 0)	{
						return $this->fields[$c];
				}
			}
			$field = new Field();
			$field->setName($Name);

			$this->fields[] = $field;
			return $field;
		}
		/**
		 * Returns the primary Key field of the table.
		 * ERROR if isn't almost one
		**/
		function getPrimaryKey()	{
			for ($c = 0; $c < count($this->fields); $c++)	{
				if ($this->fields[$c]->isPrimaryKey())
					return $this->fields[$c];
			}
			return new Field();
		}
		/**
		 * Returns the name of the table.
		**/
		function getName(){
			return $this->name;
		}
		/**
		 * Returns the description of the table.
		**/
		function getDescription(){
			return $this->description;
		}
		/**
		 * Returns the name to show of the table.
		**/
		function getNameShow(){
			return $this->nameShow;
		}
		/**
		 * Returns the temporary name of the table.
		**/
		function getNameTmp(){
			return $this->nameTmp;
		}
		/**
		 * Returns the array of fields.
		**/
		function getFields(){
			return $this->fields;
		}

	 /**********************************************
	 * Modifiers
	**********************************************/

		/**
		 * Sets the name of the table.
		**/
		function setName($aName){
			$this->name = $aName;
			$this->nameShow = ucfirst(toVirtualName($this->name));
			$this->nameTmp = '$a'.$this->nameShow;
		}
		/**
		 * Sets the description of the table.
		**/
		function setDescription($aDescription){
			$this->description = $aDescription;
		}
		/**
		 * Sets the name to show of the table.
		**/
		function setNameShow($aNameShow){
			$this->nameShow = $aNameShow;
		}
		/**
		 * Sets the temporary name of the table.
		**/
		function setNameTmp($aNameTmp){
			$this->nameTmp = $aNameTmp;
		}
		/**
		 * Sets fields of the table.
		**/
		function setFields($aFields){
			$this->fields = $aFields;
		}
		/**
		 * Adds a field to table.
		**/
		/*
		function addField($aField){
			$this->fields[] = $aField;
		}
		*/
		/**
		 * Adds a field to table.
		**/
		function addField ($name, $desc, $type = false, $obj = false, $link = false, $list = false, $primary = false, $foreignTable = false, $default = false)	{
			$field = new Field();
		
			$field->setOwner(&$this);
			$field->setName($name);
			$field->setDescription($desc);
			$field->setType($type);
			$field->setObject($obj);
			$field->setLinkKey($link);
			$field->setListKey($list);
			$field->setPrimaryKey($primary);
			if ($link)
				$field->foreignTable = &$foreignTable;
			else
				$field->setForeignTable(&$foreignTable);
			$field->setDefaultValue($default);
	
			$this->fields[] = $field;
		}

	 /**********************************************
	 * Text Functions for Generator
	**********************************************/

		/**
		 * Returns text for definition of table.
		**/
		function strDefinition ($tabs = 0)	{
		//	SHOULD RETURN A COMPLETE ARCHIVE ????????????
			$str = Tabs($tabs++)."class ".$this->nameShow."\t{\n";

			$comment = "Object var definitions.";
			$str .= asComment($comment,$tabs, true);
			for ($c = 0; $c < count ($this->fields); $c++)	{
					$str .= $this->fields[$c]->strDefinition($tabs);
			}
			$str .= "\n\n";

		//	ADDING STRING FOR CONSTRUCTOR
			$comment = "Constructor(s) of object ";
			if ($this->description)
				$comment .= $this->description;
			else
				$comment .= $this->nameShow;
			$str .= asComment($comment,$tabs - 1,true, true);
			//	CONSTRUCTOR FUNCTION
			$str .= $this->strConstructor($tabs);

		//	ADDING STRING FOR ANALIZERS
			$comment = "Analizer(s) of object ";
			if ($this->description)
				$comment .= $this->description;
			else
				$comment .= $this->nameShow;
			$str .= asComment($comment,$tabs - 1,true, true);
			//	PRINT FUNCTION
			$str .= $this->strPrint($tabs);
			//	GETS FUNCTIONS
			$str .= $this->strGets($tabs);

		//	ADDING STRING FOR MODIFIERS
			$comment = "Modifier(s) of object ";
			if ($this->description)
				$comment .= $this->description;
			else
				$comment .= $this->nameShow;
			$str .= asComment($comment,$tabs - 1,true, true);
			//	SETS FUNCTIONS
			$str .= $this->strSets($tabs);

		//	ADDING STRING FOR PERSISTANCE
			$comment = "Persistence of object ";
			if ($this->description)
				$comment .= $this->description;
			else
				$comment .= $this->nameShow;
			$str .= asComment($comment,$tabs - 1,true, true);
			//	COMMIT FUNCTION
			$str .= $this->strCommit($tabs);
			//	ERASE FUNCTION
			$str .= $this->strErase($tabs);
			//	LOAD FUNCTION
			$str .= $this->strLoad($tabs);
			$str .= Tabs($tabs)."}\n";

		//	ADDING STRING FOR ARRAYS OF CHILDS TABLES
			$comment = "Child tables list for ";
			if ($this->description)
				$comment .= $this->description;
			else
				$comment .= $this->nameShow;
			$str .= asComment($comment,$tabs - 1,true, true);
			$str .= $this->strListFunctions($tabs);

			return $str;
		}
		/**
		 * Returns text for constructor of table.
		**/
		function strConstructor ($tabs = 2)	{
			$comment = "Creates an empty ";
			if ($this->description)
				$comment .= $this->description;
			else
				$comment .= $this->nameShow;
			$comment .= " object or filled with values. ";

			$str = asComment($comment,$tabs, true);

			$str .= Tabs($tabs)."function $this->nameShow (";
			for ($c = 0; $c < count($this->fields); $c++)
				$str .= " ".$this->fields[$c]->getNameTmp()." = false,";

			$str[strlen($str) - 1] = ' ';
			$str .= ")\t{\n";
			for ($c = 0; $c < count($this->fields); $c++)
				$str .= $this->fields[$c]->strConstructor($tabs + 1);
			$str .= Tabs($tabs)."}\n\n";

			return $str;
		}
		/**
		 * Returns HTML representation of table.
		**/
		function strPrint($tabs = 1)	{

			$comment = "Returns HTML representation of object (DEBUG ONLY)";
			$str = asComment($comment,$tabs, true);

			$str .= Tabs($tabs)."function _print()\t{\n";
			$str .= Tabs($tabs + 1).'echo "<br><b>('.$this->nameShow.')</b><br>";'."\n";
			$str .= Tabs($tabs + 1).'echo "<ul>";'."\n";
			for ($c = 0; $c < count($this->fields); $c++)
				$str .= $this->fields[$c]->strPrint($tabs + 1);
			$str .= Tabs($tabs + 1).'echo "</ul>";'."\n";
			$str .= Tabs($tabs)."}\n\n";

			return $str;
		}
		/**
		 * Returns PHP getters fileds of table.
		**/
		function strGets ($tabs = 1)	{
			$str = "";
			for ($c = 0; $c < count($this->fields); $c++) {
				if ($this->description)
					$str .= $this->fields[$c]->strGet($this->description, $tabs);
				else
					$str .= $this->fields[$c]->strGet($this->nameShow, $tabs);
			}
			return $str;
		}
		/**
		 * Returns PHP setter of table.
		**/
		function strSets($tabs = 1)	{
			$str = "";
			for ($c = 0; $c < count($this->fields); $c++) {
				if ($this->description)
					$str .= $this->fields[$c]->strSet($this->description, $tabs);
				else
					$str .= $this->fields[$c]->strSet($this->nameShow, $tabs);
			}
			return $str;
		}
		/**
		 * Returns a string for PHP commit assign of table.
		**/
		function strCommit ($tabs = 0)	{

			$comment[0] = "Updates or Inserts ";
			if ($this->description)
				$comment[0] .= $this->description;
			else
				$comment[0] .= $this->nameShow;
			$comment[0] .= " information depending";
			$comment[1] = "upon existence of valid primary key.";

			$str = asComment($comment,$tabs, true);

			$pk = $this->getPrimaryKey();

			$str .= Tabs ($tabs++) . "function commit ()\t{\n";
			
			$str .= Tabs ($tabs++) . 'if ($this->' . $pk->getNameShow() . ")\t{\n";
			// GENERATING A QUERY
			$str .= Tabs ($tabs) . '$sql  = " UPDATE ' . $this->name.' SET';
			for ($c = 0; $c < count ($this->fields); $c++)	{
				//	ALL FIELDS BUT PRIMARY KEY
				if (!$this->fields[$c]->isPrimaryKey())	{
					$str .= '"' . ";\n" . Tabs($tabs) . '$sql .="' . $this->fields[$c]->strCommitAssign(0) . ',';
				}
			}
			$str[strlen($str) - 1] = '"';
			$str .= ";\n";
			$str .= Tabs ($tabs) . '$sql .= " WHERE ' . $pk->strCommitAssign(0) . '"' . ";\n";
			$str .= Tabs ($tabs) . 'db_query($sql);' . "\n\n";

			/*	delete from foreings */
			for ($c = 0; $c < count ($this->fields); $c++)	{
				if ($this->fields[$c]->isForeign())	{
					$str .= Tabs($tabs).'$sql .= "DELETE FROM '.$this->fields[$c]->foreignTable->getName();
					$fpk = $this->fields[$c]->foreignTable->getPrimaryKey();
					$str .= ' WHERE ' . $fpk->getName();
					$str .= ' = " . $this->' . $pk->getName() . ";\n";
					$str .= Tabs($tabs) . 'db_query($sql)' . ";\n";
				}
			}
			$str .= Tabs ($tabs-1)."}\telse\t{\n";
			$str .= Tabs($tabs).'$sql = " INSERT INTO '.$this->name.' ( ';

			$tmp = "";
			for ($c = 0; $c < count ($this->fields); $c++)	{
				if (!$this->fields[$c]->isPrimaryKey() && !$this->fields[$c]->isLinkKey())	{
					$str .= " ".$this->fields[$c]->getName().",";
					$tmp .= $this->fields[$c]->strcommitAsk(0) . ",";
				}
			}
			$str[strlen($str) - 1] = ' ';
			$str .= ") VALUES (";
			$str .= $tmp;
			$str[strlen($str) - 1] = ' ';
			$str .= ')";' . "\n";
			$str .= Tabs ($tabs) . 'db_query($sql);' . "\n";
			$str .= Tabs ($tabs) . '$this->' . $pk->getNameShow() . ' = db_id_insert();' . "\n";
			$str .= Tabs (--$tabs)."}\n\n";

			return $str;
		}
		/**
		 * Returns a string for PHP erase of table
		**/
		function strErase ($tabs = 0)	{
			$comment = "Deletes ";
			if ($this->description)
				$comment .= $this->description;
			else
				$comment .= $this->nameShow;
			$comment .= " object from database.";

			$str = asComment($comment, $tabs, true);

			$pk = $this->getPrimaryKey();

			$str .= Tabs ($tabs) . "function erase ()\t{\n";
			$str .= Tabs (++$tabs) . '$sql = "DELETE FROM ' . $this->name . '";' . "\n";
			$str .= Tabs ($tabs) . '$sql .= " WHERE ' . $pk->getName() . ' = " .' . $pk->strCommitAsk(0) . ";\n";
			$str .= Tabs ($tabs) . 'db_query($sql);' . "\n";

			/*for ($c = 0; $c < count ($this->fields); $c++)	{
				if ($this->fields[$c]->isLinkKey())	{
					$str .= Tabs($tabs) . '$list = $this->' . $this->fields[$c]->getNameShow() . '->' . $this->fields[$c]->getLinkListFunction() . ";\n";;
					$pk = $this->fields[$c]->foreignTable->getPrimaryKey();
					$str .= Tabs ($tabs ++) . 'for ($c = 0; $c < count($list); $c ++)' . "\t{\n";
					$str .= Tabs ($tabs) . '$obj = new ' . $this->fields[$c]->foreignTable->getNameShow() . '($list[$c]' . "['" . $pk->getName() . "']);\n";
					$str .= Tabs ($tabs) . '$obj->erase()' . ";\n";
					$str .= Tabs (--$tabs) . "}\n";
				}
			}*/
			$str .= Tabs (--$tabs)."}\n\n";

			return $str;
		}

		/**
		 * Returns a string for PHP load of table
		**/
		function strLoad ($tabs = 0)	{
			$comment[0] = "Loads ";
			if ($this->description)
				$comment[0] .= $this->description;
			else
				$comment[0] .= $this->nameShow;
			$comment[0] .= " attributes from the date base";
			$comment[1] = "and assigns them to ";
			if ($this->description)
				$comment[1] .= $this->description;
			else
				$comment[1] .= $this->nameShow;
			$comment[1] .= "'s attributes.";


			$str = asComment($comment, $tabs, true);

			$pk = $this->getPrimaryKey();

			$str .= Tabs ($tabs++)."function load (".$pk->getNameTmp().")\t{\n";
			$str .= Tabs ($tabs).'$sql = "SELECT * from '.$this->name.'";'."\n";
			$str .= Tabs ($tabs).'$sql .= " WHERE '.$pk->getName().' = " .'.$pk->getNameShow().";\n";
			$str .= Tabs ($tabs).'$results = db_query($sql);'."\n";
			$str .= Tabs ($tabs).'$row = db_fetch_array($results);'."\n\n";

			for ($c = 0; $c < count ($this->fields); $c++)	{
				if (!$this->fields[$c]->linkKey)	{
					$str .= Tabs($tabs) . '$this->' . $this->fields[$c]->getNameShow() . ' = $row' . "['" . $this->fields[$c]->getName(). "'];\n";
				}
			}
			$str .= Tabs (--$tabs)."}\n";

			return $str;
		}

		/**
		 * Returns the string for a(some) function(s) that
		 * returns an array with primary key 
		 * and other child table fields
		**/
		function strListFunctions ($tabs)	{
			$str = "";
			for ($c = 0; $c < count ($this->fields); $c++)	{
				if ($this->fields[$c]->linkKey)	{
					echo "<br>" . $this->fields[$c]->name . "<br>";
					$comment = array();
					$comment[0] = "Retruns array for ";
					if ($this->description)
						$comment[0] .= $this->description;
					else
						$comment[0] .= $this->nameShow;
					$comment[0] .= " with fields like: ";
		
					$comment[1] = "[";
		
					$flds = "";
					for ($f = 0; $f < count ($this->fields[$c]->foreignTable->fields); $f++)	{
						if ($this->fields[$c]->foreignTable->fields[$f]->listKey)
							$flds .= " " . $this->fields[$c]->foreignTable->fields[$f]->getName() . ",";
					}
					$flds[strlen($flds) - 1] = " ";
					$comment [1].= $flds. " ]";
					$str .= asComment($comment, $tabs, true);
					$str .= Tabs ($tabs++)."function " . functionLinkListName($this->fields[$c]->foreignTable->getName()) . "()\t{\n";
		
					$str .= Tabs($tabs) . '$row = new array();' . "\n";
					$fld = $this->fields[$c]->foreignTable->getForeignTo($this->name);
					if (!$fld)	{
						$str .= Tabs($tabs) . "// There is not a foreign field in " . $this->fields[$c]->foreignTable . " to this table \n";
					} else	{
						$pk = $this->getPrimaryKey();
						$str .= Tabs ($tabs) . '$sql = "SELECT ' . $flds . 'FROM ' . $this->fields[$c]->foreignTable->getName() . '";' . "\n";
						$str .= Tabs ($tabs) . '$sql .= " WHERE ' . $fld->getName() . ' = " . $this->'.$pk->getNameShow().";\n";
						$str .= Tabs ($tabs).'$results = db_query($sql);'."\n";
						$str .= Tabs ($tabs).'$row = db_fetch_array($results);'."\n\n";
					}
					$str .= Tabs ($tabs).'return $row' . ";\n";
		
					echo "<br>" . $str . "<br>";
					$str .= Tabs (--$tabs)."}\n\n";
				}
			}	
			return $str;
		}
	}
?>
