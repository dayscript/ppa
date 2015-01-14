<?
require_once($path . "class/Slot.class.php");
/*************************************************
* @ Channel Class definition
* @ author:		Juan Carlos Orrego
* @ created:	August - 21 - 2003 - 12:19:05
* @ modified:	Marzo - 19 - 2010 - 12:03:00
* @ version:	1.0
*************************************************/

class Channel {
	/**
	* @ Object var definitions.
	*/
	var $id;			//	PRIMARY KEY  int
	var $name;			//	Channel descriptive name
	var $shortName;		//	Channel short name
	var $group;			//	Channel _group
	var $logo;			//	Image Path
	var $description;	//	Channel description
	var $review;	//	Channel long description
	var $slots;			//	ARRAY of Slots (Deprecated)
	var $default_gmt;			//
	var $PPA;			//	PPA relation id (Deprecated)

	/*************************************************
	* @ Constructor(s)
	*************************************************/
		/**
		* @ Creates an empty Channel Object or filled with values.
		**/
		function Channel ( $aId = false, $aName = false, $aShortName = false, $aLogo = false, $aDescription= false , $aSlots = false, $aPPA = false, $aGroup = false ) {
			if ($aId)$this->load($aId);
		    if ($aName)$this->name= $aName;
		    if ($aShortName)$this->shortName= $aShortName;
		    if ($aLogo)$this->logo= $aLogo;
		    if ($aDescription)$this->description= $aDescription;
		    if ($aSlots)$this->slots= $aSlots;
		    if ($aGroup)$this->group= $aGroup;
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
			echo "<li><b>group: </b> $this->group </li>";
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
		* Returns group attribute
		**/
		function getGroup() {
			return $this->group;
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

		function getReview() {
			return $this->review;
		}

		function getDefaultGmt() {
			return (int)$this->default_gmt;
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
			$sql = "SELECT id from slot where channel = ".$this->id . " AND date like '$aDate%' order by date, time";
			$query = db_query($sql);
			$this->slots = array();
			while( $row = db_fetch_array( $query ) ){
			   $this->slots[] = new Slot( $row['id'] );
			   $slot = new Slot( $row['id'] );
			}
			return $this->slots;
		}
		function getSlotfromTime( $aDate, $aTime ){
		   $date = $aDate;
		     $sql = "select id, time, duration from slot where channel = ".$this->getId()." and date = '".$date."' and time <= '".$aTime."' order by time desc";
		     $query = db_query( $sql );
             if( db_numrows( $query ) > 0 ){
               $row = db_fetch_array( $query );
               $slot = new Slot( $row['id'] );
		       return $slot;
             }else{
		       $date = date( "Y-m-d", strtotime( $date ) - (60*60*24) );
		       $sql = "select id, time, duration from slot where channel = ".$this->getId()." and date = '".$date."' order by time desc";
		       $query = db_query( $sql );
               if( db_numrows( $query ) > 0 ){
                 $row = db_fetch_array( $query );
                 $slot = new Slot( $row['id'] );
		         return $slot;
               }
             }
             $slot = new Slot();
		     return $slot;
         
		}
		function getSlotsfromlastweek( $year, $month ){
		  $slots = array();
		  if((($year % 4)==0) && (($year % 100)!=0) || (($year % 400)==0)){
		    $leap = true;
		  }else{
		    $leap = false;
		  }
		  
		  if ( $month=='1' || $month=='3' || $month=='5' || $month=='7'|| $month=='8' || $month=='10' || $month=='12'){
		    $days=31;
		  }else{
		    if($month=='4'|| $month=='6' || $month=='9' || $month=='11'){
		      $days=30;
		    }else{
		      if($month=='2'){
			if($leap){
			  $days=29;
			}else{
			  $days=28;
			}
		      }
		    }
		  }
		  $datesup = $year."-".$month."-".$days;
		  $dateinf = $year."-".$month."-".($days-6);
		  $sql = "select id from slot where date >= '".$dateinf."' and date <= '".$datesup."' and channel = ".$this->getId()." order by date,time asc";
	     $query = db_query( $sql );
        while( $row = db_fetch_array( $query ) ){
		    $slot = new Slot( $row['id'] );
		    $slots[] = $slot;
		  }
		  return $slots;
		  
		}
		/**
		* Returns slots array
		**/
		function getSlotsbetweenTimes( $aDate, $aTime ) {
		  for( $i = 0; $i < 3; $i++ ){
		    $slots[] = $this->getSlotfromTime( $aDate, $aTime );
		    $temp = date("H:i", strtotime( $aTime ) + (60*30) );
		    if(  strtotime( $temp ) < strtotime( $aTime ) ){
		      $aDate = date( "Y-m-d", strtotime( $aDate ) + (60*60*24) );
		    }
		    $aTime = $temp;
		  }
		  return $slots;
		}
		/**
		* Returns slots array
		**/
		function getSlotsBetweenDates( $aDateinf = false, $aDatesup = false ) {
		    if( $aDateinf && $aDatesup ){
   			  $sql = "SELECT id from slot where channel = ".$this->id . " AND date >= '$aDateinf' AND date <= '$aDatesup' order by date, time";
			}else{
			  if( $aDateinf && !$aDatesup ){
    			  $sql = "SELECT id from slot where channel = ".$this->id . " AND date like '$aDateinf%' order by date, time";
			  }else{
			    if( !$aDateinf && $aDatesup ){
    			  $sql = "SELECT id from slot where channel = ".$this->id . " AND date like '$aDatesup%' order by date, time";
				}else{
				   if( !$aDateinf && !$aDatesup ){
    			    $sql = "SELECT id from slot where channel = ".$this->id . " order by date, time";
				   }
				}
			  }
			}
			$query = db_query($sql);
			$this->slots = array();
			while( $row = db_fetch_array( $query ) ){
			   $this->slots[] = new Slot( $row['id'] );
			}
			return $this->slots;
		}
		/**
		* Returns the id slots array
		**/
		function getSlotsBetweenDatesId( $aDateinf = false, $aDatesup = false ) {
		    if( $aDateinf && $aDatesup ){
   			  $sql = "SELECT id from slot where channel = ".$this->id . " AND date >= '$aDateinf' AND date <= '$aDatesup' order by date, time";
			}else{
			  if( $aDateinf && !$aDatesup ){
    			  $sql = "SELECT id from slot where channel = ".$this->id . " AND date like '$aDateinf%' order by date, time";
			  }else{
			    if( !$aDateinf && $aDatesup ){
    			  $sql = "SELECT id from slot where channel = ".$this->id . " AND date like '$aDatesup%' order by date, time";
				}else{
				   if( !$aDateinf && !$aDatesup ){
    			    $sql = "SELECT id from slot where channel = ".$this->id . " order by date, time";
				   }
				}
			  }
			}
			$query = db_query($sql);
			$slotsId = array();
			while( $row = db_fetch_array( $query ) ){
			   $slotsId[] = $row['id'];
			}
			return $slotsId;
		}
		function getChaptersInYearMonth( $year, $month ){
		  $chapters = array();
		  $days = date( "j", mktime(0, 0, 0, $month + 1, 1, $year)  - 1 );
		  $sql = "SELECT  C.id FROM slot S, slot_chapter SC, chapter C WHERE S.channel = ".$this->getId()." AND S.date >= '".$year."-".$month."-01"."' AND S.date <=  '".$year."-".$month."-".$days."' AND SC.slot = S.id AND SC.chapter = C.id group by C.id order by C.title";
		  $query = db_query( $sql );
		  while( $row = db_fetch_array( $query ) ){
		    $chapters[] = new Chapter( $row['id'] );
		  }
		  return $chapters;
		}
        function getChaptersInDate( $aDate ){
		  $chapters = array();
		  $sql = "SELECT c1.id FROM channel c, slot s, slot_chapter sc, chapter c1 WHERE c.id =".$this->getId()." AND s.channel = c.id AND sc.slot = s.id AND sc.chapter = c1.id AND s.date =  '".$aDate."'";
		  $query = db_query( $sql );
		  while( $row = db_fetch_array( $query ) ){
		    $chapters[] = new Chapter( $row['id'] );
		  }
		  return $chapters;
        }
	    function getNumSlotsInYearMonth( $year, $month ){
		   $sql = "SELECT count(*) FROM slot WHERE channel = ".$this->getId()." AND date LIKE  '".$year."-".$month."%'"   ;
		   $query = db_query( $sql );
		   $row = db_fetch_array( $query );
		   return $row[0];
		}
		/*From an hour string where the hours are separated by \t, and a date, it returns a slot array representing those hours*/
		function createSlotsfromHours( $hour_string, $date ){
		  $slot_array = array();
		  $hour_array = explode( "\t", $hour_string );
		  		
		  for( $i = 0; $i < count( $hour_array ); $i++ ){
		    if( isset( $hour_array[$i+1] ) ){
		      $hourinf = strtotime( $hour_array[$i] );
		      $hoursup = strtotime( $hour_array[$i+1] );
		      $dif = $hoursup - $hourinf;
		      $mins = date( "i", $dif-1 );
		      $mins += 1;
		      $slot = new Slot( false, $hour_array[$i], $date, $mins, $this->getId() );
		    }else{
		      $slot = new Slot( false, $hour_array[$i], $date, false, $this->getId() );
		    }
		    $slot_array[] = $slot;
		  }
		  return $slot_array;
		}
		/*From a given month and year ( $month, $year ) it returns the slots the last seven days in that month and year
		  duplicated through out a given month and year ( $month1, $year1 )
		*/
		function createSlotsfromMonthYear( $month, $year, $month1, $year1 ){
		  $slots1 = array();
		  $slots = $this->getSlotsfromlastweek($year, $month);
		  if((($year1 % 4)==0) && (($year1 % 100)!=0) || (($year1 % 400)==0)){
		    $leap = true;
		  }else{
		    $leap = false;
		  }
		  
		  if ( $month1=='1' || $month1=='3' || $month1=='5' || $month1=='7'|| $month1=='8' || $month1=='10' || $month1=='12'){
		    $days=31;
		  }else{
		    if($month1=='4'|| $month1=='6' || $month1=='9' || $month1=='11'){
		      $days=30;
		    }else{
		      if($month1=='2'){
			if($leap){
			  $days=29;
			}else{
			  $days=28;
			}
		      }
		    }
		  }
		  $first_day = date("w", strtotime( $year1."-".$month1."-01" ) );
		  
		  for( $i = 0 ; $i < count( $slots ); $i++ ){
		    $day =  date("w", strtotime( $slots[$i]->getDate() ) );
		    if( $day == $first_day ){
		      $starting_point = $i;
		      $i = count( $slots )+1;
		    }
		  }
		  $date = date("Y-m-d", strtotime( $year1."-".$month1."-01" ) );
		  if( count( $slots ) > 0){
  	        $date1 = $slots[0]->getDate();
		  }
		  $centi = false;
		  $stop = false;
		  $day_count = 1;
		  for( $i = 0; $i < count( $slots ); $i++ ){
		     if( strtotime( $date1 ) != strtotime( $slots[$i]->getDate() ) ){
			    $date = date("Y-m-d", strtotime( $date )+(60*60*24));
			    $date1 = $slots[$i]->getDate();
				$day_count++;
				if( $day_count > $days ){
				  $stop = true;
				  $i = count( $slots )+1;
				}
			 }
			 if( !$stop ){
		       if( $i >= $starting_point && !$centi ){
			      $slot = new Slot();
				  $slot->setDate( $date );
  				  $slot->setTime( $slots[$i]->getTime() );
				  $slot->setDuration( $slots[$i]->getDuration() );
  				  $slot->setChapters( $slots[$i]->getChapters() );
  				  $slot->setChannel( $slots[$i]->getChannel() );
  				  $slot->setTitle( $slots[$i]->getTitle() );
				  $slots1[] = $slot;
				  $centi = true;
			   }else{
			     if( $centi ){
			      $slot = new Slot();
				  $slot->setDate( $date );
  				  $slot->setTime( $slots[$i]->getTime() );
				  $slot->setDuration( $slots[$i]->getDuration() );
  				  $slot->setChapters( $slots[$i]->getChapters() );
  				  $slot->setChannel( $slots[$i]->getChannel() );
  				  $slot->setTitle( $slots[$i]->getTitle() );
				  $slots1[] = $slot;
			     }
		       }
			 }
			 if( $i+1 == count( $slots ) && $day_count < $days ){
			   $i = 0;
			 }
		  }
		  return $slots1;
		}
		function slotExists( $aSlot ){
		  if( is_object( $aSlot ) ){
		    $date = $aSlot->getDate();
		    $time = $aSlot->getTime();
		    $sql = "select id from slot where channel = ".$this->getId()." and date = '".$date."' and time = '".$time."'";
		    $query = db_query( $sql );
		    if( db_numrows($query) > 0 ){
		      return true;
		    }else{
		      return false;
		    }
		  }
		  return false;
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
		* Sets group of CLASS Channel
		**/
		function setGroup($aGroup) {
			$this->group = $aGroup;
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

		function setReview($r) {
			$this->review = $r;
		}

		function setDefaultGmt($gmt) {
			$this->default_gmt = (int)$gmt;
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
				$sql .= " review = '$this->review',";
				$sql .= " default_gmt = '$this->default_gmt',";
				$sql .= " ppa = '1'";
				$sql .= " WHERE  id = $this->id";
				db_query($sql);
			}else{
				if( $this->name != "" )
				{
					$sql = "INSERT INTO channel ( name, shortName, logo, description, review, default_gmt, ppa ) VALUES ( '$this->name', '$this->shortName', '$this->logo', '$this->description', '$this->review', '$this->default_gmt', '1' )";
					db_query($sql);
					$this->id = db_id_insert();
				}
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
			if($this->id){
				$sql = "DELETE FROM channel";
				$sql .= " WHERE id = " . $this->id;
				db_query($sql);
			}
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
			$this->group = $row['_group'];
			$this->logo = $row['logo'];
			$this->description = $row['description'];
			$this->review = $row['review'];
			$this->default_gmt = $row['default_gmt'];
			$this->slots = array();
		}

  //Amaury

  function getSlotsByDateTime( $date, $timeStart, $timeFinal ) {
    $query = db_query( "select id from slot where channel = $this->id and date = '$date' and time >= '" . $timeStart .
		       "' and time < '" . $timeFinal . "' order by time" );
    while( $row = db_fetch_array( $query ) ){
      $slots[] = $row['id'];
    }
    return $slots;
  }
  function getIdSlotsByDate( $date ){
    $result = db_query( "select id from slot where channel = $this->id and date = '$date' order by time" );
    while( $row = db_fetch_array( $result ) )
      $slots[] = $row['id'];
    return $slots;
  }

  function getIdChaptersBySlot( $listSlot ){
    $result = db_query( "select chapter from slot_chapter where slot in ( $listSlot )" );
    while( $row = db_fetch_array( $result ) )
      $chapters[] = $row['chapter'];
    return $slots;
  }

  function getSpanishTitleChapters( $listSlot ){
    $result = db_query( "select chapter from slot_chapter where slot in ( $listSlot )" );
    while( $row = db_fetch_array( $result ) )
      $chapters[] = $row['chapter'];
    return $slots;
  }
  
  function getProgramsDay( $date ){
    $result = db_query( "select a.time, c.spanishTitle from slot a, slot_chapter b, chapter c where ".
			"a.channel = $this->id and a.date = '$date' and a.id = b.slot and b.chapter = c.id order by a.time" );
    while( $row = db_fetch_array( $result ) )
      $programs[] = array( "hour" => $row[0], "title" => $row[1] ) ;
    return $programs;
  }

  function getProgramsMonth( $date, $group ){
    $result = db_query( "select a.date, a.time, c.spanishTitle, a.channel  from slot a, slot_chapter b, chapter c where ".
			"a.channel in ( $group ) and a.date like '$date-%' and a.id = b.slot and b.chapter = c.id order by a.date, a.time ");
    while( $row = db_fetch_array( $result ) )
      $programs[ $row[0] ][ $row[3] ][ strtok( $row[1], ":" ) % 24 ][] = array( "hour" => $row[1], "title" => $row[2] ) ;
    return $programs;
  }


  function getProgramsMonthGroup( $year, $month ){
    $result = db_query( "select a.time, c.spanishTitle from slot a, slot_chapter b, chapter c where ".
			"a.channel = $this->id and a.date = '$date' and a.id = b.slot and b.chapter = c.id order by a.time");
    while( $row = db_fetch_array( $result ) )
      $programs[] = array( "hour" => $row[0], "title" => $row[1] ) ;
    return $programs;
  }

  function getProgramBeforeDay( $date, $group ){
    $result = db_query( "select a.time, c.spanishTitle from slot a, slot_chapter b, chapter c where ".
			"a.channel = " . ( $channel ? $channel : $this->id )  . " and a.date = '" .
			date ( "Y-m-d", strtotime ( $date )-( 60*60*24 ) ).
			"' and a.id = b.slot and b.chapter = c.id order by a.time DESC" );
    return array( "hour" => "00:00:00", "title" => " ( Cont )"  ) ;
  }
  
  function showProgramming( $date ){
    $programs = $this->getProgramsDay( $date );
    for( $i = 0, $hour = 0 ; $i < 24 ; $i++  ){
      for( $j = $hour; $j < count( $programs ) ; $j++  )
	if( strtok( $programs[$j]['hour'], ":" ) == $i )
	  $programHour[] = $programs[$j];
	else
	  break;
      $hour = $j;
      if ( $i == 0 &&  !$programHour[0] )
	$programHour[] = $this->getProgramBeforeDay( $date );
      $html .= ( $programHour[0] ? htmlHourContent( $programHour ) : htmlHourContent(  )  ) ;
      $programHour = "";
    }
    return $html;
  }
  function showProgramming2( $yearMonth, $group ){
    $programs = $this->getProgramsMonth( $yearMonth, implode( ",", $group ) );
    $colspan = 1;
    for( $i = 1; $i <= count( $programs ); $i++  ){
      $date = $yearMonth . "-" .( $i < 10 ? "0" : "" ). $i;
      $html .= "<tr><td colspan ='25'>" . $date . "</td></tr>".
	"<tr><td>Canales</td>" . htmlHourHead( 24 ) . "</tr>";
      for( $j = 0; $j < count( $programs[ $date ] ) ; $j++  ){
	$html .= "<tr><td>Canal " . $group[ $j ] . "</td>";
	for( $k = 23; $k >= 0 ; $k-- ){
	  if( $programs[ $date ][ $group[$j] ][ $k ] ){
	    $content = htmlHourContent( $programs[ $date ][$group[$j]][$k], $colspan ) . $content  ;
	    if ( !$lastContent[$date][$group[$j]] )
	      $lastContent[$date][$group[$j]] = $programs[ $date ][$group[$j]][$k][count( $programs[ $date ][$group[$j][$k]] ) ];
	    $colspan = 1;
	  }else if( $k == 0 ){
	    $content = htmlHourContent( array( array( "hour" => "00:00", "title" => $lastContent[ date("Y-m-d", strtotime( $date)  - ( 3600*24 ) )][$group[$j]]['title']." ( Cont )" ) ), $colspan ) . $content;
	  }else{
	    $colspan ++;
	  }
	}
	$html .= $content . "</tr> ";
	$content = "";
      }
    }
    return $html;
  }
  function showProgramming3( $yearMonth, $group ){
    $programs = $this->getProgramsMonth( $yearMonth, implode( ",", $group ) );
    $colspan = 1;
    for( $i = 1; $i <= count( $programs ); $i++  ){
      $date = $yearMonth . "-" .( $i < 10 ? "0" : "" ). $i;
      $html .= "<tr><td colspan ='26'>" . $date . "</td></tr>".
	"<tr><td>Canales</td>" . htmlHourHead( 12 ) . "<td>&nbsp</td>" . htmlHourHead( 12 ) . "</tr>";
      for( $j = 0; $j < count( $programs[ $date ] ) ; $j++  ){
	$html .= "<tr><td>" . nameChannel( $group[ $j ] ) . "</td>";
	$colspan = 1;
	for( $k = 11; $k >= 0 ; $k-- ){
	  if( $programs[ $date ][ $group[$j] ][ $k ] ){
	    $contentAm = htmlHourContent( $programs[ $date ][$group[$j]][$k], $colspan ) . $contentAm  ;
	    if( !$middleContent[$date][$group[$j]] )
	      $middleContent[$date][$group[$j]] = $programs[ $date ][$group[$j]][$k][count( $programs[ $date ][$group[$j][$k]] ) ];
	    $colspan = 1;
	  }else if( $k == 0 ){
	    $contentAm = htmlHourContent( array( array( "hour" => "00:00", "title" => $lastContent[ date("Y-m-d", strtotime( $date)  - ( 3600*24 ) )][$group[$j]]['title']." ( Cont )" ) ), $colspan ) . $contentAm;
	  }else{
	    $colspan ++;
	  }
	}
	$colspan = 1;
	for( $k = 23; $k >= 12 ; $k-- ){
	  if( $programs[ $date ][ $group[$j] ][ $k ] ){
	    $contentPm = htmlHourContent( $programs[ $date ][$group[$j]][$k], $colspan ) . $contentPm  ;
	    if ( !$lastContent[$date][$group[$j]] )
	      $lastContent[$date][$group[$j]] = $programs[ $date ][$group[$j]][$k][count( $programs[ $date ][$group[$j][$k]] ) ];
	    $colspan = 1;
	  }elseif( $k == 12 ){
	    $contentPm = htmlHourContent( array( array( "hour" => "", "title" => $middleContent[$date][$group[$j]]['title']." ( Cont )" ) ), $colspan ) . $contentPm;
	  }else{
	    $colspan ++;
	  }
	}
	$html .= $contentAm . "<td>&nbsp;</td>" . $contentPm . "</tr> ";
	$contentAm = $contentPm = "";
      }
    }
    return $html;
  }
}
?>