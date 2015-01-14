<?
require_once($path . "class/Chapter.class.php");
	/*************************************************
	* @ Special Object definition
	* @ author:		Juan Carlos Orrego
	* @ created:	August - 22 - 2003 - 7:37:16
	* @ modified:	August - 22 - 2003 - 7:37:16
	* @ version:	1.0
	*************************************************/


	class Special	{
		/**
		* @ Object var definitions.
		**/
		var $id;			//	ID  int
		var $title;			//	Original Title  string
		var $spanishTitle;	//	Spanish Title  string
		var $gender;		//	Gender  string
		var $rated;			//	Rated  string
		var $starring;		//	Starring  string
		var $description;	//	Sinopsis  string
		var $PPA;			//	PPA relation id  int
		var $chapters;		//	Chapters Array


	/*************************************************
	* @ Constructor(s) of object CLASS Special
	*************************************************/
		/**
		* @ Creates an empty CLASS Special object or filled with values. 
		**/
		function Special ( $aId = false, $aTitle = false, $aSpanishTitle = false, $aGender = false, $aRated = false, $aStarring = false, $aDescription = false, $aPPA = false, $aChapters = false )	{
			if ($aId)$this->load($aId);
			if ($aTitle)$this->title = $aTitle;
			if ($aSpanishTitle)$this->spanishTitle = $aSpanishTitle;
			if ($aGender)$this->gender = $aGender;
			if ($aRated)$this->rated = $aRated;
			if ($aStarring)$this->starring = $aStarring;
			if ($aDescription)$this->description = $aDescription;
			if ($aPPA)$this->PPA = $aPPA;
		    if ($aChapters)$this->chapters= $aChapters;
			else if(!isset($this->chapters)) $this->chapters= array();
		}

	/*************************************************
	* @ Analizer(s) of object CLASS Special
	*************************************************/
		/**
		* @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print() {
			echo "<br><b>(Special)</b><br>";
			echo "<ul>";
			echo "<li><b>ID: </b> $this->id </li>";
			echo "<li><b>Original Title: </b> $this->title </li>";
			echo "<li><b>Spanish Title: </b> $this->spanishTitle </li>";
			echo "<li><b>Gender: </b> $this->gender </li>";
			echo "<li><b>Rated: </b> $this->rated </li>";
			echo "<li><b>Starring: </b> $this->starring </li>";
			echo "<li><b>Sinopsis: </b> $this->description </li>";
			echo "<li><b>PPA relation id: </b> $this->PPA </li>";
			echo "<li><b>chapters[]: </b> ";
			for( $i = 0; $i < count( $this->chapters ); $i++ ){
			   $chapter = $this->chapters[$i];
			   echo "Order: " . $this->orders[$i] . " ";
			   $chapter->_print();
			}
			echo "</li>";
			echo "</ul>";
		}

		/**
		* Returns ID of CLASS Special
		**/
		function getId() {
			return $this->id;
		}
		/**
		* Returns Original Title of CLASS Special
		**/
		function getTitle() {
			return $this->title;
		}
		/**
		* Returns Spanish Title of CLASS Special
		**/
		function getSpanishTitle() {
			return $this->spanishTitle;
		}
		/**
		* Returns Gender of CLASS Special
		**/
		function getGender() {
			return $this->gender;
		}
		/**
		* Returns Rated of CLASS Special
		**/
		function getRated() {
			return $this->rated;
		}
		/**
		* Returns Starring of CLASS Special
		**/
		function getStarring() {
			return $this->starring;
		}
		/**
		* Returns Sinopsis of CLASS Special
		**/
		function getDescription() {
			return $this->description;
		}
		/**
		* Returns PPA relation id of CLASS Special
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
	* @ Modifier(s) of object CLASS Special
	*************************************************/
		/**
		* Sets ID of CLASS Special
		**/
		function setId($aId) {
			$this->id = $aId;
		}
		/**
		* Sets Original Title of CLASS Special
		**/
		function setTitle($aTitle) {
			$this->title = $aTitle;
		}
		/**
		* Sets Spanish Title of CLASS Special
		**/
		function setSpanishTitle($aSpanishTitle) {
			$this->spanishTitle = $aSpanishTitle;
		}
		/**
		* Sets Gender of CLASS Special
		**/
		function setGender($aGender) {
			$this->gender = $aGender;
		}
		/**
		* Sets Rated of CLASS Special
		**/
		function setRated($aRated) {
			$this->rated = $aRated;
		}
		/**
		* Sets Starring of CLASS Special
		**/
		function setStarring($aStarring) {
			$this->starring = $aStarring;
		}
		/**
		* Sets Sinopsis of CLASS Special
		**/
		function setDescription($aDescription) {
			$this->description = $aDescription;
		}
		/**
		* Sets PPA relation id of CLASS Special
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
			$aChapter->setSpecial( $this->getId() );
			$this->chapters[] = $aChapter;
		}

	/*************************************************
	* @ Persistence of object CLASS Special
	*************************************************/
		/**
		* @ Updates or Inserts CLASS Special information depending
		* @ upon existence of valid primary key.
		**/
		function commit () {
		  $this->title = addslashes($this->title);
		  $this->spanishTitle = addslashes($this->spanishTitle);
		  $this->starring = addslashes($this->starring);
		  $this->description = addslashes($this->description);
			if ($this->id)	{
				$sql  = " UPDATE special SET";
				$sql .=" title = '$this->title',";
				$sql .=" spanishTitle = '$this->spanishTitle',";
				$sql .=" gender = '$this->gender',";
				$sql .=" rated = '$this->rated',";
				$sql .=" starring = '$this->starring',";
				$sql .=" description = '$this->description',";
				$sql .=" PPA = $this->PPA";
				$sql .= " WHERE  id = $this->id";
				db_query($sql);
			}	else	{
				$sql = " INSERT INTO special (  title, spanishTitle, gender, rated, starring, description, PPA ) VALUES ( '$this->title', '$this->spanishTitle', '$this->gender', '$this->rated', '$this->starring', '$this->description', $this->PPA )";
				db_query($sql);
				echo $sql;
				$this->id = db_id_insert();
			}

			$ids = "(0";
			for($i=0; $i<count($this->chapters); $i++){
			   $obj = $this->chapters[$i];
			   $obj->setSpecial( $this->getId() );
			   $obj->commit();
			   $this->chapters[$i]->setId( $obj->getId() );
			   if( $obj->getId() )
			     $ids .= ", " . $obj->getId();
			}
			$ids .= ")";
      if($this->id>0){
  			$sql = "DELETE FROM chapter WHERE and special = '$this->id' AND id NOT IN " . $ids;
  			db_query($sql);        
      }
		}

		/**
		* @ Deletes CLASS Special object from database.
		**/
		function erase () {
			if($this->id){
				$sql = "DELETE FROM special";
				$sql .= " WHERE id = " . $this->id;
				db_query($sql);
			}
			for( $i = 0; $i < count( $this->chapters ); $i++ ){
			   $this->chapters[$i]->erase();
			}
		}

		/**
		* @ Loads CLASS Special attributes from the date base
		* @ and assigns them to CLASS Special's attributes.
		**/
		function load ($aId) {
			$sql = "SELECT * from special";
			$sql .= " WHERE id = " . $aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);
			$this->id = $row['id'];
			$this->title = $row['title'];
			$this->spanishTitle = $row['spanishTitle'];
			$this->gender = $row['gender'];
			$this->rated = $row['rated'];
			$this->starring = $row['starring'];
			$this->description = $row['description'];
			$this->PPA = $row['PPA'];
			if( trim ($this->id) != "" ){
				$sql = "SELECT id from chapter where special = " . $this->id;
				$query = db_query($sql);
				while( $row = db_fetch_array( $query ) ){
			   		$this->chapters[] = new Chapter( $row['id'] );
				}
			}else{
				$sql = "SELECT id from chapter where title = '".$this->title."'";
				$query = db_query($sql);
				while( $row = db_fetch_array( $query ) ){
			   		$this->chapters[] = new Chapter( $row['id'] );
				}
			}
		}
}
?>