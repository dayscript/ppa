<?
//$link = mysql_pconnect("localhost", "dayscript", "kfc3*9mn") or mysql_die();
//$db ="program";
//mysql_select_db($db) or die("Unable to select database");
//require_once("../../include/db.inc.php");

Class Schedule
{
	var $result;
	var $channel;
	var $title;
	var $program;
	var $date;
	var $time;
	var $duration;
	var $DEBUG;
	var $SYNC;
	
	function Schedule()
	{
		$DEBUG = false;
		$SYNC = false;

		$sql = "CREATE TEMPORARY TABLE schedule ".                  
		       "( ".                                                  
		       "    channel_id int(10), ".    //     1.    10  CHANNEL ID NUMBER (Link to Channel File)            
		       "    program_id int(10), ".    //     2.    10  PROGRAM ID NUMBER (Link to Program File)            
		       "    title varchar(255), ".    //     3.     6  DATE - STYLE: MMDDYY  (NEW DATE CHANGES AT MIDNIGHT)
		       "    date varchar(10), ".      //     3.     6  DATE - STYLE: MMDDYY  (NEW DATE CHANGES AT MIDNIGHT)
		       "    time varchar(8), ".       //     4.     4  TIME - STYLE: HHMM    (24 HR FORMAT)                
		       "    duration varchar(4), ".   //     5.     4  COMPUTED DURATION - STYLE: HHMM                     
		       "    UNIQUE KEY `slot` (`channel_id`, `date`, `time`), ".     
		       "    KEY `title` (`title`) ".     
		       "      )";                      
		db_query($sql, $this->DEBUG, $this->SYNC) or die(db_error());
	}
	
	function getChannel()
	{
		return $this->channel;
	}
	
	function getProgram()
	{
		return $this->program;
	}
	
	function getTitle()
	{
		return $this->title;
	}
	
	function getDate()
	{
		return $this->date;
	}
	
	function getTime()
	{
		return $this->time;
	}
	
	function getDuration()
	{
		return $this->duration;
	}
	
	function selectAllSchedule()
	{
		$sql = "SELECT * FROM schedule ORDER BY channel_id, date, time ";
		$this->result = db_query($sql, $this->DEBUG, $this->SYNC);
	}
	
	function appendReg($channel, $program, $title, $date, $time, $duration)
	{
		$sql = "INSERT INTO schedule VALUES (" .
		       " '" . $channel ."', " .
		       " '" . $program ."', " .
		       " '" . addslashes( trim( $title ) ) ."', " .
		       " '" . date("Y-m-d", strtotime( $date ) ) ."', " .
		       " '" . $time ."', " .
		       " '" . $duration ."' " .
		       ")";

		if( !db_query($sql, $this->DEBUG, $this->SYNC) )
		{
			println( "schedule: " . db_errno() ." ". db_error() );
		}
	}
	
	function fetchScheduleArray()
	{
		if( $row = db_fetch_array( $this->result ) )
		{
			$this->channel = $row['channel_id'];
			$this->program = $row['program_id'];
			$this->title   = $row['title'];
			$this->date    = $row['date'];
			$this->time    = $row['time'];
			$this->duration= $row['duration'];
			return true;
		}
		else
		{
			return false;
		}	
	}
}

?>