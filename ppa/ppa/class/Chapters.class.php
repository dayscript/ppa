<?
require_once($path . "class/Chapter.class.php");
/*************************************************
* @ Chapters Class definition
* @ author:		Juan Carlos Orrego
* @ created:	August - 21 - 2003 - 12:19:05
* @ modified:	August - 21 - 2003 - 12:19:05
* @ version:	1.0
*************************************************/

class Chapters {
	/**
	* @ Object var definitions.
	*/
	var $chapters;		//	Chapters Array
	var $orders;		//	Orders Array
	var $slot;			//  Slot Relation id


	/*************************************************
	 * @ Constructor(s) 
	*************************************************/
		/**
		* @ Creates an empty Chapters Object. 
		**/
		function Chapters( $aSlot = false ) {
			$this->chapters= array();
			if($aSlot) $this->slot = $aSlot;
		}

	/*************************************************
	* @ Analizer(s) of object CLASS Chapters
	*************************************************/
		/**
		* @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print() {
			echo "<br><b>(Chapters)</b><br>";
			echo "<ul>";
			echo "<li><b>Slot: </b> $this->slot </li>";
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
		* Returns orders array
		**/
		function getOrders() {
			return $this->orders;
		}

		/**
		* Returns slot attribute
		**/
		function Slot() {
			return $this->slot;
		}

		/**
		* Returns chapters array
		**/
		function getChapters() {
			return $this->chapters;
		}

	/*************************************************
	* @ Modifier(s) of object CLASS Chapters
	*************************************************/

		/**
		* Sets slot of CLASS Chapters
		**/
		function setSlot($aSlot) {
		  $this->slot = $aSlot;
		}
		
		/**
		* Sets orders array
		**/
		function setOrders($aOrders) {
			$this->orders = $aOrders;
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
		function addChapter($aChapter, $aOrder = 0 ) {
			$this->chapters[] = $aChapter;
			$this->orders[] = $aOrder;
		}


	/*************************************************
	* @ Persistence of object CLASS Chapters
	*************************************************/
		/**
		* @ Updates Slot - Chapters relations
		**/
		function commit() {
			$sql  = " DELETE from slot_chapter WHERE slot = $this->slot";
			db_query($sql);
			for($i=0; $i<count($this->chapters); $i++){
				if($this->chapters[$i]->getId()){
					$sql = "INSERT INTO slot_chapter (slot, chapter, _order) VALUES ('$this->slot','" . $this->chapters[$i]->getId() . "','" . $this->orders[$i] . "')";
					db_query($sql);
				}
			}
		}

		/**
		* @ Deletes Chapters Object from database.
		**/
		function erase() {
			$sql = "DELETE FROM slot_chapter ";
			$sql .= " WHERE slot = " . $this->slot;
			db_query($sql);
		}

		/**
		* @ Loads Chapters Object attributes from the database.
		**/
		function load ($aId) {
		}
}
?>