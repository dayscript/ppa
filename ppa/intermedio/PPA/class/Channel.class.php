<?
require_once($path . "class/Slot.class.php");
/*************************************************
* @ Channel Class definition
* @ author:		Juan Carlos Orrego
* @ created:	August - 21 - 2003 - 12:19:05
* @ modified:	August - 21 - 2003 - 12:19:05
* @ version:	1.0
*************************************************/

class Channel {
	/**
	* @ Object var definitions.
	*/
	var $id;			//	PRIMARY KEY  int
	var $name;			//	Channel descriptive name
	var $shortName;		//	Channel short name
	var $logo;			//	Image Path
	var $description;	//	Channel description
	var $slots;			//	ARRAY of Slots
	var $PPA;			//	PPA relation id

	/*************************************************
	* @ Constructor(s) 
	*************************************************/
		/**
		* @ Creates an empty Channel Object or filled with values. 
		**/
		function Channel ( $aId = false, $aName = false, $aShortName = false, $aLogo = false, $aDescription= false , $aSlots = false, $aPPA = false ) {
			if ($aId)$this->load($aId);
		    if ($aName)$this->name= $aName;
		    if ($aShortName)$this->shortName= $aShortName;
		    if ($aLogo)$this->logo= $aLogo;
		    if ($aDescription)$this->description= $aDescription;
		    if ($aSlots)$this->slots= $aSlots;
			else if(!isset($this->slots)) $this->slots= array();
		    if ($aPPA)$this->PPA= $aPPA;
		}

	/*************************************************
	* @ Analizer(s) of object CLASS Channel
	*************************************************/
		/**
		* @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print() {
			echo "<br><b>(Channel)</b><br>";
			echo "<ul>";
			echo "<li><b>id: </b> $this->id </li>";
			echo "<li><b>name: </b> $this->name </li>";
			echo "<li><b>shortName: </b> $this->shortName </li>";
			echo "<li><b>logo: </b> $this->logo </li>";
			echo "<li><b>description: </b> $this->description </li>";
			echo "<li><b>PPA: </b> $this->PPA </li>";
			echo "<li><b>slots[]: </b> ";
			for( $i = 0; $i < count( $this->slots ); $i++ ){
			   $slot = $this->slots[$i];
			   $slot->_print();
			}
			echo "</li>";
			echo "</ul>";
		}

		/**
		* Returns id of CLASS Channel
		**/
		function getId() {
			return $this->id;
		}

		/**
		* Returns name attribute
		**/
		function getName() {
			return $this->name;
		}

		/**
		* Returns Short name attribute
		**/
		function getShortName() {
			return $this->shortName;
		}
		/**
		* Returns logo attribute
		**/
		function getLogo() {
			return $this->logo;
		}

		/**
		* Returns Description attribute
		**/
		function getDescription() {
			return $this->description;
		}

		/**
		* Returns ppa attribute
		**/
		function getPPA() {
			return $this->PPA;
		}

		/**
		* Returns slots array
		**/
		function getSlots( $aDate = false ) {
			if(!$aDate)$aDate = date("Y-m-d");
			$sql = "SELECT id from slot where channel = ".$this->id . " AND date like '$aDate%'";
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
			   $this->slots[] = new Slot( $row['id'] );
			}
			return $this->slots;
		}

	/*************************************************
	* @ Modifier(s) of object CLASS Channel
	*************************************************/
		/**
		* Sets ID of CLASS Channel
		**/
		function setId($aId) {
			$this->id = $aId;
		}

		/**
		* Sets name of CLASS Channel
		**/
		function setName($aName) {
			$this->name = $aName;
		}

		/**
		* Sets short name of CLASS Channel
		**/
		function setShortName($aShortName) {
			$this->shortName = $aShortName;
		}

		/**
		* Sets logo of CLASS Channel
		**/
		function setLogo($aLogo) {
			$this->logo = $aLogo;
		}

		/**
		* Sets description of CLASS Channel
		**/
		function setDescription($aDescription) {
			$this->description = $aDescription;
		}

		/**
		* Sets slots array
		**/
		function setSlots($aSlots) {
			$this->slots = $aSlots;
		}
		
		/**
		* ADDS a slot to slots array
		**/
		function addSlot($aSlot) {
			$aSlot->setChannel( $this->getId() );
			$this->slots[] = $aSlot;
		}
		/**
		* Sets PPA id of CLASS Channel
		**/
		function setPPA($aPPA) {
			$this->PPA = $aPPA;
		}

	/*************************************************
	* @ Persistence of object CLASS Channel
	*************************************************/
		/**
		* @ Updates or Inserts Channel Object information depending
		* @ upon existence of valid primary key.
		**/
		function commit() {
			if ($this->id) {
				$sql  = " UPDATE channel SET";
				$sql .= " name = '$this->name',";
				$sql .= " shortName = '$this->shortName',";
				$sql .= " logo = '$this->logo',";
				$sql .= " description = '$this->description',";
				$sql .= " ppa = '$this->PPA'";
				$sql .= " WHERE  id = $this->id";
				db_query($sql);
			}else{
				$sql = "INSERT INTO channel ( name, shortName, logo, description, ppa ) VALUES ( '$this->name', '$this->shortName', '$this->logo', '$this->description', '$this->PPA' )";
				db_query($sql);
				$this->id = db_id_insert();
			}
	
			$ids = "(0";
			for( $i = 0; $i < count( $this->slots ); $i++ ){
			   $obj = $this->slots[$i];
			   $obj->setChannel( $this->getId() );
			   $obj->commit();
			   $ids .= ", " . $obj->getId();
			}
			$ids .= ")";
			$sql = "DELETE FROM slots WHERE channel = '$this->id' AND id NOT IN " . $ids;
			db_query($sql);
		}

		/**
		* @ Deletes Channel Object from database.
		**/
		function erase () {
			$sql = "DELETE FROM channel";
			$sql .= " WHERE id = " . $this->id;
			db_query($sql);
			for( $i = 0; $i < count( $this->slots ); $i++ ){
			   $this->slots[$i]->erase();
			}
		}

		/**
		* @ Loads Channel Object attributes from the database.
		**/
		function load ($aId) {
			$sql = "SELECT * from channel";
			$sql .= " WHERE id = " . $aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->shortName = $row['shortName'];
			$this->logo = $row['logo'];
			$this->description = $row['description'];
			$this->slots = array();
		}
}
?>