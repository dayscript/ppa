<?
error_reporting(0);
ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);
error_reporting(E_ERROR);
set_time_limit( 0 );

$link = mysql_pconnect("localhost", "ppa", "kfc3*9mn") or mysql_die();
$db ="ppa";
$DEBUG = false;
$SYNC = false;

mysql_select_db($db) or die("Unable to select database");
require_once("../include/db.inc.php");
require_once("../include/util.inc.php");
require_once("../class/Ftp.class.php");
require_once("class/Properties.class.php");
require_once("class/WriteFile.class.php");
require_once("class/Schedule2.class.php");
require_once("class/Program.class.php");

define("OUT_PATH", "files/");

/*********************
* Función para convertir minutos a Horas y minutos (HH:MM)
*********************/
function toHM( $minutes )
{
	if( $minutes === "" ) return "";
	if( $minutes <= 0 ) return "0030";
//	if( $minutes > 300 ) echo "programa de mas de 5 horas\n";
	$m = $minutes % 60;
	$h = $minutes / 60;
	return sprintf( "%02d%02d", $h, $m );
}

/*********************
* Parche solo para TvCable
*********************/
function tvCablePatch( $id_client,  $id_channel )
{
	if( ( $id_client == 67 ) &&
	   (
	    $id_channel == 508 ||
	    $id_channel == 507 ||
	    $id_channel == 506 ||
	    $id_channel == 427 ||
	    $id_channel == 429 ||
	    $id_channel == 428 ||
	    $id_channel == 467 ||
	    $id_channel == 387 ||
	    $id_channel == 386 ||
	    $id_channel == 385 ||
	    $id_channel == 383 ||
	    $id_channel == 384 ||
	    $id_channel == 382 ||
	    $id_channel == 381 ||
	    $id_channel == 380 ||
	    $id_channel == 379 ||
	    $id_channel == 378 ||
	    $id_channel == 376 ||
	    $id_channel == 377 ||
	    $id_channel == 375 ||
	    $id_channel == 909
	   )
	){ return true; }
	else { return false; }
}

/*********************
* Parche solo para canales de adultos Telmex
*********************/
function tvCablePatch2( $id_client,  $id_channel )
{
	if( ( $id_client == 67 ) &&
	   (
	    $id_channel == 429 ||
	    $id_channel == 428 ||
	    $id_channel == 427 ||
	    $id_channel == 507 ||
	    $id_channel == 506 ||
	    $id_channel == 508 
	   )
	){ return true; }
	else { return false; }
}

/*********************
* Parche solo para Intercable
*********************/
function interCablePatch( $id_client,  $id_channel )
{
	if( ( $id_client == 66 ) &&
	   (
	    $id_channel == 720 ||
	    $id_channel == 719 ||
	    $id_channel == 718 ||
	    $id_channel == 432 ||
	    $id_channel == 467 ||
	    $id_channel == 388 ||
	    $id_channel == 387 ||
	    $id_channel == 386 ||
	    $id_channel == 385 ||
	    $id_channel == 383 ||
	    $id_channel == 384 ||
	    $id_channel == 382 ||
	    $id_channel == 381 ||
	    $id_channel == 379 ||
	    $id_channel == 380 ||
	    $id_channel == 378 ||
	    $id_channel == 376 ||
	    $id_channel == 377 ||
	    $id_channel == 375 
	   )
	){ return true; }
	else { return false; }
}

/*********************
* Aquí comienza todo
*********************/

if( !isset( $argv[1] ) ) die( "Se debe especificar el NOMBRE la cabecera ( create_files.php NOMBRE )\n\n");
println( date("[H:i:s] ") . "Generando archivo para " . $argv[1] );

$nodesc    = isset($argv[2])?true:false;
$props     = new Properties( "properties/" . $argv[1] .".properties" );
$GMT       = $props->getProperty("GMT");
$PREFIX    = $props->getProperty("prefix");
$ID_CLIENT = $props->getProperty("id");
$DAYS      = ($props->getProperty("days")=="" ? "8" : $props->getProperty("days"));
$DAY       = (24 * 60 * 60);
WriteFile::$LANG = ($props->getProperty("lang")=="" ? "es" : $props->getProperty("lang"));

if( $PREFIX == "" ) die ( "No se ha definido el 'prefijo' en el archivo de configuración\n\n" );
if( $ID_CLIENT == "" ) die ( "No se ha definido 'id' en el archivo de configuración\n\n" );

$starttime = time();

//$start_date = date("Y-m-d");
$start_date = date("Y-m-d", time() - $DAY );
$stop_date  = date("Y-m-d", time() + ($DAYS * $DAY) );
$stop_date_ppv  = date("Y-m-d", time() + (30 * $DAY) );

$categories = array();

$vchip = array (
"TVY"  => "Y",
"TVY7" => "Y7",
"TVG"  => "G",
"TVPG" => "PG",
"TV14" => "14",
"TVMA" => "MA"
);

$mpaa = array (
"PG-13" => "13",
"R"     => "R",
"PG"    => "PG",
"G"     => "G",
"NC-17" => "17"
);

$chn_alias = array(
"HBOP" => "HBOPE",
"MAXE" => "CMAXE",
"MAXO" => "CMAO",
"MCE" => "MCEE",
"MCO" => "MCEO",
"MCE" => "MCEE",
"ADULT" => "ADPPV",
"HOT" => "APPVH",
"2HOT" => "APPVH2"
);

/************************
* *
* Genera archivo de Categorías (.cat)
* *
*************************/

println( date("[H:i:s] ") . "Generando archivo de categorías" );
$file       = new WriteFile( $PREFIX . date("md") .".cat" );

/************************
* Trae categorias 
*************************/

$sql = "SELECT id_pioneer id, name ". 
       "FROM category ".
       "WHERE 1";

$result = db_query( $sql, $DEBUG, $SYNC );

/************************
* Escribe Archivo .cat
*************************/

while($row = db_fetch_array($result))
{
	$file->write( $row['id'], 3, "," );		  //     1.     3  CATEGORY CODE    (numeric)
	$file->write( $row['name'], 18, "," );    //     2.    18  CATEGORY TEXT             
	$file->write( "5", 2, "," );              //     3.     2  LANGUAGE CODE    (numeric)
	$file->write( "SPANISH", 30 );            //     4.    30  LANGUAGE TEXT             
	$file->writeLn( );
	
	$categories[ $row['name'] ] = $row['id'];
}

$file->close();

/************************
* *
* Genera archivo de Canales (.chn)
* *
*************************/

println( date("[H:i:s] ") . "Generando archivo de Canales" );
$file = new WriteFile( $PREFIX . date("md") .".chn" );

$sql = "SELECT channel.id, channel.shortname, channel.name, channel.review description, ". 
       "client_channel.number ".
       "FROM channel, client_channel ".
       "WHERE channel.id = client_channel.channel AND client_channel.client = " . $ID_CLIENT ." ".
       "ORDER by client_channel.number";

$result = db_query( $sql, $DEBUG, $SYNC );

while($row = db_fetch_array($result))
{
	$file->write( $row['id'], 10, "," );			//     1.    10  CHANNEL REFERENCE NUMBER (Link to Schedule File)
	$file->write( isset( $chn_alias [ $row['shortname'] ] ) ? $chn_alias [$row['shortname'] ] :  $row['shortname'], 6, "," );      //     2.     6  CALL LETTER/CABLE SERVICE NAME                  
	$file->write( $row['name'], 30, "," );          //     3.    30  EXPANDED CALL LETTERS                           
	$file->write( ""/*$row['afiliation']*/, 2, "," );     //     4.     2  AFFILIATION FIELD                               
	$file->write( ""/*$row['description']*/, 1, "," );    //     5.     1  CHANNEL DESCRIPTION                             
	$file->write( "", 3, "," );                     //     6.     3  BROADCAST CHANNEL NUMBER 3  BROADCAST CHANNEL NUMBER                        
	$file->write( ""/*$row['timezone']*/, 1, "," );       //     7.     1  TIMEZONE                                        
	$file->write( ""/*$row['daylight']*/, 1, "," );       //     8.     1  OBSERVE DAYLIGHT SAVINGS?   Y OR N              
	$file->write( ""/*$row['city']*/, 1, "," );           //     9.    37  CITY                                            
	$file->write( ""/*$row['state']*/, 1, "," );          //     10.   21  STATE                                           
	$file->write( ""/*$row['dma']*/, 1, "," );  			    //     11.   32  DMA MARKET NAME                                 
	$file->write( ""/*$row['web']*/, 1 );                 //     12.   30  WEBSITE ADDRESS OF CHANNEL                      
	$file->writeLn( );                   
}
$file->close();


