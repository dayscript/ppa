<?
require_once( "include/db.inc.php" );
require_once( "class/Application.class.php" );	
session_start();
if( isset( $_GET['ano'] ) && $_GET['ano'] >= 2003 )$ano = $_GET['ano'];
else $ano = date('Y');
/*
if(isset($_GET['user']))$user = $_GET['user'];
else $user = "dayscript";
*/
$_SESSION['app']->connect();
if($_SESSION['user']){
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>&gt;&gt;&gt;&gt;&gt; Dayware &lt;&lt;&lt;&lt;&lt;</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" type=text/css rel=stylesheet>
<style type="text/css">
.titulo_sinop {font-size: 18;font-family:arial black; color:#AA44C9}
.gender_actor_dir_sinop { font-weight: bold; font-size: 12;font-family:arial;}
.description_sinop{ font-style: italic; font-size: 12;font-family:arial; }
.horario_sinop{ font-weight: bold; font-size: 12;font-family:arial; color:#FF9900 }
</style>
<script language="JavaScript" type="text/JavaScript">
function popup(pagina,nombre,ancho,alto,scrollbar){
	newWindow=window.open(pagina,nombre,'resizable=no,status=no,scrollbars='+scrollbar+',menubar=no,width='+ancho+',height='+alto);
}
//-->
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
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
							<td width="101" valign="top"><a href=""><img src="images/cabezote_r1_c7.gif" name="Image26" width="101" height="22" border="0"></a></td>
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
				<td height="44" align="left" valign="top">  
				  <table border="0" cellpadding="0" cellspacing="0" width="761">
                      <!-- fwtable fwsrc="menu.png" fwbase="menu.gif" fwstyle="Dreamweaver" fwdocid = "742308039" fwnested="0" -->
                      <tr class="textos" bgcolor="#CCCCCC"> 
                        <td rowspan="2"><img name="menu_r2_c1_2" src="images/menu_r2_c1.gif" width="137" height="25" border="0" alt=""></td>
                        <td>&nbsp;</td>
                        <td><a href="demos/index.htm" target="_blank">Demos</a></td>
						<td><a href="cajas.php">Cajas</a></td>
		        <td><a href="ppa.php">PPA</a></td>
                        <td><a href="intermedio.php">Intermedio</a></td>
                        <td><a href="laguia.php">La Guía</a></td>
                        <td><a href="tvguia.php">Tv Guía</a></td>
                        <td><a href="index.php">Inicio</a></td>
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