<?
	Class ChapterImage
	{
		var $idChapterImage;
		var $idChapter;
		var $idChapterImageType;

		function ChapterImage( )
		{
			$this->idChapterImage = 0;
			$this->idChapter = 0;
			$this->idChapterImageType = 0;
		}

		function getIdChapterImage( )
		{
			return $this->idChapterImage;
		}

		function getIdChapter( )
		{
			return $this->idChapter;
		}

		function getIdChapterImageType( )
		{
			return $this->idChapterImageType;
		}

		function setIdChapterImage( $idChapterImage )
		{
			$this->idChapterImage = $idChapterImage;
		}

		function setIdChapter( $idChapter )
		{
			$this->idChapter = $idChapter;
		}

		function setIdChapterImageType( $idChapterImageType )
		{
			$this->idChapterImageType = $idChapterImageType;
		}

		function load()
		{
			$db = new DataBase( DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_DEBG );
			if( $this->idChapterImage> 0 )
			{
				$sql = "SELECT * FROM chapter_images " . 
					"WHERE id_chapter_image = " . $this->idChapterImage;
				$row = $db->fetchArray( $db->query( $sql ) );
				if( $row )
				{
					$this->idChapterImage = $row["id_chapter_image"];
					$this->idChapter = $row["id_chapter"];
					$this->idChapterImageType = $row["id_chapter_image_type"];
				}
			}
			else if( $this->idChapter > 0 && $this->idChapterImageType )
			{
				$sql = "SELECT * FROM chapter_images " . 
					"WHERE id_chapter = " . $this->idChapter . " AND " .
					"id_chapter_image_type = " . $this->idChapterImageType;
				$row = $db->fetchArray( $db->query( $sql ) );
				if( $row )
				{
					$this->idChapterImage = $row["id_chapter_image"];
					$this->idChapter = $row["id_chapter"];
					$this->idChapterImageType = $row["id_chapter_image_type"];
				}
			}
		}

		function commit()
		{
			$db = new DataBase( DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_DEBG );
			if( $this->idChapterImage == 0 )
			{
				$sql = "INSERT INTO chapter_images " .
					"(id_chapter, id_chapter_image_type ) " .
					"VALUES " . 
					"('" . $this->idChapter . "', '" . $this->idChapterImageType . "')";
					if( $db->query($sql) )
						$this->idChapterImage = $db->insertId();
		}
			else
			{
				$sql = "UPDATE chapter_images SET " .
					"id_chapter = '" . $this->idChapter . "', id_chapter_image_type = '" . $this->idChapterImageType . "' " . 
					"WHERE id_chapter_image = '" . $this->idChapterImage . "'";
				$db->query($sql);
			}
		}
	}
?>