/************************
* *
* Genera archivo de Programas (.prg)
* *
*************************/

println( date("[H:i:s] ") . "Generando archivo de Programas" );

$prg = new Program();
$sch = new Schedule();

$file     = new WriteFile( $PREFIX . date("md") .".prg" );
$slot_id  = Array();
$channels = Array();

/************************
* Trae programación de series con sinopsis
*************************/

println( date("[H:i:s] ") . "Consultando series");

$sql = "SELECT ".
       "  slot.id id_slt, slot.date, slot.time, slot.duration, slot.title title_slt, slot.new_episode, ".
       "  chapter.id id_chp, chapter.serie, chapter.title, chapter.spanishtitle, ".
       "  serie.rated, serie.gender, serie.year, serie.starring, serie.description sdesc, ".
       "  chapter.points, chapter.blackandwhite, chapter.surround, chapter.animated, chapter.description ldesc, ".
       "  channel.id id_chn, channel.name name_chn ".
       "FROM client, channel, client_channel, slot, chapter, slot_chapter, serie ".
       "WHERE client.id = client_channel.client ".
       "  AND channel.id = client_channel.channel ".
       "  AND client.id = " . $ID_CLIENT .
       "  AND serie.id = chapter.serie".
       "  AND chapter.serie <> 0".
       "  AND channel.id = slot.channel ".
       "  AND slot.id = slot_chapter.slot ".
       "  AND chapter.id = slot_chapter.chapter ".
       "  AND slot.date BETWEEN '". $start_date ."' AND '". $stop_date ."' ".
       "ORDER by client_channel.number, slot.date, slot.time ";
       
$result = db_query( $sql, $DEBUG, $SYNC );

println( date("[H:i:s] ") . "Escribiendo series");

while($row = db_fetch_array($result))
{
	$slot_id[$row['id_slt']] = $row['id_slt'];
	$sch->appendReg(
	                 $row['id_chn'],
	                 $row['id_chp'],
	                 $row['title_slt'],
	                 $row['date'],
	                 $row['time'], 
	                 $row['duration'],
	                 $row['new_episode']
	                );

	if( $prg->appendReg( $row['id_chp'], $row['title_slt'] ) )
	{
		$actor = explode(",", $row['starring'] );
		
		if( tvCablePatch2($ID_CLIENT, $row['id_chn']) )
		{
			$row['ldesc'] = $row['title_slt'] . " Usando control parental puede ocultar los títulos y canales que no desee ver";
			$row['title'] = "Programación " . $row['name_chn'];
			$row['title_slt'] = "Programación " . $row['name_chn'];
			$row['spanishtitle'] = "Programación " . $row['name_chn'];
		}
		if( tvCablePatch($ID_CLIENT, $row['id_chn']) )
		{
			$row['ldesc'] = "Precio con IVA - " . $row['ldesc'];
		}
		else if( interCablePatch($ID_CLIENT, $row['id_chn']) )
		{
			$cost = $props->getProperty( "channel_" . $row['id_chn'] );
			$row['ldesc'] = "Bs.F. " . $cost . ",00 con IVA - " . $row['ldesc'];
			unset($cost);
		}
	
		$file->write( $prg->getInsertId(), 10, "," );						 					  //     1.    10  PROGRAM ID NUMBER (Link to Schedule File)                   
		$file->write( $row['title_slt'], 128, "," );                        //     2.   128  TITLE                                                       
		$file->write( $row['title'], 128, "," );                            //     3.   128  ALT. TITLE 1                                                
		$file->write( $row['spanishtitle'], 128, "," );                     //     4.   128  ALT. TITLE 2                                                
		$file->write( $row['alt3'], 128, "," );                             //     5.   128  ALT. TITLE 3                                                
		$file->write( $row['alt4'], 128, "," );                             //     6.   128  ALT. TITLE 4                                                
		$file->write( $row['alt5'], 128, "," );                             //     7.   128  ALT. TITLE 5                                                
		$file->write( $row['subt'], 128, "," );                             //     8.   128  SUBTITLE                                                    
		$file->write( $row['subt1'], 128, "," );                            //     9.   128  ALTERNATE SUBTITLE 1                                        
		$file->write( $row['subt2'], 128, "," );                            //     10.  128  ALTERNATE SUBTITLE 2                                        
		$file->write( $row['episode'], 70, "," );                           //     11.   70  EPISODE TITLE                                               
		$file->write( "32", 2, "," );                                       //     12.    2  PROGRAM TYPE                                                
		$file->write( $categories[$row['gender']], 18, "," );               //     13.   18  CATEGORY   - numeric code, refer to .CAT file               
		$file->write( $row['mpaa'], 2, "," );                               //     14.    2  MPAA RATING                                                 
		$file->write( $vchip[ $row['rated'] ], 2, "," );                    //     15.    2  PARENTAL RATING  (VCHIP)                                    
		$file->write( $row['year'], 4, "," );                               //     16.    4  YEAR OF RELEASE ON MOVIES                                   
		$file->write( ( $row['points'] - 1 ) == 9 ? 8 : ( $row['points'] - 1 ), 1, "," );  //     17.    1  QUALITY STARS ON MOVIES                                     
		$file->write( $row['country'], 25, "," );                           //     18.   25  COUNTRY OF ORIGIN ON MOVIES                                 
		$file->write( toHM( $row['duration'] ), 4, "," );                   //     19.    4  TRUE DURATION (IN MINUTES)                                  
		$file->write( $row['director'], 40, "," );                          //     20.   40  DIRECTOR ON MOVIES                                          
		$file->write( $actor[0], 36, "," );                                 //     21.   36  ACTOR 1 ON  MOVIES                                          
		$file->write( $actor[1], 36, "," );                                 //     22.   36  ACTOR 2  "    "                                             
		$file->write( $actor[2], 36, "," );                                 //     23.   36  ACTOR 3  "    "                                             
		$file->write( $actor[3], 36, "," );                                 //     24.   36  ACTOR 4  "    "                                             
		$file->write( $actor[4], 36, "," );                                 //     25.   36  ACTOR 5  "    "                                             
		$file->write( $actor[5], 36, "," );                                 //     26.   36  ACTOR 6  "    "                                             
		$file->write( $row['exrating'], 15, "," );                          //     27.   15  EXPANDED RATINGS   (multiple ratings are separated by comma)
		$file->write( "", 65, "," );                             //     28.  440  DESCRIPTION 1
		if($nodesc)
			$file->write( "", 225, "," );                            //     29.  440  DESCRIPTION 2                                               
		else
			$file->write( $row['ldesc'], 225, "," );                            //     29.  440  DESCRIPTION 2                                               
		$file->write( "", 40, "," );                                        //     30.  440  DESCRIPTION 3                                               
		$file->write( $row['blackandwhite'] == 1 ? "Y" : "N", 1, "," );     //     31.    1  BLACK & WHITE DESIGNATOR   (ALL DESIGNATORS CONTAIN         
		$file->write( $row['video'] == 1 ? "Y" : "N", 1, "," );             //     32.    1  VIDEO DESIGNATOR                "Y" OR "N")                 
		$file->write( $row['surround'] == 1 ? "Y" : "N", 1, "," );          //     33.    1  SURROUND SOUUND DESIGNATOR                                  
		$file->write( $row['season'] == 1 ? "Y" : "N", 1, "," );            //     34.    1  SEASONAL PROGRAM DESIGNATOR                                 
		$file->write( $row['infomertial'] == 1 ? "Y" : "N", 1, "," );       //     35.    1  INFOMERCIAL DESIGNATOR                                      
		$file->write( $row['animated'] == 1 ? "Y" : "N", 1, "," );          //     36.    1  ANIMATED PROGRAM DESIGNATOR                                 
		$file->write( $row['letbox'] == 1 ? "Y" : "N", 1 );                 //     37.    1  LETTERBOX VERSION DESIGNATOR                                
		$file->writeLn( );
	}
}



