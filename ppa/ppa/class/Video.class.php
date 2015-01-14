<?
require_once($path . "class/Image.class.php");
/*************************************************
* @ Video Class definition
* @ author:		German Afanador
* @ created:	February - 11 - 2004 - 14:31:00
* @ modified:	February - 11 - 2004 - 14:31:00
* @ version:	1.0
*************************************************/

class Video {
	/**
	* @ Object var definitions.
	*/
	var $id;			//	PRIMARY KEY  int
	var $name;			//	Video name
	var $description;   //	Video description
	var $dateinf;		//	The date when the video will begin to show
	var $datesup;	    //	The date when the video will stop to show
	var $length;	    //	The length of the video
	var $image;			//  Image relation ID  int
	var $PPA;			//	PPA relation id

	/*************************************************
	* @ Constructor(s) 
	*************************************************/
		/**
		* @ Creates an empty Video Object or filled with values. 
		**/
		function Video ( $aId = false, $aName = false, $aDescription = false, $aDateinf = false, $aDatesup= false , $aLength=false, $aImage=false ,$aPPA = false ) {
			if ($aId)$this->load($aId);
		    if ($aName)$this->name= $aName;
		    if ($aDescription)$this->description= $aDescription;
		    if ($aDateinf)$this->dateinf= $aDateinf;
		    if ($aDatesup)$this->datesup= $aDatesup;
		    if ($aLength)$this->length= $aLength;
		    if ($aImage)$this->image= $aImage;
		    if ($aPPA)$this->PPA= $aPPA;
		}

	/*************************************************
	* @ Analizer(s) of object CLASS Video
	*************************************************/
		/**
		* @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print() {
			echo "<br><b>(Video)</b><br>";
			echo "<ul>";
			echo "<li><b>id: </b> $this->id </li>";
			echo "<li><b>name: </b> $this->name </li>";
			echo "<li><b>Dateinf: </b> $this->dateinf </li>";
			echo "<li><b>Datesup: </b> $this->datesup </li>";
			echo "<li><b>Description: </b> $this->description </li>";
			echo "<li><b>Length: </b> $this->length</li>";
			echo "<li><b>Image: </b> $this->image</li>";
			echo "<li><b>PPA: </b> $this->PPA </li>";
			echo "</ul>";
		}

		/**
		* Returns id of CLASS Video
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
		* Returns dateinf attribute
		**/
		function getDateInf() {
			return $this->dateinf;
		}
		/**
		* Returns dateinf attribute
		**/
		function getDateSup() {
			return $this->datesup;
		}
		/**
		* Returns length attribute
		**/
		function getLength() {
			return $this->length;
		}
		/**
		* Returns image attribute
		**/
		function getImage() {
			return $this->image;
		}
		/**
		* Returns description attribute
		**/
		function getDescription() {
			return $this->description;
		}

		/**
		* Returns ppa attribute
		**/
		function getPPA() {
			return $this->PPA;
		}
		/**
		* Returns an array containing the clients for this video
		**/
		function getClients() {
			$clients = array();
			$sql = "select client from video_client where video = ".$this->getId();
			$query = db_query( $sql );
			while( $row = db_fetch_array( $query ) ){
				$clients[] = $row['client'];
			}	
			return $clients;	
		}
		
		/**
		* adds a client in the client array of the video
		**/
		function addClient( $aClientid ) {
			$sql = "insert into video_client( video, client ) values(".$this->getId().", ".$aClientid.")";
			db_query( $sql );		
		}

		/*************************************************
		 * @ Modifier(s) of object CLASS Video
	*************************************************/
		/**
		* Sets ID of CLASS Video
		**/
		function setId($aId) {
			$this->id = $aId;
		}

