<?
require_once('Pagina.class.php');

/*************************************************
* @ revista Object definition
* @ author:		German Afanador
* @ created:	August - 19 - 2003 - 12:19:05
* @ modified:	August - 19 - 2003 - 12:19:05
* @ version:	1.0
*************************************************/

class Revista {
		/**
		 * @ Object var definitions.
		**/
		var $id;		//	LLAVE PRIMARIA  int
		var $ano;		//	AÑO  string
		var $mes;		//	MES  string
		var $vol;		//	VOLUMEN  string
		var $num;		//	NUMERO  string
		var $paginas;	//	ARRAY of PAGINAS


	/*************************************************
	* @ Constructor(s) of object CLASS REVISTA
	*************************************************/
		/**
		 * @ Creates an empty CLASS REVISTA object or filled with values. 
		**/
		function Revista ( $aId = false, $aAno = false, $aMes = false, $aVol = false, $aNum = false, $aPaginas = false ) {
			if ($aId)
				$this->load($aId);
		    if ($aAno)
			    $this->ano = $aAno;
		    if ($aMes)
			    $this->mes = $aMes;
  		    if ($aVol)
			    $this->vol = $aVol;
		    if ($aNum)
			    $this->num = $aNum;
		    if ($aPaginas)
			    $this->paginas = $aPaginas; 	  
			else if (!isset($this->paginas))
				$this->paginas = array();
		}

	/*************************************************
	* @ Analizer(s) of object CLASS REVISTA
	*************************************************/
		/**
		 * @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print() {
			echo "<br><b>(Revista)</b><br>";
			echo "<ul>";
			echo "<li><b>ID: </b> $this->id </li>";
			echo "<li><b>A&Ntilde;O: </b> $this->ano </li>";
			echo "<li><b>MES: </b> $this->mes </li>";
			echo "<li><b>VOLUMEN: </b> $this->vol </li>";
			echo "<li><b>NUMERO: </b> $this->num </li>";
			echo "<li><b>paginas[]: </b> ";
			for( $i = 0; $i < count( $this->paginas ); $i++ ){
			   $pagina = $this->paginas[$i];
			   $pagina->_print();
			}
			echo "</li>";
			echo "</ul>";
		}

		/**
		 * Returns ID of CLASS REVISTA
		**/
		function getId() {
			return $this->id;
		}
		/**
		 * Returns AÑO of CLASS REVISTA
		**/
		function getAno() {
			return $this->ano;
		}
		/**
		 * Returns MES of CLASS REVISTA
		**/
		function getMes() {
			return $this->mes;
		}
		/**
		 * Returns VOLUMEN of CLASS REVISTA
		**/
		function getVol() {
			return $this->vol;
		}
		/**
		 * Returns NUMERO of CLASS REVISTA
		**/
		function getNum() {
			return $this->num;
		}
		/**
		 * Returns LINK TO CHILD TABLE pagina of CLASS REVISTA
		**/
		function getPaginas() {
			return $this->paginas;
		}
	/*************************************************
	* @ Modifier(s) of object CLASS REVISTA
	*************************************************/
		/**
		 * Sets ID of CLASS REVISTA
		**/
		function setId($aId) {
			$this->id = $aId;
		}
		/**
		 * Sets AÑO of CLASS REVISTA
		**/
		function setAno($aAno) {
			$this->ano = $aAno;
		}
		/**
		 * Sets MES of CLASS REVISTA
		**/
		function setMes($aMes) {
			$this->mes = $aMes;
		}
		/**
		 * Sets VOLUMEN of CLASS REVISTA
		**/
		function setVol($aVol) {
			$this->vol = $aVol;
		}
		/**
		 * Sets NUMERO of CLASS REVISTA 
		**/
		function setNum($aNum) {
			$this->num = $aNum;
		}
		/**
		 * Sets PAGINAS of CLASS REVISTA
		**/
		function setPaginas($aPaginas) {
			$this->paginas = $aPaginas;
		}
		/**
		 * ADDS a PAGINA to CLASS REVISTA
		**/
		function addPagina($aPagina) {
			$this->paginas[] = $aPagina;
		}
		
	/*************************************************
	* @ Persistence of object CLASS REVISTA
	*************************************************/
		/**
		 * @ Updates or Inserts CLASS REVISTA information depending
		 * @ upon existence of valid primary key.
		**/
		function commit () {
			if ($this->id) {
				$sql  = " UPDATE revista SET";
				$sql .=" ano = '$this->ano',";
				$sql .=" mes = '$this->mes',";
				$sql .=" vol = '$this->vol',";
				$sql .=" num = '$this->num'";
				$sql .= " WHERE  id = $this->id";
				db_query($sql);
			}else{
				$sql = " INSERT INTO revista (  ano, mes, vol, num ) VALUES ( '$this->ano', '$this->mes', '$this->vol', '$this->num' )";
				db_query($sql);
				$this->id = db_id_insert();
			}
			$ids = "(0";
			for( $i = 0; $i < count( $this->paginas ); $i++ ){
			   $pagina = $this->paginas[$i];
			   $pagina->setRevista( $this->getId() );
			   $pagina->commit();
			   $ids .= ", " . $pagina->getId();
			}
			$ids .= ")";
			$sql = "DELETE FROM pagina WHERE revista = '$this->id' AND id NOT IN " . $ids;
			db_query($sql);
		}	

		/**
		 * @ Deletes CLASS REVISTA object from database.
		**/
		function erase () {
			if($this->id){
				$sql = "DELETE FROM revista";
				$sql .= " WHERE id = " . $this->id;
				db_query($sql);
			}
			for( $i = 0; $i < count( $this->paginas ); $i++ ){
		      $this->paginas[$i]->erase();
			}
		}

		/**
		 * @ Loads CLASS REVISTA attributes from the date base
		 * @ and assigns them to CLASS REVISTA's attributes.
		**/
		function load ($aId) {
			$sql = "SELECT * from revista";
			$sql .= " WHERE id = " . $aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);
			$this->id = $row['id'];
			$this->ano = $row['ano'];
			$this->mes = $row['mes'];
			$this->vol = $row['vol'];
			$this->num = $row['num'];
			$sql = "SELECT * from pagina where revista = ".$this->id . " order by numero";
			$query = db_query($sql);
			$i = -1;
			while( $row = db_fetch_array( $query ) ){
			  $pagina = new Pagina();
			  $pagina->setId( $row['id'] );
			  $pagina->setNumero( $i );
			  $pagina->setPreview( $row['preview'] );
			  $pagina->setFechaAprobacion( $row['fecha_aprobacion'] );
			  $pagina->setComentario( $row['comentario'] );
			  $pagina->setRevista( $row['revista'] );
//			  $pagina->_print();
			  $tipo = new Tipo( $row['tipo'] );
			  $pagina->tipo =  $tipo;
				if(class_exists(ucwords($tipo->getNombre()))){
					$sql = "SELECT id FROM " . $tipo->getNombre() . " WHERE pagina = '" . $pagina->getId() . "'";
					$query2 = db_query($sql);
					if (db_numrows($query2)>0){
						$row = db_fetch_array($query2);
						eval("\$obj = new " . ucwords($tipo->getNombre()) . "(" . $row['id'] . ");");
						$pagina->setObjeto($obj);
//						$pagina->_print();
					}
				} else {
					$pagina->setObjeto(new Portada());
				}
			  $this->paginas[] = $pagina;
			  $i++;
			}
//		print_r($this->paginas);
		}
}
?>