/************************
* Trae programación de películas con sinopsis
*************************/

println( date("[H:i:s] ") . "Consultando películas");

$sql = "SELECT ".
       "  slot.id id_slt, slot.date, slot.time, slot.duration, slot.title title_slt, slot.new_episode, ".
       "  chapter.id id_chp, chapter.movie, chapter.title, chapter.spanishtitle, ".
       "  movie.gender, movie.rated, movie.tvrated, movie.year, movie.country, movie.duration, movie.director, movie.actors, movie.description sdesc, ".
       "  chapter.points, chapter.blackandwhite, chapter.surround, chapter.animated, chapter.description ldesc, ".
       "  channel.id id_chn, channel.name name_chn ".
       "FROM client, channel, client_channel, slot, chapter, slot_chapter, movie ".
       "WHERE client.id = client_channel.client ".
       "  AND channel.id = client_channel.channel ".
       "  AND client.id = " . $ID_CLIENT ."".
       "  AND movie.id = chapter.movie".
       "  AND chapter.movie <> 0".
       "  AND channel.id = slot.channel ".
       "  AND slot.id = slot_chapter.slot ".
       "  AND chapter.id = slot_chapter.chapter ".
       "  AND slot.date BETWEEN '". $start_date ."' AND '". $stop_date ."' " .
       "ORDER by client_channel.number, slot.date, slot.time ";

$result = db_query( $sql, $DEBUG, $SYNC );

println( date("[H:i:s] ") . "Escribiendo películas" );

while($row = db_fetch_array($result))
{
	$slot_id[$row['id_slt']] = $row['id_slt'];
	$sch->appendReg(
	                 $row['id_chn'],
	                 $row['id_chp'],
	                 $row['title_slt'],
	                 $row['date'],
	                 $row['time'], 
	                 $row['duration'],
	                 $row['new_episode']
	                );

	if( $prg->appendReg( $row['id_chp'], $row['title_slt'] ) )
	{
//		$actor = explode(",", $row['starring'] );
		$actor = array();
		$row['year'] = "";
		$row['country'] = "";
		$row['director'] = "";
		$row['spanishtitle'] = "";
	
		if( tvCablePatch2($ID_CLIENT, $row['id_chn']) )
		{
			$row['ldesc'] = $row['title_slt'] . " Usando control parental puede ocultar los títulos y canales que no desee ver";
			$row['title'] = "Programación " . $row['name_chn'];
			$row['title_slt'] = "Programación " . $row['name_chn'];
			$row['spanishtitle'] = "Programación " . $row['name_chn'];
		}
		if( tvCablePatch($ID_CLIENT, $row['id_chn']) )
		{
			$row['ldesc'] = "Precio con IVA - " . $row['ldesc'];
		}
		else if( interCablePatch($ID_CLIENT, $row['id_chn']) )
		{
			$cost = $props->getProperty( "channel_" . $row['id_chn'] );
			$row['ldesc'] = "Bs.F. " . $cost . ",00 con IVA - " . $row['ldesc'];
			unset($cost);
		}
	
		$file->write( $prg->getInsertId(), 10, "," );						 					  //     1.    10  PROGRAM ID NUMBER (Link to Schedule File)                   
		$file->write( $row['title_slt'], 128, "," );                        //     2.   128  TITLE                                                       
		$file->write( $row['title'], 128, "," );                            //     3.   128  ALT. TITLE 1                                                
		$file->write( $row['spanishtitle'], 128, "," );                     //     4.   128  ALT. TITLE 2                                                
		$file->write( $row['alt3'], 128, "," );                             //     5.   128  ALT. TITLE 3                                                
		$file->write( $row['alt4'], 128, "," );                             //     6.   128  ALT. TITLE 4                                                
		$file->write( $row['alt5'], 128, "," );                             //     7.   128  ALT. TITLE 5                                                
		$file->write( $row['subt'], 128, "," );                             //     8.   128  SUBTITLE                                                    
		$file->write( $row['subt1'], 128, "," );                            //     9.   128  ALTERNATE SUBTITLE 1                                        
		$file->write( $row['subt2'], 128, "," );                            //     10.  128  ALTERNATE SUBTITLE 2                                        
		$file->write( $row['episode'], 70, "," );                           //     11.   70  EPISODE TITLE                                               
		$file->write( "01", 2, "," );                                       //     12.    2  PROGRAM TYPE                                                
		$file->write( $categories[$row['gender']], 18, "," );               //     13.   18  CATEGORY   - numeric code, refer to .CAT file               
		$file->write( $mpaa[ $row['rated'] ], 2, "," );                     //     14.    2  MPAA RATING                                                 
		$file->write( $vchip[ $row['rated'] ], 2, "," );                    //     15.    2  PARENTAL RATING  (VCHIP)                                    
		$file->write( $row['year'], 4, "," );                               //     16.    4  YEAR OF RELEASE ON MOVIES                                   
		$file->write( ( $row['points'] - 1 ) == 9 ? 8 : ( $row['points'] - 1 ), 1, "," );  //     17.    1  QUALITY STARS ON MOVIES                                     
		$file->write( $row['country'], 25, "," );                           //     18.   25  COUNTRY OF ORIGIN ON MOVIES                                 
		$file->write( toHM( $row['duration'] ), 4, "," );                   //     19.    4  TRUE DURATION (IN MINUTES)                                  
		$file->write( $row['director'], 40, "," );                          //     20.   40  DIRECTOR ON MOVIES                                          
		$file->write( $actor[0], 36, "," );                                 //     21.   36  ACTOR 1 ON  MOVIES                                          
		$file->write( $actor[1], 36, "," );                                 //     22.   36  ACTOR 2  "    "                                             
		$file->write( $actor[2], 36, "," );                                 //     23.   36  ACTOR 3  "    "                                             
		$file->write( $actor[3], 36, "," );                                 //     24.   36  ACTOR 4  "    "                                             
		$file->write( $actor[4], 36, "," );                                 //     25.   36  ACTOR 5  "    "                                             
		$file->write( $actor[5], 36, "," );                                 //     26.   36  ACTOR 6  "    "                                             
		$file->write( $row['exrating'], 15, "," );                          //     27.   15  EXPANDED RATINGS   (multiple ratings are separated by comma)
		$file->write( "", 65, "," );                             						//     28.  440  DESCRIPTION 1                                               
		if($nodesc)
			$file->write( "", 225, "," );                                   //     29.  440  DESCRIPTION 2                                               
		else
			$file->write( $row['ldesc'], 370, "," );                        //     29.  440  DESCRIPTION 2                                               
		$file->write( "", 40, "," );                                        //     30.  440  DESCRIPTION 3                                               
		$file->write( $row['blackandwhite'] == 1 ? "Y" : "N", 1, "," );     //     31.    1  BLACK & WHITE DESIGNATOR   (ALL DESIGNATORS CONTAIN         
		$file->write( $row['video'] == 1 ? "Y" : "N", 1, "," );             //     32.    1  VIDEO DESIGNATOR                "Y" OR "N")                 
		$file->write( $row['surround'] == 1 ? "Y" : "N", 1, "," );          //     33.    1  SURROUND SOUUND DESIGNATOR                                  
		$file->write( $row['season'] == 1 ? "Y" : "N", 1, "," );            //     34.    1  SEASONAL PROGRAM DESIGNATOR                                 
		$file->write( $row['infomertial'] == 1 ? "Y" : "N", 1, "," );       //     35.    1  INFOMERCIAL DESIGNATOR                                      
		$file->write( $row['animated'] == 1 ? "Y" : "N", 1, "," );          //     36.    1  ANIMATED PROGRAM DESIGNATOR                                 
		$file->write( $row['letbox'] == 1 ? "Y" : "N", 1 );                 //     37.    1  LETTERBOX VERSION DESIGNATOR                                
		$file->writeLn( );
	}
}

