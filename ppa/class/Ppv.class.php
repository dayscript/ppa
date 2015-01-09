<?
	require_once('Contenido.class.php');

	 /*************************************************
	 * @ ppv Object definition
	 * @ author:	German Afanador
	 * @ created:	August - 22 - 2003 - 3:05:18
	 * @ modified:	August - 22 - 2003 - 3:05:18
	 * @ version:	1.0
	*************************************************/

	class Ppv {
		/**
		 * @ Object var definitions.
		**/
		var $id;		//	ID  int
		var $base;		//	BASE  string
		var $contenido;	//	CONTENIDO  Contenido
		var $pagina;	//	PAGINA  int


	 /*************************************************
	 * @ Constructor(s) of object CLASS PPV
	*************************************************/
		/**
		 * @ Creates an empty CLASS PPV object or filled with values. 
		**/
		function Ppv ( $aId = false, $aBase = false, $aContenido = false, $aPagina = false ) {
			if ($aId)
				$this->load($aId);
			if ($aBase)
				$this->base = $aBase;
			else
			    $this->base = "<center><h3>PPV</h3></center><br>";					
			if ($aContenido)
				$this->contenido = $aContenido;
			else if (!isset($this->contenido))
				$this->contenido = new Contenido();				
			if ($aPagina)
				$this->pagina = $aPagina;
		}

	 /*************************************************
	 * @ Analizer(s) of object CLASS PPV
	*************************************************/
		/**
		 * @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print() {
			echo "<br><b>(Ppv)</b><br>";
			echo "<ul>";
			echo "<li><b>ID: </b> $this->id </li>";
			echo "<li><b>BASE: </b> $this->base </li>";
		    echo "<li><b>CONTENIDO: </b>".$this->contenido->getTexto()."</li>";
			echo "</ul>";
		}

		/**
		 * Returns ID of CLASS PPV
		**/
		function getId() {
			return $this->id;
		}
		/**
		 * Returns BASE of CLASS PPV
		**/
		function getBase() {
			return $this->base;
		}
		/**
		 * Returns CONTENIDO of CLASS PPV
		**/
		function getContenido() {
			return $this->contenido;
		}
		/**
		 * Returns state of CLASS PPV
		 * 1 -> Object content not approved
		 * 2 -> Object content approved
		**/
		function getState() {
			if ($this->contenido->getFechaAprobacion() != "0000-00-00")
				return 2;
			return 1;
		}
		/**
		 * Returns PAGINA of CLASS PPV
		**/
		function getPagina() {
			return $this->pagina;
		}
	/*************************************************
	* @ Modifier(s) of object CLASS PPV
	*************************************************/
		/**
		 * Sets ID of CLASS PPV
		**/
		function setId($aId) {
			$this->id = $aId;
		}
		/**
		 * Sets BASE of CLASS PPV
		**/
		function setBase($aBase) {
			$this->base = $aBase;
		}
		/**
		 * Sets CONTENIDO of CLASS PPV
		**/
		function setContenido($aContenido) {
			$this->contenido = $aContenido;
		}
		/**
		 * Sets PAGINA of CLASS PPV
		**/
		function setPagina($aPagina) {
			$this->pagina = $aPagina;
		}
	 /*************************************************
	 * @ Persistence of object CLASS PPV
	*************************************************/
		/**
		 * @ Updates or Inserts CLASS PPV information depending
		 * @ upon existence of valid primary key.
		**/
		function commit () {
			$this->contenido->commit();
			if ($this->id) {
				$sql  = " UPDATE ppv SET";
    			$sql .=" contenido = '".$this->contenido->getId()."',";
				$sql .=" pagina = $this->pagina";
				$sql .= " WHERE  id = $this->id";
				db_query($sql);
			}	else {
				$sql = " INSERT INTO ppv( contenido, pagina ) VALUES (";
			    $sql .= "'".$this->contenido->getId()."',";
				$sql .= "'".$this->pagina."' )";
				db_query($sql);
				$this->id = db_id_insert();
			}
		}	
		/**
		 * @ Deletes CLASS PPV object from database.
		**/
		function erase () {
			if($this->id){
				$sql = "DELETE FROM ppv";
				$sql .= " WHERE id = " . $this->id;
				db_query($sql);
			}
			$this->contenido->erase();			
		}

		/**
		 * @ Loads CLASS PPV attributes from the date base
		 * @ and assigns them to CLASS PPV's attributes.
		**/
		function load ($aId) {
			$sql = "SELECT * from ppv";
			$sql .= " WHERE id = " .$aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);

			$this->id = $row['id'];
			$this->contenido = new Contenido( $row['contenido'] );
			$this->pagina = $row['pagina'];
		}
}
?>