		/**
		* Sets name of CLASS Video
		**/
		function setName($aName) {
			$this->name = $aName;
		}
		/**
		* Sets dateinf of CLASS Video
		**/
		function setDateInf($aDateInf) {
			$this->dateinf = $aDateInf;
		}
		/**
		* Sets datesup of CLASS Video
		**/
		function setDateSup($aDateSup) {
			$this->datesup = $aDateSup;
		}
		/**
		* Sets length of CLASS Video
		**/
		function setLength($aLength) {
			$this->length = $aLength;
		}
		/**
		* Sets image of CLASS Video
		**/
		function setImage($aImage) {
			$this->image = $aImage;
		}
		/**
		* Sets description of CLASS Video
		**/
		function setDescription($aDescription) {
			$this->description = $aDescription;
		}
		/**
		* Sets PPA id of CLASS Video
		**/
		function setPPA($aPPA) {
			$this->PPA = $aPPA;
		}
		/*Copies video file to image directory*/
		function copyVideo( $aSource , $aPath ){		  
		  if( copy($aSource, $aPath."/".$this->getId().".mpg" ) ){
				return true;
			}else{
				return false;
			}			
		}
		/*Changes video directory structure for a given dateinf and datesup*/
		function changeDirectories( $aPath, $aClients, $aDateinf, $aDatesup ){
			$clients = array();
			$clients1 = array();
			$clients2 = array();
			$dates = array();
			$clients = $aClients;
			$date1 = $this->getDateInf();
			$date2 = $this->getDateSup();		
			if( strtotime( $date1) <= strtotime( $date2 ) ){
				$dateinf = $date1;
				$datesup = $date2;
			}else{
				$dateinf = $date2;
				$datesup = $date1;
			}
			if( strtotime( $aDateinf ) > strtotime( $aDatesup ) ){
				$temp = $aDateinf;
				$aDateinf = $aDatesup;
				$aDatesup = $temp;
			}

			if(  count( $aClients ) < count( $this->getClients() ) ){
			        $clients1 = $this->getClients();
				for( $i = 0 ; $i < count( $clients1 ); $i++ ){
					if( !in_array( $clients1[$i], $aClients ) ){
						$clients2[] = $clients1[$i];				
					}
				}
				$this->deleteEntries( "output_videos", $clients2, $dateinf, $datesup );				
			}
			if( ( strtotime( $aDateinf ) <= strtotime( $dateinf ) && strtotime( $aDatesup ) <= strtotime( $datesup ) && strtotime( $aDatesup ) <= strtotime( $dateinf ) ) || ( strtotime( $aDateinf ) >= strtotime( $dateinf ) && strtotime( $aDatesup ) >= strtotime( $datesup ) && strtotime( $aDateinf ) >= strtotime( $datesup ) )){
				$this->deleteEntries( "output_videos", $clients, $dateinf, $datesup );				
			}else{
				if( strtotime( $dateinf ) <= strtotime( $aDateinf ) && strtotime( $aDatesup ) <= strtotime( $datesup )  ){
					$this->deleteEntries( "output_videos", $clients, $dateinf, $aDateinf );
					$this->deleteEntries( "output_videos", $clients, $aDatesup, $datesup );
				}				
			}
			$temp_date = strtotime( $aDateinf );
			$dates[] = $aDateinf;
			while( !$centi && ( strtotime( $aDateinf ) != strtotime( $aDatesup ) ) ){
				$temp_date = $temp_date +(60*60*24);
				if( $temp_date  >= strtotime( $aDatesup ) ){
					$dates[] = date("Y-m-d", $temp_date);
					$centi = true;
				}else{
					$dates[] = date("Y-m-d", $temp_date);
				}
			}     
			for( $i = 0; $i < count( $clients ); $i++ ){
				for( $j = 0; $j < count( $dates ); $j++ ){
					$year = date("Y", strtotime( $dates[$j] ) );
					$month = date("m", strtotime( $dates[$j] ) );
					$day = date("d", strtotime( $dates[$j] ) );
					if( !is_dir( $aPath."/".$clients[$i] ) ){
						mkdir( $aPath."/".$clients[$i] );
					}
					if( !is_dir( $aPath."/".$clients[$i]."/".$year ) ){
						mkdir( $aPath."/".$clients[$i]."/".$year );
					}
					if( !is_dir( $aPath."/".$clients[$i]."/".$year."/".$month ) ){
						mkdir( $aPath."/".$clients[$i]."/".$year."/".$month );
					}
					if( !is_dir( $aPath."/".$clients[$i]."/".$year."/".$month."/".$day ) ){
						mkdir( $aPath."/".$clients[$i]."/".$year."/".$month."/".$day );
					}
					$file = file( $aPath."/".$clients[$i]."/".$year."/".$month."/".$day."/videos.txt" );
					$video_in_file = false;
					for( $k = 0 ; $k < count( $file ); $k++ ){
						if( trim( $file[$k] ) != $this->getId() ){
							$videos_id[] = $file[$k];
						}else{
							$video_in_file = true;
						}
					}	
					if( !$video_in_file ){
						$videos_id[] = $this->getId();
						$newfile = "";
						for( $l = 0; $l < count( $videos_id ); $l++ ){
							if( trim( $videos_id[$l] ) != "" ){
								$videos_id[$l] = str_replace( "\n", "", $videos_id[$l] );
								$newfile .= $videos_id[$l]."\n";
							}
						}	
						unlink( $aPath."/".$clients[$i]."/".$year."/".$month."/".$day."/videos.txt" );
						$handle = fopen( $aPath."/".$clients[$i]."/".$year."/".$month."/".$day."/videos.txt", "w+" );
						fwrite( $handle, $newfile );
						fclose( $handle );
					}
					$videos_id = array();
				}
			}    
			return true;
		}
		/*Deletes an entry for this video en each video file in the video directory structure for the video`s dateinf and datesup*/
		function deleteEntries( $aPath, $aClients, $aDateinf, $aDatesup ){
			$videos_id = array();
			$clients = array();
			$dates = array();
			$centi = false;
			$clients = $aClients;
			$date1 = $aDateinf;
			$date2 = $aDatesup;	
		       
			if( strtotime( $aDateinf ) != strtotime( $aDatesup )  ){
				if( strtotime( $date1 ) <= strtotime( $date2 ) ){
					$dateinf = $date1;
					$datesup = $date2;
				}else{
					$dateinf = $date2;
					$datesup = $date1;
				}
				$temp_date = strtotime( $dateinf );
				$dates[] = $dateinf;
				while( !$centi && ( strtotime( $dateinf ) != strtotime( $datesup ) ) ){
					$temp_date = $temp_date +(60*60*24);
					if( $temp_date  >= strtotime( $datesup ) ){
						$dates[] = date("Y-m-d", $temp_date);
						$centi = true;
					}else{
						$dates[] = date("Y-m-d", $temp_date);
					}
				}     
				for( $i = 0; $i < count( $clients ); $i++ ){
					for( $j = 0; $j < count( $dates ); $j++ ){
						$year = date("Y", strtotime( $dates[$j] ) );
						$month = date("m", strtotime( $dates[$j] ) );
						$day = date("d", strtotime( $dates[$j] ) );
						$file = file( $aPath."/".$clients[$i]."/".$year."/".$month."/".$day."/videos.txt" );
						for( $k = 0 ; $k < count( $file ); $k++ ){
							if( trim( $file[$k] ) != $this->getId() ){
								$videos_id[] = $file[$k];
							}
						}
						$newfile = "";
						for( $l = 0; $l < count( $videos_id ); $l++ ){
							if( trim( $videos_id[$l] ) != "" ){
								$videos_id[$l] = str_replace( "\n", "", $videos_id[$l] );
								$newfile .= $videos_id[$l]."\n";
							}
						}		
						unlink( $aPath."/".$clients[$i]."/".$year."/".$month."/".$day."/videos.txt" );
						$handle = fopen( $aPath."/".$clients[$i]."/".$year."/".$month."/".$day."/videos.txt", "w+" );
						fwrite( $handle, $newfile );
						fclose( $handle );
						$videos_id = array();
					}
				}
			 }
			return true;
		}
//Deletes the file for this video;
		function deleteVideofile( $aPath ){
			if( unlink( $aPath."/".$this->getId().".mpg" ) ){
				return true;
			}else{
				return false;		
			}
		}
		/*Changes video directory structure */
		function changeProgramDirectories( $aPath, $aPathvideos, $aClients ){
			$clients = array();
			$dates = array();
			$clients = $aClients;
			$date1 = $this->getDateInf();
			$date2 = $this->getDateSup();		
			if( strtotime( $date1) <= strtotime( $date2 ) ){
				$dateinf = $date1;
				$datesup = $date2;
			}else{
				$dateinf = $date2;
				$datesup = $date1;
			}
			$temp_date = strtotime( $dateinf );
			$dates[] = $dateinf;
			while( !$centi && ( strtotime( $dateinf ) != strtotime( $datesup ) ) ){
				$temp_date = $temp_date +(60*60*24);
				if( $temp_date  >= strtotime( $datesup ) ){
					$dates[] = date("Y-m-d", $temp_date);
					$centi = true;
				}else{
					$dates[] = date("Y-m-d", $temp_date);
				}
			}
			for( $i = 0; $i < count( $clients ); $i++ ){
				for( $j = 0; $j < count( $dates ); $j++ ){
					$year = date("Y", strtotime( $dates[$j] ) );
					$month = date("m", strtotime( $dates[$j] ) );
					$day = date("d", strtotime( $dates[$j] ) );
					$file = file( $aPathvideos."/".$clients[$i]."/".$year."/".$month."/".$day."/videos.txt" );
					for( $k = 0; $k < count( $file ); $k++ ){
						if( trim( $file[$k] ) == $this->getId() ){
							$pos = $k;
						}
					}
					$hour = "00_00";
					for( $k = 0; $k < 48; $k++ ){
						$file = file( $aPath."/".$clients[$i]."/".$year."/".$month."/".$day."/".$hour."/p".($pos+1).".txt" );
						if( ( $pos + 1 ) == 6 || ( $pos + 1 ) == 12 || ( $pos + 1 ) == 18 || ( $pos + 1 ) == 24 || ( $pos + 1 ) == 30 || ( $pos + 1 ) == 36 ){
							$file[0] = (($this->getLength())*1000)."\n";
						}else{
							$file[31] = (($this->getLength())*1000)."\n";
						}
						$newfile = implode( "", $file );
						$handle = fopen( $aPath."/".$clients[$i]."/".$year."/".$month."/".$day."/".$hour."/p".($pos+1).".txt", "w+" );
						fwrite( $handle, $newfile );
						fclose( $handle );		
   						$hour = date( "H_i", strtotime( str_replace( "_", ":", $hour ) ) + (60*30) );				
					}
				}
			}    
			return true;
		}
		/*Changes video directory structure for a given dateinf and datesup*/
		function changeProgramDirectoriesDate( $aPath, $aPathvideos, $aClients, $aDateinf, $aDatesup ){
			$clients = array();
			$dates = array();
			$clients = $aClients;
			$date1 = $aDateinf;
			$date2 = $aDatesup;		
			if( strtotime( $date1) <= strtotime( $date2 ) ){
				$dateinf = $date1;
				$datesup = $date2;
			}else{
				$dateinf = $date2;
				$datesup = $date1;
			}
			$temp_date = strtotime( $dateinf );
			$dates[] = $dateinf;
			while( !$centi && ( strtotime( $dateinf ) != strtotime( $datesup ) ) ){
				$temp_date = $temp_date +(60*60*24);
				if( $temp_date  >= strtotime( $datesup ) ){
					$dates[] = date("Y-m-d", $temp_date);
					$centi = true;
				}else{
					$dates[] = date("Y-m-d", $temp_date);
				}
			}
			for( $i = 0; $i < count( $clients ); $i++ ){
				for( $j = 0; $j < count( $dates ); $j++ ){
					$year = date("Y", strtotime( $dates[$j] ) );
					$month = date("m", strtotime( $dates[$j] ) );
					$day = date("d", strtotime( $dates[$j] ) );
					$file = file( $aPathvideos."/".$clients[$i]."/".$year."/".$month."/".$day."/videos.txt" );
					for( $k = 0; $k < count( $file ); $k++ ){
						if( trim( $file[$k] ) == $this->getId() ){
							$pos = $k;
						}
					}
					$hour = "00_00";
					for( $k = 0; $k < 48; $k++ ){
						$file = file( $aPath."/".$clients[$i]."/".$year."/".$month."/".$day."/".$hour."/p".($pos+1).".txt" );
						if( ( $pos + 1 ) == 6 || ( $pos + 1 ) == 12 || ( $pos + 1 ) == 18 || ( $pos + 1 ) == 24 || ( $pos + 1 ) == 30 || ( $pos + 1 ) == 36 ){
							$file[0] = (($this->getLength())*1000)."\n";
						}else{
							$file[31] = (($this->getLength())*1000)."\n";
						}
						$newfile = implode( "", $file );
						$handle = fopen( $aPath."/".$clients[$i]."/".$year."/".$month."/".$day."/".$hour."/p".($pos+1).".txt", "w+" );
						fwrite( $handle, $newfile );
						fclose( $handle );		
   						$hour = date( "H_i", strtotime( str_replace( "_", ":", $hour ) ) + (60*30) );				
					}
				}
			}    
			return true;
		}
	/*************************************************
	* @ Persistence of object CLASS Video
	*************************************************/
		/**
		* @ Updates or Inserts Channel Object information depending
		* @ upon existence of valid primary key.
		**/
		function commit() {
			if ($this->id) {
				$sql  = " UPDATE video SET";
				$sql .= " name = '$this->name',";
				$sql .= " dateinf = '$this->dateinf',";
				$sql .= " datesup = '$this->datesup',";
				$sql .= " length = '$this->length',";
				$sql .= " image = '$this->image',";
				$sql .= " description = '$this->description',";
				$sql .= " ppa = '1'";
				$sql .= " WHERE  id = $this->id";
				db_query($sql);
			}else{
				$sql = "INSERT INTO video ( name, dateinf, datesup, length, image, description, ppa ) VALUES ( '$this->name', '$this->dateinf', '$this->datesup', '$this->length', '$this->image' , '$this->description', '1' )";
				db_query($sql);
				$this->id = db_id_insert();
			}	
		}

		/**
		* @ Deletes Video Object from database.
		**/
		function erase( $aPathfiles, $aPathvideo, $aPathimage ) {
			if($this->id){
				$sql = "DELETE FROM video";
				$sql .= " WHERE id = " . $this->id;
				db_query($sql);
				$clients = $this->getClients();
				$this->deleteEntries( $aPathfiles, $clients, $this->getDateInf(), $this->getDateSup() );
				$this->deleteVideofile( $aPathvideo );
				$image = new Image( $this->getImage() );
				$image->deleteImagefile( $aPathimage );
				$image->erase();
				$sql = "DELETE FROM video_client WHERE video = ".$this->id;
				db_query($sql);
			}
		}

		/**
		* @ Loads Video Object attributes from the database.
		**/
		function load ($aId) {
			$sql = "SELECT * from video";
			$sql .= " WHERE id = " . $aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->dateinf = $row['dateinf'];
			$this->datesup = $row['datesup'];
			$this->length = $row['length'];
			$this->image = $row['image'];
			$this->description = $row['description'];
		}

}
?>