/************************
* Trae programación de especiales con sinopsis
*************************/

println( date("[H:i:s] ") . "Consultando especiales");

$sql = "SELECT ".
       "  slot.id id_slt, slot.date, slot.time, slot.duration, slot.title title_slt, slot.new_episode, ".
       "  chapter.id id_chp, chapter.special, chapter.title, chapter.spanishtitle, ".
       "  special.gender, special.rated, special.starring, special.description sdesc, ".
       "  chapter.points, chapter.blackandwhite, chapter.surround, chapter.animated, chapter.description ldesc, ".
       "  channel.id id_chn, channel.name name_chn ".
       "FROM client, channel, client_channel, slot, chapter, slot_chapter, special ".
       "WHERE client.id = client_channel.client ".
       "  AND channel.id = client_channel.channel ".
       "  AND client.id = " . $ID_CLIENT ."".
       "  AND special.id = chapter.special".
       "  AND chapter.special <> 0".
       "  AND channel.id = slot.channel ".
       "  AND slot.id = slot_chapter.slot ".
       "  AND chapter.id = slot_chapter.chapter ".
       "  AND slot.date BETWEEN '". $start_date ."' AND '". $stop_date ."' ".
       "ORDER by client_channel.number, slot.date, slot.time ";

$result = db_query( $sql, $DEBUG, $SYNC );

println( date("[H:i:s] ") . "Escribiendo especiales");

while($row = db_fetch_array($result))
{
	$slot_id[$row['id_slt']] = $row['id_slt'];
	$sch->appendReg(
	                 $row['id_chn'],
	                 $row['id_chp'],
	                 $row['title_slt'],
	                 $row['date'],
	                 $row['time'], 
	                 $row['duration'],
	                 $row['new_episode']
	                );

	if( $prg->appendReg( $row['id_chp'], $row['title_slt'] ) )
	{
		$actor = explode(",", $row['starring'] );
	
		if( tvCablePatch2($ID_CLIENT, $row['id_chn']) )
		{
			$row['ldesc'] = $row['title_slt'] . " Usando control parental puede ocultar los títulos y canales que no desee ver";
			$row['title'] = "Programación " . $row['name_chn'];
			$row['title_slt'] = "Programación " . $row['name_chn'];
			$row['spanishtitle'] = "Programación " . $row['name_chn'];
		}
		if( tvCablePatch($ID_CLIENT, $row['id_chn']) )
		{
			$row['ldesc'] = "Precio con IVA - " . $row['ldesc'];
		}
		else if( interCablePatch($ID_CLIENT, $row['id_chn']) )
		{
			$cost = $props->getProperty( "channel_" . $row['id_chn'] );
			$row['ldesc'] = "Bs.F. " . $cost . ",00 con IVA - " . $row['ldesc'];
			unset($cost);
		}
	
		$file->write( $prg->getInsertId(), 10, "," );						 					  //     1.    10  PROGRAM ID NUMBER (Link to Schedule File)                   
		$file->write( $row['title_slt'], 128, "," );                    	  //     2.   128  TITLE                                                       
		$file->write( $row['title'], 128, "," );                            //     3.   128  ALT. TITLE 1                                                
		$file->write( $row['spanishtitle'], 128, "," );                     //     4.   128  ALT. TITLE 2                                                
		$file->write( $row['alt3'], 128, "," );                             //     5.   128  ALT. TITLE 3                                                
		$file->write( $row['alt4'], 128, "," );                             //     6.   128  ALT. TITLE 4                                                
		$file->write( $row['alt5'], 128, "," );                             //     7.   128  ALT. TITLE 5                                                
		$file->write( $row['subt'], 128, "," );                             //     8.   128  SUBTITLE                                                    
		$file->write( $row['subt1'], 128, "," );                            //     9.   128  ALTERNATE SUBTITLE 1                                        
		$file->write( $row['subt2'], 128, "," );                            //     10.  128  ALTERNATE SUBTITLE 2                                        
		$file->write( $row['episode'], 70, "," );                           //     11.   70  EPISODE TITLE                                               
		$file->write( "10", 2, "," );                                       //     12.    2  PROGRAM TYPE                                                
		$file->write( $categories[$row['gender']], 18, "," );               //     13.   18  CATEGORY   - numeric code, refer to .CAT file               
		$file->write( $row['mpaa'], 2, "," );                               //     14.    2  MPAA RATING                                                 
		$file->write( $vchip[ $row['rated'] ], 2, "," );                    //     15.    2  PARENTAL RATING  (VCHIP)                                    
		$file->write( $row['year'], 4, "," );                               //     16.    4  YEAR OF RELEASE ON MOVIES                                   
		$file->write( ( $row['points'] - 1 ) == 9 ? 8 : ( $row['points'] - 1 ), 1, "," );  //     17.    1  QUALITY STARS ON MOVIES                                     
		$file->write( $row['country'], 25, "," );                           //     18.   25  COUNTRY OF ORIGIN ON MOVIES                                 
		$file->write( toHM( $row['duration'] ), 4, "," );                   //     19.    4  TRUE DURATION (IN MINUTES)                                  
		$file->write( $row['director'], 40, "," );                          //     20.   40  DIRECTOR ON MOVIES                                          
		$file->write( $actor[0], 36, "," );                                 //     21.   36  ACTOR 1 ON  MOVIES                                          
		$file->write( $actor[1], 36, "," );                                 //     22.   36  ACTOR 2  "    "                                             
		$file->write( $actor[2], 36, "," );                                 //     23.   36  ACTOR 3  "    "                                             
		$file->write( $actor[3], 36, "," );                                 //     24.   36  ACTOR 4  "    "                                             
		$file->write( $actor[4], 36, "," );                                 //     25.   36  ACTOR 5  "    "                                             
		$file->write( $actor[5], 36, "," );                                 //     26.   36  ACTOR 6  "    "                                             
		$file->write( $row['exrating'], 15, "," );                          //     27.   15  EXPANDED RATINGS   (multiple ratings are separated by comma)
		$file->write( "", 65, "," );                             //     28.  440  DESCRIPTION 1                                               
		if($nodesc)
			$file->write( "", 225, "," );                            //     29.  440  DESCRIPTION 2                                               
		else
			$file->write( $row['ldesc'], 225, "," );                            //     29.  440  DESCRIPTION 2                                               
		$file->write( "", 40, "," );                                        //     30.  440  DESCRIPTION 3                                               
		$file->write( $row['blackandwhite'] == 1 ? "Y" : "N", 1, "," );     //     31.    1  BLACK & WHITE DESIGNATOR   (ALL DESIGNATORS CONTAIN         
		$file->write( $row['video'] == 1 ? "Y" : "N", 1, "," );             //     32.    1  VIDEO DESIGNATOR                "Y" OR "N")                 
		$file->write( $row['surround'] == 1 ? "Y" : "N", 1, "," );          //     33.    1  SURROUND SOUUND DESIGNATOR                                  
		$file->write( $row['season'] == 1 ? "Y" : "N", 1, "," );            //     34.    1  SEASONAL PROGRAM DESIGNATOR                                 
		$file->write( $row['infomertial'] == 1 ? "Y" : "N", 1, "," );       //     35.    1  INFOMERCIAL DESIGNATOR                                      
		$file->write( $row['animated'] == 1 ? "Y" : "N", 1, "," );          //     36.    1  ANIMATED PROGRAM DESIGNATOR                                 
		$file->write( $row['letbox'] == 1 ? "Y" : "N", 1 );                 //     37.    1  LETTERBOX VERSION DESIGNATOR                                
		$file->writeLn( );
	}
}

$last_with_sinopsis = $prg->getInsertId();

/************************
* Trae programación sin sinopsis
*************************/

