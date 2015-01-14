<?
	Class ChapterImageType
	{
		var $idChapterImageType;
		var $name;
		var $width;
		var $height;

		function ChapterImageType( )
		{
			$this->idChapterImageType = 0;
			$this->name = "";
			$this->width = 0;
			$this->height = 0;
		}

		function getIdChapterImageType( )
		{
			return $this->idChapterImageType;
		}

		function getName( )
		{
			return $this->name;
		}

		function getWidth( )
		{
			return $this->width;
		}

		function getHeight( )
		{
			return $this->height;
		}

		function setIdChapterImageType( $idChapterImageType )
		{
			$this->idChapterImageType = $idChapterImageType;
		}

		function setName( $name )
		{
			$this->name = $name;
		}

		function setWidth( $width )
		{
			$this->width = $width;
		}

		function setHeight( $height )
		{
			$this->height = $height;
		}

		function load()
		{
			$db = new DataBase( DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_DEBG );
			if( $this->idChapterImageType> 0 )
			{
				$sql = "SELECT * FROM chapter_image_types " . 
					"WHERE id_chapter_image_type = " . $this->idChapterImageType;
				$row = $db->fetchArray( $db->query( $sql ) );
				if( $row )
				{
					$this->idChapterImageType = $row["id_chapter_image_type"];
					$this->name = $row["name"];
					$this->width = $row["width"];
					$this->height = $row["height"];
				}
			}
		}

		function commit()
		{
			$db = new DataBase( DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_DEBG );
			if( $this->idChapterImageType == 0 )
			{
				$sql = "INSERT INTO chapter_image_types " .
					"(name, width, height) " .
					"VALUES " . 
					"('" . $this->name . "', '" . $this->width . "', '" . $this->height . "') ";
			}
			else
			{
				$sql = "UPDATE chapter_image_types SET " .
					"name = '" . $this->name . "', width = '" . $this->width . "', height = '" . $this->height . "' " . 
					"WHERE id_chapterimagetype = '" . $this->idChapterImageType . "'";
			}
			$db->query($sql);
		}

	}
?>
