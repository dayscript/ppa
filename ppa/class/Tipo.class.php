<?
	 /*************************************************
	 * @ tipo Object definition
	 * @ author:		German Afanador
	 * @ created:		August - 20 - 2003 - 5:34:42
	 * @ modified:	August - 20 - 2003 - 5:34:42
	 * @ version:	1.0
	*************************************************/


	class Tipo	{
		/**
		 * @ Object var definitions.
		**/
		var $id;		//	ID  int
		var $modulo;		//	MODULO  string
		var $nombre;		//	NOMBRE  string

	 /*************************************************
	 * @ Constructor(s) of object CLASS TIPO
	*************************************************/
		/**
		 * @ Creates an empty CLASS TIPO object or filled with values. 
		**/
		function Tipo ( $aId = false ,$aModulo = false, $aNombre = false )	{
			$this->id = 0;
			if ($aId)
				$this->load($aId);
			if ($aModulo)
				$this->modulo = $aModulo;
			if ($aNombre)
				$this->nombre = $aNombre;
		}

	 /*************************************************
	 * @ Analizer(s) of object CLASS TIPO
	*************************************************/
		/**
		 * @ Returns HTML representation of object (DEBUG ONLY)
		**/
		function _print()	{
			echo "<br><b>(Tipo)</b><br>";
			echo "<ul>";
			echo "<li><b>ID: </b> $this->id </li>";
			echo "<li><b>MODULO: </b> $this->modulo </li>";
			echo "<li><b>NOMBRE: </b> $this->nombre </li>";
			echo "</ul>";
		}

		/**
		 * Returns ID of CLASS TIPO
		**/
		function getId()	{
			return $this->id;
		}
		/**
		 * Returns MODULO of CLASS TIPO
		**/
		function getModulo()	{
			return $this->modulo;
		}
		/**
		 * Returns NOMBRE of CLASS TIPO
		**/
		function getNombre()	{
			return $this->nombre;
		}
	 /*************************************************
	 * @ Modifier(s) of object CLASS TIPO
	*************************************************/
		/**
		 * Sets ID of CLASS TIPO
		**/
		function setId($aId)	{
			$this->id = $aId;
		}
		/**
		 * Sets MODULO of CLASS TIPO
		**/
		function setModulo($aModulo)	{
			$this->modulo = $aModulo;
		}
		/**
		 * Sets NOMBRE of CLASS TIPO
		**/
		function setNombre($aNombre)	{
			$this->nombre = $aNombre;
		}
	/*************************************************
	* @ Persistence of object CLASS TIPO
	*************************************************/
		/**
		 * @ Updates or Inserts CLASS TIPO information depending
		 * @ upon existence of valid primary key.
		**/
		function commit ()	{
			if ($this->id)	{
				$sql  = " UPDATE tipo SET";
				$sql .=" modulo = '$this->modulo',";
				$sql .=" nombre = '$this->nombre'";
				$sql .= " WHERE  id = $this->id";
				db_query($sql);

			}else{
				$sql = " INSERT INTO tipo (  modulo, nombre ) VALUES ( '$this->modulo', '$this->nombre' )";
				db_query($sql);
				$this->id = db_id_insert();
			}
       }
		/**
		 * @ Deletes CLASS TIPO object from database.
		**/
		function erase ()	{
			if($this->id){
				$sql = "DELETE FROM tipo";
				$sql .= " WHERE id = " . $this->id;
				db_query($sql);
			}
		}

		/**
		 * @ Loads CLASS TIPO attributes from the date base
		 * @ and assigns them to CLASS TIPO's attributes.
		**/
		function load ($aId)	{
			$sql = "SELECT * from tipo";
			$sql .= " WHERE id = " .$aId;
			$results = db_query($sql);
			$row = db_fetch_array($results);

			$this->id = $row['id'];
			$this->modulo = $row['modulo'];
			$this->nombre = $row['nombre'];
		}
		/*Load the object with a given name*/
		function load_nombre ($aNombre)	{
			$sql = "SELECT * from tipo";
			$sql .= " WHERE nombre = '" .$aNombre."'";
			$results = db_query($sql);
			$row = db_fetch_array($results);
			$this->id = $row['id'];
			$this->modulo = $row['modulo'];
			$this->nombre = $row['nombre'];
		}
 }
?>