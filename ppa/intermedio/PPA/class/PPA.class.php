<?
require_once($path . "class/Channel.class.php");
require_once($path . "class/Client.class.php");
require_once($path . "class/Movie.class.php");
require_once($path . "class/Special.class.php");
require_once($path . "class/Serie.class.php");
/*************************************************
* @ PPA Class definition
* @ author:		Juan Carlos Orrego
* @ created:	August - 21 - 2003 - 12:19:05
* @ modified:	August - 21 - 2003 - 12:19:05
* @ version:	1.0
*************************************************/

class PPA {
	/**
	* @ Object var definitions.
	*/
	var $id;			//	PRIMARY KEY  int
	var $name;			//	PPA descriptive name
	var $clients;		//	ARRAY of Clients
	var $channels;		//	ARRAY of Clients
	var $movies;		//	ARRAY of Clients
	var $specials;		//	ARRAY of Clients
	var $series;		//	ARRAY of Clients


	/*************************************************
	* @ Constructor(s) 
	*************************************************/
		/**
		* @ Creates an empty PPA Object or filled with values. 
		**/
		function PPA ( $aId = false, $aName = false, $aClients = false, $aChannels= false , $aMovies = false, $aSpecials = false, $aSeries = false ) {
			if ($aId)$this->load($aId);
		    if ($aName)$this->name= $aName;
		    if ($aClients)$this->clients = $aClients;
			else if(!isset($this->clients)) $this->clients = array();
		    if ($aChannels)$this->channels= $aChannels;
			else if(!isset($this->channels)) $this->channels= array();
		    if ($aMovies)$this->movies = $aMovies;
			else if(!isset($this->movies)) $this->movies= array();
		    if ($aSpecials)$this->specials = $aSpecials;
			else if(!isset($this->specials)) $this->specials = array();
		    if ($aSeries)$this->series = $aSeries;
			else if(!isset($this->series)) $this->series = array();
		}

	/*************************************************
	* @ Analizer(s) of object CLASS PPA
	*************************************************/
		/**
		* @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print() {
			echo "<br><b>(PPA)</b><br>";
			echo "<ul>";
			echo "<li><b>id: </b> $this->id </li>";
			echo "<li><b>name: </b> $this->name </li>";
			echo "<li><b>clients[]: </b> ";
			for( $i = 0; $i < count( $this->clients ); $i++ ){
			   $client = $this->clients[$i];
			   $client->_print();
			}
			echo "</li>";
			echo "<li><b>channels[]: </b> ";
			for( $i = 0; $i < count( $this->channels ); $i++ ){
			   $channel = $this->channels[$i];
			   $channel->_print();
			}
			echo "</li>";
			echo "<li><b>movies[]: </b> ";
			for( $i = 0; $i < count( $this->movies ); $i++ ){
			   $movie = $this->movies[$i];
			   $movie->_print();
			}
			echo "</li>";
			echo "<li><b>specials[]: </b> ";
			for( $i = 0; $i < count( $this->specials ); $i++ ){
			   $special = $this->specials[$i];
			   $special->_print();
			}
			echo "</li>";
			echo "<li><b>series[]: </b> ";
			for( $i = 0; $i < count( $this->series ); $i++ ){
			   $serie = $this->series[$i];
			   $serie->_print();
			}
			echo "</li>";
			echo "</ul>";
		}

		/**
		* Returns id of CLASS PPA
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
		* Returns clients array
		**/
		function getClients() {
			return $this->clients;
		}

		/**
		* Returns client matching text
		**/
		function getClient( $aName = "" ) {
			for($i=0; $i<count($this->clients); $i++){
				if(stristr($this->clients[$i]->getName(),$aName))
					return $this->clients[$i];
			}
		}
		/**
		* Returns channels array
		**/
		function getChannels() {
			return $this->channels;
		}

