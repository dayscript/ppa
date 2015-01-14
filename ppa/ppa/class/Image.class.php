<?
/*************************************************
* @ Image Class definition
* @ author:		German Afanador
* @ created:	February - 11 - 2004 - 14:31:00
* @ modified:	February - 11 - 2004 - 14:31:00
* @ version:	1.0
*************************************************/

class Image {
	/**
	* @ Object var definitions.
	*/
	var $id;			//	PRIMARY KEY  int
	var $name;			//	Image name
	var $description;   //	Image description
	var $width;		    //	Image width
	var $height;	    //	Image height
	var $PPA;			//	PPA relation id

	/*************************************************
	* @ Constructor(s) 
	*************************************************/
		/**
		* @ Creates an empty Image Object or filled with values. 
		**/
		function Image ( $aId = false, $aName = false, $aDescription = false, $aWidth = false, $aHeight= false, $aPPA = false ) {
			if ($aId)$this->load($aId);
		    if ($aName)$this->name= $aName;
		    if ($aDescription)$this->description= $aDescription;
		    if ($aWidth)$this->width= $aWidth;
		    if ($aHeight)$this->height= $aHeight;
		    if ($aPPA)$this->PPA= $aPPA;
		}

	/*************************************************
	* @ Analizer(s) of object CLASS Image
	*************************************************/
		/**
		* @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print() {
			echo "<br><b>(Image)</b><br>";
			echo "<ul>";
			echo "<li><b>id: </b> $this->id </li>";
			echo "<li><b>name: </b> $this->name </li>";
			echo "<li><b>Description: </b> $this->description </li>";
			echo "<li><b>Width: </b> $this->width</li>";
			echo "<li><b>Height: </b> $this->height</li>";
			echo "<li><b>PPA: </b> $this->PPA </li>";
			echo "</ul>";
		}

		/**
		* Returns id of CLASS Image
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
		* Returns description attribute
		**/
		function getDescription() {
			return $this->description;
		}
		/**
		* Returns width attribute
		**/
		function getWidth() {
			return $this->width;
		}
		/**
		* Returns Height attribute
		**/
		function getHeight() {
			return $this->height;
		}
		/**
		* Returns ppa attribute
		**/
		function getPPA() {
			return $this->PPA;
		}

		/*************************************************
		 * @ Modifier(s) of object CLASS Image
	*************************************************/
		/**
		* Sets ID of CLASS Image
		**/
		function setId($aId) {
			$this->id = $aId;
		}

		/**
		* Sets name of CLASS Image
		**/
		function setName($aName) {
			$this->name = $aName;
		}
		/**
		* Sets description of CLASS Image
		**/
		function setDescription($aDescription) {
			$this->decription = $aDescription;
		}
		/**
		* Sets width of CLASS Image
		**/
		function setWidth($aWidth) {
			$this->width = $aWidth;
		}
		/**
		* Sets height of CLASS Image
		**/
		function setHeight($aHeight) {
			$this->height = $aHeight;
		}
		/**
		* Sets PPA id of CLASS Image
		**/
		function setPPA($aPPA) {
			$this->PPA = $aPPA;
		}
		/*Copies image file to image directory*/
		function copyImage( $aSource, $aPath ){
			if( copy($aSource, $aPath."/".$this->getId().".jpg" ) ){
				$size_array = getimagesize( $aPath."/".$this->getId().".jpg" );
				$this->setWidth( $size_array[0] );
				$this->setHeight( $size_array[1] );
				return true;
			}else{
				return false;
			}
		}
//Deletes the file for this video;
		function deleteImagefile( $aPath ){
			if( unlink( $aPath."/".$this->getId().".jpg" ) ){
				return true;
			}else{
				return false;		
			}
		}
	/*************************************************
	* @ Persistence of object CLASS Image
	*************************************************/
		/**
		* @ Updates or Inserts Image Object information depending
		* @ upon existence of valid primary key.
		**/
		function commit() {
			if ($this->id) {
				$sql  = " UPDATE image SET";
				$sql .= " name = '$this->name',";
				$sql .= " description = '$this->description',";
				$sql .= " width = '$this->width',";
				$sql .= " height = '$this->height',";
				$sql .= " ppa = '1'";
				$sql .= " WHERE  id = $this->id";
				db_query($sql);
			}else{
				$sql = "INSERT INTO image ( name, description, width, height, ppa ) VALUES ( '$this->name', '$this->description', '$this->width', '$this->height', '1' )";
				db_query($sql);
				$this->id = db_id_insert();
			}	
		}

		/**
		* @ Deletes Image Object from database.
		**/
		function erase () {
			if($this->id){
				$sql = "DELETE FROM image";
				$sql .= " WHERE id = " . $this->id;
				db_query($sql);
			}
		}

		/**
		* @ Loads Image Object attributes from the database.
		**/
		function load ($aId) {
			$sql = "SELECT * from image";
			$sql .= " WHERE id = " . $aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->description = $row['description'];
			$this->width = $row['width'];
			$this->height = $row['height'];
		}

}
?>