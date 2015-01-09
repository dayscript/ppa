<?
ini_set('display_errors', 0);
error_reporting( 0 );
define("DB_HOST", "localhost");
define("DB_NAME", "ppa");
define("DB_USER", "ppa");
define("DB_PASS", "kfc3*9mn");
define("DB_DEBG", false);
define("CHAPTER_IMAGES_PATH", "chapter_images");

if( !$_GET['nohead'] )
	require("header.php");
else
{
	session_start();
	require_once( "include/db.inc.php" );
}

$CLASSPATH = "class/";
setlocale (LC_ALL,"spanish");
if( $_SESSION['user'] == "admin" )
	$link = mysql_connect("localhost", "root", "krxo4578") or mysql_die();
else
	$link = mysql_connect("localhost", "ppa", "kfc3*9mn") or mysql_die();

$db ="ppa";
mysql_select_db($db) or die("Unable to select database");

	switch( $_GET['location'] )
	{
		case "assign_synopsis":
			include("ppa11/assign_synopsis.php");
		break;
		case "add_client_channels":
			include("client_header/add_channels.php");
		break;
		case "develop":
			include("ppa11/assign_chapter_develop.php");
		case "assign_chapter":
			include("ppa11/assign_chapter.php");
		break;
		case "chapter_images":
			include("ppa11/chapters/chapter_images.php");
		break;
		case "channels":
			include("ppa11/channels.php");
		break;
		case "channels2":
			include("ppa11/channels2.php");
		break;
		case "ccchl": //Lista grillas fijas revista Superview
			include("ppa11/channels.php");
		break;
		case "ccfl_gtxt":
			include("magazine/cablecentro/generate_file.php");
		case "ccfl_gwxml":
			include("magazine/cablecentro/generate_week_xml.php");
		break;
		case "ccfl_chkl":
			include("magazine/cablecentro/checklist.php");
		break;
		case "ccfl_chkl2":
			include("magazine/cablecentro/checklist2.php");
		break;
		case "del_synopsis":
			include("ppa11/del_synopsis.php");
		break;
		case "edit_header":
			include("client_header/edit.php");
		break;
		case "edit_client_channel":
			include("client_header/edit_channel.php");
		break;
		case "feedprogram":
			include("ppa11/feedprogram.php");
		break;
		case "feedprogram2":
			include("ppa11/feedprogram2.php");
		break;
		case "une_gxi": // UNE : generate excel interactive guide
			include("une/generate_xls_interactive.php");
		break;
		case "une_gxw": // UNE : generate excel web guide
			include("une/generate_xls_web.php");
		break;
		case "une_gxp": // UNE : generate excel PPV guide
			include("une/generate_xls_ppv.php");
		break;
		case "une_gcw": // UNE : generate excel PPV guide
			include("une/generate_csv_web.php");
		break;
		case "une_home":
			include("une/home.php");
		break;
		case "headers":
			include("client_header/list.php");
		break;
		case "highlight":
			include("ppa11/channels.php");
		break;
		case "hgl_assign":
			include("highlight/highlight_assign.php");
		break;
		case "hgl_checklist":
			include("highlight/highlight_checklist.php");
		break;
		case "hgl_checklist2":
			include("highlight/highlight_checklist2.php");
		break;
		case "hgl_view":
			include("highlight/highlight_view.php");
		break;
		case "hgl_export":
			include("highlight/highlight_export.php");
		break;
		case "list_synopsis":
			include("ppa11/list_synopsis.php");
		break;
		case "search_synopsis":
			include("ppa11/search_synopsis.php");
		break;
		case "check_synopsis":
			include("ppa11/check_synopsis.php");
		break;
		case "show_synopsis":
			include("ppa11/show_synopsis.php");
		break;
		case "sinopsis":
			include("ppa11/channels.php");
		break;

		/* revista Superview*/
		case "svchl": //Lista grillas fijas revista Superview
		if( !isset( $_GET['client'] ) || !ereg( "[0-9]*", $_GET['client'] ) )
		{
			$_GET['client'] = 86;
		}			include("ppa11/channels.php");
		break;
		case "svfl_gxls":
			include("magazine/superview/generate_xls.php");
		break;
		case "svml": //Lista grillas mensuales (eps) revista Superview
		if( !isset( $_GET['client'] ) || !ereg( "[0-9]*", $_GET['client'] ) )
		{
			$_GET['client'] = 65;
		}		include("magazine/superview/svml_select.php");
		break;
		case "svfl_gexls":
			include("magazine/superview/generate_eps_xls.php");
		break;

		/* revista UNE*/
		case "unechl": //Listado de grillas UNE
		if( !isset( $_GET['client'] ) || !ereg( "[0-9]*", $_GET['client'] ) )
		{
			$_GET['client'] = 78;
		}			include("ppa11/channels.php");
		break;
		case "unem_gxml":
			include("magazine/une/generate_mensual_xml.php");
		break;
		
		/* revista TvCable*/
		case "tvcfl": //Lista grillas fijas revista TvCable
		if( !isset( $_GET['client'] ) || !ereg( "[0-9]*", $_GET['client'] ) )
		{
			$_GET['client'] = 65;
		}			include("ppa11/channels.php");
		break;
		case "tvcfl_checklist": //Lista grillas fijas revista TvCable ( veces de programa por mes )
			include("magazine/tvcable/tvcfl_checklist.php");
		break;
		case "tvcfl_checklist2": //Lista grillas fijas revista TvCable ( cada vez del programa )
			include("magazine/tvcable/tvcfl_checklist2.php");
		break;
		case "tvcfl_gxls":
			include("magazine/tvcable/generate_xls.php");
		break;
		case "tvcml": //Lista grillas mensuales (eps) revista TvCable
		if( !isset( $_GET['client'] ) || !ereg( "[0-9]*", $_GET['client'] ) )
		{
			$_GET['client'] = 65;
		}			include("magazine/tvcable/tvcml_select.php");
		break;
		case "tvcfl_gexls":
			include("magazine/tvcable/generate_eps_xls.php");
		break;
		case "test":
			include("une/test.php");
		break;
		case "viewChapter":
			include("ppa11/view_chapter.php");
		break;
		case "viewChapterImage":
			include("ppa11/view_chapter_image.php");
		break;
		case "programming":
			include("ppa11/test_programming.php");
		break;
		case "auto_sinopsis":
			include("ppa11/auto_sinopsis.php");
		break;
		default:
			echo "Error grave";
		break;
	}
if( !$_GET['nohead'] )
{
	require("footer.php");
?>
</body>
</html>
<? } ?> 