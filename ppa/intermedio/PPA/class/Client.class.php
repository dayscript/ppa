<?
require_once($path . "class/Channels.class.php");
/*************************************************
* @ Client Class definition
* @ author:		Juan Carlos Orrego
* @ created:	August - 21 - 2003 - 12:19:05
* @ modified:	August - 21 - 2003 - 12:19:05
* @ version:	1.0
*************************************************/

class Client {
	/**
	* @ Object var definitions.
	*/
	var $id;			//	PRIMARY KEY  int
	var $name;			//	Client descriptive name
	var $timezone;		//	Client Time Zone
	var $channels;		//	Channels Container
	var $PPA;			//	PPA relation id


	/*************************************************
	 * @ Constructor(s) 
	*************************************************/
		/**
		* @ Creates an empty Client Object or filled with values. 
		**/
		function Client ( $aId = false, $aName = false, $aTimezone = false, $aChannels= false , $aPPA = false ) {
			if ($aId)$this->load($aId);
		    if ($aName)$this->name= $aName;
		    if ($aTimezone)$this->timezone= $aTimezone;
		    if ($aChannels)$this->channels= $aChannels;
			else if(!isset($this->channels)) $this->channels= new Channels($aId);
		    if ($aPPA)$this->PPA= $aPPA;
		}

	/*************************************************
	* @ Analizer(s) of object CLASS Client
	*************************************************/
		/**
		* @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print() {
			echo "<br><b>(Client)</b><br>";
			echo "<ul>";
			echo "<li><b>id: </b> $this->id </li>";
			echo "<li><b>name: </b> $this->name </li>";
			echo "<li><b>timezone: </b> $this->timezone </li>";
			echo "<li><b>PPA: </b> $this->PPA </li>";
			echo "<li><b>channels[]: </b> ";
			   $this->channels->_print();
			echo "</li>";
			echo "</ul>";
		}

		/**
		* Returns id of CLASS Client
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
		* Returns ppa attribute
		**/
		function getPPA() {
			return $this->PPA;
		}

		/**
		* Returns timezone attribute
		**/
		function getTimezone() {
			return $this->timezone;
		}

		/**
		* Returns channels container
		**/
		function getChannels() {
			return $this->channels;
		}

	/*************************************************
	* @ Modifier(s) of object CLASS Client
	*************************************************/
		/**
		* Sets ID of CLASS Client
		**/
		function setId($aId) {
			$this->id = $aId;
		}

		/**
		* Sets name of CLASS Client
		**/
		function setName($aName) {
			$this->name = $aName;
		}

		/**
		* Sets timezone of CLASS Client
		**/
		function setTimezone($aTimezone) {
			$this->timezone = $aTimezone;
		}
		
		/**
		* Sets PPA of CLASS Client
		**/
		function setPPA($aPPA) {
			$this->PPA = $aPPA;
		}

		/**
		* Sets channels container
		**/
		function setChannels($aChannels) {
			$aChannels->setClient( $this->getId() );
			$this->channels = $aChannels;
		}
		
		/**
		* ADDS a channel to channels container
		**/
		function addChannel($aChannel, $aNumber = 0 ) {
			$this->channels->addChannel( $aChannel, $aNumber );
		}


	/*************************************************
	* @ Persistence of object CLASS Client
	*************************************************/
		/**
		* @ Updates or Inserts Client Object information depending
		* @ upon existence of valid primary key.
		**/
		function commit() {
			if ($this->id) {
				$sql  = " UPDATE client SET";
				$sql .= " name = '$this->name',";
				$sql .= " ppa = '$this->PPA',";
				$sql .= " timezone = '$this->timezone'";
				$sql .= " WHERE  id = $this->id";
				db_query($sql);
			    $this->channels->setClient( $this->getId() );
			    $this->channels->commit();
			}else{
				$sql = "INSERT INTO client ( name, timezone, ppa ) VALUES ( '$this->name', '$this->timezone', '$this->PPA' )";
				db_query($sql);
				$this->id = db_id_insert();
    			$this->channels->setClient( $this->getId() );
			    $this->channels->commit();   
			}
		}

		/**
		 * @ Deletes Client Object from database.
		**/
		function erase () {
			$sql = "DELETE FROM client";
			$sql .= " WHERE id = " . $this->id;
			db_query($sql);
		    $this->channels->erase();
		}

		/**
		 * @ Loads Client Object attributes from the database.
		**/
		function load ($aId) {
			$sql = "SELECT * from client";
			$sql .= " WHERE id = " . $aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->timezone = $row['timezone'];
			$this->PPA = $row['ppa'];
			$this->channels = new Channels($aId);
			$sql = "SELECT channel, number from client_channel where client = " . $this->id . " ORDER BY channel";
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
				$ch = new Channel( $row['channel'] );
				$this->channels->addChannel( $ch, $row['number'], $row['_group'] );
			}
		}
}
?>