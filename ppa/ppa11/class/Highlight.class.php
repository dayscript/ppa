<?
	Class Highlight
	{
		var $idHighlight;
		var $idClient;
		var $idChapter;
		var $score;
		var $channel;
		var $title;
		var $time;
		var $date;
		var $sdesc;
		var $ldesc;
		var $IMAGE_PATH;
		var $IMAGE_URL;

		function Highlight( )
		{
			$this->idHighlight = 0;
			$this->idClient    = 0;
			$this->idChapter   = 0;
			$this->score       = 0;
			$this->channel     = "";
			$this->title       = "";
			$this->time        = "";
			$this->date        = "";
			$this->sdesc       = "";
			$this->ldesc       = "";
			$this->IMAGE_PATH  = "/home/ppa/public_html/chapter_images";
			$this->IMAGE_URL   = "http://190.27.201.2/ppa/chapter_images";
		}
		
		function setImageUrl($url)
		{
			$this->IMAGE_URL = $url;
		}

		function getIdHighlight( )
		{
			return $this->idHighlight;
		}

		function getIdClient( )
		{
			return $this->idClient;
		}

		function getChannel( )
		{
			return $this->channel;
		}

		function getTitle( )
		{
			return $this->title;
		}

		function getScore( )
		{
			return $this->score;
		}

		function getTime( )
		{
			return $this->time;
		}

		function getDate( )
		{
			return $this->date;
		}

		function getSdesc( )
		{
			return $this->sdesc;
		}

		function getLdesc( )
		{
			return $this->ldesc;
		}

		function getImage( $name )
		{
			$tmp = "/" . $name . "/" . $this->idChapter . ".jpg";
			if( file_exists( $this->IMAGE_PATH . $tmp ) )
				return $this->IMAGE_URL . $tmp;
			else 
				return null;
		}

		function setIdHighlight( $idHighlight )
		{
			$this->idHighlight = $idHighlight;
		}

		function setIdClient($id)
		{
			$this->idClient = $id;
		}

		function setChannel( $channel )
		{
			$this->channel = $channel;
		}

		function setTitle( $title )
		{
			$this->title = $title;
		}

		function setTime( $time )
		{
			$this->time = $time;
		}

		function setScore( $score)
		{
			$this->score = $score;
		}

		function setDate( $date )
		{
			$this->date = $date;
		}

		function setSdesc( $sdesc )
		{
			$this->sdesc = $sdesc;
		}

		function setLdesc( $ldesc )
		{
			$this->ldesc = $ldesc;
		}

		function load()
		{
			$db = new DataBase( DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_DEBG );
			if( $this->idHighlight > 0 && $this->idClient > 0 )
			{
				$sql = "SELECT h.id_highlight, ch.name, s.title, s.time, s.date, c.description ldesc, c.id, c.serie, c.movie, c.special, c.points FROM " .
					"highlights h, channel ch, slot s, chapter c, slot_chapter sc WHERE " .
					"s.id = h.id_slot AND " .
					"s.id = sc.slot AND " .
					"s.channel = ch.id AND " .
					"c.id = sc.chapter AND " .
					"h.id_highlight = " . $this->idHighlight;

				$row = $db->fetchArray($db->query( $sql ));
				if( $row )
				{
					$this->idHighlight = $row["id_highlight"];
					$this->idChapter = $row["id"];
					$this->title = $row["title"];
					$this->channel = $row["name"];
					$this->score = $row["points"];
					$this->title = $row["title"];
					$this->time = $row["time"];
					$this->date = $row["date"];
					$this->ldesc = $row["ldesc"];
					
					if( $row["serie"] > 0 ){ $table = "serie"; $id = $row["serie"]; }
					else if( $row["movie"] > 0 ){ $table = "movie"; $id = $row["movie"]; }
					else if( $row["special"] > 0 ){ $table = "special"; $id = $row["special"]; }
					else return;

					$sql = "SELECT description sdesc FROM " . $table ." WHERE id = " . $id;
					$row = $db->fetchArray($db->query( $sql ));
					if( $row )
						$this->sdesc = $row["ldesc"];
				}
			}
		}

/*		function commit()
		{
			$db = new DataBase( DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_DEBG );
			if( $this->idHighlight == 0 )
			{
				$sql = "INSERT INTO highlights " .
					"(channel, time, date, sdesc, ldesc) " .
					"VALUES " . 
					"('" . $this->channel . "', '" . $this->time . "', '" . $this->date . "', '" . $this->sdesc . "', '" . $this->ldesc . "') ";
				if( $db->query($sql) )
					$this->idHighlight = $db->insertId();
			}
			else
			{
				$sql = "UPDATE highlights SET " .
					"channel = '" . $this->channel . "', time = '" . $this->time . "', date = '" . $this->date . "', sdesc = '" . $this->sdesc . "', ldesc = '" . $this->ldesc . "' " . 
					"WHERE id_highlight = '" . $this->idHighlight . "'";
				$db->query($sql);
			}
		}
*/
	}
?>
