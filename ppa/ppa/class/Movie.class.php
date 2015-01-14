<?
require_once($path . "class/Chapter.class.php");
	/*************************************************
	* @ Movie Object definition
	* @ author:		Juan Carlos Orrego
	* @ created:	August - 22 - 2003 - 7:37:16
	* @ modified:	August - 22 - 2003 - 7:37:16
	* @ version:	1.0
	*************************************************/


	class Movie	{
		/**
		* @ Object var definitions.
		**/
		var $id;			//	ID  int
		var $title;			//	Original Title  string
		var $spanishTitle;	//	Spanish Title  string
		var $englishTitle;	//	English Title  string
		var $gender;		//	Gender  string
		var $rated;			//	Rated  string
		var $tvRated;		//	TV Rated  string
		var $year;			//	Year  string
		var $actors;		//	Principal Actors  string
		var $director;		//	Director  string
		var $description;	//	Sinopsis  string
		var $country;		//	Country  string
		var $language;		//	Original language  string
		var $PPA;			//	PPA relation id  int
		var $chapters;		//	Chapters Array


	/*************************************************
	* @ Constructor(s) of object CLASS Movie
	*************************************************/
		/**
		* @ Creates an empty CLASS Movie object or filled with values. 
		**/
		function Movie ( $aId = false, $aTitle = false, $aSpanishTitle = false, $aEnglishTitle = false, $aGender = false, $aRated = false, $aTvRated = false, $aYear = false, $aActors = false, $aDirector = false, $aDescription = false, $aCountry = false, $aLanguage = false, $aPPA = false, $aChapters = false )	{
			if ($aId)$this->load($aId);
			if ($aTitle)$this->title = $aTitle;
			if ($aSpanishTitle)$this->spanishTitle = $aSpanishTitle;
			if ($aEnglishTitle)$this->englishTitle = $aEnglishTitle;
			if ($aGender)$this->gender = $aGender;
			if ($aRated)$this->rated = $aRated;
			if ($aTvRated)$this->tvRated = $aTvRated;
			if ($aYear)$this->year = $aYear;
			if ($aActors)$this->actors = $aActors;
			if ($aDirector)$this->director = $aDirector;
			if ($aDescription)$this->description = $aDescription;
			if ($aCountry)$this->country = $aCountry;
			if ($aLanguage)$this->language = $aLanguage;
			if ($aPPA)$this->PPA = $aPPA;
		    if ($aChapters)$this->chapters= $aChapters;
			else if(!isset($this->chapters)) $this->chapters= array();

		}

	/*************************************************
	* @ Analizer(s) of object CLASS Movie
	*************************************************/
		/**
		* @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print() {
			echo "<br><b>(Movie)</b><br>";
			echo "<ul>";
			echo "<li><b>ID: </b> $this->id </li>";
			echo "<li><b>Original Title: </b> $this->title </li>";
			echo "<li><b>Spanish Title: </b> $this->spanishTitle </li>";
			echo "<li><b>English Title: </b> $this->englishTitle </li>";
			echo "<li><b>Gender: </b> $this->gender </li>";
			echo "<li><b>Rated: </b> $this->rated </li>";
			echo "<li><b>TV Rated: </b> $this->tvRated </li>";
			echo "<li><b>Year: </b> $this->year </li>";
			echo "<li><b>Principal Actors: </b> $this->actors </li>";
			echo "<li><b>Director: </b> $this->director </li>";
			echo "<li><b>Sinopsis: </b> $this->description </li>";
			echo "<li><b>Country: </b> $this->country </li>";
			echo "<li><b>Original language: </b> $this->language </li>";
			echo "<li><b>PPA relation id: </b> $this->PPA </li>";
			echo "<li><b>chapters[" . count($this->chapters) . "]: </b> ";
			for( $i = 0; $i < count( $this->chapters ); $i++ ){
			   $chapter = $this->chapters[$i];
			   echo "Order: " . $this->orders[$i] . " ";
			   $chapter->_print();
			}
			echo "</li>";
			echo "</ul>";
		}

		/**
		* Returns ID of CLASS Movie
		**/
		function getId() {
			return $this->id;
		}
		/**
		* Returns Original Title of CLASS Movie
		**/
		function getTitle() {
			return $this->title;
		}
		/**
		* Returns Spanish Title of CLASS Movie
		**/
		function getSpanishTitle() {
			return $this->spanishTitle;
		}
		/**
		* Returns English Title of CLASS Movie
		**/
		function getEnglishTitle() {
			return $this->englishTitle;
		}
		/**
		* Returns Gender of CLASS Movie
		**/
		function getGender() {
			return $this->gender;
		}
		/**
		* Returns Rated of CLASS Movie
		**/
		function getRated() {
			return $this->rated;
		}
		/**
		* Returns TVRated of CLASS Movie
		**/
		function getTvRated() {
			return $this->tvRated;
		}
		/**
		* Returns Year of CLASS Movie
		**/
		function getYear() {
			return $this->year;
		}
		/**
		* Returns Principal Actors of CLASS Movie
		**/
		function getActors() {
			return $this->actors;
		}
		/**
		* Returns Director of CLASS Movie
		**/
		function getDirector() {
			return $this->director;
		}
		/**
		* Returns Sinopsis of CLASS Movie
		**/
		function getDescription() {
			return $this->description;
		}
		/**
		* Returns Country of CLASS Movie
		**/
		function getCountry() {
			return $this->country;
		}
		/**
		* Returns Original language of CLASS Movie
		**/
		function getLanguage() {
			return $this->language;
		}
		/**
		* Returns PPA relation id of CLASS Movie
		**/
		function getPPA() {
			return $this->PPA;
		}
		/**
		* Returns chapters array
		**/
		function getChapters() {
			return $this->chapters;
		}
	/*************************************************
	* @ Modifier(s) of object CLASS Movie
	*************************************************/
		/**
		* Sets ID of CLASS Movie
		**/
		function setId($aId) {
			$this->id = $aId;
		}
		/**
		* Sets Original Title of CLASS Movie
		**/
		function setTitle($aTitle) {
			$this->title = $aTitle;
		}
		/**
		* Sets Spanish Title of CLASS Movie
		**/
		function setSpanishTitle($aSpanishTitle) {
			$this->spanishTitle = $aSpanishTitle;
		}
		/**
		* Sets English Title of CLASS Movie
		**/
		function setEnglishTitle($aEnglishTitle) {
			$this->englishTitle = $aEnglishTitle;
		}
		/**
		* Sets Gender of CLASS Movie
		**/
		function setGender($aGender) {
			$this->gender = $aGender;
		}
		/**
		* Sets Rated of CLASS Movie
		**/
		function setRated($aRated) {
			$this->rated = $aRated;
		}
		/**
		* Sets TVRated of CLASS Movie
		**/
		function setTvRated($aTvRated) {
			$this->tvRated = $aTvRated;
		}
		/**
		* Sets Year of CLASS Movie
		**/
		function setYear($aYear) {
			$this->year = $aYear;
		}
		/**
		* Sets Principal Actors of CLASS Movie
		**/
		function setActors($aActors) {
			$this->actors = $aActors;
		}
		/**
		* Sets Director of CLASS Movie
		**/
		function setDirector($aDirector) {
			$this->director = $aDirector;
		}
		/**
		* Sets Sinopsis of CLASS Movie
		**/
		function setDescription($aDescription) {
			$this->description = $aDescription;
		}
		/**
		* Sets Country of CLASS Movie
		**/
		function setCountry($aCountry) {
			$this->country = $aCountry;
		}
		/**
		* Sets Original language of CLASS Movie
		**/
		function setLanguage($aLanguage) {
			$this->language = $aLanguage;
		}
		/**
		* Sets PPA relation id of CLASS Movie
		**/
		function setPPA($aPPA) {
			$this->PPA = $aPPA;
		}

		/**
		* Sets chapters array
		**/
		function setChapters($aChapters) {
			$this->chapters = $aChapters;
		}
		
		/**
		* ADDS a chapter to chapters array
		**/
		function addChapter($aChapter) {
			$aChapter->setMovie( $this->getId() );
			$this->chapters[0] = $aChapter;
		}

	/*************************************************
	* @ Persistence of object CLASS Movie
	*************************************************/
		/**
		* @ Updates or Inserts CLASS Movie information depending
		* @ upon existence of valid primary key.
		**/
		function commit () {
		  $this->title = addslashes($this->title);
		  $this->spanishTitle = addslashes($this->spanishTitle);
		  $this->englishTitle = addslashes($this->englishTitle);
		  $this->actors = addslashes($this->actors);
		  $this->description = addslashes($this->description);
			if ($this->id){
				$sql  = " UPDATE movie SET";
				$sql .=" title = '" . addslashes(  $this->title ) . "',";
				$sql .=" spanishTitle = '" . addslashes( $this->spanishTitle ) . "',";
				$sql .=" englishTitle = '" . addslashes( $this->englishTitle ) . "',";
				$sql .=" gender = '$this->gender',";
				$sql .=" rated = '$this->rated',";
				$sql .=" tvRated = '$this->tvRated',";
				$sql .=" year = '$this->year',";
				$sql .=" actors = '" . addslashes( $this->actors ) . "',";
				$sql .=" director = '" . addslashes( $this->director ) . "',";
				$sql .=" description = '" . addslashes( $this->description ) . "',";
				$sql .=" country = '$this->country',";
				$sql .=" language = '$this->language',";
				$sql .=" PPA = $this->PPA";
				$sql .= " WHERE  id = $this->id";
				if( !db_query($sql) ) echo $sql ;
			}	else	{
				$sql = " INSERT INTO movie ( title, spanishTitle, englishTitle, gender, rated, tvRated, year, actors, director, description, country, language, PPA ) VALUES ( ";
				$sql .=" '" . addslashes(  $this->title ) . "',";
				$sql .=" '" . addslashes( $this->spanishTitle ) . "',";
				$sql .=" '" . addslashes( $this->englishTitle ) . "',";
				$sql .=" '$this->gender',";
				$sql .=" '$this->rated',";
				$sql .=" '$this->tvRated',";
				$sql .=" '$this->year',";
				$sql .=" '" . addslashes( $this->actors ) . "',";
				$sql .=" '" . addslashes( $this->director ) . "',";
				$sql .=" '" . addslashes( $this->description ) . "',";
				$sql .=" '$this->country',";
				$sql .=" '$this->language',";
				$sql .=" $this->PPA )";
//					"( '$this->title', '$this->spanishTitle', '$this->englishTitle', '$this->gender', '$this->rated', '$this->tvRated', '$this->year', '$this->actors', '$this->director', '$this->description', '$this->country', '$this->language', $this->PPA )";
				db_query($sql);
				$this->id = db_id_insert();
			}

			$ids = "(0";
			for($i=0; $i<count($this->chapters); $i++){
			   $obj = $this->chapters[$i];
			   $obj->setMovie( $this->getId() );
			   $obj->commit(); 
			   $this->chapters[$i]->setId( $obj->getId() );
			   if($obj->getId())
			   	  $ids .= ", " . $obj->getId();
			}
			$ids .= ")";
			$sql = "DELETE FROM chapter WHERE movie = '$this->id' AND id NOT IN " . $ids;
			db_query($sql);
		}

		/**
		* @ Deletes CLASS Movie object from database.
		**/
		function erase () {
			$sql = "DELETE FROM movie";
			$sql .= " WHERE id = " . $this->id;
			db_query($sql);
			for( $i = 0; $i < count( $this->chapters ); $i++ ){
			   $this->chapters[$i]->erase();
			}
		}

		/**
		* @ Loads CLASS Movie attributes from the date base
		* @ and assigns them to CLASS Movie's attributes.
		**/
		function load ($aId) {
			$sql = "SELECT * from movie";
			$sql .= " WHERE id = " . $aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);
			$this->id = $row['id'];
			$this->title = $row['title'];
			$this->spanishTitle = $row['spanishTitle'];
			$this->englishTitle = $row['englishTitle'];
			$this->gender = $row['gender'];
			$this->rated = $row['rated'];
			$this->tvRated = $row['tvRated'];
			$this->year = $row['year'];
			$this->actors = $row['actors'];
			$this->director = $row['director'];
			$this->description = $row['description'];
			$this->country = $row['country'];
			$this->language = $row['language'];
			$this->PPA = $row['PPA'];
			$sql = "SELECT id from chapter where movie = ".$this->id;
			$query = db_query($sql);
			while( $row = db_fetch_array( $query ) ){
			   $this->chapters[] = new Chapter( $row['id'] );
			}
		}
}
?>