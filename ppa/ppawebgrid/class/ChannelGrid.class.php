<?
class ChannelGrid
{
	var $name;
	var $shortname;
	var $number;
	var $program;
	var $logo;
	var $idChannel;
	
	function Channel()
	{
		$this->name      = "";
		$this->shortname = "";
		$this->logo      = "";
		$this->idChannel = 0;
		$this->program   = Array();		
	}
	
	/***********************
	*  SETS
	***********************/
	
	function setName( $str )
	{
		$this->name = $str;
	}

	function setNumber( $str )
	{
		$this->number = $str;
	}

	function setShortName( $str )
	{
		$this->shortname = $str;
	}

	function setLogo( $str )
	{
		$this->logo = $str;
	}

	function setIdChannel( $id )
	{
		$this->idChannel = $id;
	}

	/***********************
	*  GETS
	***********************/

	function getName()
	{
		return $this->name;
	}

	function getNumber()
	{
		return $this->number;
	}

	function getShortName()
	{
		return $this->shortname;
	}

	function getLogo()
	{
		return $this->logo;
	}
	
	function getIdChannel()
	{
		return $this->idChannel;
	}
	
	function getProgramBySchedule( $search_date, $search_hour )
	{
		$find = -1;
		if(is_array($this->program[$search_date]))
		{
			foreach($this->program[$search_date] as $hour => $title)
			{
				if( $hour > $search_hour )
					if($find == -1)
						return $this->getProgramBySchedule(date("Y-m-d", strtotime($search_date, -86400)), "23:59");
					else
						return $find;
				else
				{
//					$find = $title;
					$start = $hour;
					list($end, $val) = each($this->program[$search_date]);
					$find = array($title,$start,$end);
				}
			}
			return $find;
		}
		return array("0|||Programación Sin Confirmar",$start,$end);
	}

	function getProgramsBySchedule( $search_date, $search_hour, $time=0, $ofs=0 )
	{
//		echo "searching: $search_date, $search_hour, $time <br>";
		$r=array();
		if(is_array($this->program[$search_date]))
		{
			$prgs = $this->program[$search_date];
			$prev_day = $this->program[date("Y-m-d", strtotime($search_date)-86400)];
			$mod = hourToMinutes($search_hour); //minutes of day
			$limit = $mod + $time;
			$prv_prg = end($prev_day);
			$i=0;
			while($prg=current($prgs))
			{
//				print_r($prg);exit;
				$key = $prg["hour"];
				$mod = $prg["mod"];
				$prg["mod"]+=$ofs;
//				$key = key($prgs);
//				$mod = hourToMinutes($key);
				if($mod >= $limit){/*echo "break<br>";$r[$i-1]["end"]=$prg["hour"]*/;break;};
				if($key >= $search_hour)
				{
					if($key > $search_hour && empty($r) ) $r[$i++]=$prv_prg;
					if($i!=0){$r[$i-1]["end"]=$prg["hour"];}
					$r[$i++] = $prg;
				}
				$prv_prg = $prg;
				next($prgs);
			}
			if ($mod < $limit)
			{
				$r2 = $this->getProgramsBySchedule(date("Y-m-d", strtotime($search_date)+86400), "00:00", $time-(1440-hourToMinutes($search_hour)),1440);
				$r[count($r)-1]["end"] = $r2[0]["hour"];
				$r = array_merge($r,$r2);
				unset($r2);
			}
		}
//		print_r($r);die;
		return $r;
//		return array("0|||Programación Sin Confirmar",$start,$end);
	}

	/***********************
	 *
	 **********************/

	function appendProgram( $date, $hour, $title, $slot )
	{
//		$this->program[$date][$hour] = $slot . "|||" . $title;
		$this->program[$date][$hour]["hour"]  = $hour;
		$this->program[$date][$hour]["mod"]  = hourToMinutes($hour);
		$this->program[$date][$hour]["title"] = $title;
		$this->program[$date][$hour]["id_slot"] = $slot;
	}
}
?>