<?
	require_once('Contenido.class.php');

	 /*************************************************
	 * @ destacados Object definition
	 * @ author:	German Afanador
	 * @ created:	August - 22 - 2003 - 3:05:18
	 * @ modified:	August - 22 - 2003 - 3:05:18
	 * @ version:	1.0
	*************************************************/

	class Articulo {
		/**
		 * @ Object var definitions.
		**/
		var $id;		//	ID  int
		var $base;		//	BASE  string
		var $contenido;	//	CONTENIDO  Contenido
		var $pagina;	//	PAGINA  int
        var $categoria; //  CATEGORIA	string

	 /*************************************************
	 * @ Constructor(s) of object CLASS ARTICULO
	*************************************************/
		/**
		 * @ Creates an empty CLASS ARTICULO object or filled with values. 
		**/
		function Articulo ( $aId = false, $aBase = false, $aContenido = false,  $aPagina = false, $aCategoria = false ) {
			if ($aId)
				$this->load($aId);
			if ($aBase)
				$this->base = $aBase;
			else
			    $this->base = "<center><h3>Artículo</h3></center><br>";	
			if ($aContenido)
				$this->contenido = $aContenido;
			else if (!isset($this->contenido))
				$this->contenido = new Contenido();				
			if ($aPagina)
				$this->pagina = $aPagina;
			if ($aCategoria)
				$this->categoria = $aCategoria;
		}

	 /*************************************************
	 * @ Analizer(s) of object CLASS ARTICULO
	 *************************************************/
		/**
		 * @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print() {
			echo "<br><b>(Artículo)</b><br>";
			echo "<ul>";
			echo "<li><b>ID: </b> $this->id </li>";
			echo "<li><b>CATEGORIA: </b> $this->categoria </li>";			
			echo "<li><b>BASE: </b> $this->base </li>";
    		echo "<li><b>CONTENIDO: </b>".$this->contenido->getTexto()."</li>";
			echo "<li><b>PAGINA: </b> $this->pagina </li>";
			echo "</ul>";
		}

		/**
		 * Returns ID of CLASS ARTICULO
		 **/
		function getId() {
			return $this->id;
		}
		/**
		 * Returns BASE of CLASS ARTICULO
		**/
		function getBase() {
			return $this->base;
		}
		/**
		 * Returns CONTENIDO of CLASS ARTICULO
		**/
		function getContenido() {
			return $this->contenido;
		}
		/**
		 * Returns state of CLASS Taquilla
		 * 1 -> Object content not approved
		 * 2 -> Object content approved
		**/
		function getState() {
			if ($this->contenido->getFechaAprobacion() != "0000-00-00")
				return 2;
			return 1;
		}
		/**
		 * Returns PAGINA of CLASS ARTICULO
		**/
		function getPagina() {
			return $this->pagina;
		}
		/**
		 * Returns CATEGORIA of CLASS ARTICULO
		**/
		function getCategoria() {
			return $this->categoria;
		}		
	 /*************************************************
	 * @ Modifier(s) of object CLASS ARTICULO
	*************************************************/
		/**
		 * Sets ID of CLASS ARTICULO
		**/
		function setId($aId) {
			$this->id = $aId;
		}
		/**
		 * Sets BASE of CLASS ARTICULO
		**/
		function setBase($aBase) {
			$this->base = $aBase;
		}
		/**
		 * Sets CONTENIDO of CLASS ARTICULO
		**/
		function setContenido($aContenido) {
			$this->contenido = $aContenido;
		}
		/**
		 * Sets PAGINA of CLASS ARTICULO
		**/
		function setPagina($aPagina) {
			$this->pagina = $aPagina;
		}
		/**
		 * Sets CATEGORIA of CLASS ARTICULO
		**/
		function setCategoria($aCategoria) {
			$this->categoria = $aCategoria;
		}		
	 /*************************************************
	 * @ Persistence of object CLASS ARTICULO
	*************************************************/
		/**
		 * @ Updates or Inserts CLASS ARTICULO information depending
		 * @ upon existence of valid primary key.
		**/
		function commit () {
			$this->contenido->commit();
			if ($this->id) {
				$sql  = " UPDATE articulo SET";
    			$sql .=" contenido = '".$this->contenido->getId()."',";
				$sql .=" categoria = '$this->categoria',";				
				$sql .=" pagina = $this->pagina";
				$sql .= " WHERE  id = $this->id";
				db_query($sql);
			}	else {
				$sql = " INSERT INTO articulo( contenido, categoria, pagina ) VALUES (";
			    $sql .= "'".$this->contenido->getId()."',";
			    $sql .= "'".$this->categoria."',";				
				$sql .= "'".$this->pagina."' )";
				db_query($sql);
				$this->id = db_id_insert();
			}
		}	
		/**
		 * @ Deletes CLASS ARTICULO object from database.
		**/
		function erase () {
			if($this->id){
				$sql = "DELETE FROM articulo";
				$sql .= " WHERE id = " . $this->id;
				db_query($sql);
			}
			$this->contenido->erase();			
		}

		/**
		 * @ Loads CLASS ARTICULO attributes from the date base
		 * @ and assigns them to CLASS ARTICULO's attributes.
		**/
		function load ($aId) {
			$sql = "SELECT * from articulo";
			$sql .= " WHERE id = " .$aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);

			$this->id = $row['id'];
			$this->contenido = new Contenido( $row['contenido'] );
			$this->pagina = $row['pagina'];
			$this->categoria = $row['categoria'];			
		}
}
?>