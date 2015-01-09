<?
	//$PATH = "d:/htdocs/live/";
	$PATH = "/home/ppa/public_html/";
	
	require_once( $PATH . "ppa/include/config.inc.php" );
	require_once( $PATH . "ppa/include/db.inc.php" );
	require_once( $PATH . "menu/classes/admin/PrivilegeManager.class.php" );
	
	$manager = new PrivilegeManager();
	$menuTree = $manager->createTreeFromSource( "../", $manager->getRestApplicationMenu() );
?>
<script>
	var URLBaseLive = "../";
</script>
<script language="javascript" src="include/tree.js"></script>
<script language="javascript" src="include/imageColor.js"></script>
<script type="text/javascript" src="include/dom2_events.js"></script>
<link href="include/styles.css" rel="stylesheet" type="text/css">
<body leftmargin="0" topmargin="0" rightmargin="0" onLoad="MM_preloadImages( '<?= $_SESSION['BASE_URL'] ?>images/carpeta.gif', '<?= $_SESSION['BASE_URL'] ?>images/menu_corner.gif', '<?= $_SESSION['BASE_URL'] ?>images/menu_corner_minus.gif', '<?= $_SESSION['BASE_URL'] ?>images/menu_corner_plus.gif' );">
<script>initialize();</script>
<table width="100%" cellpadding="0" cellspacing="0" border="0" >
	<tr>
		<td class="textos1" id="startRow"><?= $menuTree->renderizarArbol(); ?></td>
	</tr>
</table>
<div id="menuDerecho" class="inicial" onMouseOver="clearTimeOutMenu();" onMouseOut="initTimeOutMenu();" >
	<table width="128" cellspacing="0" cellpadding="2" border="0" >
		<tr><td width="124" class="textos" onClick="agregarMenu( <?= $_GET['id_group'] ?> );" onMouseOver="changeColor(this, '#FFEF03', '#000000' );" onMouseOut="changeColor(this, '', '#FFFFFF' )">&nbsp;Agregar este menu</td></tr>
	</table>
</div>
