<?

require_once( "menu/include/config.inc.php" );
require_once( "menu/classes/ApplicationMenu.class.php" );
require_once( "menu/include/util.inc.php" );
require_once( "include/db.inc.php" );
require_once( "class/Application.class.php" );	

/**
 * para evitar el fatal error: by BendeR
 */
session_start();

//print_r( $_SESSION['applicationMenu'] );
if( !isset( $_SESSION['user'] ) )
{
	session_destroy();
	echo "    <script>";
	echo "       document.location = 'index.php'";;
	echo "    </script>";
}

/**
 * fin: by bender
 */

if( isset( $_SESSION['applicationMenu'] ) ){
	$options = $_SESSION['applicationMenu']->getAllowMenus();
}
if( isset( $_GET['ano'] ) && $_GET['ano'] >= 2003 )$ano = $_GET['ano'];
else $ano = date('Y');
$_SESSION['app']->connect();
if($_SESSION['user']){
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>&gt;&gt;&gt;&gt;&gt; Dayware &lt;&lt;&lt;&lt;&lt;</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="include/estilos.css" type=text/css rel=stylesheet>
<style type="text/css">
.titulo_sinop {font-size: 18;font-family:arial black; color:#AA44C9}
.gender_actor_dir_sinop { font-weight: bold; font-size: 12;font-family:arial;}
.description_sinop{ font-style: italic; font-size: 12;font-family:arial; }
.horario_sinop{ font-weight: bold; font-size: 12;font-family:arial; color:#FF9900 }
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script language="javascript" src="menu/include/functions.js"></script>
<script language="javascript" src="menu/include/imageColor.js"></script>
<script language="javascript" src="menu/include/forward.js"></script>
<script language="javascript" src="menu/include/validate.js"></script>
<script language="JavaScript" src="menu/include/mm_menu.js" ></script>
<script language="JavaScript" type="text/JavaScript">
<!--
function popup(pagina,nombre,ancho,alto,scrollbar){
	newWindow=window.open(pagina,nombre,'resizable=no,status=no,scrollbars='+scrollbar+',menubar=no,width='+ancho+',height='+alto);
}
//-->

function mmLoadMenusold() {
  if (window.mm_menu_0609102530_1 ) return;
window.mm_menu_0609102530_1 = new Menu("root",177,16,"Verdana, Arial, Helvetica, sans-serif",10,"#000000","#ffffff","#ffffff","#666666","left","middle",3,0,1000,-5,7,true,true,true,0,true,true);
  mm_menu_0609102530_1.addMenuItem("Intermedio","location='intermedio.php'");
  mm_menu_0609102530_1.addMenuItem("La Guia","location='laguia.php'");
   mm_menu_0609102530_1.hideOnMouseOut=true;
   mm_menu_0609102530_1.bgColor='#333333';
   mm_menu_0609102530_1.menuBorder=0;
   mm_menu_0609102530_1.menuLiteBgColor='#ffffff';
   mm_menu_0609102530_1.menuBorderBgColor='#cccccc';
   window.mm_menu_0609102530_2 = new Menu("root",177,16,"Verdana, Arial, Helvetica, sans-serif",10,"#000000","#ffffff","#ffffff","#666666","left","middle",3,0,1000,-5,7,true,true,true,0,true,true);
  mm_menu_0609102530_2.addMenuItem("Crawl","location='tvguianew.php?crawl=1'");
  mm_menu_0609102530_2.addMenuItem("Nuevos Videos","location='tvguianew.php?newvideo=1'");
  mm_menu_0609102530_2.addMenuItem("Editar Videos","location='tvguianew.php?editvideo=1'");
  mm_menu_0609102530_2.addMenuItem("Reporte","location='tvguianew.php?reportvideo=1'");
  //mm_menu_0609102530_2.addMenuItem("Formulario","location='laguia.php'");
   mm_menu_0609102530_2.hideOnMouseOut=true;
   mm_menu_0609102530_2.bgColor='#333333';
   mm_menu_0609102530_2.menuBorder=0;	
   mm_menu_0609102530_2.menuLiteBgColor='#ffffff';
   mm_menu_0609102530_2.menuBorderBgColor='#cccccc';

   mm_menu_0609102530_2.writeMenus();
} // mmLoadMenus()


function mmLoadMenus() {
<? 
$hasMenus = false;

for( $menu = 0; $menu < count( $options ); $menu++ ){
	for( $submenu = 0; $submenu < count( $options[$menu]['submenus'] ); $submenu++ ){
		if( count( $options[$menu]['submenus'][$submenu]['submenus'] ) != 0 ){ 
			$hasMenus = true;
			$hasChildMenus = hasChildNodes( $options[$menu]['submenus'][$submenu]['submenus'] );
			escribirMenu( $options[$menu]['submenus'][$submenu]['submenus'], "mm_menu_0428174051_" . $menu . "_" . $submenu, ucwords( strtolower( $options[$menu]['submenus'][$submenu]['menu']['name'] ) ) ); 
?>		
			mm_menu_0428174051_<?= $menu . "_" . $submenu ?>.hideOnMouseOut=true;
	   		mm_menu_0428174051_<?= $menu . "_" . $submenu ?>.bgColor='#333333';
			mm_menu_0428174051_<?= $menu . "_" . $submenu ?>.childMenuIcon="images/arrows_right.gif";
	   		mm_menu_0428174051_<?= $menu . "_" . $submenu ?>.menuBorder=1;
   			mm_menu_0428174051_<?= $menu . "_" . $submenu ?>.menuLiteBgColor='#333333';
   			mm_menu_0428174051_<?= $menu . "_" . $submenu ?>.menuBorderBgColor='#333333';
<?			
		}
	}

?>
	  if (window.mm_menu_0428174051_<?= $menu ?>) return;
  			window.mm_menu_0428174051_<?= $menu ?> = new Menu("root",110,20,"Arial, Helvetica, sans-serif",11,"#FFFFFF","#000000","#000000","#FFEF03","left","middle",3,0,1000,3,0,true,true,true,0,true,false);
		
<?		
		for( $submenu = 0; $submenu < count( $options[$menu]['submenus'] ); $submenu++ ){
			if( count( $options[$menu]['submenus'][$submenu]['submenus'] ) == 0 ){ 
?>
				mm_menu_0428174051_<?= $menu ?>.addMenuItem( "<?= ucwords( strtolower( $options[$menu]['submenus'][$submenu]['menu']['name'] ) ) ?>", "location='<?= $options[$menu]['submenus'][$submenu]['menu']['function'] ?>'" );
<?
			}else{
?>
  				mm_menu_0428174051_<?= $menu ?>.addMenuItem( mm_menu_0428174051_<?= $menu . "_" . $submenu ?>, "location='<?= $options[$menu]['submenus'][$submenu]['menu']['function'] ?>'" );
<?
			}
		}
?>
		mm_menu_0428174051_<?= $menu ?>.hideOnMouseOut=true;
		mm_menu_0428174051_<?= $menu ?>.childMenuIcon="images/arrows_right.gif";
   		mm_menu_0428174051_<?= $menu ?>.bgColor='#333333';
   		mm_menu_0428174051_<?= $menu ?>.menuBorder=1;
   		mm_menu_0428174051_<?= $menu ?>.menuLiteBgColor='#333333';
   		mm_menu_0428174051_<?= $menu ?>.menuBorderBgColor='#333333';
<?
}
if( count( $options ) > 0 ){
?>
mm_menu_0428174051_<?= count( $options ) - 1 ?>.writeMenus();
<?
}
?>
} // mmLoadMenus1()


function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_nbGroup(event, grpName) { //v6.0
  var i,img,nbArr,args=MM_nbGroup.arguments;
  if (event == "init" && args.length > 2) {
    if ((img = MM_findObj(args[2])) != null && !img.MM_init) {
      img.MM_init = true; img.MM_up = args[3]; img.MM_dn = img.src;
      if ((nbArr = document[grpName]) == null) nbArr = document[grpName] = new Array();
      nbArr[nbArr.length] = img;
      for (i=4; i < args.length-1; i+=2) if ((img = MM_findObj(args[i])) != null) {
        if (!img.MM_up) img.MM_up = img.src;
        img.src = img.MM_dn = args[i+1];
        nbArr[nbArr.length] = img;
    } }
  } else if (event == "over") {
    document.MM_nbOver = nbArr = new Array();
    for (i=1; i < args.length-1; i+=3) if ((img = MM_findObj(args[i])) != null) {
      if (!img.MM_up) img.MM_up = img.src;
      img.src = (img.MM_dn && args[i+2]) ? args[i+2] : ((args[i+1])? args[i+1] : img.MM_up);
      nbArr[nbArr.length] = img;
    }
  } else if (event == "out" ) {
    for (i=0; i < document.MM_nbOver.length; i++) {
      img = document.MM_nbOver[i]; img.src = (img.MM_dn) ? img.MM_dn : img.MM_up; }
  } else if (event == "down") {
    nbArr = document[grpName];
    if (nbArr)
      for (i=0; i < nbArr.length; i++) { img=nbArr[i]; img.src = img.MM_up; img.MM_dn = 0; }
    document[grpName] = nbArr = new Array();
    for (i=2; i < args.length-1; i+=2) if ((img = MM_findObj(args[i])) != null) {
      if (!img.MM_up) img.MM_up = img.src;
      img.src = img.MM_dn = (args[i+1])? args[i+1] : img.MM_up;
      nbArr[nbArr.length] = img;
  } }
}
//-->
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('images/menu_r2_c2_f3.gif','images/menu_r2_c2_f2.gif','images/menu_r2_c3_f3.gif','images/menu_r2_c3_f2.gif','images/menu_r2_c4_f3.gif','images/menu_r2_c4_f2.gif','images/menu_r2_c5_f3.gif','images/menu_r2_c5_f2.gif','images/menu_r2_c6_f3.gif','images/menu_r2_c6_f2.gif')">
<form name="buscar" method="post" action="">
<table width="797" border="0" cellspacing="0" cellpadding="0">
  <!--DWLayoutTable-->
  <tr> 
	<td width="36" height="159">&nbsp;</td>
	<td width="761" align="center" valign="top"><table width="91%" border="0" align="center" cellpadding="0" cellspacing="0">
		<!--DWLayoutTable-->
		<tr> 
		  <td width="760" height="18" align="left" valign="top"><img name="base_r1_c2" src="images/base_r1_c2.gif" width="760" height="18" border="0" alt=""></td>
		  <td width="1"></td>
		</tr>
		<tr> 
		  <td height="141" colspan="2" align="left" valign="top"> <table width="96%" border="0" cellspacing="0" cellpadding="0">
			  <!--DWLayoutTable-->
			  <tr> 
				<td width="761" height="78" align="left" valign="top"><table width="91%" border="0" cellspacing="0" cellpadding="0">
					<!--DWLayoutTable-->
					<tr align="left" valign="top"> 
					  <td width="332" height="62"><img name="cabezote_r1_c1" src="images/cabezote_r1_c1.gif" width="332" height="62" border="0" alt=""></td>
					  <td width="200"><table width="34%" border="0" cellspacing="0" cellpadding="0">
						  <tr align="left" valign="top"> 
							<td colspan="2"><img name="cabezote_r1_c2" src="images/cabezote_r1_c2.gif" width="200" height="7" border="0" alt=""></td>
						  </tr>
						  <tr align="left" valign="top"> 
							<td width="66%" class="textos"> 
							  <?=$_SESSION['username']?>
							</td>
							<td width="34%"><a href="index.php?function=close"><img src="images/cabezote_r2_c4.gif" name="Image23" width="67" height="27" border="0"></a></td>
						  </tr>
						  <tr align="left" valign="top"> 
							<td><a href="" target="_top"><img src="images/cabezote_r4_c2.gif" alt="" name="cabezote_r4_c2" width="59" height="23" border="0"></a></td>
							<td>&nbsp;</td>
						  </tr>
						  <tr align="left" valign="top"> 
							<td colspan="2"><img name="cabezote_r6_c2" src="images/cabezote_r6_c2.gif" width="200" height="5" border="0" alt=""></td>
						  </tr>
						</table></td>
					  <td width="31"><img name="cabezote_r1_c5" src="images/cabezote_r1_c5.gif" width="31" height="62" border="0" alt=""></td>
					  <td width="198" valign="top"><table width="64%" border="0" cellspacing="0" cellpadding="0">
						  <!--DWLayoutTable-->
						  <tr align="left" valign="top"> 
							<td width="97" height="22"><a href="" ><img src="images/cabezote_r1_c6.gif" name="Image25" width="97" height="22" border="0"></a></td>
							<td width="101" valign="top"><a href="soporte.php"><img src="images/cabezote_r1_c7.gif" name="Image26" width="101" height="22" border="0"></a></td>
						  </tr>
						  <tr align="left" valign="top"> 
							<td height="14" colspan="2" valign="top"><img name="cabezote_r3_c6" src="images/cabezote_r3_c6.gif" width="198" height="14" border="0" alt=""></td>
						  </tr>
						  <tr align="left" valign="middle"> 
							<td height="26" colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
								<!--DWLayoutTable-->
								<tr align="left" valign="top"> 
								  <td width="15%" height="26" valign="middle"><font color="#333333" size="1" face="Arial, Helvetica, sans-serif"><strong>BUSCAR</strong></font></td>
								  <td width="5%" valign="middle">&nbsp;</td>
								  <td width="35%" valign="middle"><input name="textfield" type="text" size="15"></td>
								  <td width="27" valign="top"><a href="#"><img src="images/cabezote_r5_c8.gif" name="Image24" width="27" height="26" border="0"></a></td>
								  <td width="40">&nbsp;</td>
								</tr>
							  </table></td>
						  </tr>
						</table></td>
					</tr>
					<tr align="left" valign="top"> 
					  <td height="16" colspan="4" valign="top"><img name="cabezote_r7_c1" src="images/cabezote_r7_c1.gif" width="761" height="16" border="0" alt=""></td>
					</tr>
				  </table></td>
			  </tr>
			  <tr> 
				<td height="25" align="left" valign="top"> <script language="JavaScript1.2">mmLoadMenus();</script>
					<table  bgcolor="#333333" border="0"  cellpadding="3" cellspacing="0" width="761">
                  <!-- fwtable fwsrc="menu.png" fwbase="menu.gif" fwstyle="Dreamweaver" fwdocid = "742308039" fwnested="0" -->
						
                  <tr>

			   <? 
					for( $menu = 0; $menu < count( $options ); $menu++ ){
				?>	
    				<td align="left" id="td_<?= $menu ?>_" onMouseOver="changeBgColor( '#FFEF03', 'td_<?= $menu ?>_' );<?= ( count( $options[$menu]['submenus'] ) > 0 )?"MM_showMenu(window.mm_menu_0428174051_". $menu . ",-3,17,null,'menu" . $menu . "');" :  "" ?>" onMouseOut="changeBgColor( '', 'td_<?= $menu ?>_' );MM_startTimeout();" width="142" ><a href="<?=$options[$menu]['menu']['function']?>" name="menu<?= $menu ?>" class="style4" id="td_<?= $menu ?>_1" ><?= $options[$menu]['menu']['name'] ?></a>
					<?
						if( count( $options[$menu]['submenus'] ) > 0 ){
					?>
						<img src="images/arrows.gif" width="8" height="8">
					<?
						}else{
					?>
						&nbsp;
					<?
						}
					?>
					</td>
			<?
				}
			?>
                  </tr>
                </table></td>
			  </tr>
			  <tr> 
				<td height="18" align="left" valign="top"> <table width="89%" border="0" cellspacing="0" cellpadding="0">
					<!--DWLayoutTable-->
					<tr> 
					  <td width="761" align="left" valign="top">&nbsp;</td>
					</tr>
				  </table></td>
			  </tr>
			  <tr> 
				<td height="1"></td>
			  </tr>
			</table></td>
		</tr>
	  </table></td>
  </tr>
</table>
</form>
<table width="797">
<tr>
<td align="center">
<?
}
?>