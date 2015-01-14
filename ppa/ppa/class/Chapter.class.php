<?
	/*************************************************
	* @ Chapter Object definition
	* @ author:		Juan Carlos Orrego
	* @ created:	August - 22 - 2003 - 6:53:10
	* @ modified:	August - 22 - 2003 - 6:53:10
	* @ version:	1.0
	*************************************************/


	class Chapter	{
		/**
		* @ Object var definitions.
		**/
		var $id;				//	ID  int
		var $title;				//	Original Title  string
		var $spanishTitle;		//	Spanish Title  string
		var $number;			//	Chapter Number  int
		var $description;		//	Chapter Sinopsis  string
		var $duration;			//	Chapter duration (minutes)  string
		var $stereo;			//	Stereo  bool
		var $surround;			//	Surround  bool
		var $sap;				//	SAP  bool
		var $closeCaption;		//	Close Captioned  bool
		var $animated;			//	Animated  bool
		var $blackAndWhite;		//	Black & White  bool
		var $repetition;		//	Repetition  bool
		var $onLive;			//	On Live  bool
		var $nudity;			//	Nude Content  bool
		var $ofensiveLanguage;	//	Ofensive Language  bool
		var $violence;			//	Violence  bool
		var $adultContent;		//	Adult Content  bool
		var $points;			//	Rating Points
		var $movie;				//	Movie relation ID  int
		var $special;			//	Special relation ID  int
		var $serie;				//	Serie relation ID  int


	/*************************************************
	* @ Constructor(s) of object CLASS CHAPTER
	*************************************************/
		/**
		* @ Creates an empty CLASS CHAPTER object or filled with values. 
		**/
		function Chapter ( $aId = false, $aTitle = false, $aSpanishTitle = false, $aNumber = false, $aDescription = false, $aDuration = false, $aStereo = -1, $aSurround = -1, $aSap = -1, $aCloseCaption = -1, $aAnimated = -1, $aBlackAndWhite = -1, $aRepetition = -1, $aOnLive = -1, $aNudity = -1, $aOfensiveLanguage = -1, $aViolence = -1, $aAdultContent = -1, $aPoints = false, $aMovie = false, $aSpecial = false, $aSerie = false ) {
			if ($aId)$this->load($aId);
			if ($aTitle)$this->title = $aTitle;
			if ($aSpanishTitle)$this->spanishTitle = $aSpanishTitle;
			if ($aNumber)$this->number = $aNumber;
			if ($aDescription)$this->description = $aDescription;
			if ($aDuration)$this->duration = $aDuration;
			if ($aStereo >= 0)$this->stereo = $aStereo;
			if ($aSurround >= 0)$this->surround = $aSurround;
			if ($aSap >= 0)$this->sap = $aSap;
			if ($aCloseCaption >= 0)$this->closeCaption = $aCloseCaption;
			if ($aAnimated >= 0)$this->animated = $aAnimated;
			if ($aBlackAndWhite >= 0)$this->blackAndWhite = $aBlackAndWhite;
			if ($aRepetition >= 0)$this->repetition = $aRepetition;
			if ($aOnLive >= 0)$this->onLive = $aOnLive;
			if ($aNudity >= 0)$this->nudity = $aNudity;
			if ($aOfensiveLanguage >= 0)$this->ofensiveLanguage = $aOfensiveLanguage;
			if ($aViolence >= 0)$this->violence = $aViolence;
			if ($aAdultContent >= 0)$this->adultContent = $aAdultContent;
			if ($aPoints)$this->points = $aPoints;
			if ($aMovie)$this->movie = $aMovie;
			if ($aSpecial)$this->special = $aSpecial;
			if ($aSerie)$this->serie = $aSerie;
		}

	/*************************************************
	* @ Analizer(s) of object CLASS CHAPTER
	*************************************************/
		/**
		* @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print()	{
			echo "<br><b>(Chapter)</b><br>";
			echo "<ul>";
			echo "<li><b>ID: </b> $this->id </li>";
			echo "<li><b>Original Title: </b> $this->title </li>";
			echo "<li><b>Spanish Title: </b> $this->spanishTitle </li>";
			echo "<li><b>Chapter Number: </b> $this->number </li>";
			echo "<li><b>Chapter Sinopsis: </b> $this->description </li>";
			echo "<li><b>Chapter duration (minutes): </b> $this->duration </li>";
			echo "<li><b>Stereo: </b> $this->stereo </li>";
			echo "<li><b>Surround: </b> $this->surround </li>";
			echo "<li><b>SAP: </b> $this->sap </li>";
			echo "<li><b>Close Captioned: </b> $this->closeCaption </li>";
			echo "<li><b>Animated: </b> $this->animated </li>";
			echo "<li><b>Black & White: </b> $this->blackAndWhite </li>";
			echo "<li><b>Repetition: </b> $this->repetition </li>";
			echo "<li><b>On Live: </b> $this->onLive </li>";
			echo "<li><b>Nude Content: </b> $this->nudity </li>";
			echo "<li><b>Ofensive Language: </b> $this->ofensiveLanguage </li>";
			echo "<li><b>Violence: </b> $this->violence </li>";
			echo "<li><b>Adult Content: </b> $this->adultContent </li>";
			echo "<li><b>Rating Points: </b> $this->points </li>";
			echo "<li><b>Movie relation ID: </b> $this->movie </li>";
			echo "<li><b>Special relation ID: </b> $this->special </li>";
			echo "<li><b>Serie relation ID: </b> $this->serie </li>";
			echo "</ul>";
		}

		/**
		* Returns ID of CLASS CHAPTER
		**/
		function getId() {
			return $this->id;
		}
		/**
		* Returns Original Title of CLASS CHAPTER
		**/
		function getTitle() {
			return $this->title;
		}
		/**
		* Returns Spanish Title of CLASS CHAPTER
		**/
		function getSpanishTitle() {
			return $this->spanishTitle;
		}
		/**
		* Returns Chapter Number of CLASS CHAPTER
		**/
		function getNumber() {
			return $this->number;
		}
		/**
		* Returns Chapter Sinopsis of CLASS CHAPTER
		**/
		function getDescription() {
			return $this->description;
		}
		/**
		* Returns Chapter duration (minutes) of CLASS CHAPTER
		**/
		function getDuration() {
			return $this->duration;
		}
		/**
		* Returns Stereo of CLASS CHAPTER
		**/
		function getStereo()	{
			return $this->stereo;
		}
		/**
		* Returns Surround of CLASS CHAPTER
		**/
		function getSurround()	{
			return $this->surround;
		}
		/**
		* Returns SAP of CLASS CHAPTER
		**/
		function getSap()	{
			return $this->sap;
		}
		/**
		* Returns Close Captioned of CLASS CHAPTER
		**/
		function getCloseCaption()	{
			return $this->closeCaption;
		}
		/**
		* Returns Animated of CLASS CHAPTER
		**/
		function getAnimated()	{
			return $this->animated;
		}
		/**
		* Returns Black & White of CLASS CHAPTER
		**/
		function getBlackAndWhite()	{
			return $this->blackAndWhite;
		}
		/**
		* Returns Repetition of CLASS CHAPTER
		**/
		function getRepetition()	{
			return $this->repetition;
		}
		/**
		* Returns On Live of CLASS CHAPTER
		**/
		function getOnLive()	{
			return $this->onLive;
		}
		/**
		* Returns Nude Content of CLASS CHAPTER
		**/
		function getNudity()	{
			return $this->nudity;
		}
		/**
		* Returns Ofensive Language of CLASS CHAPTER
		**/
		function getOfensiveLanguage()	{
			return $this->ofensiveLanguage;
		}
		/**
		* Returns Violence of CLASS CHAPTER
		**/
		function getViolence()	{
			return $this->violence;
		}
		/**
		* Returns Adult Content of CLASS CHAPTER
		**/
		function getAdultContent()	{
			return $this->adultContent;
		}
		/**
		* Returns Points of CLASS CHAPTER
		**/
		function getPoints()	{
			return $this->points;
		}

		/**
		* Returns Movie relation ID of CLASS CHAPTER
		**/
		function getMovie()	{
			return $this->movie;
		}
		/**
		* Returns Special relation ID of CLASS CHAPTER
		**/
		function getSpecial()	{
			return $this->special;
		}
		/**
		* Returns Serie relation ID of CLASS CHAPTER
		**/
		function getSerie()	{
			return $this->serie;
		}
		/*Returns the channels where the chapter is showing in a given month and year*/
		function getChannelsfromYearMonth( $year, $month ){
		  $channels = array();
		  $days = date( "j", mktime(0, 0, 0, $month + 1, 1, $year) - 1 );
		  $sql = "SELECT CH.id FROM channel CH, slot S, slot_chapter SC, chapter C WHERE C.id = ".$this->getId()." AND SC.chapter = C.id AND SC.slot = S.id AND S.date >= '".$year."-".$month."-01"."' AND S.date <= '".$year."-".$month."-".$days."' AND S.channel = CH.id group by CH.name";
		  $query = db_query( $sql );
		  while( $row = db_fetch_array( $query ) ){
		    $channels[] = new Channel( $row['id'] );
		  }
		  return $channels;
		}
		/*Returns the slots for a given chapter in a month and year */
	   	function getSlotsfromYearMonth(  $year, $month  ){
		  $slots = array();
		  $days = date( "j", mktime(0, 0, 0, $month + 1, 1, $year) - 1 );
		  $sql = "SELECT slot FROM slot_chapter SC,  slot S WHERE SC.chapter = ".$this->getId()." AND SC.slot = S.id AND S.date >= '".$year."-".$month."-01"."' AND S.date <= '".$year."-".$month."-".$days."' order by S.date";
		  $query = db_query( $sql );
		  while( $row = db_fetch_array( $query ) ){
		    $slots[] = new Slot( $row['slot'] );
		  }
		  return $slots;
		  
		 }
		/*Returns the type of the chapter: movie, series, special*/
		function getType(){
		  if( trim( $this->getMovie() ) != "0" ){
		    return "Movie";
		  }else{
		    if( trim( $this->getSerie() ) != "0" ){
		      return "Serie";
		    }else{
		      if( trim( $this->getSpecial() ) != "0" ){
			return "Special";
		      }
		    }
		  }
		  return false;
		}
	/*************************************************
	* @ Modifier(s) of object CLASS CHAPTER
	*************************************************/
		/**
		* Sets ID of CLASS CHAPTER
		**/
		function setId($aId) {
			$this->id = $aId;
		}
		/**
		* Sets Original Title of CLASS CHAPTER
		**/
		function setTitle($aTitle) {
			$this->title = $aTitle;
		}
		/**
		* Sets Spanish Title of CLASS CHAPTER
		**/
		function setSpanishTitle($aSpanishTitle) {
			$this->spanishTitle = $aSpanishTitle;
		}
		/**
		* Sets Chapter Number of CLASS CHAPTER
		**/
		function setNumber($aNumber) {
			$this->number = $aNumber;
		}
		/**
		* Sets Chapter Sinopsis of CLASS CHAPTER
		**/
		function setDescription($aDescription) {
			$this->description = $aDescription;
		}
		/**
		* Sets Chapter duration (minutes) of CLASS CHAPTER
		**/
		function setDuration($aDuration) {
			$this->duration = $aDuration;
		}
		/**
		* Sets Stereo of CLASS CHAPTER
		**/
		function setStereo($aStereo)	{
			$this->stereo = $aStereo;
		}
		/**
		* Sets Surround of CLASS CHAPTER
		**/
		function setSurround($aSurround)	{
			$this->surround = $aSurround;
		}
		/**
		* Sets SAP of CLASS CHAPTER
		**/
		function setSap($aSap)	{
			$this->sap = $aSap;
		}
		/**
		* Sets Close Captioned of CLASS CHAPTER
		**/
		function setCloseCaption($aCloseCaption)	{
			$this->closeCaption = $aCloseCaption;
		}
		/**
		* Sets Animated of CLASS CHAPTER
		**/
		function setAnimated($aAnimated)	{
			$this->animated = $aAnimated;
		}
		/**
		* Sets Black & White of CLASS CHAPTER
		**/
		function setBlackAndWhite($aBlackAndWhite)	{
			$this->blackAndWhite = $aBlackAndWhite;
		}
		/**
		* Sets Repetition of CLASS CHAPTER
		**/
		function setRepetition($aRepetition)	{
			$this->repetition = $aRepetition;
		}
		/**
		* Sets On Live of CLASS CHAPTER
		**/
		function setOnLive($aOnLive)	{
			$this->onLive = $aOnLive;
		}
		/**
		* Sets Nude Content of CLASS CHAPTER
		**/
		function setNudity($aNudity)	{
			$this->nudity = $aNudity;
		}
		/**
		* Sets Ofensive Language of CLASS CHAPTER
		**/
		function setOfensiveLanguage($aOfensiveLanguage)	{
			$this->ofensiveLanguage = $aOfensiveLanguage;
		}
		/**
		* Sets Violence of CLASS CHAPTER
		**/
		function setViolence($aViolence)	{
			$this->violence = $aViolence;
		}
		/**
		* Sets Adult Content of CLASS CHAPTER
		**/
		function setAdultContent($aAdultContent)	{
			$this->adultContent = $aAdultContent;
		}
		/**
		* Sets Points of CLASS CHAPTER
		**/
		function setPoints($aPoints)	{
			$this->points = $aPoints;
		}

		/**
		* Sets Movie relation ID of CLASS CHAPTER
		**/
		function setMovie($aMovie)	{
			$this->movie = $aMovie;
		}
		/**
		* Sets Special relation ID of CLASS CHAPTER
		**/
		function setSpecial($aSpecial)	{
			$this->special = $aSpecial;
		}
		/**
		* Sets Serie relation ID of CLASS CHAPTER
		**/
		function setSerie($aSerie)	{
			$this->serie = $aSerie;
		}

	/*************************************************
	* @ Persistence of object CLASS CHAPTER
	*************************************************/
		/**
		* @ Updates or Inserts CLASS CHAPTER information depending
		* @ upon existence of valid primary key.
		**/
		function commit ()	{
			if ($this->id)	{
				$sql  = " UPDATE chapter SET";
				$sql .=" title = '" . addslashes( $this->title ) . "',";
				$sql .=" spanishTitle = '" . addslashes( $this->spanishTitle ) . "',";
				$sql .=" number = $this->number,";
				$sql .=" description = '". addslashes($this->description) . "',";
				$sql .=" duration = '$this->duration',";
				$sql .=" stereo = '$this->stereo',";
				$sql .=" surround = '$this->surround',";
				$sql .=" sap = '$this->sap',";
				$sql .=" closeCaption = '$this->closeCaption',";
				$sql .=" animated = '$this->animated',";
				$sql .=" blackAndWhite = '$this->blackAndWhite',";
				$sql .=" repetition = '$this->repetition',";
				$sql .=" onLive = '$this->onLive',";
				$sql .=" nudity = '$this->nudity',";
				$sql .=" ofensiveLanguage = '$this->ofensiveLanguage',";
				$sql .=" violence = '$this->violence',";
				$sql .=" adultContent = '$this->adultContent',";
				$sql .=" points = '$this->points',";
				$sql .=" movie = $this->movie,";
				$sql .=" special = $this->special,";
				$sql .=" serie = $this->serie";
				$sql .= " WHERE  id = $this->id";
				if( !db_query($sql) ) echo db_error( $sql );
			}	else	{
				$sql = " INSERT INTO chapter ( title, spanishTitle, number, description, duration, stereo, surround, sap, closeCaption, animated, blackAndWhite, repetition, onLive, nudity, ofensiveLanguage, violence, adultContent, points, movie, special, serie ) VALUES ( ";
				$sql .=" '" . addslashes( $this->title ) . "',";
				$sql .=" '" . addslashes( $this->spanishTitle ) . "',";
				$sql .=" $this->number,";
				$sql .=" '". addslashes($this->description) . "',";
				$sql .=" '$this->duration',";
				$sql .=" '$this->stereo',";
				$sql .=" '$this->surround',";
				$sql .=" '$this->sap',";
				$sql .=" '$this->closeCaption',";
				$sql .=" '$this->animated',";
				$sql .=" '$this->blackAndWhite',";
				$sql .=" '$this->repetition',";
				$sql .=" '$this->onLive',";
				$sql .=" '$this->nudity',";
				$sql .=" '$this->ofensiveLanguage',";
				$sql .=" '$this->violence',";
				$sql .=" '$this->adultContent',";
				$sql .=" '$this->points',";
				$sql .=" '$this->movie',";
				$sql .=" '$this->special',";
				$sql .=" '$this->serie' )";

				//"( '$this->title', '$this->spanishTitle', $this->number, '$this->description', '$this->duration', '$this->stereo', '$this->surround', '$this->sap', '$this->closeCaption', '$this->animated', '$this->blackAndWhite', '$this->repetition', '$this->onLive', '$this->nudity', '$this->ofensiveLanguage', '$this->violence', '$this->adultContent', '$this->points', '$this->movie', '$this->special', '$this->serie' )";
				db_query($sql);
				$this->id = db_id_insert();
			}
		}
		/**
		* @ Deletes CLASS CHAPTER object from database.
		**/
		function erase ()	{
			if($this->id){
				$sql = "DELETE FROM chapter";
				$sql .= " WHERE id = " . $this->id;
				db_query($sql);
			}
		}

		/**
		* @ Loads CLASS CHAPTER attributes from the date base
		* @ and assigns them to CLASS CHAPTER's attributes.
		**/
		function load ($aId)	{
			$sql = "SELECT * from chapter";
			$sql .= " WHERE id = " . $aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);
			$this->id = $row['id'];
			$this->title = $row['title'];
			$this->spanishTitle = $row['spanishTitle'];
			$this->number = $row['number'];
			$this->description = $row['description'];
			$this->duration = $row['duration'];
			$this->stereo = $row['stereo'];
			$this->surround = $row['surround'];
			$this->sap = $row['sap'];
			$this->closeCaption = $row['closeCaption'];
			$this->animated = $row['animated'];
			$this->blackAndWhite = $row['blackAndWhite'];
			$this->repetition = $row['repetition'];
			$this->onLive = $row['onLive'];
			$this->nudity = $row['nudity'];
			$this->ofensiveLanguage = $row['ofensiveLanguage'];
			$this->violence = $row['violence'];
			$this->adultContent = $row['adultContent'];
			$this->points = $row['points'];
			$this->movie = $row['movie'];
			$this->special = $row['special'];
			$this->serie = $row['serie'];
		}
}
?>
