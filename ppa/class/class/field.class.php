<?
	/**
	 *  @Field Definition for Generator only
	 *
	 * @author     Nelson Daza
	 * @created    Julio 11 2003
	 * @modified   Julio 11 2003
	 * @version   1.0
	**/

	require_once("globals.php");
	
	/**
	 *	Class to manipulate a database field
	**/

	class Field	{
		/**
		 *	Properties for Field
		**/
		var $owner;			//	pointer to owner table (Table)
		var $name;			//	name of this field
		var $description;		//	description of this field
		var $nameShow;		//	name to show
		var $nameTmp;		//	temporary name
		var $type;			//	type of field {str}
		var $object;		//	is object ? (true / false)
		var $defaultVal;		//	default value? (false = NONE / other = other)}

		var $foreign;			//	? (true/false)
		var $foreignTable;		//	link table (table object)

		var $primaryKey;		//	The field is primary Key? (true / false)
		var $linkKey;			//	The field will be used as link to child tables? (true / false)
		var $listKey;			//	The field will be used in a list of fileds? (true / false)

	 /**********************************************
	 * Constructor
	**********************************************/

		function Field ()	{
			$this->name = "";
			$this->description = "";
			$this->nameShow = "";
			$this->nameTmp = "";
			$this->type = "";
			$this->object = false;
			$this->defaultVal = false;
			
			$this->foreign = false;
			$this->foreignTable = new Table();

			$this->primaryKey = false;
			$this->linkKey = false;
			$this->listKey = false;
			$this->owner = false;
		}

	 /**********************************************
	 * Analizers
	**********************************************/
		/**
		 * Prints HTML representation of this object.
		**/

		function _print()	{
			echo "<b>(FIELD)</b><br>";
			echo "<ul>";
			if ($this->primaryKey)
				echo "<li><b><i>  PRIMARY KEY  </i></b></li>";
			if ($this->listKey)
				echo "<li><i>  LIST KEY  </i></li>";
			if ($this->linkKey)
				echo "<li><i>  LINK KEY  TO " . strtoupper($this->foreignTable->getName()) . "</i></li>";
			if ($this->foreign)
				echo "<li><i>  FOREIGN KEY  TO " . strtoupper($this->foreignTable->getName()) . "</i></li>";
			if ($this->object)
				echo "<li><i>  OBJECT DATA </i></li>";
			echo "<li><b>Name:</b> '".$this->name."'</li>";
			echo "<li><b>Name to Show:</b> '".$this->nameShow."'</li>";
			echo "<li><b>Temporary Name:</b> '".$this->nameTmp."'</li>";
			echo "<li><b>Type:</b> '".$this->type."'</li>";
			echo "<li><b>Default Value:</b> '".$this->defaultVal."'</li>";
			echo "</ul>";
		}
		/**
		 * Returns the name of the field.
		**/
		function getName(){
			return $this->name;
		}
		/**
		 * Returns the description of the field.
		**/
		function getDescription(){
			return $this->description;
		}
		/**
		 * Returns the name to show of the field.
		**/
		function getNameShow(){
			return $this->nameShow;
		}
		/**
		 * Returns the owner table of the field.
		**/
		function getOwner(){
			return $this->owner;
		}
		/**
		 * Returns the temporary name of the field.
		**/
		function getNameTmp(){
			return $this->nameTmp;
		}
		/**
		 * Returns the type of the field.
		**/
		function getType(){
			return $this->type;
		}
		/**
		 * Returns the default value of the field.
		**/
		function getDefaultValue(){
			return $this->defaultVal;
		}
		/**
		 * Returns if is foreign.
		**/
		function isForeign(){
			return $this->foreign;
		}
		/**
		 * Returns if is object.
		**/
		function isObject(){
			return $this->object;
		}
		/**
		 * Returns if is foreign table.
		**/
		function getForeignTable(){
			return $this->foreignTable;
		}
		/**
		 * Returns if it's part of a list.
		**/
		function isListKey(){
			return $this->listKey;
		}
		/**
		 * Returns if it's alink to a child table
		**/
		function isLinkKey(){
			return $this->linkKey;
		}
		/**
		 * Returns the name of function that returns a list.
		**/
		function getLinkListFunction(){
			if ($this->linkKey)
				return functionLinkListName($this->foreignTable->getName());
			return false;
		}
		/**
		 * Returns if is primary Key.
		**/
		function isPrimaryKey(){
			return $this->primaryKey;
		}

	 /**********************************************
	 * Modifiers
	**********************************************/

		/**
		 * Sets the owner table of the field.
		**/
		function setOwner(&$aOwner){
			$this->owner = &$aOwner;
		}
		/**
		 * Sets the name of the field.
		**/
		function setName($aName){
			$this->name = $aName;
			$this->nameShow = toVirtualName($this->name);
			$this->nameTmp = '$a' . ucfirst($this->nameShow);
		}
		/**
		 * Sets the description of the field.
		**/
		function setDescription($aDescription){
			$this->description = $aDescription;
		}
		/**
		 * Sets the name to show of the field.
		**/
		function setNameShow($aNameShow){
			$this->nameShow = $aNameShow;
		}
		/**
		 * Sets the temporary name of the field.
		**/
		function setNameTmp($aNameTmp){
			$this->nameTmp = $aNameTmp;
		}
		/**
		 * Sets the type of the field.
		**/
		function setType($aType){
			$this->type = $aType;
		}
		/**
		 * Sets the default value of the field.
		**/
		function setDefaultValue($aDefault){
			$this->defaultVal = $aDefault;
		}
		/**
		 * Sets if is foreign.
		**/
		function setForeign($aForeign){
			$this->foreign = $aForeign;
			if ($this->foreign)	{
				$this->object = false;
				$this->linkKey = false;
			}
		}
		/**
		 * Sets if is an object.
		**/
		function setObject($aObject){
			$this->object = $aObject;
			if ($this->object)	{
				$this->foreign = false;
				$this->listKey = false;
				$this->linkKey = false;
			}
		}
		/**
		 * Sets foreign table.
		**/
		function setForeignTable(&$aForeignTbl){
			$this->setForeign($aForeignTbl !== false);
			if ($this->foreign)	{
//				function addField ($name, $desc, $type = false, $obj = false, $link = false, $list = false, $primary = false, $foreignTable = false, $default = false)	{
				$aForeignTbl->addField($this->name, "LINK TO CHILD TABLE " . $this->owner->getName(), $this->owner->getNameShow(), true,  true, false, false, &$this->owner, false);
				$this->foreignTable = &$aForeignTbl;
			}
		}
		/**
		 * Sets if is primary Key.
		**/
		function setPrimaryKey($aPrimaryKey){
			$this->primaryKey = $aPrimaryKey;
			if ($aPrimaryKey)	{
				$this->linkKey = false;
				$this->listKey = true;
				$this->object = false;
			}
		}
		/**
		 * Sets if it's part of a list.
		**/
		function setListKey($aListKey){
			$this->listKey = $aListKey;
			if ($this->listKey)	{
				$this->linkKey = false;
				$this->object = false;
			}
		}
		/**
		 * Sets if it's link to a table.
		**/
		function setLinkKey($aLinkKey){
			$this->linkKey = $aLinkKey;
			if ($aLinkKey)	{
				$this->listKey = false;
				$this->foreign = false;
			}
		}
		
	  /**********************************************
	 * Text Functions for Generator
	**********************************************/

		/**
		 * Returns text for definition of Field.
		**/
		function strDefinition ($tabs = 0)	{
			$str = Tabs($tabs).'var $'.$this->nameShow.';';
			$str .= Tabs(2).'//'.Tabs(1).$this->description;

			if ($this->foreign)
				$str .= " {Object of type " . $this->type . " }";
			else if ($this->foreign)
			 	$str .= " {Foreign Key to " . $this->foreignTable->getName() . " }  {$this->type};";
			else if ($this->linkKey)
				$str .= " {Link Key as an Object {" . $this->type . "} to " . $this->foreignTable->getName() . " table }";
			$str .= "  {$this->type}";
			$str .= "\n";
			return $str;
		}
		/**
		 * Returns text (evaluation an assign) for construction of Field.
		**/
		function strConstructor ($tabs = 0)	{
		
			$str = Tabs($tabs)."if (".$this->nameTmp.")\n";
			if ($this->primaryKey)
				$str .=  Tabs($tabs + 1) . '$this->load(' . $this->nameTmp.");\n";
			else
				$str .= Tabs($tabs + 1).'$this->'.$this->nameShow." = ".$this->nameTmp.";\n";
			
			if ($this->linkKey)	{
				$str .= Tabs($tabs)."else\n";
				$str .= Tabs($tabs + 1) . '$this->' . $this->nameShow . ' = new ' . $this->foreignTable->getNameShow() . '(' . $this->defaultVal . ");\n";
			}
			else if ($this->object)	{
				$str .= Tabs($tabs)."else\n";
				$str .= Tabs($tabs + 1) . '$this->' . $this->nameShow . ' = new ' . $this->type . '(' . $this->defaultVal . ");\n";
			}
			else if ($this->defaultVal)	{
				$str .= Tabs($tabs)."else\n";
				$str .= Tabs($tabs + 1).'$this->'.$this->nameShow." = ".$this->defaultVal.";\n";
			}
			$str .= "\n";
			return $str;
		}
		/**
		 * Returns HTML representation of Field.
		**/
		function strPrint($tabs = 0)	{
			$str = Tabs($tabs);

			$str .= 'echo "<li><b>';
			if ($this->description)	// has description
				$str .= $this->description;
			else
				$str .= $this->nameShow;
			
			$str .= ': </b> $this->'.$this->nameShow;

			if ($this->object)	{
				$str .= "\n" . Tabs($tabs + 1) . "<lu>";
				$str .= '$this->' . $this->nameShow . '->_print();';
				$str .= "\n" . Tabs($tabs + 1) . "</lu>";
			}
			$str .= ' </li>";'."\n";
			
			return $str;
		}
		/**
		 * Returns PHP getter of Field.
		**/
		function strGet($table = "", $tabs = 1)	{
			$comment = "Returns ";
			if ($this->description)
				$comment .= $this->description;
			else
				$comment .= $this->nameShow;
			$comment .= " of $table";

			$str = asComment($comment,$tabs);
			$str .= Tabs($tabs)."function get".ucwords( $this->nameShow )."()\t{\n";
			$str .= Tabs($tabs + 1).'return $this->'."$this->nameShow;\n";
			$str .= Tabs($tabs)."}\n";

			return $str;
		}
		/**
		 * Returns PHP setter of Field.
		**/
		function strSet($table = "object", $tabs = 1)	{
			$comment = "Sets ";
			if ($this->description)
				$comment .= $this->description;
			else
				$comment .= $this->nameShow;
			$comment .= " of $table";

			$str = asComment($comment,$tabs);
			$str .= Tabs($tabs)."function set".ucwords( $this->nameShow )."($this->nameTmp)\t{\n";
			$str .= Tabs($tabs + 1).'$this->'."$this->nameShow = $this->nameTmp;\n";
			$str .= Tabs($tabs)."}\n";
			return $str;
		}
		/**
		 * Returns a string for PHP commit assign of Field.
		**/
		function strCommitAssign ($tabs = 1)	{
			$str = Tabs($tabs)." $this->name =";
			$str .= $this->strCommitAsk(0);
			return $str;
		}
		/**
		 * Returns a string for PHP commit ask of Field.
		**/
		function strCommitAsk ($tabs = 1)	{
			$str = Tabs($tabs);
			switch ($this->type)	{
				case "int":
					$str .= ' $this->'."$this->nameShow";
					break;
				case "string":
					$str .= " '".'$this->'."$this->nameShow'";
					break;
				default :
					$str .= " '".'$this->'."$this->nameShow'";
			}
			return $str;
		}
	}
?>
