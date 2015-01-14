<?
include("ppa11/class/dao/DataBase.class.php");
include("ppa11/class/ChapterImage.class.php");
include("ppa11/class/ChapterImageType.class.php");
session_start();

$cit = new ChapterImageType( );
$cit->setIdChapterImageType( $_GET["type"] );
$cit->load();
?>
<style>
	body, img {margin:0;padding:0}
	div{text-align:center;}
</style>
<div><img src="<?=CHAPTER_IMAGES_PATH?>/<?=$cit->getName()?>/<?=$_GET["id_chapter"]?>.jpg"/></div>