println( date("[H:i:s] ") . "Escribiendo programación sin clasificar" );

$sql = "SELECT ".
       "  slot.id id_slt, slot.title, slot.date, slot.time, slot.duration, ".
       "  channel.id id_chn, channel.name name_chn, ".
       "  client_channel._group ".
       "FROM ppa.client, channel, client_channel, slot ".
       "WHERE ppa.client.id = client_channel.client ".
       "  AND channel.id = client_channel.channel ".
       "  AND ppa.client.id = " . $ID_CLIENT ."".
       "  AND channel.id = slot.channel ".
       "  AND slot.date BETWEEN '". $start_date ."' AND '". $stop_date ."' ".
       "  AND slot.id NOT IN (" . implode(",", array_keys( $slot_id ) ) . ") ".
       "ORDER by client_channel.number, slot.date, slot.time ";

$result = db_query( $sql, $DEBUG, $SYNC );

while($row = db_fetch_array($result))
{
	$sch->appendReg(
	                 $row['id_chn'],
	                 $row['id_slt'],
	                 $row['title'],
	                 $row['date'],
	                 $row['time'], 
	                 $row['duration'],
	                 '0'
	                );
	                
	if( $prg->appendReg( $row['id_chp'], $row['title'] ) )
	{
		$desc = $row['title'];

		if( tvCablePatch2($ID_CLIENT, $row['id_chn']) )
		{
			$row['ldesc'] = $row['title_slt'] . " Usando control parental puede ocultar los títulos y canales que no desee ver";
			$row['title'] = "Programación " . $row['name_chn'];
			$row['title_slt'] = "Programación " . $row['name_chn'];
			$row['spanishtitle'] = "Programación " . $row['name_chn'];
		}
		if( tvCablePatch($ID_CLIENT, $row['id_chn']) )
		{
			$row['ldesc'] = "Precio con IVA - " . $row['ldesc'];
		}
		else if( interCablePatch($ID_CLIENT, $row['id_chn']) )
		{
			$cost = $props->getProperty( "channel_" . $row['id_chn'] );
			$desc = "Bs.F. " . $cost . ",00 con IVA - " . $row['title'];
			unset($cost);
		}
		
		$cat   = $props->getProperty("cat_" . strtolower($row['_group'])) ;
		$ptype =  $props->getProperty("ptype_" . strtolower($row['_group']) ) != "" ? $props->getProperty("ptype_" . strtolower($row['_group'])) : "62";
		
		$file->write( $prg->getInsertId(), 10, "," );						//     1.    10  PROGRAM ID NUMBER (Link to Schedule File)                   
		$file->write( $row['title'], 128, "," );                            //     2.   128  TITLE                                                       
		$file->write( "", 128, "," );                                       //     3.   128  ALT. TITLE 1                                                
		$file->write( "", 128, "," );                                       //     4.   128  ALT. TITLE 2                                                
		$file->write( "", 128, "," );                                       //     5.   128  ALT. TITLE 3                                                
		$file->write( "", 128, "," );                                       //     6.   128  ALT. TITLE 4                                                
		$file->write( "", 128, "," );                                       //     7.   128  ALT. TITLE 5                                                
		$file->write( "", 128, "," );                                       //     8.   128  SUBTITLE                                                    
		$file->write( "", 128, "," );                                       //     9.   128  ALTERNATE SUBTITLE 1                                        
		$file->write( "", 128, "," );                                       //     10.  128  ALTERNATE SUBTITLE 2                                        
		$file->write( "", 70, "," );                                        //     11.   70  EPISODE TITLE                                               
		$file->write( $ptype, 2, "," );                                     //     12.    2  PROGRAM TYPE                                                
		$file->write( $cat, 18, "," );                                      //     13.   18  CATEGORY   - numeric code, refer to .CAT file               
		$file->write( "", 2, "," );                                         //     14.    2  MPAA RATING                                                 
		$file->write( "", 2, "," );                                         //     15.    2  PARENTAL RATING  (VCHIP)                                    
		$file->write( "", 4, "," );                                         //     16.    4  YEAR OF RELEASE ON MOVIES                                   
		$file->write( "", 1, "," );                                         //     17.    1  QUALITY STARS ON MOVIES                                     
		$file->write( "", 25, "," );                                        //     18.   25  COUNTRY OF ORIGIN ON MOVIES                                 
		$file->write( toHM( $row['duration'] ), 4, "," );                   //     19.    4  TRUE DURATION (IN MINUTES)                                  
		$file->write( "", 40, "," );                                        //     20.   40  DIRECTOR ON MOVIES                                          
		$file->write( "", 36, "," );                                        //     21.   36  ACTOR 1 ON  MOVIES                                          
		$file->write( "", 36, "," );                                        //     22.   36  ACTOR 2  "    "                                             
		$file->write( "", 36, "," );                                        //     23.   36  ACTOR 3  "    "                                             
		$file->write( "", 36, "," );                                        //     24.   36  ACTOR 4  "    "                                             
		$file->write( "", 36, "," );                                        //     25.   36  ACTOR 5  "    "                                             
		$file->write( "", 36, "," );                                        //     26.   36  ACTOR 6  "    "                                             
		$file->write( "", 15, "," );                                        //     27.   15  EXPANDED RATINGS   (multiple ratings are separated by comma)
		$file->write( "", 65, "," );                                        //     28.  440  DESCRIPTION 1                                               
		if($nodesc)
			$file->write( "", 225, "," );                            //     29.  440  DESCRIPTION 2                                               
		else
			$file->write( $desc, 225, "," );                                    //     29.  440  DESCRIPTION 2                                               
		$file->write( "", 40, "," );                                        //     30.  440  DESCRIPTION 3                                               
		$file->write( "N", 1, "," );                                        //     31.    1  BLACK & WHITE DESIGNATOR   (ALL DESIGNATORS CONTAIN         
		$file->write( "N", 1, "," );                                        //     32.    1  VIDEO DESIGNATOR                "Y" OR "N")                 
		$file->write( "N", 1, "," );                                        //     33.    1  SURROUND SOUUND DESIGNATOR                                  
		$file->write( "N", 1, "," );                                        //     34.    1  SEASONAL PROGRAM DESIGNATOR                                 
		$file->write( "N", 1, "," );                                        //     35.    1  INFOMERCIAL DESIGNATOR                                      
		$file->write( "N", 1, "," );                                        //     36.    1  ANIMATED PROGRAM DESIGNATOR                                 
		$file->write( "N", 1 );                                             //     37.    1  LETTERBOX VERSION DESIGNATOR                                
		$file->writeLn( );
	}
}

/************************
* Trae canales sin programación
*************************/

println ( date("[H:i:s] ") . "Canales sin programación") ;

$sql =	"SELECT ".
		"  DISTINCT channel ".
		"FROM slot ".
		"WHERE ".
		"  date BETWEEN '". $start_date ."' AND '". $stop_date ."' ";

$result = db_query( $sql, $DEBUG, $SYNC );

while($row = db_fetch_array($result))
{
	$channels[] = "'" . $row['channel'] . "'";
}

$sql =	"SELECT ".
		"  * ".
		"FROM ".
		"  channel, client_channel ".
		"WHERE ".
		"  client_channel.channel =  channel.id ".
		"  AND client_channel.client = " . $ID_CLIENT ." ".
		"  AND client_channel.channel NOT IN (" . implode(",", $channels ) . ") ".
		"  ORDER BY id";
		  
$result = db_query( $sql, $DEBUG, $SYNC );

