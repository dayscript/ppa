<?
require_once($path . "class/Chapters.class.php");
	/*************************************************
	* @ Slot Object definition
	* @ author:		Juan Carlos Orrego
	* @ created:	August - 22 - 2003 - 7:18:49
	* @ modified:	August - 22 - 2003 - 7:18:49
	* @ version:	1.0
	*************************************************/


	class Slot	{
		/**
		* @ Object var definitions.
		**/
		var $id;			//	ID  int
		var $time;			//	Start Hour  string
		var $date;			//	Calendar Date  string
		var $duration;		//	Slot Duration in minutes  int
		var $chapters;		//	Chapters Container
		var $channel;		//	Channel relation ID  int
		var $title;             //      Title for the slot


	/*************************************************
	* @ Constructor(s) of object CLASS SLOT
	*************************************************/
		/**
		* @ Creates an empty CLASS SLOT object or filled with values. 
		**/
		function Slot ( $aId = false, $aTime = false, $aDate = false, $aDuration = false, $aChannel = false, $aTitle = false )	{
			if ($aId)$this->load($aId);
			if ($aTime)$this->time = $aTime;
			if ($aDate)$this->date = $aDate;
			if ($aDuration)$this->duration = $aDuration;
		    if ($aChapters)$this->chapters= $aChapters;
			else if(!isset($this->chapters)) $this->chapters= new Chapters($aId);
			if ($aChannel)$this->channel = $aChannel;
			if ($aTitle)$this->title = $aTitle;
		}

	/*************************************************
	* @ Analizer(s) of object CLASS SLOT
	*************************************************/
		/**
		* @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print()	{
			echo "<br><b>(Slot)</b><br>";
			echo "<ul>";
			echo "<li><b>ID: </b> $this->id </li>";
			echo "<li><b>Start Hour: </b> $this->time </li>";
			echo "<li><b>Calendar Date: </b> $this->date </li>";
			echo "<li><b>Slot Duration in minutes: </b> $this->duration </li>";
			echo "<li><b>chapters[]: </b> ";
			   $this->chapters->_print();
			echo "</li>";
			echo "<li><b>Channel relation ID: </b> $this->channel </li>";
			echo "<li><b>Title</b> $this->title </li>";
			echo "</ul>";
		}

		/**
		* Returns ID of CLASS SLOT
		**/
		function getId()	{
			return $this->id;
		}
		/**
		* Returns Start Hour of CLASS SLOT
		**/
		function getTime()	{
			return $this->time;
		}
		/**
		* Returns Calendar Date of CLASS SLOT
		**/
		function getDate()	{
			return $this->date;
		}
		/**
		* Returns Slot Duration in minutes of CLASS SLOT
		**/
		function getDuration()	{
			return $this->duration;
		}
		/**
		* Returns Channel relation ID of CLASS SLOT
		**/
		function getChannel()	{
			return $this->channel;
		}
		/**
		* Returns the title of  CLASS SLOT
		**/
		function getTitle()	{
			return $this->title;
		}
		/**
		* Returns Channel relation ID of CLASS SLOT
		**/
		function getChapters()	{
			return $this->chapters;
		}
	/*************************************************
	* @ Modifier(s) of object CLASS SLOT
	*************************************************/
		/**
		* Sets ID of CLASS SLOT
		**/
		function setId($aId)	{
			$this->id = $aId;
		}
		/**
		* Sets Start Hour of CLASS SLOT
		**/
		function setTime($aTime)	{
			$this->time = $aTime;
		}
		/**
		* Sets Calendar Date of CLASS SLOT
		**/
		function setDate($aDate)	{
			$this->date = $aDate;
		}
		/**
		* Sets Slot Duration in minutes of CLASS SLOT
		**/
		function setDuration($aDuration)	{
			$this->duration = $aDuration;
		}
		/**
		* Sets Slot title of CLASS SLOT
		**/
		function setTitle($aTitle)	{
			$this->title = $aTitle;
		}
		/**
		* Sets chapters container
		**/
		function setChapters($aChapters) {
			$aChapters->setSlot( $this->getId() );
			$this->chapters = $aChapters;
		}
		/**
		* ADDS a chapter to chapters container
		**/
		function addChapter($aChapter, $aOrder = 0 ) {
			$this->chapters->addChapter( $aChapter, $aOrder );
		}
		/**
		* Sets Channel relation ID of CLASS SLOT
		**/
		function setChannel($aChannel)	{
			$this->channel = $aChannel;
		}
		/**
		* Moves the slot for a given hour
		**/
		function moveSlot($aHoras){
	       $time = $this->getTime();
           $time_stamp = strtotime( $time ) + $aHoras*(60*60);
           $newtime = date( "H:i", $time_stamp );  
           $this->setTime( $newtime );
           if( $aHoras < 0 ){
             if( strtotime( $time ) < strtotime( $newtime ) ){
               $date = $this->getDate();
               $date_stamp = strtotime( $date ) - (60*60*24);
	           $newdate = date( "Y-m-d", $date_stamp );
               $this->setDate( $newdate); 
             }
           }else{
             if( $aHoras > 0 ){
               if( strtotime( $time ) > strtotime( $newtime ) ){
                 $date = $this->getDate();
                 $date_stamp = strtotime( $date ) + (60*60*24);
	             $newdate = date( "Y-m-d", $date_stamp );
                 $this->setDate( $newdate); 
                }
             }
           }	 
		}		
		
	/*************************************************
	* @ Persistence of object CLASS SLOT
	*************************************************/
		/**
		* @ Updates or Inserts CLASS SLOT information depending
		* @ upon existence of valid primary key.
		**/
		function commit ()	{
			if ($this->id)	{
				$sql  = " UPDATE slot SET";
				$sql .=" time = '$this->time',";
				$sql .=" date = '$this->date',";
				$sql .=" duration = $this->duration,";
				$sql .=" channel = $this->channel,";
				$sql .=" title = $this->title";
				$sql .= " WHERE  id = $this->id";
				db_query($sql);
			    $this->chapters->setSlot( $this->getId() );
			    $this->chapters->commit();
			}	else	{
				$sql = " INSERT INTO slot (  time, date, duration, channel, title ) VALUES ( '$this->time', '$this->date', '$this->duration', '$this->channel', '$this->title' )";
				db_query($sql);
				$this->id = db_id_insert();			       
				$this->chapters->setSlot( $this->getId() );
				$this->chapters->commit();   
			}
		}
		/**
		* @ Deletes CLASS SLOT object from database.
		**/
		function erase ()	{
			if($this->id){
				$sql = "DELETE FROM slot";
				$sql .= " WHERE id = " . $this->id;
				db_query($sql);
				$sql = "DELETE FROM slot_chapter WHERE slot = ".$this->id;
				db_query($sql);				
			}
		}

		/**
		* @ Loads CLASS SLOT attributes from the date base
		* @ and assigns them to CLASS SLOT's attributes.
		**/
		function load ($aId)	{
			$sql = "SELECT * from slot";
			$sql .= " WHERE id = " . $aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);
			$this->id = $row['id'];
			$this->time = $row['time'];
			$this->date = $row['date'];
			$this->duration = $row['duration'];
			$this->channel = $row['channel'];
			$this->title = $row['title'];
			$sql = "SELECT chapter, _order from slot_chapter where slot = " . $this->id;
			$query = db_query($sql);
			if( db_numrows( $query ) > 0 ){
			  while( $row = db_fetch_array( $query ) ){
			    $ch = new Chapter( $row['chapter'] );
			    $this->chapters = new Chapters();
			    $this->chapters->addChapter( $ch, $row['_order'] );
			  }
			}else{
			  $ch = new Chapter();
			  $this->chapters = new Chapters();
			  $this->chapters->addChapter( $ch, 0 );
			}
		}
}
?>