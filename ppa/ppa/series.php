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
if(isset($_GET['search_series']))$_POST['search_series'] = $_GET['search_series'];
if(isset($_GET['search_seriesEs']))$_POST['search_seriesEs'] = $_GET['search_seriesEs'];

if(isset($_POST['search_series'])){
	$search_series = $_POST['search_series'];
	session_register('search_series');
	$_SESSION['search_series'] = $search_series;
	session_unregister('search2_series');
	unset($_SESSION['search2_series']);
	session_unregister('search_seriesEs');
	unset($_SESSION['search2_seriesEs']);
} else if(isset($_POST['search2_series'])){
	$search2_series = $_POST['search2_series'];
	session_register('search2_series');
	$_SESSION['search2_series'] = $search2_series;
	session_unregister('search_series');
	unset($_SESSION['search_series']);
	session_unregister('search_seriesEs');
	unset($_SESSION['search2_seriesEs']);
} else if(isset($_POST['search_seriesEs'])){
	$search_seriesEs = $_POST['search_seriesEs'];
	session_register('search_seriesEs');
	$_SESSION['search_seriesEs'] = $search_seriesEs;
	session_unregister('search_series');
	unset($_SESSION['search_series']);
	session_unregister('search2_series');
	unset($_SESSION['search2_series']);
}	
$DEBUG=false;
$ppa = new PPA(1);
if(isset($_POST['send_series'])){
	$m = new Serie($_POST['id'], $_POST['title'], $_POST['spanishTitle'],$_POST['gender'], $_POST['year'], $_POST['rated'],
		       $_POST['starring'],$_POST['description'], $_POST['season'], $_POST['ppa']);
	$c = new Chapter($_POST['chapterId'], $_POST['title'], $_POST['spanishTitle'], 1, $_POST['description2'], 
			 $_POST['duration'],($_POST['stereo']==1?1:0), ($_POST['surround']==1?1:0),($_POST['sap']==1?1:0),
			 ($_POST['closeCaption']==1?1:0),($_POST['animated']==1?1:0),($_POST['blackAndWhite']==1?1:0),
			 ($_POST['repetition']==1?1:0),($_POST['onLive']==1?1:0),($_POST['nudity']==1?1:0),
			 ($_POST['ofensiveLanguage']==1?1:0),($_POST['violence']==1?1:0),($_POST['adultContent']==1?1:0),
			 $_POST['points']);
	$m->addChapter($c);
	$m->commit();
	if( $_POST['popup'] == 1){
	  $chapters = $m->getChapters();
	  $chapter = $chapters[0];
	  $channel = new Channel( $_POST['channel'] );
	  if( isset( $_POST['new'] ) ){
		$slot = new Slot( $_POST['slot'] );		
	    $_SESSION['programs_found'][$chapter->getId()] = $_POST['title'];
		print_r( $_SESSION['programs_found']);
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
				$msj = "Las dimensiones de la imágen no coinciden con el formato seleccionado<br>";
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
}else if(isset($_POST['dup_series'])){
	$m = new Serie(false, $_POST['title'], $_POST['spanishTitle'], $_POST['gender'], $_POST['year'], $_POST['rated'], 
		       $_POST['starring'],$_POST['description'], $_POST['season'], $_POST['ppa']);
	$c = new Chapter(false, $_POST['title'], $_POST['spanishTitle'], 1, $_POST['description2'], $_POST['duration'],
	           ($_POST['stereo']==1?1:0), ($_POST['surround']==1?1:0),($_POST['sap']==1?1:0),($_POST['closeCaption']==1?1:0),
		       ($_POST['animated']==1?1:0),($_POST['blackAndWhite']==1?1:0),($_POST['repetition']==1?1:0),
		       ($_POST['onLive']==1?1:0),($_POST['nudity']==1?1:0),($_POST['ofensiveLanguage']==1?1:0),
		       ($_POST['violence']==1?1:0),($_POST['adultContent']==1?1:0),$_POST['points']);
	$m->addChapter($c);
	$m->commit();
} else if (isset($_GET['delete_series']) && $_GET['delete_series']){
	$m = new Serie($_GET['id']);
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
//window.opener.location = "../ppa.php?paso=2&dia=<?=$_POST['dia']?>&mes=<?=$_POST['mes']?>&ano=<?=$_POST['ano']?>&tipo_archivo=<?=$_POST['tipo_archivo']?>&id=<?=$channel->getId()?>&asign_program=1&bd=<?=$_POST['bd']?>&rand=<?=rand(1,100)?>";
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
//  require_once("ppa/header.php");
}
?>
<table width="600" border="0" cellpadding="0" cellspacing="1">
  <?
if( (isset($_GET['add_series']) && $_GET['add_series']) || (isset($_GET['edit_series']) && $_GET['edit_series']) ){
	if(isset($_GET['edit_series']) && $_GET['edit_series']){
		$mv = new Serie($_GET['id']);
		$cs = $mv->getChapters();
		if(count($cs)>0)$ch = $cs[0];
		else $ch = new Chapter();
	} else {
		$mv = new Serie();
		$ch = new Chapter();
	}
	$title = $mv->getTitle();
	$spanishTitle = $mv->getSpanishTitle();
	$gender = $mv->getGender();
	$rated = $mv->getRated();
	$year = $mv->getYear();
	$starring = $mv->getStarring();
	$description = $mv->getDescription();
	$description2 = $ch->getDescription();
	$season = $mv->getSeason();
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
        <? } ?>
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
      <td><div align="right"><strong>Protagonistas</strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <input class="large" type="text" name="starring" value="<?=$starring?>">
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
      <td><div align="right"><strong>Temporada</strong></div></td>
      <td>&nbsp;</td>
      <td><select name="season" class="large">
          <option <?=($season=="0"?"selected":"")?> value="0">No aplica</option>
          <option <?=($season=="1"?"selected":"")?> value="1">Primera</option>
          <option <?=($season=="2"?"selected":"")?> value="2">Segunda</option>
          <option <?=($season=="3"?"selected":"")?> value="3">Tercera</option>
          <option <?=($season=="4"?"selected":"")?> value="4">Cuarta</option>
          <option <?=($season=="5"?"selected":"")?> value="5">Quinta</option>
          <option <?=($season=="6"?"selected":"")?> value="6">Sexta</option>
          <option <?=($season=="7"?"selected":"")?> value="7">Séptima</option>
          <option <?=($season=="8"?"selected":"")?> value="8">Octava</option>
          <option <?=($season=="9"?"selected":"")?> value="9">Novena</option>
          <option <?=($season=="10"?"selected":"")?> value="10">Décima</option>
          <option <?=($season=="11"?"selected":"")?> value="11">11</option>
          <option <?=($season=="12"?"selected":"")?> value="12">12</option>
          <option <?=($season=="13"?"selected":"")?> value="13">13</option>
          <option <?=($season=="14"?"selected":"")?> value="14">14</option>
          <option <?=($season=="15"?"selected":"")?> value="15">15</option>
          <option <?=($season=="16"?"selected":"")?> value="16">16</option>
          <option <?=($season=="17"?"selected":"")?> value="17">17</option>
          <option <?=($season=="18"?"selected":"")?> value="18">18</option>
          <option <?=($season=="19"?"selected":"")?> value="19">19</option>
          <option <?=($season=="20"?"selected":"")?> value="20">20</option>
        </select></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>G&eacute;nero</strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <select name="gender" class="large">
            <option <?=($gender=="Acción"?"selected":"")?> value="Acción">Acción</option>
            <option <?=($gender=="Adulto"?"selected":"")?> value="Adulto">Adulto</option>			
            <option <?=($gender=="Aventura"?"selected":"")?> value="Aventura">Aventura</option>
            <option <?=($gender=="Animación"?"selected":"")?> value="Animación">Animación</option>
            <option <?=($gender=="Biografía"?"selected":"")?> value="Biografía">Biografía</option>			
            <option <?=($gender=="Comedia"?"selected":"")?> value="Comedia">Comedia</option>
            <option <?=($gender=="Ciencia Ficción"?"selected":"")?> value="Ciencia Ficción">Ciencia Ficción</option>			
            <option <?=($gender=="Concurso"?"selected":"")?> value="Concurso">Concurso</option>			
            <option <?=($gender=="Cortometraje"?"selected":"")?> value="Cortometraje">Cortometraje</option>			
            <option <?=($gender=="Crimen"?"selected":"")?> value="Crimen">Crimen</option>
            <option <?=($gender=="Deportes"?"selected":"")?> value="Deportes">Deportes</option>
            <option <?=($gender=="Documental"?"selected":"")?> value="Documental">Documental</option>
            <option <?=($gender=="Drama"?"selected":"")?> value="Drama">Drama</option>
            <option <?=($gender=="Familia"?"selected":"")?> value="Familia">Familia</option>
            <option <?=($gender=="Fantasía"?"selected":"")?> value="Fantasía">Fantasía</option>
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
            <option <?=($gender=="Opinión"?"selected":"")?> value="Opinión">Opinión</option>									
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
      <td><div align="right"><strong>TV Rating <a href="ratings.php#tv" target="_blank">Ayuda</a></strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <select name="rated" class="large">
            <option <?=($rated=="TVY"?"selected":"")?> value="TVY">TVY</option>
            <option <?=($rated=="TVY7"?"selected":"")?> value="TVY7">TVY7</option>
            <option <?=($rated=="TVG"?"selected":"")?> value="TVG">TVG</option>
            <option <?=($rated=="TVPG"?"selected":"")?> value="TVPG">TVPG</option>
            <option <?=($rated=="TV14"?"selected":"")?> value="TV14">TV14</option>
            <option <?=($rated=="TVMA"?"selected":"")?> value="TVMA">TVMA</option>
          </select>
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>Description Corta</strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <textarea name="description" class="large" rows="3"><?=$description?></textarea>
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong>Description Larga</strong></div></td>
      <td>&nbsp;</td>
      <td> <div align="left"> 
          <textarea name="description2" class="large" rows="4"><?=$description2?></textarea>
        </div></td>
    </tr>
   <tr> 
      <td><div align="right"><strong>Calificaci&oacute;n</strong></div></td>
      <td>&nbsp;</td>
      <td><select name="points" class="large">
          <option <?=($points=="8"?"selected":"")?> value="8">8</option>
          <option <?=($points=="1"?"selected":"")?> value="1">1</option>
          <option <?=($points=="2"?"selected":"")?> value="2">2</option>
          <option <?=($points=="3"?"selected":"")?> value="3">3</option>
          <option <?=($points=="4"?"selected":"")?> value="4">4</option>
          <option <?=($points=="5"?"selected":"")?> value="5">5</option>
          <option <?=($points=="6"?"selected":"")?> value="6">6</option>
          <option <?=($points=="7"?"selected":"")?> value="7">7</option>
          <option <?=($points=="9"?"selected":"")?> value="9">9</option>
          <option <?=($points=="10"?"selected":"")?> value="10">10</option>
        </select></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td align="right"><b>Imágenes</b></td>
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
    <input type="hidden" name="id" value="<?=($_GET['add_series']?"0":$_GET['id'])?>">
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
      <td height="39" colspan="3" align="center"><input type="submit" name="send_series" value="<?=($_GET['add_series']?"Crear":"Actualizar")?>" class="large">
        <br>
	<? if( $_GET['popup'] != 1 ){ ?>
        <input type="button" name="send2" value="Regresar" class="large" onClick='document.location="ppa.php?serie=1"'>
	<? }?>
	  </td>       
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td height="39" colspan="3" align="center">
	  <? if (!$_GET['add_series']){
		  ?>Es posible duplicar los datos de esta Serie, y guardarlos como un registro nuevo.
		  <br><input type="submit" name="dup_series" value="Guardar como nuevo registro" class="large"><?
		 }
		 ?>
	  </td>
    </tr>
	<tr><td colspan="3">
	</td></tr>
<? if( $_GET['popup'] == 1  ){ ?>
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
	<i><b>Nota:</b> puede usar el caracter '%' como comodín en las palabras de búsqueda.</i><br>
		<input type="text" class="large" name="search_series" value="<?=(isset($_SESSION['search_series'])?$_SESSION['search_series']:"")?>">
		<input type="submit" name="buscar" value="Buscar en Título">
	</form><br>
	<form name="f4" action="ppa.php" method="post">
		<input type="text" class="large" name="search_seriesEs" value="<?=(isset($_SESSION['search_seriesEs'])?$_SESSION['search_seriesEs']:"")?>">
		<input type="submit" name="buscar" value="Buscar Título en Español">
	</form><br>	
	<form name="f3" action="ppa.php" method="post">
		<input type="text" class="large" name="search2_series" value="<?=(isset($_SESSION['search2_series'])?$_SESSION['search2_series']:"")?>">
		<input type="submit" name="buscar" value="Buscar en Protagistas">
	</form><br>
	
	<?
	$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '¡' ,'¿');
	for($i=0; $i<count($letters); $i++){
		?><a href="ppa.php?search_series=<?=$letters[$i]?>%"><?=$letters[$i]?></a> - <?
	}
	if (isset($_SESSION['search_series']) || isset($_SESSION['search2_series']) || isset($_SESSION['search_seriesEs'])){
		if (isset($_SESSION['search_series'])){
			$series = $ppa->getSeries($_SESSION['search_series']);
		} else {
			if (isset($_SESSION['search2_series'])){
				$series = $ppa->getSeriesByStarring($_SESSION['search2_series']);
			}else{
				if (isset($_SESSION['search_seriesEs'])){
					$series = $ppa->getSeriesEs($_SESSION['search_seriesEs']);
				} 
			}
		}
		for ($i=0; $i<count($series); $i++){
			?>
		  <tr> 
			<td bgcolor="#<?=($series[$i]->getStarring()?"FFEEDD":"DDEEFF")?>"> <strong> 
			<? if (isset($_SESSION['search_seriesEs'])){ ?>			 
			  <a href="ppa.php?edit_series=1&id=<?=$series[$i]->getId()?>"><?=$series[$i]->getSpanishtitle()?></a>
			  <? }else{ ?>
			  <a href="ppa.php?edit_series=1&id=<?=$series[$i]->getId()?>"><?=$series[$i]->getTitle()?></a>
			  <? } ?>
			  </strong> </td>
			<td bgcolor="#FFFFFF">&nbsp;</td>
			<td align="center" bgcolor="#DDEEFF">
			  <a href="ppa.php?delete_series=1&id=<?=$series[$i]->getId()?>">Borrar</a></td>
		  </tr>
	    <?
		}
		?>
	  <tr> 
		<td colspan="3" align="center">
			<table border="1" cellpadding="1" cellspacing="1">
				<tr>
					<td bgcolor="#FFEEDD">&nbsp;&nbsp;</td>
					<td>Con Protagonistas</td>
					<td bgcolor="#DDEEFF">&nbsp;&nbsp;</td>
					<td>Sin Protagonistas</td>
				</tr>
			</table>
		</td>
	  </tr>
	  <tr> 
		<td colspan="3" align="center" bgcolor="#FFFFCC"><a href="ppa.php?add_series=1">Agregar 
		  Serie</a></td>
	  </tr><?
	}
}
?>
</table>
<form action="http://www.imdb.com/Find" method=post target="_blank">
<table cellpadding="0" cellspacing="" border="0" width="600">
<tr>
      <td align="center" bgcolor="#000000"> <font color="#FFFFFF" size=2><br>
        Escriba el nombre de una <strong>Película</strong> o <strong>Programa 
        de Televisión</strong> y haga click en "IMDB" para obtener información 
        desde <em>www.imdb.com</em></font><font size=2><br>
        &nbsp;</font> 
        <input type=hidden name=select value="All">
		<input align="absmiddle" type=text name="for" class="large" value="<?=(isset($title)?$title:(isset($_SESSION['search_series'])?$_SESSION['search_series']:""))?>">&nbsp;
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