while($row = db_fetch_array($result))
{
//	for($date = strtotime($start_date); $date <= strtotime($stop_date); $date += (24*60*60))
	for($date = strtotime($start_date); $date <= strtotime($stop_date); $date += (60*60))
	{
		$sch->appendReg(
		                 $row['id'],
		                 $row['id'] . "99999",
		                 "Programación " . $row['name'],
		                 date("Y-m-d", $date),
		                 date("H:i:00", $date),
		                 (60*24),
		                 '0'
		                );
	}

	$file->write( $row['id'] ."99999", 10, "," );             //     1.    10  PROGRAM ID NUMBER (Link to Schedule File)                   
	$file->write( "Programación " . $row['name'], 128, "," ); //     2.   128  TITLE                                                       
	$file->write( "", 128, "," );                             //     3.   128  ALT. TITLE 1                                                
	$file->write( "", 128, "," );                             //     4.   128  ALT. TITLE 2                                                
	$file->write( "", 128, "," );                             //     5.   128  ALT. TITLE 3                                                
	$file->write( "", 128, "," );                             //     6.   128  ALT. TITLE 4                                                
	$file->write( "", 128, "," );                             //     7.   128  ALT. TITLE 5                                                
	$file->write( "", 128, "," );                             //     8.   128  SUBTITLE                                                    
	$file->write( "", 128, "," );                             //     9.   128  ALTERNATE SUBTITLE 1                                        
	$file->write( "", 128, "," );                             //     10.  128  ALTERNATE SUBTITLE 2                                        
	$file->write( "", 70, "," );                              //     11.   70  EPISODE TITLE                                               
	$file->write( "62", 2, "," );                             //     12.    2  PROGRAM TYPE                                                
	$file->write( "", 18, "," );                              //     13.   18  CATEGORY   - numeric code, refer to .CAT file               
	$file->write( "", 2, "," );                               //     14.    2  MPAA RATING                                                 
	$file->write( "", 2, "," );                               //     15.    2  PARENTAL RATING  (VCHIP)                                    
	$file->write( "", 4, "," );                               //     16.    4  YEAR OF RELEASE ON MOVIES                                   
	$file->write( "", 1, "," );                               //     17.    1  QUALITY STARS ON MOVIES                                     
	$file->write( "", 25, "," );                              //     18.   25  COUNTRY OF ORIGIN ON MOVIES                                 
	$file->write( 60*24, 4, "," );                            //     19.    4  TRUE DURATION (IN MINUTES)                                  
	$file->write( "", 40, "," );                              //     20.   40  DIRECTOR ON MOVIES                                          
	$file->write( "", 36, "," );                              //     21.   36  ACTOR 1 ON  MOVIES                                          
	$file->write( "", 36, "," );                              //     22.   36  ACTOR 2  "    "                                             
	$file->write( "", 36, "," );                              //     23.   36  ACTOR 3  "    "                                             
	$file->write( "", 36, "," );                              //     24.   36  ACTOR 4  "    "                                             
	$file->write( "", 36, "," );                              //     25.   36  ACTOR 5  "    "                                             
	$file->write( "", 36, "," );                              //     26.   36  ACTOR 6  "    "                                             
	$file->write( "", 15, "," );                              //     27.   15  EXPANDED RATINGS   (multiple ratings are separated by comma)
	$file->write( "", 65, "," );                              //     28.  440  DESCRIPTION 1                                               
	if($nodesc)
		$file->write( "", 225, "," );                            //     29.  440  DESCRIPTION 2                                               
	else
		$file->write( $row['name'], 140, "," );                   //     29.  440  DESCRIPTION 2                                               
	$file->write( "", 40, "," );                              //     30.  440  DESCRIPTION 3                                               
	$file->write( "", 1, "," );                               //     31.    1  BLACK & WHITE DESIGNATOR   (ALL DESIGNATORS CONTAIN         
	$file->write( "", 1, "," );                               //     32.    1  VIDEO DESIGNATOR                "Y" OR "N")                 
	$file->write( "", 1, "," );                               //     33.    1  SURROUND SOUUND DESIGNATOR                                  
	$file->write( "", 1, "," );                               //     34.    1  SEASONAL PROGRAM DESIGNATOR                                 
	$file->write( "", 1, "," );                               //     35.    1  INFOMERCIAL DESIGNATOR                                      
	$file->write( "", 1, "," );                               //     36.    1  ANIMATED PROGRAM DESIGNATOR                                 
	$file->write( "", 1 );                                    //     37.    1  LETTERBOX VERSION DESIGNATOR                                
	$file->writeLn( );	
}

/************************
* *
* Genera archivo de horarios (.sch)
* *
*************************/

println ( date("[H:i:s] ") . "Generando archivo de horarios" );

$file     = new WriteFile( $PREFIX . date("md") .".sch" );
$channels = Array();

/************************
* Actualiza tabla temporal 'Schedule'
*************************/

$sql =	"SELECT ".
		"  * ".
		"FROM ".
		"  program ".
		"WHERE ".
		"  1 ";

$prg->query( $sql );

while($prg->fetchProgramArray())
{
	$sql = "UPDATE schedule SET " . 
		"program_id = '" . $prg->getId() ."' ".
		"WHERE title = '" . addslashes( $prg->getTitle() )."'";
	db_query( $sql, $DEBUG, $SYNC );
}

/************************
* Escribe archivo de horarios (.sch)
*************************/

println ( date("[H:i:s] ") . "Escribiendo archivo de horarios" );

$sch->selectAllSchedule();
$sch->fetchScheduleArray();

$last_array['channel']     = $sch->getChannel();
$last_array['program']     = $sch->getProgram();
$last_array['time']        = $sch->getTime();
$last_array['date']        = $sch->getDate();
$last_array['new_episode'] = ($sch->isNewEpisode()?"New":"");

while($sch->fetchScheduleArray())
{
	$duration = ( strtotime( $sch->getDate() ." ". $sch->getTime() ) - ( strtotime( $last_array['date'] ." ". $last_array['time'] ) ) ) / 60;
	if ($duration < 0 )	$duration = 30;
	
	$file->write( $last_array['channel'], 10, "," );                                               //     1.    10  CHANNEL ID NUMBER (Link to Channel File)            
	$file->write( $last_array['program'], 10, "," );                                               //     2.    10  PROGRAM ID NUMBER (Link to Program File)            
	$file->write( date( "mdy", strtotime( $last_array['date'] ." ". $last_array['time']  )  + ($GMT * 60 * 60) ), 6, "," );   //     3.     6  DATE - STYLE: MMDDYY  (NEW DATE CHANGES AT MIDNIGHT)
	$file->write( date( "Hi", strtotime( $last_array['time'] ) + ($GMT * 60 * 60)) , 4, "," );      //     4.     4  TIME - STYLE: HHMM    (24 HR FORMAT)                
	$file->write( toHM( $duration ), 4, "," );                                                     //     5.     4  COMPUTED DURATION - STYLE: HHMM                     
	$file->write( $vacio['q1'], 50, "," );                                                         //     6.    50  QUALIFIER 1  (MULTIPLE QUALIFIERS IN EACH LEVEL ARE 
	$file->write( $vacio['q2'], 40, "," );                                                         //     7.    40  QUALIFIER 2     SEPARATED BY COMMAS)                
	$file->write( $vacio['q3'], 40, "," );                                                         //     8.    40  QUALIFIER 3  (see attached list of all qualifiers)  
	$file->write( $last_array['new_episode'], 40, "," );                                                         //     9.    40  QUALIFIER 4                                         
	$file->write( $vacio['q5'], 40 );                                                              //     10.   40  QUALIFIER 5                                         
	$file->writeLn( );
	
	if( ( $last_array['channel'] != $sch->getChannel() ) && ( $last_array['date'] < $stop_date ) )
	{	
		for($date = strtotime( $last_array['date'] ." ". $last_array['time'] )  + ($GMT * 60 * 60) + ($duration * 60) ; $date <= strtotime($stop_date); $date += (24*60*60))
		{
			$channels[$last_array['channel']] = "'" . $last_array['channel'] . "'";
			$file->write( $last_array['channel'], 10, "," );           //     1.    10  CHANNEL ID NUMBER (Link to Channel File)            
			$file->write( $last_array['channel'] . "99999", 10, "," ); //     2.    10  PROGRAM ID NUMBER (Link to Program File)            
			$file->write( date( "mdy", $date), 6, "," );               //     3.     6  DATE - STYLE: MMDDYY  (NEW DATE CHANGES AT MIDNIGHT)
			$file->write( date( "Hi", $date), 4, "," );                //     3.     6  DATE - STYLE: MMDDYY  (NEW DATE CHANGES AT MIDNIGHT)
			$file->write( "2400", 4, "," );                            //     5.     4  COMPUTED DURATION - STYLE: HHMM                     
			$file->write( $vacio['q1'], 50, "," );                     //     6.    50  QUALIFIER 1  (MULTIPLE QUALIFIERS IN EACH LEVEL ARE 
			$file->write( $vacio['q2'], 40, "," );                     //     7.    40  QUALIFIER 2     SEPARATED BY COMMAS)                
			$file->write( $vacio['q3'], 40, "," );                     //     8.    40  QUALIFIER 3  (see attached list of all qualifiers)  
			$file->write( $vacio['q4'], 40, "," );                     //     9.    40  QUALIFIER 4                                         
			$file->write( $vacio['q5'], 40 );                          //     10.   40  QUALIFIER 5                                         
			$file->writeLn( );
		}
	}

	$last_array['channel']     = $sch->getChannel();
	$last_array['program']     = $sch->getProgram();
	$last_array['time']        = $sch->getTime();
	$last_array['date']        = $sch->getDate();
	$last_array['new_episode'] = ($sch->isNewEpisode()?"New":"");
}

