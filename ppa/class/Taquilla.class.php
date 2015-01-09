<?
require_once('Contenido.class.php');
	 /*************************************************
	 * @ taquilla Object definition
	 * @ author:		German Afanador
	 * @ created:		August - 22 - 2003 - 11:08:19
	 * @ modified:	August - 22 - 2003 - 11:08:19
	 * @ version:	1.0
	*************************************************/


	class Taquilla	{
		/**
		 * @ Object var definitions.
		**/
		var $id;		//	ID  int
		var $pagina;	//	PAGINA  string
		var $base;		//	BASE  string
		var $contenido;	//	CONTENIDO  Contenido

	/*************************************************
	* @ Constructor(s) of object CLASS TAQUILLA
	*************************************************/
		/**
		 * @ Creates an empty CLASS TAQUILLA object or filled with values. 
		**/
		function Taquilla ( $aId = false, $aBase = false, $aContenido = false, $aPagina = false )	{
			if ($aId)
				$this->load($aId);
			if ($aBase)
				$this->base = $aBase;
			else
			    $this->base = "<center><h3>Taquilla</h3></center><br>";
			if ($aContenido)
				$this->contenido = $aContenido;
			else if (!isset($this->contenido))
				$this->contenido = new Contenido();				
			if ($aPagina)
				$this->pagina = $aPagina;
		}

	/*************************************************
	* @ Analizer(s) of object CLASS TAQUILLA
	*************************************************/
		/**
		 * @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print()	{
			echo "<br><b>(Taquilla)</b><br>";
			echo "<ul>";
			echo "<li><b>ID: </b> $this->id </li>";
			echo "<li><b>BASE: </b> $this->base </li>";
		    echo "<li><b>CONTENIDO: </b>".$this->contenido->getTexto()."</li>";
			echo "</ul>";
		}

		/**
		 * Returns ID of CLASS TAQUILLA
		**/
		function getId()	{
			return $this->id;
		}
		/**
		 * Returns BASE of CLASS TELEVISION
		**/
		function getBase() {
			return $this->base;
		}
		/**
		 * Returns CONTENIDO of CLASS TELEVISION
		**/
		function getContenido() {
			return $this->contenido;
		}
		/**
		 * Returns state of CLASS TELEVISION
		 * 1 -> Object content not approved
		 * 2 -> Object content approved
		**/
		function getState() {
			if ($this->contenido->getFechaAprobacion() != "0000-00-00")
				return 2;
			return 1;
		}
		/**
		 * Returns PAGINA of CLASS TAQUILLA
		**/
		function getPagina()	{
			return $this->pagina;
		}
	/*************************************************
	* @ Modifier(s) of object CLASS TAQUILLA
	*************************************************/
		/**
		 * Sets ID of CLASS TAQUILLA
		**/
		function setId($aId)	{
			$this->id = $aId;
		}
		/**
		 * Sets BASE of CLASS TELEVISION
		**/
		function setBase($aBase) {
			$this->base = $aBase;
		}
		/**
		 * Sets CONTENIDO of CLASS TELEVISION
		**/
		function setContenido($aContenido) {
			$this->contenido = $aContenido;
		}
		/**
		 * Sets PAGINA of CLASS TAQUILLA
		**/
		function setPagina($aPagina)	{
			$this->pagina = $aPagina;
		}
	/*************************************************
	* @ Persistence of object CLASS TAQUILLA
	*************************************************/
		/**
		 * @ Updates or Inserts CLASS TAQUILLA information depending
		 * @ upon existence of valid primary key.
		**/
		function commit ()	{
			$this->contenido->commit();
			if ($this->id) {
				$sql  = " UPDATE taquilla SET";
    			$sql .=" contenido = '".$this->contenido->getId()."',";
				$sql .=" pagina = $this->pagina";
				$sql .= " WHERE  id = $this->id";
				db_query($sql);
			}	else {
				$sql = " INSERT INTO taquilla ( contenido, pagina ) VALUES (";
			    $sql .= "'".$this->contenido->getId()."',";
				$sql .= "'".$this->pagina."' )";
				db_query($sql);
				$this->id = db_id_insert();
			}
		}	

		/**
		 * @ Deletes CLASS TAQUILLA object from database.
		**/
		function erase ()	{
			if($this->id){
				$sql = "DELETE FROM taquilla";
				$sql .= " WHERE id = " . $this->id;
				db_query($sql);
			}
			$this->contenido->erase();			
		}

		/**
		 * @ Loads CLASS TAQUILLA attributes from the date base
		 * @ and assigns them to CLASS TAQUILLA's attributes.
		**/
		function load ($aId)	{
			$sql = "SELECT * from taquilla";
			$sql .= " WHERE id = " .$aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);
			$this->id = $row['id'];
			$this->contenido = new Contenido( $row['contenido'] );
			$this->pagina = $row['pagina'];
		}
}

?>