		/**
		* Returns movies array
		**/
		function getMovies( $aTitle = "" ) {
			$sql = "SELECT id from movie where ppa = " . $this->id . " AND title like '%$aTitle%'";
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
			   $this->movies[] = new Movie( $row['id'] );
			}
			return $this->movies;
		}

		/**
		* Returns specials array
		**/
		function getSpecials( $aTitle = "" ) {
			$sql = "SELECT id from special where ppa = ".$this->id . " AND title like '%$aTitle%'";
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
			   $this->specials[] = new Special( $row['id'] );
			}
			return $this->specials;
		}

		/**
		* Returns series array
		**/
		function getSeries( $aTitle = "" ) {
			$sql = "SELECT id from serie where ppa = ".$this->id . " AND title like '%$aTitle%'";
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
			   $this->series[] = new Serie( $row['id'] );
			}
			return $this->series;
		}
	/*************************************************
	* @ Modifier(s) of object CLASS PPA
	*************************************************/
		/**
		* Sets ID of CLASS PPA
		**/
		function setId($aId) {
			$this->id = $aId;
		}

		/**
		* Sets name of CLASS PPA
		**/
		function setName($aName) {
			$this->name = $aName;
		}

		/**
		* Sets clients array
		**/
		function setClients($aClients) {
			$this->clients = $aClients;
		}
		
		/**
		* ADDS a client to clients array
		**/
		function addClient($aClient) {
			$aClient->setPPA( $this->getId() );
			$this->clients[] = $aClient;
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
		function addChannel($aChannel) {
			$aChannel->setPPA( $this->getId() );
			$this->channels[] = $aChannel;
		}

		/**
		* Sets movies array
		**/
		function setMovies($aMovies) {
			$this->movies = $aMovies;
		}
		
		/**
		* ADDS a movie to movies array
		**/
		function addMovie($aMovie) {
			$aMovie->setPPA( $this->getId() );
			$this->movies[] = $aMovie;
		}
		
		/**
		* Sets specials array
		**/
		function setSpecials($aSpecials) {
			$this->specials = $aSpecials;
		}
		
		/**
		* ADDS a special to specials array
		**/
		function addSpecial($aSpecial) {
			$aSpecial->setPPA( $this->getId() );
			$this->specials[] = $aSpecial;
		}

		/**
		* Sets series array
		**/
		function setSeries($aSeries) {
			$this->series = $aSeries;
		}
		
		/**
		* ADDS a serie to series array
		**/
		function addSerie($aSerie) {
			$aSerie->setPPA( $this->getId() );
			$this->series[] = $aSerie;
		}

	/*************************************************
	* @ Persistence of object CLASS PPA
	*************************************************/
		/**
		* @ Updates or Inserts PPA Object information depending
		* @ upon existence of valid primary key.
		**/
		function commit() {
			if ($this->id) {
				$sql  = " UPDATE ppa SET";
				$sql .= " name = '$this->name'";
				$sql .= " WHERE  id = $this->id";
				db_query($sql);
			}else{
				$sql = "INSERT INTO ppa ( name ) VALUES ( '$this->name' )";
				db_query($sql);
				$this->id = db_id_insert();
			}

			$ids = "(0";
			for( $i = 0; $i < count( $this->clients ); $i++ ){
			   $obj = $this->clients[$i];
			   $obj->setPPA( $this->getId() );
			   $obj->commit();   
			   $ids .= ", " . $obj->getId();
			}
			$ids .= ")";
			$sql = "DELETE FROM client WHERE ppa = '$this->id' AND id NOT IN " . $ids;
			db_query($sql);
			
			$ids = "(0";
			for( $i = 0; $i < count( $this->channels ); $i++ ){
			   $obj = $this->channels[$i];
			   $obj->setPPA( $this->getId() );
			   $obj->commit();   
			   $ids .= ", " . $obj->getId();
			}				
			$ids .= ")";
			$sql = "DELETE FROM channel WHERE ppa = '$this->id' AND id NOT IN " . $ids;
			db_query($sql);

			$ids = "(0";
			for( $i = 0; $i < count( $this->movies ); $i++ ){
			   $obj = $this->movies[$i];
			   $obj->setPPA( $this->getId() );
			   $obj->commit();   
			   $ids .= ", " . $obj->getId();
			}				
			$ids .= ")";
			$sql = "DELETE FROM movie WHERE ppa = '$this->id' AND id NOT IN " . $ids;
			db_query($sql);

			$ids = "(0";
			for( $i = 0; $i < count( $this->specials ); $i++ ){
			   $obj = $this->specials[$i];
			   $obj->setPPA( $this->getId() );
			   $obj->commit();   
			   $ids .= ", " . $obj->getId();
			}				
			$ids .= ")";
			$sql = "DELETE FROM special WHERE ppa = '$this->id' AND id NOT IN " . $ids;
			db_query($sql);

			$ids = "(0";
			for( $i = 0; $i < count( $this->series ); $i++ ){
			   $obj = $this->series[$i];
			   $obj->setPPA( $this->getId() );
			   $obj->commit();   
			   $ids .= ", " . $obj->getId();
			}
			$ids .= ")";
			$sql = "DELETE FROM serie WHERE ppa = '$this->id' AND id NOT IN " . $ids;
			db_query($sql);
		}

		/**
		* @ Deletes PPA Object from database.
		**/
		function erase () {
			$sql = "DELETE FROM ppa";
			$sql .= " WHERE id = " . $this->id;
			db_query($sql);
			for( $i = 0; $i < count( $this->clients ); $i++ ){
			   $this->clients[$i]->erase();
			}
			for( $i = 0; $i < count( $this->channels ); $i++ ){
			   $this->channels[$i]->erase();
			}
			for( $i = 0; $i < count( $this->specials ); $i++ ){
			   $this->specials[$i]->erase();
			}
			for( $i = 0; $i < count( $this->movies ); $i++ ){
			   $this->movies[$i]->erase();
			}
			for( $i = 0; $i < count( $this->series ); $i++ ){
			   $this->series[$i]->erase();
			}
		}

		/**
		* @ Loads PPA Object attributes from the database.
		**/
		function load ($aId) {
			$sql = "SELECT * from ppa";
			$sql .= " WHERE id = " . $aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);
			$this->id = $row['id'];
			$this->name = $row['name'];
			$sql = "SELECT id from client where ppa = ".$this->id;
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
			   $this->clients[] = new Client( $row['id'] );
			}
			$sql = "SELECT id from channel where ppa = ".$this->id;
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
			   $this->channels[] = new Channel( $row['id'] );
			}
			$this->movies[] = array();
			$this->specials[] = array();
			$this->series[] = array();
		}
}
?>