unset($last_array);

$file->close();

/************************
* *
* Actualiza archivo de Programas (.prg)
* *
*************************/

$file = new WriteFile( $PREFIX . date("md") .".prg", "a+" );

/************************
* Actualiza archivo de programas(.prg)
*************************/

println( date("[H:i:s] ") . "Actualizando archivo de programas");

if(!empty ($channels) )
{
	$sql =	"SELECT ".
			"  * ".
			"FROM ".
			"  channel ".
			"WHERE ".
			"  id IN (" . implode(",", $channels ) . ") ";
	
	$result = db_query( $sql, $DEBUG, $SYNC );
	
	while($row = db_fetch_array($result))
	{
		$file->write( $row['id'] ."99999", 10, "," );             //     1.    10  PROGRAM ID NUMBER (Link to Schedule File)                   
		$file->write( "Programación " . $row['name'], 128, "," ); //     2.   128  TITLE                                                       
		$file->write( "", 128, "," );                             //     3.   128  ALT. TITLE 1                                                
		$file->write( "", 128, "," );                             //     4.   128  ALT. TITLE 2                                                
		$file->write( "", 128, "," );                             //     5.   128  ALT. TITLE 3                                                
		$file->write( "", 128, "," );                             //     6.   128  ALT. TITLE 4                                                
		$file->write( "", 128, "," );                             //     7.   128  ALT. TITLE 5                                                
		$file->write( "", 128, "," );                             //     8.   128  SUBTITLE                                                    
		$file->write( "", 128, "," );                             //     9.   128  ALTERNATE SUBTITLE 1                                        
		$file->write( "", 128, "," );                             //     10.  128  ALTERNATE SUBTITLE 2                                        
		$file->write( "", 70, "," );                              //     11.   70  EPISODE TITLE                                               
		$file->write( "62", 2, "," );                             //     12.    2  PROGRAM TYPE                                                
		$file->write( "", 18, "," );                              //     13.   18  CATEGORY   - numeric code, refer to .CAT file               
		$file->write( "", 2, "," );                               //     14.    2  MPAA RATING                                                 
		$file->write( "", 2, "," );                               //     15.    2  PARENTAL RATING  (VCHIP)                                    
		$file->write( "", 4, "," );                               //     16.    4  YEAR OF RELEASE ON MOVIES                                   
		$file->write( "", 1, "," );                               //     17.    1  QUALITY STARS ON MOVIES                                     
		$file->write( "", 25, "," );                              //     18.   25  COUNTRY OF ORIGIN ON MOVIES                                 
		$file->write( 60*24, 4, "," );                            //     19.    4  TRUE DURATION (IN MINUTES)                                  
		$file->write( "", 40, "," );                              //     20.   40  DIRECTOR ON MOVIES                                          
		$file->write( "", 36, "," );                              //     21.   36  ACTOR 1 ON  MOVIES                                          
		$file->write( "", 36, "," );                              //     22.   36  ACTOR 2  "    "                                             
		$file->write( "", 36, "," );                              //     23.   36  ACTOR 3  "    "                                             
		$file->write( "", 36, "," );                              //     24.   36  ACTOR 4  "    "                                             
		$file->write( "", 36, "," );                              //     25.   36  ACTOR 5  "    "                                             
		$file->write( "", 36, "," );                              //     26.   36  ACTOR 6  "    "                                             
		$file->write( "", 15, "," );                              //     27.   15  EXPANDED RATINGS   (multiple ratings are separated by comma)
		$file->write( "", 65, "," );                              //     28.  440  DESCRIPTION 1                                               
		if($nodesc)
			$file->write( "", 225, "," );                            //     29.  440  DESCRIPTION 2                                               
		else
			$file->write( $row['name'], 140, "," );                   //     29.  440  DESCRIPTION 2                                               
		$file->write( "", 40, "," );                              //     30.  440  DESCRIPTION 3                                               
		$file->write( "", 1, "," );                               //     31.    1  BLACK & WHITE DESIGNATOR   (ALL DESIGNATORS CONTAIN         
		$file->write( "", 1, "," );                               //     32.    1  VIDEO DESIGNATOR                "Y" OR "N")                 
		$file->write( "", 1, "," );                               //     33.    1  SURROUND SOUUND DESIGNATOR                                  
		$file->write( "", 1, "," );                               //     34.    1  SEASONAL PROGRAM DESIGNATOR                                 
		$file->write( "", 1, "," );                               //     35.    1  INFOMERCIAL DESIGNATOR                                      
		$file->write( "", 1, "," );                               //     36.    1  ANIMATED PROGRAM DESIGNATOR                                 
		$file->write( "", 1 );                                    //     37.    1  LETTERBOX VERSION DESIGNATOR                                
		$file->writeLn( );	
	}
	
	$file->close();
}
	
/************************
* *
* Genera archivo de PPV (.ppv)
* *
*************************/

println( date("[H:i:s] ") . "Generando archivo de PPV" );
$file = new WriteFile( $PREFIX . date("md") .".ppv" );

/************************
* Trae programacion PPV
*************************/

$sql = "SELECT ".
       "  slot.id sid, slot.title, slot.date, slot.time, slot.duration, ".
       "  channel.id id, channel.shortname, ".
       "  client_channel.number ".
       "FROM ppa.client, channel, client_channel, slot ".
       "WHERE ppa.client.id = client_channel.client ".
       "  AND channel.id = client_channel.channel ".
       "  AND ppa.client.id = " . $ID_CLIENT ."".
       "  AND channel.id = slot.channel ".
       "  AND client_channel._group like 'PPV' ".
       "  AND slot.date BETWEEN '". $start_date ."' AND '". $stop_date_ppv ."' ".
       "ORDER by client_channel.number, slot.date, slot.time ";


$result = db_query( $sql, $DEBUG, $SYNC );

println( date("[H:i:s] ") . "Escribiendo archivo de PPV" );

$file->write( "IpgProviderId", 100, "," );	    //"IpgProviderId" - Service identifier; same as in *.sch and *.chn file     
$file->write( "EventID", 100, "," );            //"EventID"       - Unique ID that identifies an event in a time slot on a  
$file->write( "CallLetters", 100, "," );        //"CallLetters"   - Same as call letters of the PPV service in *.chn file.  
$file->write( "StartDateTime", 100, "," );      //"StartDateTime" - Start time of event same as in *.sch file.  In UTC time.
$file->write( "RunningTime", 100, "," );    		//"RunningTime"   - Time slot duration in minutes.  Note that this may be   
$file->write( "Title", 100, "," );              //"Title" - Title as in the *.prg file.                                     
$file->write( "Cost", 100, "" );                //"Cost"  - Cost of event in centavos.                                      
$file->writeLn( );

$row = db_fetch_array($result);
$cid = array();

