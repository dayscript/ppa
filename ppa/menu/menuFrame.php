<?
	//$PATH = "d:/htdocs/live/";
	$PATH = "/home/ppa/public_html/";
	require_once( $PATH . "ppa/include/config.inc.php" );
	require_once( $PATH . "ppa/include/db.inc.php" );
	require_once( $PATH . "menu/classes/admin/MenuManager.class.php" );
	
	$manager = new MenuManager();
	$menuTree = $manager->createTreeFromSource( "../", $manager->getApplicationMenu() );
?>
<html>
<head>
<script>
	var URLBaseLive = "../";
</script>
<script language="javascript" src="include/tree.js"></script>
<script language="javascript" src="include/imageColor.js"></script>
<script type="text/javascript" src="include/dom2_events.js"></script>
<link href="include/styles.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="MM_preloadImages( '../images/carpeta.gif', '../images/menu_corner.gif', '../images/menu_corner_minus.gif', '../images/menu_corner_plus.gif' );">
<script>initialize();</script>
<table width="100%" cellpadding="0" cellspacing="0" border="0" >
	<tr>
		<td class="textos1"><?= $menuTree->renderizarArbol(); ?></td>
	</tr>
</table>
<div id="menuDerecho" class="inicial" onMouseOver="clearTimeOutMenu();" onMouseOut="initTimeOutMenu();" >
	<table width="120" cellspacing="0" cellpadding="2" border="0" >
		<tr><td class="textos" onMouseOver="changeColor(this, '#FFEF03', '#000000' );" onMouseOut="changeColor(this, '', '#FFFFFF' )" onClick="editMenu();">&nbsp;Editar Menus</td></tr>
		<tr><td class="textos" onMouseOver="changeColor(this, '#FFEF03', '#000000' );" onMouseOut="changeColor(this, '', '#FFFFFF' )" onClick="addMenu();">&nbsp;Agregar Submenu</td></tr>
		<tr><td class="textos" onMouseOver="changeColor(this, '#FFEF03', '#000000' );" onMouseOut="changeColor(this, '', '#FFFFFF' )" onClick="delMenu();">&nbsp;Eliminar Menu</td></tr>
	</table>
</div>
</body>
</html>
