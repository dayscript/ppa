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
	var $channels;		//	ARRAY of Channels
	var $movies;		//	ARRAY of Movies
	var $specials;		//	ARRAY of Specials
	var $series;		//	ARRAY of Series
	var $videos;		//	ARRAY of Videos
	var $images;		//	ARRAY of Images


	/*************************************************
	* @ Constructor(s) 
	*************************************************/
		/**
		* @ Creates an empty PPA Object or filled with values. 
		**/
		function PPA ( $aId = false, $aName = false, $aClients = false, $aChannels= false , $aMovies = false, $aSpecials = false, $aSeries = false, $aVideos = false, $aImages = false ) {
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
		    if ($aVideos)$this->videos = $aVideos;
			else if(!isset($this->videos)) $this->videos = array();
		    if ($aImages)$this->images = $aImages;
			else if(!isset($this->images)) $this->images = array();

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
			echo "<li><b>videos[]: </b> ";
			for( $i = 0; $i < count( $this->videos ); $i++ ){
			   $video = $this->videos[$i];
			   $video->_print();
			}
			echo "</li>";
			echo "<li><b>images[]: </b> ";
			for( $i = 0; $i < count( $this->images ); $i++ ){
			   $image = $this->images[$i];
			   $image->_print();
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
		function getClientById( $aId ) {
			foreach($this->clients as $client){
				if($client->getId() == $aId)
					return $client;
			}
			return false;
		}
		/**
		* Returns channels array
		**/
		function getChannels() {
			return $this->channels;
		}
		/**
		* Returns videos array
		**/
		function getVideos() {
			$sql = "SELECT id FROM video where ppa = " . $this->id;
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
				$video = new Video( $row['id'] );
				$this->addVideo($video);
			}
			return $this->videos;
		}
		/**
		* Returns images array
		**/
		function getImages() {
			$sql = "SELECT id FROM image where ppa = " . $this->id;
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
				$image = new Image( $row['id'] );
				$this->addImage($image);
			}
			return $this->images;
		}
		/**
		* Returns movies array
		**/
		function getMovies( $aTitle = "" ) {
			$sql = "SELECT id from movie where ppa = " . $this->id . " AND title like '$aTitle'";
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
				$mv = new Movie( $row['id'] );
				$this->addMovie($mv);
			}
			return $this->movies;
		}
		/**
		* Returns movies array
		**/
		function getMoviesEs( $aTitle = "" ) {
			$sql = "SELECT id from movie where ppa = " . $this->id . " AND spanishTitle like '$aTitle'";
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
				$mv = new Movie( $row['id'] );
				$this->addMovie($mv);
			}
			return $this->movies;
		}


		/**
		* Returns movies array matching director
		**/
		function getMoviesByDirector( $aDirector = "" ) {
			$sql = "SELECT id from movie where ppa = " . $this->id . " AND director like '$aDirector'";
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
				$mv = new Movie( $row['id'] );
				$this->addMovie($mv);
			}
			return $this->movies;
		}
		/**
		* Returns specials array
		**/
		function getSpecials( $aTitle = "" ) {
			$sql = "SELECT id from special where ppa = ".$this->id . " AND title like '$aTitle'";
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
			   $this->specials[] = new Special( $row['id'] );
			}
			return $this->specials;
		}
		/**
		* Returns specials array
		**/
		function getSpecialsEs( $aTitle = "" ) {
			$sql = "SELECT id from special where ppa = ".$this->id . " AND spanishTitle like '$aTitle'";
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
			   $this->specials[] = new Special( $row['id'] );
			}
			return $this->specials;
		}
		
		/**
		* Returns specials array matching starring
		**/
		function getSpecialsByStarring( $aStarring = "" ) {
			$sql = "SELECT id from special where ppa = " . $this->id . " AND starring like '$aStarring'";
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
				$mv = new Special( $row['id'] );
				$this->addSpecial($mv);
			}
			return $this->series;
		}

		/**
		* Returns series array
		**/
		function getSeries( $aTitle = "" ) {
			$sql = "SELECT id from serie where ppa = ".$this->id . " AND title like '$aTitle' order by title ASC";
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
			   $this->series[] = new Serie( $row['id'] );
			}
			return $this->series;
		}
		/**
		* Returns series array
		**/
		function getSeriesEs( $aTitle = "" ) {
			$sql = "SELECT id from serie where ppa = ".$this->id . " AND spanishTitle like '$aTitle' order by title ASC";
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
			   $this->series[] = new Serie( $row['id'] );
			}
			return $this->series;
		}

        /* Return a slot array for a give program*/
		function getProgramSlots( $aProgram, $aDateinf, $aDatesup ) {
            $slots = array();
            if( trim( $aProgram ) != "" ){
			  $sql = 'SELECT s.id FROM chapter c, slot_chapter sc, slot s WHERE ( c.title LIKE  "%'.$aProgram.'%" OR c.spanishTitle LIKE  "%'.$aProgram.'%" ) AND c.id = sc.chapter AND sc.slot = s.id AND s.date >= "'.$aDateinf.'" AND s.date <= "'.$aDatesup.'" order by s.date';
 			  $query = db_query($sql );
			  while( $row = db_fetch_array( $query ) ){
			    $slots[] = new Slot( $row['id'] );
			  }
            }
            return $slots;
		}
		        /* Return a slot array for a give program*/
		function getProgramSlots1( $aProgram, $aDateinf, $aDatesup ) {
            $slots = array();
            if( trim( $aProgram ) != "" ){
			  $sql = 'SELECT id FROM slot  WHERE title LIKE  "%'.$aProgram.'%" AND date >= "'.$aDateinf.'" AND date <= "'.$aDatesup.'" order by date';
 			  $query = db_query($sql );
			  while( $row = db_fetch_array( $query ) ){
			    $slots[] = new Slot( $row['id'] );
			  }
            }
            return $slots;
		}
        /* Return a slot array for a give actor*/
		function getActorSlots( $aActor, $aDateinf, $aDatesup ) {
            $slots = array();
            if( trim( $aActor ) != "" ){
			  $sql = 'SELECT s1.id FROM serie s, chapter c, slot_chapter sc, slot s1 WHERE c.serie = s.id AND s.starring LIKE  "%'.$aActor.'%" AND c.id = sc.chapter AND sc.slot = s1.id AND s1.date >= "'.$aDateinf.'" AND s1.date <= "'.$aDatesup.'" order by s1.date';
 			  $query = db_query($sql );
			  while( $row = db_fetch_array( $query ) ){
			    $slots[] = new Slot( $row['id'] );
			  }
			  $sql = 'SELECT s1.id FROM movie s, chapter c, slot_chapter sc, slot s1 WHERE c.movie = s.id AND s.actors LIKE  "%'.$aActor.'%" AND c.id = sc.chapter AND sc.slot = s1.id AND s1.date >= "'.$aDateinf.'" AND s1.date <= "'.$aDatesup.'" order by s1.date';
 			  $query = db_query($sql );
			  while( $row = db_fetch_array( $query ) ){
			    $slots[] = new Slot( $row['id'] );
			  }
			  $sql = 'SELECT s1.id FROM special s, chapter c, slot_chapter sc, slot s1 WHERE c.special = s.id AND s.starring LIKE  "%'.$aActor.'%"  AND c.id = sc.chapter AND sc.slot = s1.id AND s1.date >= "'.$aDateinf.'" AND s1.date <= "'.$aDatesup.'" order by s1.date';
 			  $query = db_query($sql );
			  while( $row = db_fetch_array( $query ) ){
			    $slots[] = new Slot( $row['id'] );
			  }
            }
            return $slots;
		}
        /* Return a slot array for a give director*/
		function getDirectorSlots( $aDirector, $aDateinf, $aDatesup ) {
            $slots = array();
            if( trim( $aDirector ) != "" ){
			  $sql = 'SELECT s1.id FROM movie s, chapter c, slot_chapter sc, slot s1 WHERE c.movie = s.id AND s.director LIKE  "%'.$aDirector.'%" AND c.id = sc.chapter AND sc.slot = s1.id AND s1.date >= "'.$aDateinf.'" AND s1.date <= "'.$aDatesup.'" order by s1.date';
 			  $query = db_query($sql );
			  while( $row = db_fetch_array( $query ) ){
			    $slots[] = new Slot( $row['id'] );
			  }
            }
            return $slots;
		}
        /* Return a slot array for a give director*/
		function getGenderSlots( $aGender, $aDateinf, $aDatesup ) {
            $slots = array();
            if( trim( $aGender ) != "" ){
			  $sql = 'SELECT s1.id FROM serie s, chapter c, slot_chapter sc, slot s1 WHERE c.serie = s.id AND s.gender LIKE  "%'.$aGender.'%" AND c.id = sc.chapter AND sc.slot = s1.id AND s1.date >= "'.$aDateinf.'" AND s1.date <= "'.$aDatesup.'" order by s1.date';
 			  $query = db_query($sql );
			  while( $row = db_fetch_array( $query ) ){
			    $slots[] = new Slot( $row['id'] );
			  }
			  $sql = 'SELECT s1.id FROM movie s, chapter c, slot_chapter sc, slot s1 WHERE c.movie = s.id AND s.gender LIKE  "%'.$aGender.'%" AND c.id = sc.chapter AND sc.slot = s1.id AND s1.date >= "'.$aDateinf.'" AND s1.date <= "'.$aDatesup.'" order by s1.date';
 			  $query = db_query($sql );
			  while( $row = db_fetch_array( $query ) ){
			    $slots[] = new Slot( $row['id'] );
			  }
			  $sql = 'SELECT s1.id FROM special s, chapter c, slot_chapter sc, slot s1 WHERE c.special = s.id AND s.gender LIKE  "%'.$aGender.'%"  AND c.id = sc.chapter AND sc.slot = s1.id AND s1.date >= "'.$aDateinf.'" AND s1.date <= "'.$aDatesup.'" order by s1.date';
 			  $query = db_query($sql );
			  while( $row = db_fetch_array( $query ) ){
			    $slots[] = new Slot( $row['id'] );
			  }
            }
            return $slots;
		}
		/**
		* Returns series array matching starring
		**/
		function getSeriesByStarring( $aStarring = "" ) {
			$sql = "SELECT id from serie where ppa = " . $this->id . " AND starring like '$aStarring' order by title ";
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
				$mv = new Serie( $row['id'] );
				$this->addSerie($mv);
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
		* Sets videos array
		**/
		function setVideos($aVideos) {
			$this->videos = $aVideos;
		}
		
		/**
		* ADDS a video to videos array
		**/
		function addVideo($aVideo) {
			$aVideo->setPPA( $this->getId() );
			$this->videos[] = $aVideo;
		}
		/**
		* Sets images array
		**/
		function setImages($aImages) {
			$this->images = $aImages;
		}
		
		/**
		* ADDS a image to images array
		**/
		function addImage($aImages) {
			$aImage->setPPA( $this->getId() );
			$this->images[] = $aImage;
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
		  /*
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
		  */
		}

		/**
		* @ Deletes PPA Object from database.
		**/
		function erase () {
			if($this->id){
				$sql = "DELETE FROM ppa";
				$sql .= " WHERE id = " . $this->id;
				db_query($sql);
			}
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
			for( $i = 0; $i < count( $this->videos ); $i++ ){
			   $this->videos[$i]->erase();
			}
			for( $i = 0; $i < count( $this->images ); $i++ ){
			   $this->images[$i]->erase();
			}
		}

		/**
		* @ Loads PPA Object attributes from the database.
		**/
		function load ($aId) {
			$sql = "SELECT * from ppa";
			$sql .= " WHERE id = ".$aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);
			$this->id = $row['id'];
			$this->name = $row['name'];
			$sql = "SELECT id from client where ppa = ".$this->id . " ORDER BY name";
			$query = db_query( $sql );
			while( $row = db_fetch_array( $query ) && true ){				
			   $this->clients[] = new Client( $row['id'] );
			}
			$sql = "SELECT id from channel where ppa = ".$this->id." order by name";
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
			   $this->channels[] = new Channel( $row['id'] );
			}
			$this->movies   = array();
			$this->specials = array();
			$this->series   = array();
		}
}

?>