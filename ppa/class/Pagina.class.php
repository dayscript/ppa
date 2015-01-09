<?
   require_once( 'Tipo.class.php' );
   require_once( 'Portada.class.php' );
   require_once( 'Taquilla.class.php' );
   require_once( 'Destacados.class.php' );
   require_once( 'Articulo.class.php' );   
   require_once( 'Television.class.php' );   
   require_once( 'Ppv.class.php' );   
   require_once( 'Premium.class.php' );   
   require_once( 'Nacionales.class.php' );   

	 /*************************************************
	 * @ pagina Object definition
	 * @ author:	German Afanador
	 * @ created:	August - 19 - 2003 - 12:19:05
	 * @ modified:	August - 19 - 2003 - 12:19:05
	 * @ version:	1.0
	*************************************************/


	class Pagina {
		/**
		 * @ Object var definitions.
		**/
		var $id;				//	LLAVE PRIMARIA  int
		var $numero;			//	Numero de pagina  string
		var $preview;			//	  string
		var $fechaAprobacion;	//	FECHA AP  string
		var $comentario;		//	  string
		var $revista;			// REVISTA int
		var $tipo;          	// TIPO tipo
        var $objeto;         	// OBJECT taquilla o portada o destacados o articulo o television o ppv o premium o nacionales
		
	 /*************************************************
	 * @ Constructor(s) of object CLASS PAGINA
	*************************************************/
		/**
		 * @ Creates an empty CLASS PAGINA object or filled with values. 
		**/
		function Pagina ( $aId = false, $aNumero = false, $aPreview = false, $aFechaAprobacion = false, $aComentario = false, $aRevista = false, $aTipo = false, $aObjecto = false ) {
			if ($aId)
				$this->load($aId);
			if ($aNumero)
			   $this->numero= $aNumero;
		    if ($aPreview)
			   $this->preview = $aPreview;
		    if ($aFechaAprobacion)
			   $this->fechaAprobacion = $aFechaAprobacion;
			if ($aComentario)
			   $this->comentario = $aComentario;
			if ($aRevista)
			   $this->revista = $aRevista;
			if ($aTipo)
			   $this->tipo = $aTipo;
			else if (!isset($this->tipo))
				$this->tipo = new Tipo();
			if ($aObjeto)
			   $this->objeto = $aObjeto;
			else if (!isset($this->objeto)){
//				echo ucwords($this->tipo->getNombre()) . "<br>";
				if( is_object($this->tipo) && $this->tipo->getNombre() != "" && class_exists(ucwords($this->tipo->getNombre()))){
					eval("\$this->objeto = new " . ucwords($this->tipo->getNombre()) . "();");
				} else{
					$this->objeto = new Portada();
				}
			}		
		}

	 /*************************************************
	 * @ Analizer(s) of object CLASS PAGINA
	*************************************************/
		/**
		 * @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print() {
			echo "<br><b>(Página)</b><br>";
			echo "<ul>";
			echo "<li><b>ID: </b> $this->id </li>";
			echo "<li><b>NUMERO: </b> $this->numero </li>";
			echo "<li><b>PREVIEW: </b> $this->preview </li>";
			echo "<li><b>FECHA APROBACION: </b> $this->fechaAprobacion </li>";
			echo "<li><b>COMENTARIO: </b> $this->comentario </li>";
			echo "<li><b>REVISTA: </b> $this->revista </li>";
			echo "<li><b>TIPO: </b>".$this->tipo->getNombre()."</li>";
			echo "<li><b>OBJETO: </b>";
			   $this->objeto->_print();						
			echo "</li>";
			echo "</ul>";
		}

		/**
		 * Returns LLAVE PRIMARIA of CLASS PAGINA
		**/
		function getId() {
			return $this->id;
		}
		/**
		 * Returns numero of CLASS PAGINA
		**/
		function getNumero() {
			return $this->numero;
		}
		/**
		 * Returns preview of CLASS PAGINA
		**/
		function getPreview() {
			return $this->preview;
		}
		/**
		 * Returns FECHA AP of CLASS PAGINA
		**/
		function getFechaAprobacion() {
			return $this->fechaAprobacion;
		}
		/**
		 * Returns comentario of CLASS PAGINA
		**/
		function getComentario() {
			return $this->comentario;
		}
		/**
		 * Returns tipo of CLASS PAGINA
		**/
		function getTipo() {
			return $this->tipo;
		}		
		/**
		 * Returns objeto of CLASS PAGINA
		**/
		function getObjeto() {
		   return $this->objeto;
		}				
		/**
		 * Returns revista of CLASS PAGINA
		**/
		function getRevista() {
			return $this->revista;
		}
		/**
		 * Returns state of CLASS PAGINA
		 * 0 -> No type asigned
		 * 1 -> Object content not approved
		 * 2 -> Object content approved
		**/
		function getState() {
			if($this->tipo->getNombre() == ""){
				return 0;
			} else if (!$this->validObject()){
				return 1;
			} else {
				return $this->objeto->getState();
			}
		}
		/**
		 * returns true if objeto has meaning
		**/
		function validObject() {
			if(is_object($this->objeto))
				return (ucwords($this->tipo->getNombre()) == ucwords(get_class($this->objeto)));
			return false;
		}
	 /*************************************************
	 * @ Modifier(s) of object CLASS PAGINA
	*************************************************/
		/**
		 * Sets ID of CLASS PAGINA
		**/
		function setid($aId) {
			$this->id = $aId;
		}
		/**
		 * Sets numero of CLASS PAGINA
		**/
		function setNumero($aNumero) {
			$this->numero = $aNumero;
		}
		/**
		 * Sets preview of CLASS PAGINA
		**/
		function setPreview($aPreview) {
			$this->preview = $aPreview;
		}
		/**
		 * Sets FECHA AP of CLASS PAGINA
		**/
		function setFechaAprobacion($aFechaAprobacion) {
			$this->fechaAprobacion = $aFechaAprobacion;
		}
		/**
		 * Sets comentario of CLASS PAGINA
		**/
		function setComentario($aComentario) {
			$this->comentario = $aComentario;
		}
		/**
		 * Sets TIPO of CLASS PAGINA
		**/
		function setTipo($aTipo) {
			if($aTipo->getNombre() == ""){
				if($this->validObject()){
					echo "borrando objeto anterior: " . $this->objeto->id . "<br>"; 
					$this->objeto->erase();
					$this->objeto = new Portada();
					$this->setPreview("");
					$this->setFechaAprobacion("0000-00-00");
				}
				$this->tipo = $aTipo;
			}else{
				if( $aTipo->getNombre() != "contenido" && class_exists(ucwords($aTipo->getNombre())) ){
					$this->tipo = $aTipo;
					if(!$this->validObject()){
						$this->setPreview("");
						$this->setFechaAprobacion("0000-00-00");
						if(is_object($this->objeto))
							$this->objeto->erase();
						$str = "\$obj = new ".ucwords( $aTipo->getNombre() )."();";
						eval( $str );
						$this->setObjeto($obj);
					}
				} else {
					if($this->validObject()){
						echo "borrando objeto anterior: " . $this->objeto->id . "<br>"; 
						$this->objeto->erase();
						$this->objeto = new Portada();
					}
					$this->setPreview("");
					$this->setFechaAprobacion("0000-00-00");
					$this->tipo = $aTipo;
					$this->setObjeto( new Portada() );
				}
			}
		}		
		/**
		 * Sets OBJETO of CLASS PAGINA
		**/
		function setObjeto($aObjeto) {
			if(ucwords(get_class($aObjeto)) == ucwords($this->tipo->getNombre()) && ucwords($this->tipo->getNombre()) != "Contenido"){
				$aObjeto->setPagina($this->getId());
//				print_r($this->objeto); 
//				print_r($this->objeto->getId());
				if(is_object($this->objeto) && $this->objeto->getId()){
					$this->objeto->erase();
				}
				$this->objeto= $aObjeto;
			}
		}				
		/**
		 * Sets REVISTA of CLASS PAGINA
		**/
		function setRevista($aRevista) {
			$this->revista = $aRevista;
		}
	/*************************************************
	 * @ Persistence of object CLASS PAGINA
	*************************************************/
		/**
		 * @ Updates or Inserts CLASS PAGINA information depending
		 * @ upon existence of valid primary key.
		**/
		function commit () {
			if ($this->id) {
				$sql  = " UPDATE pagina SET";
				$sql .=" preview = '$this->preview',";
				$sql .=" numero = '$this->numero',";
				$sql .=" fecha_aprobacion = '$this->fechaAprobacion',";
				$sql .=" comentario = '$this->comentario',";
				$sql .=" tipo = '".$this->tipo->getId()."',";
				$sql .=" revista = $this->revista";
				$sql .= " WHERE  id = $this->id";
				db_query($sql);
			} else {
				$sql = " INSERT INTO pagina (  numero, preview, fecha_aprobacion, comentario, revista, tipo ) VALUES (";
				$sql .= "'".$this->numero."', ";
				$sql .= "'".$this->preview."', ";
			    $sql .= "'".$this->fechaAprobacion."', ";
			    $sql .= "'".$this->comentario."', ";
			    $sql .= "'".$this->revista."', ";
			    $sql .= "'".$this->tipo->getId()."') ";
				db_query($sql);				
				$this->id = db_id_insert();
			}
			if ($this->validObject()){
				$this->objeto->setPagina( $this->getId() );
				$this->objeto->commit();
			}
		}	

		/**
		 * @ Deletes CLASS PAGINA object from database.
		**/
		function erase () {
			if($this->id){
				$sql = "DELETE FROM pagina";
				$sql .= " WHERE id = " . $this->id;
				db_query($sql);
			}
		    $this->objeto->erase();
		}

		/**
		 * @ Loads CLASS PAGINA attributes from the date base
		 * @ and assigns them to CLASS PAGINA's attributes.
		**/
		function load ($aId) {
			$sql = "SELECT * from pagina";
			$sql .= " WHERE id = " . $aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);

			$this->id = $row['id'];
			$this->numero = $row['numero'];
			$this->preview = $row['preview'];
			$this->fechaAprobacion = $row['fecha_aprobacion'];
			$this->comentario = $row['comentario'];
			$this->revista = $row['revista'];
			if( $row['tipo'] != 0 ){
				$tipo = new Tipo( $row['tipo'] );
				$this->tipo = $tipo;
				if(class_exists(ucwords($tipo->getNombre()))){
					$sql = "SELECT id FROM " . $tipo->getNombre() . " WHERE pagina = '" . $this->getId() . "'";
					$query = db_query($sql);
					if (db_numrows($query)>0){
						$row = db_fetch_array($query);
						eval("\$obj = new " . ucwords($tipo->getNombre()) . "(" . $row['id'] . ");");
						$this->setObjeto($obj);
					}
				} else {
					$this->setObjeto(new Portada());
				}
			}
		}
}
?>