while($next_row = db_fetch_array($result))
{
	$duration = ( strtotime( $next_row['date'] ." ". $next_row['time'] ) - ( strtotime( $row['date'] ." ". substr( $row['time'], 0, 5) . ":00" ) ) ) / 60;
	$duration = sprintf( "%d", $duration );
	if ($duration < 0 )	$duration = 30;

	$time_field =  date("Y-m-d ", strtotime( $row['date'] ." ". $row['time'] ) + ($GMT * 60 * 60) ) . date("H:i:00", strtotime( $row['time'] ) + ($GMT * 60 * 60) );
	if( $row['id'] == 506 && ereg( "00:00:00|19:00:00", $row['time'] ) && $row["date"] < '2008-07-01' ) $cost_field = "1200";
	elseif( $row['id'] == 507 && ereg( "00:00:00|19:00:00", $row['time'] ) && $row["date"] < '2008-07-01' ) $cost_field = "1000";
	elseif( $row['id'] == 508 && ereg( "00:00:00|19:00:00", $row['time'] ) && $row["date"] < '2008-07-01' ) $cost_field = "1300";
	else $cost_field = $props->getProperty( "channel_" . $row['id'] );
	
	if( $cost_field == "" ) println( "channel_" . $row['id'] . " whitout price");
//	$cost_field = $row['number'] <= 113 ? "620" : "1110";
	              
	$file->write( $row['id'], 10, ",", "" );						//"IpgProviderId" - Service identifier; same as in *.sch and *.chn file     
	$file->write( $row['sid'], 10, ",", "" );           //"EventID"       - Unique ID that identifies an event in a time slot on a  
	$file->write( $row['shortname'], 10, "," );         //"CallLetters"   - Same as call letters of the PPV service in *.chn file.  
	$file->write( $time_field, 20, ",", "" );           //"StartDateTime" - Start time of event same as in *.sch file.  In UTC time.
	$file->write( $duration, 10, ",", "" );             //"RunningTime"   - Time slot duration in minutes.  Note that this may be   
	$file->write( $row['title'], 100, "," );            //"Title" - Title as in the *.prg file.                                     
	$file->write( $cost_field, 100, "", "" );           //"Cost"  - Cost of event in centavos.                                      
	$file->writeLn( );
	$cid[$row['id']] = $row['id'];

	
	$copies = $props->getProperty( "channel_copy_" . $row['shortname'] );
	if( $copies )	{
		$copies = json_decode( $copies, true );
		foreach( $copies as $CopyName => $CopyObj )	{
			
			$file->write( $CopyObj['id'], 10, ",", "" );		//"IpgProviderId" - Service identifier; same as in *.sch and *.chn file     
			$file->write( $row['sid'], 10, ",", "" );           //"EventID"       - Unique ID that identifies an event in a time slot on a  
			$file->write( $CopyName, 10, "," );         		//"CallLetters"   - Same as call letters of the PPV service in *.chn file.  
			$file->write( $time_field, 20, ",", "" );           //"StartDateTime" - Start time of event same as in *.sch file.  In UTC time.
			$file->write( $duration, 10, ",", "" );             //"RunningTime"   - Time slot duration in minutes.  Note that this may be   
			$file->write( $row['title'], 100, "," );            //"Title" - Title as in the *.prg file.                                     
			$file->write( $CopyObj['price'], 100, "", "" );     //"Cost"  - Cost of event in centavos.                                      
			$file->writeLn( );
		}
	}
	

	$row = $next_row;
}

/************************
* Trae Canales PPV sin programación
*************************/

$sql = "SELECT ".
       "  channel.id id, channel.shortname, channel.name, ".
       "  client_channel.number ".
       "FROM ppa.client, channel, client_channel ".
       "WHERE ppa.client.id = client_channel.client ".
       "  AND channel.id = client_channel.channel ".
       "  AND ppa.client.id = " . $ID_CLIENT ."".
       "  AND client_channel._group like 'PPV' ".
       "  AND channel.id NOT IN (" . implode(",", array_keys( $cid ) ) . ") ".
       "ORDER by client_channel.number ";

$result = db_query( $sql, $DEBUG, $SYNC );

while($row = db_fetch_array($result))
{
//	for($date = strtotime($start_date), $i=0; $date <= strtotime($stop_date_ppv); $date += (24*60*60), $i++)
	for($date = strtotime($start_date), $i=0; $date <= strtotime($stop_date_ppv); $date += (60*60), $i++)
	{
//			$time_field =  date("Y-m-d ", $date + ($GMT * 60 * 60) ) . date("H:i:s", strtotime( "00:00:00" ) + ($GMT * 60 * 60) );
			$time_field =  date("Y-m-d H:i:00", $date + ($GMT * 60 * 60) );
//			$cost_field = $row['number'] <= 113 ? "620" : "1110";
			$cost_field = $props->getProperty( "channel_" . $row['id'] );
			              
			$file->write( $row['id'], 10, ",", "" );              //"IpgProviderId" - Service identifier; same as in *.sch and *.chn file     
			$file->write( $row['id'] . $i . "999", 10, ",", "" ); //"EventID"       - Unique ID that identifies an event in a time slot on a  
			$file->write( $row['shortname'], 10, "," );           //"CallLetters"   - Same as call letters of the PPV service in *.chn file.  
			$file->write( $time_field, 20, ",", "" );             //"StartDateTime" - Start time of event same as in *.sch file.  In UTC time.
//			$file->write( (60*24, 10, ",", "" );                 //"RunningTime"   - Time slot duration in minutes.  Note that this may be   
			$file->write( "60", 10, ",", "" );                 //"RunningTime"   - Time slot duration in minutes.  Note that this may be   
			$file->write( "Programación " . $row['name'], 100, "," );                //"Title" - Title as in the *.prg file.                                     
			$file->write( $cost_field, 100, "", "" );             //"Cost"  - Cost of event in centavos.                                      
			$file->writeLn( );
			
			
			$copies = $props->getProperty( "channel_copy_" . $row['shortname'] );
			if( $copies )	{
				$copies = json_decode( $copies, true );
				foreach( $copies as $CopyName => $CopyObj )	{
					
					$file->write( $CopyObj['id'], 10, ",", "" );		//"IpgProviderId" - Service identifier; same as in *.sch and *.chn file     
					$file->write( $CopyObj['id'], 10, ",", "" );        //"EventID"       - Unique ID that identifies an event in a time slot on a  
					$file->write( $CopyName, 10, "," );         		//"CallLetters"   - Same as call letters of the PPV service in *.chn file.  
					$file->write( $time_field, 20, ",", "" );           //"StartDateTime" - Start time of event same as in *.sch file.  In UTC time.
					$file->write( 60, 10, ",", "" );             		//"RunningTime"   - Time slot duration in minutes.  Note that this may be   
					$file->write( "Programación " . $row['name'], 100, "," );        //"Title" - Title as in the *.prg file.                                     
					$file->write( $CopyObj['price'], 100, "", "" );     //"Cost"  - Cost of event in centavos.                                      
					$file->writeLn( );
				}
			}
			
			
	}
}

$file->close();

/************************
* *
* Comprime archivos en zip (.zip)
* *
*************************/

println( date("[H:i:s] ") . "Coprimiendo archivos" );

@unlink(OUT_PATH . $PREFIX . ".zip");

$command = "cd " . OUT_PATH . "; zip " . $PREFIX . ".zip " . $PREFIX . date("md") .".*";
`$command`;

println( date("[H:i:s] ") . "Enviando archivo" );

$command = "scp " . OUT_PATH . $PREFIX . ".zip " . $props->getProperty( "ftp_user" ) ."@" . $props->getProperty( "ftp_server" ) . ":~/" . $PREFIX . date("md") .".zip";
`$command`;

println( date("[H:i:s] ") . "Tarea realizada en -> " .  ( time() - $starttime )  / 60  . " minutos" );
//println( date("[H:i:s] ") . "Tarea realizada en -> " . toHM ( (time() - $starttime )  / 60 ) . " minutos" );
db_close();
?>
