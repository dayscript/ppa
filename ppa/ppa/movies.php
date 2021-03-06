<?
error_reporting(E_ERROR);
if($_GET["popup"] == 1)
{
	include("../ppa11/class/dao/DataBase.class.php");
	include("../ppa11/class/ChapterImage.class.php");
	include("../ppa11/class/ChapterImageType.class.php");
}
else
{
	include("ppa11/class/dao/DataBase.class.php");
	include("ppa11/class/ChapterImage.class.php");
	include("ppa11/class/ChapterImageType.class.php");
}
session_start();

$_GET['program'] = urldecode(stripslashes($_GET['program']));
$_GET['program'] = str_replace( "\'", "'", $_GET['program'] );
$_GET['program'] = str_replace( '\"', '"', $_GET['program'] );

if( isset( $_GET['popup'] ) || isset( $_POST['popup'] ) ){
  require_once("config1.php");
}else{
  require_once("ppa/config.php");
}
if(isset($_GET['search_movie']))$_POST['search_movie'] = $_GET['search_movie'];
if(isset($_GET['search_movieEs']))$_POST['search_movieEs'] = $_GET['search_movieEs'];

if(isset($_POST['search_movie'])){
	$search_movie = $_POST['search_movie'];
	session_register('search_movie');
	$_SESSION['search_movie'] = $search_movie;
	session_unregister('search2_movie');
	unset($_SESSION['search2_movie']);
	session_unregister('search_movieEs');
	unset($_SESSION['search_movieEs']);
} else if(isset($_POST['search2_movie'])){
	$search2_movie = $_POST['search2_movie'];
	session_register('search2_movie');
	$_SESSION['search2_movie'] = $search2_movie;
	session_unregister('search_movie');
	unset($_SESSION['search_movie']);
	session_unregister('search_movieEs');
	unset($_SESSION['search_movieEs']);
}else if(isset($_POST['search_movieEs'])){
	$search_movieEs = $_POST['search_movieEs'];
	session_register('search_movieEs');
	$_SESSION['search_movieEs'] = $search_movieEs;
	session_unregister('search_movie');
	unset($_SESSION['search_movie']);
	session_unregister('search2_movie');
	unset($_SESSION['search2_movie']);
}	
$DEBUG=false;
$ppa = new PPA(1);
if(isset($_POST['send_movie'])){
	$m = new Movie($_POST['id'], $_POST['title'], $_POST['spanishTitle'], $_POST['englishTitle'],
	               $_POST['gender'], $_POST['rated'], $_POST['tvRated'], $_POST['year'], $_POST['actors'], 
				   $_POST['director'], $_POST['description'], $_POST['country'], $_POST['language'], $_POST['ppa']);
	$c = new Chapter($_POST['chapterId'], $_POST['title'], $_POST['spanishTitle'], 1, $_POST['description2'], $_POST['duration'],
	               ($_POST['stereo']==1?1:0), ($_POST['surround']==1?1:0),($_POST['sap']==1?1:0),($_POST['closeCaption']==1?1:0),
				   ($_POST['animated']==1?1:0),($_POST['blackAndWhite']==1?1:0),($_POST['repetition']==1?1:0),($_POST['onLive']==1?1:0),
				   ($_POST['nudity']==1?1:0),($_POST['ofensiveLanguage']==1?1:0),($_POST['violence']==1?1:0),($_POST['adultContent']==1?1:0),$_POST['points']);
	$m->addChapter($c);
	$m->commit();
	if( $_POST['popup'] == 1){
	  $chapters = $m->getChapters();
	  $chapter = $chapters[0];
	  $channel = new Channel( $_POST['channel'] );
  	  if( isset( $_POST['new'] ) ){
		$slot = new Slot( $_POST['slot'] );		
	    $_SESSION['programs_found'][$chapter->getId()] = $_POST['title'];
   	  	$slot_asignadonew = true;		
	  }else{
	  	$slot = new Slot();
   	  	$slot_asignado = true;
	  }
	  $slot->addChapter($chapter);
	  $slot->setChannel( $channel->getId() );
	  $slot->setDate( $_POST['date'] );
	  $slot->setTime( $_POST['time'] );
	  $slot->setDuration( $_POST['duration'] );
	  $slot->commit();
	  $channel->addSlot( $slot );
	  $channel->commit();
	}
	if( $_FILES["image_file"]["error"] == 0 && $_FILES["image_file"]["type"] == "image/jpeg" )
	{
			list($width, $height) = getimagesize($_FILES["image_file"]["tmp_name"]);
			$cit = new ChapterImageType();
			$cit->setIdChapterImageType($_POST["image_type"]);
			$cit->load();
			if( $width != $cit->getWidth() || $height != $cit->getHeight() )
			{
				$msj = "Las dimensiones de la im�gen no coinciden con el formato seleccionado<br>";
				$msj .= '<a href="#" onclick="history.back();">regresar</a><br>';
				echo $msj;
				exit;
				unlink( $_FILES["image_file"]["tmp_name"] );
			}
			else
			{
				$ci = new ChapterImage();
				$ci->setIdChapter($_POST['chapterId']);
				$ci->setIdChapterImageType($cit->getIdChapterImageType());
				$ci->load();
				$ci->commit();
				$filename = CHAPTER_IMAGES_PATH . "/" . $cit->getName() . "/" . $ci->getIdChapter() . ".jpg";
				@unlink( $filename );
				move_uploaded_file( $_FILES["image_file"]["tmp_name"], $filename );
			}
	}
	echo "<script>if(window.opener)window.close();</script>";
}else if(isset($_POST['dup_movie'])){
        $m = new Movie(false, $_POST['title'], $_POST['spanishTitle'], $_POST['englishTitle'],
		 $_POST['gender'], $_POST['rated'], $_POST['tvRated'], $_POST['year'], $_POST['actors'], 
				   $_POST['director'], $_POST['description'], $_POST['country'], $_POST['language'], $_POST['ppa']);
	$c = new Chapter(false, $_POST['title'], $_POST['spanishTitle'], 1, $_POST['description2'], $_POST['duration'],
	               ($_POST['stereo']==1?1:0), ($_POST['surround']==1?1:0),($_POST['sap']==1?1:0),($_POST['closeCaption']==1?1:0),
				   ($_POST['animated']==1?1:0),($_POST['blackAndWhite']==1?1:0),($_POST['repetition']==1?1:0),($_POST['onLive']==1?1:0),
				   ($_POST['nudity']==1?1:0),($_POST['ofensiveLanguage']==1?1:0),($_POST['violence']==1?1:0),($_POST['adultContent']==1?1:0),$_POST['points']);
	$m->addChapter($c);
	$m->commit();
} else if (isset($_GET['delete_movie']) && $_GET['delete_movie']){
	$m = new Movie($_GET['id']);
	$m->erase();
	unset($m);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>PPA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body,table {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #444444;
}
input, select, textarea, {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #444444;
	border: 1px solid #000000;
	background-color: #DDEEFF;
}
.large {
	width: 300;
}
-->
</style>
<script>
function viewChapterImage( id )
{
	var ti = document.getElementById('sel_img_types').selectedIndex;
	var t = image_types[ti][0];
	if( !image_types[ti][3] ){ alert("No hay imagen de este tipo"); return; }
	var s = image_types[ti][2].split("x");
	window.open('ppa11.php?location=viewChapterImage&nohead=true&id_chapter=' + id + "&type=" + t, "chapter_image", 
		"width=" + (parseInt(s[0])+10) + ",height=" + (parseInt(s[1])+10) );
}
</script>
</head>

<body>
<? if( $slot_asignado ){?>
<script>
window.opener.location = "../ppa.php?paso=2&dia=<?=$_POST['dia']?>&mes=<?=$_POST['mes']?>&ano=<?=$_POST['ano']?>&tipo_archivo=<?=$_POST['tipo_archivo']?>&id=<?=$channel->getId()?>&asign_program=1&bd=<?=$_POST['bd']?>&rand=<?=rand(1,100)?>";
window.close();
</script>
<? }else{
	if( $slot_asignadonew ){
 ?>
<script>
//window.opener.location = "../ppa.php?asign_chapters=1&paso=2&mes=<?=$_POST['mes']?>&ano=<?=$_POST['ano']?>&channel=<?=$channel->getId()?>&rand=<?=rand(1,100)?>";
window.close();
</script>
<? } 
} ?>
<?
if( $_GET['popup'] != 1 ){
// require_once("ppa/header.php");
}

?>
<table width="600" border="0" cellpadding="0" cellspacing="1">
  <?
if( (isset($_GET['add_movie']) && $_GET['add_movie']) || (isset($_GET['edit_movie']) && $_GET['edit_movie']) ){
	if(isset($_GET['edit_movie']) && $_GET['edit_movie']){
		$mv = new Movie($_GET['id']);
		$cs = $mv->getChapters();
		if(count($cs)>0)$ch = $cs[0];
		else $ch = new Chapter();
	} else {
		$mv = new Movie();
		$ch = new Chapter();
	}
	$title = $mv->getTitle();
	$spanishTitle = $mv->getSpanishTitle();
	$englishTitle = $mv->getEnglishTitle();
	$gender = $mv->getGender();
	$rated = $mv->getRated();
	$tvRated = $mv->getTvRated();
	$duration = $ch->getDuration();
	$year = $mv->getYear();
	$actors = $mv->getActors();
	$director = $mv->getDirector();
	$description = $mv->getDescription();
	$description2 = $ch->getDescription();
	$country = $mv->getCountry();
	$language = $mv->getLanguage();
	$stereo = $ch->getStereo();
	$surround = $ch->getSurround();
	$sap = $ch->getSap();
	$closeCaption = $ch->getCloseCaption();
	$animated = $ch->getAnimated();
	$blackAndWhite = $ch->getBlackAndWhite();
	$repetition = $ch->getRepetition();
	$onLive = $ch->getOnLive();
	$nudity = $ch->getNudity();
	$violence = $ch->getViolence();
	$ofensiveLanguage = $ch->getOfensiveLanguage();
	$adultContent = $ch->getAdultContent();
	$points = $ch->getPoints();
	$chapterId = $ch->getId();
	
	/* images and image types */
	if( $chapterId != "" )
	{
		$sql = "SELECT id_chapter_image_type id FROM chapter_images WHERE id_chapter = " . $chapterId;
		$result = db_query( $sql );
		while( $row = db_fetch_array($result) )
			$cits[$row["id"]] = "";
	}
	
	$sql = "SELECT id_chapter_image_type, name, width, height FROM chapter_image_types";
	$result = db_query( $sql );
	while( $row = db_fetch_array($result) )
	{
		$cit = isset($cits[$row["id_chapter_image_type"]]) ? "true" : "false";
		$js_cit_arr .= "[" . $row['id_chapter_image_type'] . ", '" . $row['name'] . "', '" . $row['width'] . "x" . $row['height'] . "', " . $cit . " ], ";
	}
	$js_cit_arr = substr( $js_cit_arr, 0, -2 );
	/* end images and image types */
?>
  <form name="f1" enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>" method="post">
    <tr> 
      <td width="146"><div align="right"><strong>T&iacute;tulo Original</strong></div></td>
      <td width="15">&nbsp;</td>
      <td width="439"> <div align="left"> 
	<? if( $_GET['popup'] != 1){ ?>
          <input class="large" type="text" name="title" value="<?=$title?>">
        <? }else{?>
          <input class="large" type="text" name="title" value="<?=$_GET['program']?>">
	<? }?>	 
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>T&iacute;tulo en Espa&ntilde;ol</strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <input class="large" type="text" name="spanishTitle" value="<?=$spanishTitle?>">
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>T&iacute;tulo en Ingl&eacute;s</strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <input class="large" type="text" name="englishTitle" value="<?=$englishTitle?>">
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>G&eacute;nero</strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <select name="gender" class="large">
            <option <?=($gender=="Acci�n"?"selected":"")?> value="Acci�n">Acci�n</option>
            <option <?=($gender=="Adulto"?"selected":"")?> value="Adulto">Adulto</option>			
            <option <?=($gender=="Aventura"?"selected":"")?> value="Aventura">Aventura</option>
            <option <?=($gender=="Animaci�n"?"selected":"")?> value="Animaci�n">Animaci�n</option>
            <option <?=($gender=="Biograf�a"?"selected":"")?> value="Biograf�a">Biograf�a</option>			
            <option <?=($gender=="Comedia"?"selected":"")?> value="Comedia">Comedia</option>
            <option <?=($gender=="Ciencia Ficci�n"?"selected":"")?> value="Ciencia Ficci�n">Ciencia Ficci�n</option>			
            <option <?=($gender=="Concurso"?"selected":"")?> value="Concurso">Concurso</option>			
            <option <?=($gender=="Cortometraje"?"selected":"")?> value="Cortometraje">Cortometraje</option>			
            <option <?=($gender=="Crimen"?"selected":"")?> value="Crimen">Crimen</option>
            <option <?=($gender=="Deportes"?"selected":"")?> value="Deportes">Deportes</option>
            <option <?=($gender=="Documental"?"selected":"")?> value="Documental">Documental</option>
            <option <?=($gender=="Drama"?"selected":"")?> value="Drama">Drama</option>
            <option <?=($gender=="Familia"?"selected":"")?> value="Familia">Familia</option>
            <option <?=($gender=="Fantas�a"?"selected":"")?> value="Fantas�a">Fantas�a</option>
            <option <?=($gender=="Guerra"?"selected":"")?> value="Guerra">Guerra</option>			
            <option <?=($gender=="Historia"?"selected":"")?> value="Historia">Historia</option>			
            <option <?=($gender=="Horror"?"selected":"")?> value="Horror">Horror</option>
            <option <?=($gender=="Instruccional"?"selected":"")?> value="Instruccional">Instruccional</option>			
            <option <?=($gender=="Infantil"?"selected":"")?> value="Infantil">Infantil</option>			
            <option <?=($gender=="Musical"?"selected":"")?> value="Musical">Musical</option>
            <option <?=($gender=="Misterio"?"selected":"")?> value="Misterio">Misterio</option>
            <option <?=($gender=="Naturaleza"?"selected":"")?> value="Naturaleza">Naturaleza</option>
            <option <?=($gender=="Noticias"?"selected":"")?> value="Noticias">Noticias</option>
            <option <?=($gender=="Oeste"?"selected":"")?> value="Oeste">Oeste</option>									
            <option <?=($gender=="Opini�n"?"selected":"")?> value="Opini�n">Opini�n</option>									
            <option <?=($gender=="Realidad"?"selected":"")?> value="Realidad">Realidad</option>			
            <option <?=($gender=="Reality"?"selected":"")?> value="Reality">Reality</option>			
            <option <?=($gender=="Religioso"?"selected":"")?> value="Religioso">Religioso</option>						
            <option <?=($gender=="Romance"?"selected":"")?> value="Romance">Romance</option>
            <option <?=($gender=="Salud"?"selected":"")?> value="Salud">Salud</option>
            <option <?=($gender=="Suspenso"?"selected":"")?> value="Suspenso">Suspenso</option>
            <option <?=($gender=="Talk Shows"?"selected":"")?> value="Talk Shows">Talk Shows</option>			
            <option <?=($gender=="Telenovela"?"selected":"")?> value="Telenovela">Telenovela</option>
            <option <?=($gender=="Variedad"?"selected":"")?> value="Variedad">Variedad</option>			
          </select>
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>Movie Rating <a href="ratings.php" target="_blank">Ayuda</a></strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <select name="rated" class="large">
            <option <?=($rated=="G"?"selected":"")?> value="G">G</option>
            <option <?=($rated=="PG"?"selected":"")?> value="PG">PG</option>
            <option <?=($rated=="PG-13"?"selected":"")?> value="PG-13">PG-13</option>
            <option <?=($rated=="R"?"selected":"")?> value="R">R</option>
            <option <?=($rated=="NC-17"?"selected":"")?> value="NC-17">NC-17</option>
	    <option <?=($rated=="X"?"selected":"")?> value="X">X</option>
	    <option <?=($rated=="MM"?"selected":"")?> value="MM">MM</option>
          </select>
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>TV Rating <a href="ratings.php#tv" target="_blank">Ayuda</a></strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <select name="tvRated" class="large">
            <option <?=($tvRated=="TVY"?"selected":"")?> value="TVY">TVY</option>
            <option <?=($tvRated=="TVY7"?"selected":"")?> value="TVY7">TVY7</option>
            <option <?=($tvRated=="TVG"?"selected":"")?> value="TVG">TVG</option>
            <option <?=($tvRated=="TVPG"?"selected":"")?> value="TVPG">TVPG</option>
            <option <?=($tvRated=="TV14"?"selected":"")?> value="TV14">TV14</option>
            <option <?=($tvRated=="TVMA"?"selected":"")?> value="TVMA">TVMA</option>
          </select>
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>Duraci&oacute;n (minutos)</strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <input type="text" name="duration" value="<?=$duration?>" class="large">
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>A&ntilde;o</strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <input type="text" name="year" value="<?=$year?>" class="large">
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>Actores</strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <input type="text" name="actors" value="<?=$actors?>" class="large">
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>Director</strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <input type="text" name="director" value="<?=$director?>" class="large">
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>Descripci�n Corta</strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <textarea name="description" class="large" rows="3"><?=$description?></textarea>
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>Descripci�n Larga</strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <textarea name="description2" class="large" rows="4"><?=$description2?></textarea>
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>Pa&iacute;s</strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <input type="text" name="country" value="<?=$country?>" class="large">
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>Idioma</strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <select name="language" class="large">
				<option <?=($language=="Alem�n"?"selected":"")?> value="Alem�n">Alem�n</option>
				<option <?=($language=="Cantonese"?"selected":"")?> value="Cantonese">Cantonese</option>
				<option <?=($language=="Catal�n"?"selected":"")?> value="Catal�n">Catal�n</option>
				<option <?=($language=="Checo"?"selected":"")?> value="Checo">Checo</option>
				<option <?=($language=="Chino"?"selected":"")?> value="Chino">Chino</option>
				<option <?=($language=="Coreano"?"selected":"")?> value="Coreano">Coreano</option>
				<option <?=($language=="Dan�s"?"selected":"")?> value="Dan�s">Dan�s</option>
				<option <?=($language=="Dari"?"selected":"")?> value="Dari">Dari</option>
				<option <?=($language=="Eslovaco"?"selected":"")?> value="Eslovaco">Eslovaco</option>
				<option <?=($language=="Espa�ol"?"selected":"")?> value="Espa�ol">Espa�ol</option>
				<option <?=($language=="Franc�s"?"selected":"")?> value="Franc�s">Franc�s</option>
				<option <?=($language=="Hebreo"?"selected":"")?> value="Hebreo">Hebreo</option>
				<option <?=($language=="Hind�"?"selected":"")?> value="Hind�">Hind�</option>						       
				<option <?=($language=="Holand�s"?"selected":"")?> value="Holand�s">Holand�s</option>
				<option <?=($language=="H�ngaro"?"selected":"")?> value="H�ngaro">H�ngaro</option>
				<option <?=($language=="Ingl�s"?"selected":"")?> value="Ingl�s">Ingl�s</option>
				<option <?=($language=="Iran�"?"selected":"")?> value="Iran�">Iran�</option>
				<option <?=($language=="Italiano"?"selected":"")?> value="Italiano">Italiano</option>
				<option <?=($language=="Japon�s"?"selected":"")?> value="Japon�s">Japon�s</option>						
				<option <?=($language=="Mandar�n"?"selected":"")?> value="Mandar�n">Mandar�n</option>						
				<option <?=($language=="Polaco"?"selected":"")?> value="Polaco">Polaco</option>						
				<option <?=($language=="Portugu�s"?"selected":"")?> value="Portugu�s">Portugu�s</option>
				<option <?=($language=="Ruso"?"selected":"")?> value="Ruso">Ruso</option>						
				<option <?=($language=="Serbio-Croata"?"selected":"")?> value="Serbio-Croata">Serbio-Croata</option>
				<option <?=($language=="Sueco"?"selected":"")?> value="Sueco">Sueco</option>						
				<option <?=($language=="Tagalog"?"selected":"")?> value="Tagalog">Tagalog</option>						
				<option <?=($language=="Zulu"?"selected":"")?> value="Tagalog">Zulu</option>						
            </select>
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>Calificaci&oacute;n</strong></div></td>
      <td>&nbsp;</td>
      <td><select name="points" class="large">
          <option <?=($points=="1"?"selected":"")?> value="1">1</option>
          <option <?=($points=="2"?"selected":"")?> value="2">2</option>
          <option <?=($points=="3"?"selected":"")?> value="3">3</option>
          <option <?=($points=="4"?"selected":"")?> value="4">4</option>
          <option <?=($points=="5"?"selected":"")?> value="5">5</option>
          <option <?=($points=="6"?"selected":"")?> value="6">6</option>
          <option <?=($points=="7"?"selected":"")?> value="7">7</option>
          <option <?=($points=="8"?"selected":"")?> value="8">8</option>
          <option <?=($points=="9"?"selected":"")?> value="9">9</option>
          <option <?=($points=="10"?"selected":"")?> value="10">10</option>
        </select></td>
    </tr>
    <tr> 
      <td align="right"><b>Im�genes</b></td>
      <td></td>
      <td><select id="sel_img_types" name="image_type"></select><input type="button" style="margin-left:54px" value="Ver" onclick="viewChapterImage(<?=($chapterId?$chapterId:"0")?>);">
      	<script>
					var image_types = [<?=$js_cit_arr?>];
					var cit = <?=isset($_GET["cit"])?$_GET["cit"]:"0"?>;
      		var sel = document.getElementById('sel_img_types');
      		for( var i=0; i<image_types.length; i++)
      		{
      			var opt = new Option(image_types[i][1], image_types[i][0]);
      			if(image_types[i][0] == cit) opt.selected = true;
      			sel.appendChild(opt);
      		}
      	</script></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="file" name="image_file"></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <input type="hidden" name="ppa" value="<?=$ppa->getId()?>">
    <input type="hidden" name="id" value="<?=($_GET['add_movie']?"0":$_GET['id'])?>">
    <input type="hidden" name="chapterId" value="<?=($chapterId?$chapterId:"0")?>">
    <tr> 
      <td align="center" colspan="3"> <table cellpadding="0" cellspacing="0" border="0" width="100%">
          <tr> 
            <td> <input type="checkbox" <?=($stereo=="1"?"checked":"")?> name="stereo" value="1">
              Stereo<br> <input type="checkbox" <?=($surround=="1"?"checked":"")?> name="surround" value="1">
              Surround<br> <input type="checkbox" <?=($sap=="1"?"checked":"")?> name="sap" value="1">
              SAP <br> <input type="checkbox" <?=($closeCaption=="1"?"checked":"")?> name="closeCaption" value="1">
              Closed Captioned </td>
            <td> <input type="checkbox" <?=($animated=="1"?"checked":"")?> name="animated" value="1">
              Animado<br> <input type="checkbox" <?=($blackAndWhite=="1"?"checked":"")?> name="blackAndWhite" value="1">
              Blanco y Negro<br> <input type="checkbox" <?=($repetition=="1"?"checked":"")?> name="repetition" value="1">
              Repetici&oacute;n <br> <input type="checkbox" <?=($onLive=="1"?"checked":"")?> name="onLive" value="1">
              En Vivo</td>
            <td> <input type="checkbox" <?=($nudity=="1"?"checked":"")?> name="nudity" value="1">
              Desnudos<br> <input type="checkbox" <?=($ofensiveLanguage=="1"?"checked":"")?> name="ofensiveLanguage" value="1">
              Lenguaje Ofensivo<br> <input type="checkbox" <?=($violence=="1"?"checked":"")?> name="violence" value="1">
              Violencia<br> <input type="checkbox" <?=($adultContent=="1"?"checked":"")?> name="adultContent" value="1">
              Contenido Adulto</td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="39" colspan="3" align="center"><input type="submit" name="send_movie" value="<?=($_GET['add_movie']?"Crear":"Actualizar")?>" class="large">
        <br>
	<? if( $_GET['popup'] != 1 ){ ?>
        <input type="button" name="send2" value="Regresar" class="large" onClick='document.location="ppa.php?movie=1"'>
	<? }?>
	  </td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td height="39" colspan="3" align="center">
	  <? if (!$_GET['add_movie']){
		  ?>Es posible duplicar los datos de esta Pel�cula, y guardarlos como un registro nuevo.
		  <br><input type="submit" name="dup_movie" value="Guardar como nuevo registro" class="large"><?
		 }
		 ?>
	  </td>
    </tr>
	<tr><td colspan="3">
	</td></tr>
<? if( $_GET['popup'] == 1 ){ ?>
<? if( !isset( $_GET['new'] ) ){?>
<input type = "hidden" name="popup" value="<?=$_GET['popup']?>">
<input type = "hidden" name="bd" value="<?=$_GET['bd']?>">
<input type = "hidden" name="dia" value="<?=$_GET['dia']?>">
<input type = "hidden" name="mes" value="<?=$_GET['mes']?>">
<input type = "hidden" name="ano" value="<?=$_GET['ano']?>">
<input type = "hidden" name="tipo_archivo" value="<?=$_GET['tipo_archivo']?>">
<input type = "hidden" name="date" value="<?=$_GET['date']?>">
<input type = "hidden" name="time" value="<?=$_GET['time']?>">
<input type = "hidden" name="duration" value="<?=$_GET['duration']?>">
<input type = "hidden" name="channel" value="<?=$_GET['channel']?>">
<? }else{ ?>
<input type = "hidden" name="slot" value="<?=$_GET['slot']?>">
<input type = "hidden" name="new" value="<?=$_GET['new']?>">
<input type = "hidden" name="popup" value="<?=$_GET['popup']?>">
<input type = "hidden" name="mes" value="<?=$_GET['mes']?>">
<input type = "hidden" name="ano" value="<?=$_GET['ano']?>">
<input type = "hidden" name="date" value="<?=$_GET['date']?>">
<input type = "hidden" name="time" value="<?=$_GET['time']?>">
<input type = "hidden" name="duration" value="<?=$_GET['duration']?>">
<input type = "hidden" name="channel" value="<?=$_GET['channel']?>">

<? } ?>

<?}?>
  </form>
  <?
}else{
?>
	<form name="f2" action="ppa.php" method="post">
	<i><b>Nota:</b> puede usar el caracter '%' como comod�n en las palabras de b�squeda.</i><br>
		<input type="text" class="large" name="search_movie" value="<?=(isset($_SESSION['search_movie'])?$_SESSION['search_movie']:"")?>">
		<input type="submit" name="buscar" value="Buscar en T�tulo">
	</form><br>
	<form name="f4" action="ppa.php" method="post">
		<input type="text" class="large" name="search_movieEs" value="<?=(isset($_SESSION['search_movieEs'])?$_SESSION['search_movieEs']:"")?>">
		<input type="submit" name="buscar" value="Buscar T�tulo en Espa�ol">
	</form><br>	
	<form name="f3" action="ppa.php" method="post">
		<input type="text" class="large" name="search2_movie" value="<?=(isset($_SESSION['search2_movie'])?$_SESSION['search2_movie']:"")?>">
		<input type="submit" name="buscar" value="Buscar en Director">
	</form><br>
	
	<?
	$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '�' ,'�');
	for($i=0; $i<count($letters); $i++){
		?><a href="ppa.php?search_movie=<?=$letters[$i]?>%"><?=$letters[$i]?></a> - <?
	}
	if (isset($_SESSION['search_movie']) || isset($_SESSION['search2_movie']) || isset($_SESSION['search_movieEs'])){
		if (isset($_SESSION['search_movie'])){
			$movies = $ppa->getMovies($_SESSION['search_movie']);		
		} else {
		 	if( $_SESSION['search2_movie'] ){
				$movies = $ppa->getMoviesByDirector($_SESSION['search2_movie']);
			}else{
				if (isset($_SESSION['search_movieEs'])){
					$movies = $ppa->getMoviesEs($_SESSION['search_movieEs']);
				}
			}
		}
		for ($i=0; $i<count($movies); $i++){
			?>
		  <tr> 
			<td bgcolor="#<?=($movies[$i]->getDirector()?"FFEEDD":"DDEEFF")?>"> <strong>
			<? if (isset($_SESSION['search_movieEs'])){ ?>			 
			  <a href="ppa.php?edit_movie=1&id=<?=$movies[$i]->getId()?>"><?=$movies[$i]->getSpanishtitle()?></a>
			  <? }else{ ?>
			  <a href="ppa.php?edit_movie=1&id=<?=$movies[$i]->getId()?>"><?=$movies[$i]->getTitle()?></a>
			  <? } ?>
			  </strong> </td>
			<td bgcolor="#FFFFFF">&nbsp;</td>
			<td align="center" bgcolor="#DDEEFF"> 
			  <a href="ppa.php?delete_movie=1&id=<?=$movies[$i]->getId()?>">Borrar</a></td>
		  </tr>
	    <?
		}
		?>
	  <tr> 
		<td colspan="3" align="center">
			<table border="1" cellpadding="1" cellspacing="1">
				<tr>
					<td bgcolor="#FFEEDD">&nbsp;&nbsp;</td>
					<td>Con Director</td>
					<td bgcolor="#DDEEFF">&nbsp;&nbsp;</td>
					<td>Sin Director</td>
				</tr>
			</table>
		</td>
	  </tr>
	  <tr> 
		<td colspan="3" align="center" bgcolor="#FFFFCC"><a href="ppa.php?add_movie=1">Agregar 
		  Pel�cula</a></td>
	  </tr><?
	}
}
?>
</table>
<form action="http://www.imdb.com/Find" method=post target="_blank">
<table cellpadding="0" cellspacing="" border="0" width="600">
<tr>
      <td align="center" bgcolor="#000000"> <font color="#FFFFFF" size=2><br>
        Escriba el nombre de una <strong>Pel�cula</strong> o <strong>Programa 
        de Televisi�n</strong> y haga click en "IMDB" para obtener informaci�n 
        desde <em>www.imdb.com</em></font><font size=2><br>
        &nbsp;</font> 
        <input type=hidden name=select value="All">
		<input align="absmiddle" type=text name="for" class="large" value="<?=(isset($title)?$title:(isset($_SESSION['search_movie'])?$_SESSION['search_movie']:""))?>">&nbsp;
            <? if( isset( $_GET['popup'] ) || isset( $_POST['popup'] ) ){?>
               <input type="image" value="Ir" src="images/logoimdb.gif" align="middle" width="55" height="27">
            <? }else{ ?>
		<input type="image" value="Ir" src="ppa/images/logoimdb.gif" align="middle" width="55" height="27">
            <? } ?>
        <br>
        <br>
      </td>
    </tr>
</table>
</form>
</body>
</html>