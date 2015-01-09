<?
require_once('Revista.class.php');
//require_once('Cintillo.class.php');
require_once('Tipo.class.php');

$link = false;
$db = false;
/*************************************************
* @ application Object definition
* @ author:		Juan Carlos Orrego
* @ created:	August - 26 - 2003 - 12:19:05
* @ modified:	August - 26 - 2003 - 12:19:05
* @ version:	1.0
*************************************************/

class Application {
		/**
		 * @ Object var definitions.
		**/
		var $revistas;		// ARRAY of REVISTAS
		var $cintillos;		// ARRAY of CINTILLOS
		var $tipos;			// ARRAY of TIPOS
		var $link;			// Database Connection
		var $server;		// Database Server
		var $user;			// Database User
		var $password;		// Database Password
		var $database;		// Database

	/*************************************************
	* @ Constructor(s) of object CLASS APPLICATION
	*************************************************/
		/**
		 * @ Creates an empty CLASS APPLICATION object or filled with values. 
		**/
		function Application ( $aConfigFile = "include/config.php" ) {
			if(file_exists($aConfigFile)){
				$in = file($aConfigFile);
				for($i=0; $i<count($in); $i++){
					if(strstr($in[$i],"\$"))
						eval($in[$i]);
				}
			}
		}

	/*************************************************
	* @ Analizer(s) of object CLASS APPLICATION
	*************************************************/
		/**
		 * @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print() {
			echo "<br><b>(Application)</b><br>";
			echo "<ul>";
			echo "<li><b>server: </b> $this->server </li>";
			echo "<li><b>user: </b> $this->user </li>";
			echo "<li><b>password: </b> $this->password </li>";
			echo "<li><b>database: </b> $this->database </li>";
			echo "<li><b>revistas[]: </b> ";
			for( $i = 0; $i < count( $this->revistas ); $i++ ){
			   $obj = $this->revistas[$i];
			   $obj->_print();
			}
			echo "</li>";
			echo "<li><b>cintillos[]: </b> ";
			for( $i = 0; $i < count( $this->cintillos ); $i++ ){
			   $obj = $this->cintillos[$i];
			   $obj->_print();
			}
			echo "</li>";
			echo "<li><b>tipos[]: </b> ";
			for( $i = 0; $i < count( $this->tipos ); $i++ ){
			   $obj = $this->tipos[$i];
			   $obj->_print();
			}
			echo "</li>";
			echo "</ul>";
		}

		/**
		 * Returns Revistas Array
		**/
		function getRevistas($aYear = "", $aMonth = "") {
			global $link;
			if($aYear=="" && $aMonth==""){
				return $this->revistas;
			}
			if (!$this->link)
				$this->connect();
			$sql = "SELECT * FROM revista WHERE ano LIKE '%$aYear%' AND mes LIKE '%$aMonth%'";
			$results  = db_query($sql);
			$this->revistas = array();
			if(db_numrows($results)>0){
			$results  = db_query($sql);
				while($row = db_fetch_array($results)){
					$this->revistas[] = new Revista($row['id']);
				}
			}
			return $this->revistas;
		}
		/**
		 * Returns Cintillos Array
		**/
		function getCintillos($aNombre = "") {
			global $link;
			if (!$this->link)
				$this->connect();
			$sql = "SELECT * FROM cintillo WHERE nombre LIKE '%$aNombre%'";
			$results  = db_query($sql);
			$this->cintillos = array();
			if(db_numrows($results)>0){
			$results  = db_query($sql);
				while($row = db_fetch_array($results)){
					$this->cintillas[] = new Cintillo($row['id']);
				}
			}
			return $this->cintillos;
		}
		/**
		 * Returns Cintillos Array
		**/
		function getTipos($aNombre = "") {
			global $link;
			if (!$this->link)
				$this->connect();
			$sql = "SELECT * FROM tipo WHERE nombre LIKE '%$aNombre%'";
			$results  = db_query($sql);
			$this->tipos = array();
			if(db_numrows($results)>0){
			$results  = db_query($sql);
				while($row = db_fetch_array($results)){
					$this->tipos[] = new Tipo($row['id']);
				}
			}
			return $this->tipos;
		}

	/*************************************************
	* @ Modifier(s) of object CLASS APPLICATION
	*************************************************/
		/**
		 * Connects to DB Server
		**/
		function connect() {
			global $link, $db;
			if (!$this->link){
				$link = mysql_connect($this->server, $this->user, $this->password);
				$db = $this->database;
				mysql_select_db($this->database);
			} else {
				$link = $this->link;
			}
			
		}

		/**
		 * ADDS a REVISTA to CLASS APPLICATION
		**/
		function addRevista($aRevista) {
			$this->revistas[] = $aRevista;
		}
		
		/**
		 * DELETES a REVISTA from CLASS APPLICATION
		**/
		function delRevista($aRevista) {
			for($i=0; $i<count($this->revistas); $i++){
				if($aRevista == $this->revistas[$i])
					unset($this->revistas[$i]);
			}
			if($aRevista->getId())
				$aRevista->erase();
		}

		/**
		 * ADDS a CINTILO to CLASS APPLICATION
		**/
		function addCintillo($aCintillo) {
			$this->cintillos[] = $aCintillo;
		}
		
		/**
		 * DELETES a CINTILLO from CLASS APPLICATION
		**/
		function delCintillo($aCintillo) {
			for($i=0; $i<count($this->cintillo); $i++){
				if($aCintillo == $this->cintillo[$i])
					unset($this->cintillos[$i]);
			}
			if($aCintillo->getId())
				$aCintillo->erase();
		}

		/**
		 * ADDS a TIPO to CLASS APPLICATION
		**/
		function addTipo($aTipo) {
			$this->tipos[] = $aTipo;
		}

		/**
		 * DELETES a TIPO from CLASS APPLICATION
		**/
		function delTipo($aTipo) {
			for($i=0; $i<count($this->tipos); $i++){
				if($aTipo == $this->tipos[$i])
					unset($this->tipos[$i]);
			}
			if($aTipo->getId())
				$aTipo->erase();
		}

	/*************************************************
	* @ Persistence of object CLASS APPLICATION
	*************************************************/
	
		function commit(){
			$arr = $this->revistas;
			for($i=0; $i<count($arr); $i++){
				$arr[$i]->commit();
			}
			$arr = $this->cintillos;
			for($i=0; $i<count($arr); $i++){
				$arr[$i]->commit();
			}
			$arr = $this->tipos;
			for($i=0; $i<count($arr); $i++){
				$arr[$i]->commit();
			}
		}
}
?>