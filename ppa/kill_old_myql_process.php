<?
$link = mysql_connect ("localhost", "dayscript", "kfc3*9mn") or die ("Could not connect");

print ("Connected successfully");
$result = mysql_query("SHOW FULL PROCESSLIST");
while ($row=mysql_fetch_array($result)) 
{
        $process_id=$row["Id"];
        if ($row["Time"] > 200 )
        {
                $sql="KILL $process_id";
                mysql_query($sql);
        }
}
 mysql_close ($link);

?>