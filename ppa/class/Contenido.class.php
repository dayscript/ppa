<?
	 /*************************************************
	 * @ contenido Object definition
	 * @ author:		German Afanador
	 * @ created:		August - 22 - 2003 - 3:21:53
	 * @ modified:	August - 22 - 2003 - 3:21:53
	 * @ version:	1.0
	*************************************************/


	class Contenido	{
		/**
		 * @ Object var definitions.
		**/
		var $id;		//	ID  int
		var $texto;		//	TEXTO  string
		var $fechaAprobacion;		//	FECHA_APROBACION  string
		var $comentario;		//	COMENTARIO  string


	 /*************************************************
	 * @ Constructor(s) of object CLASS CONTENIDO
	*************************************************/
		/**
		 * @ Creates an empty CLASS CONTENIDO object or filled with values. 
		**/
		function Contenido ( $aId = false, $aTexto = false, $aFechaAprobacion = false, $aComentario = false )	{
			if ($aId)
				$this->load($aId);
			if ($aTexto)
				$this->texto = $aTexto;
			if ($aFechaAprobacion)
				$this->fechaAprobacion = $aFechaAprobacion;
			if ($aComentario)
				$this->comentario = $aComentario;
		}

	 /*************************************************
	 * @ Analizer(s) of object CLASS CONTENIDO
	*************************************************/
		/**
		 * @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print()	{
			echo "<br><b>(Contenido)</b><br>";
			echo "<ul>";
			echo "<li><b>ID: </b> $this->id </li>";
			echo "<li><b>TEXTO: </b> $this->texto </li>";
			echo "<li><b>FECHA_APROBACION: </b> $this->fechaAprobacion </li>";
			echo "<li><b>COMENTARIO: </b> $this->comentario </li>";
			echo "</ul>";
		}

		/**
		 * Returns ID of CLASS CONTENIDO
		**/
		function getId()	{
			return $this->id;
		}
		/**
		 * Returns TEXTO of CLASS CONTENIDO
		**/
		function getTexto()	{
			return $this->texto;
		}
		/**
		 * Returns FECHA_APROBACION of CLASS CONTENIDO
		**/
		function getFechaAprobacion()	{
			return $this->fechaAprobacion;
		}
		/**
		 * Returns COMENTARIO of CLASS CONTENIDO
		**/
		function getComentario()	{
			return $this->comentario;
		}
	 /*************************************************
	 * @ Modifier(s) of object CLASS CONTENIDO
	*************************************************/
		/**
		 * Sets ID of CLASS CONTENIDO
		**/
		function setId($aId)	{
			$this->id = $aId;
		}
		/**
		 * Sets TEXTO of CLASS CONTENIDO
		**/
		function setTexto($aTexto)	{
			$this->texto = $aTexto;
		}
		/**
		 * Sets FECHA_APROBACION of CLASS CONTENIDO
		**/
		function setFechaAprobacion($aFechaAprobacion)	{
			$this->fechaAprobacion = $aFechaAprobacion;
		}
		/**
		 * Sets COMENTARIO of CLASS CONTENIDO
		**/
		function setComentario($aComentario)	{
			$this->comentario = $aComentario;
		}
	 /*************************************************
	 * @ Persistence of object CLASS CONTENIDO
	*************************************************/
		/**
		 * @ Updates or Inserts CLASS CONTENIDO information depending
		 * @ upon existence of valid primary key.
		**/
		function commit ()	{
			if ($this->id)	{
				$sql  = " UPDATE contenido SET";
				$sql .=" texto = '$this->texto',";
				$sql .=" fecha_aprobacion = '$this->fechaAprobacion',";
				$sql .=" comentario = '$this->comentario'";
				$sql .= " WHERE  id = $this->id";
				db_query($sql);

			}	else	{
				$sql = " INSERT INTO contenido (  texto, fecha_aprobacion, comentario ) VALUES ( '$this->texto', '$this->fechaAprobacion', '$this->comentario' )";
				db_query($sql);
				$this->id = db_id_insert();
			}
        }
		/**
		 * @ Deletes CLASS CONTENIDO object from database.
		**/
		function erase ()	{
			if($this->id){
				$sql = "DELETE FROM contenido";
				$sql .= " WHERE id = " . $this->id;
				db_query($sql);
			}
		}

		/**
		 * @ Loads CLASS CONTENIDO attributes from the date base
		 * @ and assigns them to CLASS CONTENIDO's attributes.
		**/
		function load ($aId)	{
			$sql = "SELECT * from contenido";
			$sql .= " WHERE id = " .$aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);

			$this->id = $row['id'];
			$this->texto = $row['texto'];
			$this->fechaAprobacion = $row['fecha_aprobacion'];
			$this->comentario = $row['comentario'];
		}
}
?>
