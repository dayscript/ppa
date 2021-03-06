<?
require_once($path . "class/Channel.class.php");
/*************************************************
* @ Channels Class definition
* @ author:		Juan Carlos Orrego
* @ created:	August - 21 - 2003 - 12:19:05
* @ modified:	August - 21 - 2003 - 12:19:05
* @ version:	1.0
*************************************************/

class Channels {
	/**
	* @ Object var definitions.
	*/
	var $channels;	//	Channels Array
	var $numbers;		//	Numbers Array
	var $groups;		//	Groups Array
	var $client;		//  Client Relation id


	/*************************************************
	 * @ Constructor(s) 
	*************************************************/
		/**
		* @ Creates an empty Channels Object. 
		**/
		function Channels( $aClient = false ) {
			$this->channels= array();
			$this->numbers= array();
			$this->groups= array();
			if($aClient) $this->client = $aClient;
		}

	/*************************************************
	* @ Analizer(s) of object CLASS Channels
	*************************************************/
		/**
		* @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print() {
			echo "<br><b>(Channels)</b><br>";
			echo "<ul>";
			echo "<li><b>Client: </b> $this->client </li>";
			echo "<li><b>channels[]: </b> ";
			for( $i = 0; $i < count( $this->channels ); $i++ ){
			   $channel = $this->channels[$i];
			   echo "Number: " . $this->numbers[$i] . " ";
			   echo "Group: " . $this->groups[$i] . " ";
			   $channel->_print();
			}
			echo "</li>";
			echo "</ul>";
		}

		/**
		* Returns numbers array
		**/
		function getNumbers() {
			return $this->numbers;
		}

		/**
		* Returns groups array
		**/
		function getGroups() {
			return $this->groups;
		}

		/**
		* Returns client attribute
		**/
		function Client() {
			return $this->client;
		}

		/**
		* Returns channels array
		**/
		function getChannels() {
			return $this->channels;
		}
		/**
		* Returns valid channels array
		**/
		function getValidChannels( $year, $month) {
		  $channels = $this->getChannels();
		  $valid_channels = array();
		  for( $i = 0 ; $i < count( $channels ); $i++ ){
		    $numslots = $channels[$i]->getNumSlotsInYearMonth( $year, $month );
		    if( $numslots > 0 ){
		      $valid_channels[] = $channels[$i];
		    }
		  }
		  return $valid_channels;
		   
		}
		/**
		* Returns valid numbers array
		**/
		function getValidNumbers( $year, $month) {
		  $channels = $this->getChannels();
		  $numbers = $this->getNumbers();
		  $valid_channels = array();
		  for( $i = 0 ; $i < count( $channels ); $i++ ){
		    $numslots = $channels[$i]->getNumSlotsInYearMonth( $year, $month );
		    if( $numslots > 0 ){
		      $valid_numbers[] = $numbers[$i];
		    }
		  }
		  return $valid_numbers;
		   
		}

	/*************************************************
	* @ Modifier(s) of object CLASS Channels
	*************************************************/

		/**
		* Sets client of CLASS Channels
		**/
		function setClient($aClient) {
			$this->client = $aClient;
		}
		
		/**
		* Sets numbers array
		**/
		function setNumbers($aNumbers) {
			$this->numbers = $aNumbers;
		}

		/**
		* Sets groups array
		**/
		function setGroups($aGroups) {
			$this->groups = $aGroups;
		}

		/**
		* Sets channels array
		**/
		function setChannels($aChannels) {
			$this->channels = $aChannels;
		}
		
		/**
		* ADDS a channel to channels array
		**/
		function addChannel($aChannel, $aNumber = 0, $aGroup = "" ) {
			$this->channels[] = $aChannel;
			$this->numbers[] = $aNumber;
			$this->groups[] = $aGroup;
		}


	/*************************************************
	* @ Persistence of object CLASS Channels
	*************************************************/
		/**
		* Updates Client - Channels relations
		* @ deprecated
		**/
		function commit() {
			die( "ocurri� un error gravisimo!!... porfavor comuniquelo al patr�n" );
			$sql  = "DELETE from client_channel WHERE client = $this->client";
			db_query($sql);
			for($i=0; $i<count($this->channels); $i++){
				if($this->channels[$i]->getId()){
					$sql = "INSERT INTO client_channel (client, channel, number, _group) VALUES ('$this->client','" . $this->channels[$i]->getId() . "','" . $this->numbers[$i] . "','" . $this->groups[$i] . "')";
					db_query($sql);
				}
			}
		}

		/**
		* @ Deletes Channels Object from database.
		**/
		function erase() {
			$sql = "DELETE FROM client_channel ";
			$sql .= " WHERE client = " . $this->client;
			db_query($sql);
		}

		/**
		* @ Loads Channels Object attributes from the database.
		**/
		function load ($aId) {
		}
}
?>