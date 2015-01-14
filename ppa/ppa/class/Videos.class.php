<?
require_once($path . "class/Video.class.php");
/*************************************************
* @ Videos Class definition
* @ author:		Germán Afanador
* @ created:	February - 12 - 2004 - 10:18:05
* @ modified:	February - 12 - 2004 - 10:18:05
* @ version:	1.0
*************************************************/

class Videos {
	/**
	* @ Object var definitions.
	*/
	var $videos;		//	Videos Array
	var $client;		//  Client Relation id


	/*************************************************
	 * @ Constructor(s) 
	*************************************************/
		/**
		* @ Creates an empty Videos Object. 
		**/
		function Videos( $aClient = false ) {
			$this->videos= array();
			if($aClient) $this->client = $aClient;
		}

	/*************************************************
	* @ Analizer(s) of object CLASS Videos
	*************************************************/
		/**
		* @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print() {
			echo "<br><b>(Videos)</b><br>";
			echo "<ul>";
			echo "<li><b>Client: </b> $this->client </li>";
			echo "<li><b>Videos[]: </b> ";
			for( $i = 0; $i < count( $this->videos ); $i++ ){
			   $video = $this->videos[$i];
			   $video->_print();
			}
			echo "</li>";
			echo "</ul>";
		}
		/**
		* Returns client attribute
		**/
		function Client() {
			return $this->client;
		}

		/**
		* Returns Videos array
		**/
		function getVideos() {
			return $this->Videos;
		}
	/*************************************************
	* @ Modifier(s) of object CLASS Videos
	*************************************************/

		/**
		* Sets client of CLASS Videos
		**/
		function setClient($aClient) {
			$this->client = $aClient;
		}
		/**
		* Sets Videos array
		**/
		function setVideos($aVideos) {
			$this->Videos = $aVideos;
		}
		
		/**
		* ADDS a video to Videos array
		**/
		function addVideo($aVideo) {
			$this->videos[] = $aVideo;
		}


	/*************************************************
	* @ Persistence of object CLASS Videos
	*************************************************/
		/**
		* @ Updates Client - Videos relations
		**/
		function commit() {
			$sql  = " DELETE from client_video WHERE client = $this->client";
			db_query($sql);
			for($i=0; $i<count($this->videos); $i++){
				if($this->videos[$i]->getId()){
					$sql = "INSERT INTO client_video (client, video) VALUES ('$this->client','" . $this->videos[$i]->getId() . "')";
					db_query($sql);
				}
			}
		}

		/**
		* @ Deletes Videos Object from database.
		**/
		function erase() {
			$sql = "DELETE FROM client_video ";
			$sql .= " WHERE client = " . $this->client;
			db_query($sql);
		}
}
?>