<?
session_start();
set_time_limit( -1 );
function spanishString( $aStr ){
	$aStr = str_replace( "á", "\'e1", $aStr );
	$aStr = str_replace( "é", "\'e9", $aStr );
	$aStr = str_replace( "í", "\'ed", $aStr );
	$aStr = str_replace( "ó", "\'f3", $aStr );
	$aStr = str_replace( "ú", "\'fa", $aStr );
	$aStr = str_replace( "Á", "\'c1", $aStr );
	$aStr = str_replace( "É", "\'c9", $aStr );
	$aStr = str_replace( "Í", "\'cd", $aStr );
	$aStr = str_replace( "Ó", "\'d3", $aStr );
	$aStr = str_replace( "Ú", "\'da", $aStr );
	$aStr = str_replace( "Ñ", "\'d1", $aStr );
	$aStr = str_replace( "ñ", "\'f1", $aStr );
	return $aStr;
}
header('Content-Type: application/rtf');
header('Content-Disposition: attachment; filename="archivo.rtf"');
?>
{\rtf1\ansi\ansicpg1252\uc1\deff0\stshfdbch0\stshfloch0\stshfhich0\stshfbi0\deflang3082\deflangfe3082{\fonttbl{\f0\froman\fcharset0\fprq2{\*\panose 02020603050405020304}Times New Roman{\*\falt Courier New};}
{\f37\froman\fcharset238\fprq2 Times New Roman CE{\*\falt Courier New};}{\f38\froman\fcharset204\fprq2 Times New Roman Cyr{\*\falt Courier New};}{\f40\froman\fcharset161\fprq2 Times New Roman Greek{\*\falt Courier New};}
{\f41\froman\fcharset162\fprq2 Times New Roman Tur{\*\falt Courier New};}{\f42\froman\fcharset177\fprq2 Times New Roman (Hebrew){\*\falt Courier New};}{\f43\froman\fcharset178\fprq2 Times New Roman (Arabic){\*\falt Courier New};}
{\f44\froman\fcharset186\fprq2 Times New Roman Baltic{\*\falt Courier New};}{\f45\froman\fcharset163\fprq2 Times New Roman (Vietnamese){\*\falt Courier New};}}{\colortbl;\red0\green0\blue0;\red0\green0\blue255;\red0\green255\blue255;\red0\green255\blue0;
\red255\green0\blue255;\red255\green0\blue0;\red255\green255\blue0;\red255\green255\blue255;\red0\green0\blue128;\red0\green128\blue128;\red0\green128\blue0;\red128\green0\blue128;\red128\green0\blue0;\red128\green128\blue0;\red128\green128\blue128;
\red192\green192\blue192;}{\stylesheet{\ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 \fs24\lang3082\langfe3082\cgrid\langnp3082\langfenp3082 \snext0 Normal;}{\*\cs10 \additive \ssemihidden Default Paragraph Font;}{\*
\ts11\tsrowd\trftsWidthB3\trpaddl108\trpaddr108\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3\trcbpat1\trcfpat1\tscellwidthfts0\tsvertalt\tsbrdrt\tsbrdrl\tsbrdrb\tsbrdrr\tsbrdrdgl\tsbrdrdgr\tsbrdrh\tsbrdrv 
\ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 \fs20\lang1024\langfe1024\cgrid\langnp1024\langfenp1024 \snext11 \ssemihidden Normal Table;}}{\*\rsidtbl \rsid2772924\rsid5840518\rsid6030275\rsid9262926}
{\*\generator Microsoft Word 10.0.2627;}{\info{\title \'e1 \'e9 \'ed \'f3 \'fa \'c1 \'c9 \'cd \'d3 \'da \'d1 \'f1}{\author dayscript}{\operator dayscript}{\creatim\yr2004\mo4\dy7\hr16\min20}{\revtim\yr2004\mo4\dy7\hr16\min37}{\version2}{\edmins17}
{\nofpages1}{\nofwords4}{\nofchars28}{\*\company  }{\nofcharsws31}{\vern16437}}\paperw11906\paperh16838\margl1701\margr1701\margt1417\margb1417 
\deftab708\widowctrl\ftnbj\aenddoc\hyphhotz425\noxlattoyen\expshrtn\noultrlspc\dntblnsbdb\nospaceforul\formshade\horzdoc\dgmargin\dghspace180\dgvspace180\dghorigin1701\dgvorigin1417\dghshow1\dgvshow1
\jexpand\viewkind1\viewscale100\pgbrdrhead\pgbrdrfoot\splytwnine\ftnlytwnine\htmautsp\nolnhtadjtbl\useltbaln\alntblind\lytcalctblwd\lyttblrtgr\lnbrkrule\nobrkwrptbl\snaptogridincell\allowfieldendsel\wrppunct\asianbrkrule\rsidroot6030275 \fet0\sectd 
\linex0\headery708\footery708\colsx708\endnhere\sectlinegrid360\sectdefaultcl\sftnbj {\*\pnseclvl1\pnucrm\pnstart1\pnindent720\pnhang {\pntxta .}}{\*\pnseclvl2\pnucltr\pnstart1\pnindent720\pnhang {\pntxta .}}{\*\pnseclvl3\pndec\pnstart1\pnindent720\pnhang
{\pntxta .}}{\*\pnseclvl4\pnlcltr\pnstart1\pnindent720\pnhang {\pntxta )}}{\*\pnseclvl5\pndec\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}{\*\pnseclvl6\pnlcltr\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}{\*\pnseclvl7
\pnlcrm\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}{\*\pnseclvl8\pnlcltr\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}{\*\pnseclvl9\pnlcrm\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}\pard\plain 
\ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 \fs24\lang3082\langfe3082\cgrid\langnp3082\langfenp3082
<?
require_once( "config.php" );
  
  $channels = "(0";
  for($i=0; $i<count($_POST['channels']); $i++){
    $channels .= ", " . $_POST['channels'][$i];
  }
  $channels .= ")";
  $slots_array = array();
  $max = date( "j", mktime(0, 0, 0, $_POST['mes'] + 1, 1, $_POST['year']) - 1 );
  for( $k = 1; $k <= $max; $k++ ){
    if( $k < 10 ){
      $dia = "0".$k;
    }else{
      $dia = $k;
    }
//    $sql = "SELECT S.time, C.shortName ,S.title, C.id, S.id, S.date  FROM channel C, slot S WHERE C.id IN $channels  AND S.channel = C.id AND S.date = '".$_POST['ano']."-".$_POST['mes']."-".$dia."' ORDER BY time";					
    $sql = "SELECT S.time, C.shortName ,S.title, C.id, S.id, S.date  FROM channel C, slot S WHERE C.id IN $channels  AND S.channel = C.id AND S.date = '".$_POST['ano']."-".$_POST['mes']."-".$dia."' ORDER BY time, C.shortName";
    $results = db_query($sql);
	while( $row = db_fetch_array( $results ) ){
      $timediff = $_POST['channels_time'][$row[3]];
      $row[0] = date( "H:i", strtotime( $row[0] ) );
      $date = date( "Y-m-d", strtotime( $row[5]." ".$row[0] )+ ((60*60)*$timediff) );
      $time = date( "H:i", strtotime( $row[5]." ".$row[0] )+ ((60*60)*$timediff) );
      if( trim( $row[2] ) != "" ){
		$slots_array[$date][$time][$row[1]] = ucwords(strtolower($row[2]));
      }else{
		$slots_array[$date][$time][$row[1]] = ucwords(strtolower($row[7]));
      }
    }	
  }
  $keys = array_keys( $slots_array );
  for( $i = 0; $i < count( $keys ); $i++ ){
    $keys1 = array_keys( $slots_array[$keys[$i]] );
    ksort($slots_array[$keys[$i]], SORT_STRING);
  }
  $dias[] = "DOMINGO";
  $dias[] = "LUNES";
  $dias[] = "MARTES";
  $dias[] = "MIERCOLES";
  $dias[] = "JUEVES";
  $dias[] = "VIERNES";
  $dias[] = "SABADO";
  $max = date( "j", mktime(0, 0, 0, $_POST['mes'] + 1, 1, $_POST['year']) - 1 );
  for($j=1;$j<=$max;$j++){
	if($j<10)$dia = "0" . $j;
		else $dia = $j;			
	$date = $_POST['ano']."-".$_POST['mes']."-".$dia;
?>{\b\f1\fs17 <?=$dias[date("w",strtotime($_POST['ano'] . "-" . $_POST['mes'] . "-" . $dia))]?> <?=$dia?>}{\par }<?
	for( $k = 0; $k < count( $slots_array[$date] ); $k++ ){
        $keys = array_keys( $slots_array[$date] );
		$keys1 = array_keys( $slots_array[$date][$keys[$k]] );
		for( $m = 0; $m < count( $slots_array[$date][$keys[$k]] ); $m++ ){
			if( $m == 0 ){
			  	
		  if( strlen($slots_array[$date][$keys[$k]][$keys1[$m]]) > 21 ){
			    $pos = strrpos(substr($slots_array[$date][$keys[$k]][$keys1[$m]],0,21)," ");
			    $t1 = substr($slots_array[$date][$keys[$k]][$keys1[$m]],0,$pos);
			    $t2 = substr( substr($slots_array[$date][$keys[$k]][$keys1[$m]],$pos+1), 0, 21 );
?>{\f1\fs17 <?=date("h:i A", strtotime( $keys[$k] ))?>\tab }{\b\f1\fs17 <?=$keys1[$m]?>\tab}{\f1\fs17 <?=spanishString($t1)?>}{ \par }{\tab\tab}{\f1\fs17 <?=spanishString($t2)?>}{ \par }<?
		  }else{
			?>{\f1\fs17 <?=date("h:i A", strtotime( $keys[$k] ))?>\tab }{\b\f1\fs17 <?=$keys1[$m]?>\tab}{\f1\fs17 <?=spanishString($slots_array[$date][$keys[$k]][$keys1[$m]])?>}{ \par }<?
																				       }
			}else{			  
			  if( strlen($slots_array[$date][$keys[$k]][$keys1[$m]]) > 21 ){
			    $pos = strrpos(substr($slots_array[$date][$keys[$k]][$keys1[$m]],0,21)," ");
			    $t1 = substr($slots_array[$date][$keys[$k]][$keys1[$m]],0,$pos);
			    $t2 = substr( substr($slots_array[$date][$keys[$k]][$keys1[$m]],$pos+1), 0, 21 );
?>{\~\tab }{\b\f1\fs17 <?=$keys1[$m]?>\tab}{\f1\fs17 <?=spanishString($t1)?>}{ \par }{\tab\tab}{\f1\fs17 <?=spanishString($t2)?>}{ \par }<?
  }else{
			?>{\~\tab }{\b\f1\fs17 <?=$keys1[$m]?>\tab}{\f1\fs17 <?=spanishString($slots_array[$date][$keys[$k]][$keys1[$m]])?>}{ \par }<?
																	      }			
        		}
		}
	}	
 ?>
<? } ?>
}
