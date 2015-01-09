<?
	 /*************************************************
	 * @ portada Object definition
	 * @ author:		German Afanador
	 * @ created:		August - 20 - 2003 - 6:10:35
	 * @ modified:	August - 20 - 2003 - 6:10:35
	 * @ version:	1.0
	*************************************************/


	class Portada	{
		/**
		 * @ Object var definitions.
		**/
		var $id;		//	ID  int
		var $tema;		//	TEMA  string
		var $pagina;		//	PAGINA  int
		var $comentario;		//	COMENTARIO  string


	 /*************************************************
	 * @ Constructor(s) of object CLASS PORTADA
	*************************************************/
		/**
		 * @ Creates an empty CLASS PORTADA object or filled with values. 
		**/
		function Portada ( $aId = false, $aTema = false, $aPagina = false, $aComentario = false ) {
			if ($aId)
				$this->load($aId);
			if ($aTema)
				$this->tema = $aTema;
			if ($aPagina)
				$this->pagina = $aPagina;
			if ($aComentario)
				$this->comentario = $aComentario;				
		}

	 /*************************************************
	 * @ Analizer(s) of object CLASS PORTADA
	*************************************************/
		/**
		 * @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print()	{
			echo "<br><b>(Portada)</b><br>";
			echo "<ul>";
			echo "<li><b>ID: </b> $this->id </li>";
			echo "<li><b>TEMA: </b> $this->tema </li>";
			echo "<li><b>COMENTARIO: </b> $this->comentario </li>";			
			echo "</ul>";
		}

		/**
		 * Returns ID of CLASS PORTADA
		**/
		function getId()	{
			return $this->id;
		}
		/**
		 * Returns TEMA of CLASS PORTADA
		**/
		function getTema()	{
			return $this->tema;
		}
		/**
		 * Returns PAGINA of CLASS PORTADA
		**/
		function getPagina()	{
			return $this->pagina;
		}
		/**
		 * Returns STATE of CLASS PORTADA
		**/
		function getState()	{
			if($this->tema != "")
				return 2;
			return 1;
		}
		/**
		 * Returns COMENTARIO of CLASS CONTENIDO
		**/
		function getComentario()	{
			return $this->comentario;
		}		
	 /*************************************************
	 * @ Modifier(s) of object CLASS PORTADA
	*************************************************/
		/**
		 * Sets ID of CLASS PORTADA
		**/
		function setId($aId)	{
			$this->id = $aId;
		}
		/**
		 * Sets TEMA of CLASS PORTADA
		**/
		function setTema($aTema)	{
			$this->tema = $aTema;
		}
		/**
		 * Sets PAGINA of CLASS PORTADA
		**/
		function setPagina($aPagina)	{
			$this->pagina = $aPagina;
		}
		/**
		 * Sets STATE of CLASS PORTADA
		**/
		function setState($aState)	{
			$this->state = $aState;
		}
		/**
		 * Sets COMENTARIO of CLASS CONTENIDO
		**/
		function setComentario($aComentario)	{
			$this->comentario = $aComentario;
		}		
	 /*************************************************
	 * @ Persistence of object CLASS PORTADA
	*************************************************/
		/**
		 * @ Updates or Inserts CLASS PORTADA information depending
		 * @ upon existence of valid primary key.
		**/
		function commit ()	{
			if ($this->id)	{
				$sql  = " UPDATE portada SET";
				$sql .=" tema = '$this->tema',";
				$sql .=" comentario = '$this->comentario',";				
				$sql .=" pagina = '$this->pagina'";
				$sql .= " WHERE  id = $this->id";
				db_query($sql);
			}	else	{
				$sql = " INSERT INTO portada (  tema, pagina, comentario ) VALUES ( '$this->tema','$this->pagina' , '$this->comentario'  )";
				db_query($sql);
				$this->id = db_id_insert();
			}
        }
		/**
		 * @ Deletes CLASS PORTADA object from database.
		**/
		function erase ()	{
			if($this->id){
				$sql = "DELETE FROM portada";
				$sql .= " WHERE id = " . $this->id;
				db_query($sql);
			}
		}

		/**
		 * @ Loads CLASS PORTADA attributes from the date base
		 * @ and assigns them to CLASS PORTADA's attributes.
		**/
		function load ($aId)	{
			$sql = "SELECT * from portada";
			$sql .= " WHERE id = " .$aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);
			$this->id = $row['id'];
			$this->tema = $row['tema'];
			$this->pagina = $row['pagina'];
			$this->comentario = $row['comentario'];
		}
}

?>
