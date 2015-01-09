<?
	global $colors;
	$colors = array();
	$colors[0][0] = "CC0000";
	$colors[0][1] = "A40000";
	
	$colors[1][0] = "FFCC00";
	$colors[1][1] = "CEA500";
		
	$colors[2][0] = "FF6600";
	$colors[2][1] = "D25400";
		
	$colors[3][0] = "FF0000";
	$colors[3][1] = "D50000";
		
	$colors[4][0] = "FF3300";
	$colors[4][1] = "D72B00";
		
	$colors[5][0] = "FF9900";
	$colors[5][1] = "D78100";
		
	$colors[6][0] = "FFFF00";
	$colors[6][1] = "D5D500";

	function escribirMenu( $menus, $padre, $menuName ){
		
		$hasMenus = false;
		for( $i = 0; $i < count( $menus ); $i++ ){
			if( count( $menus[$i]['submenus'] ) != 0 ){
				
				$hasChildMenus = hasChildNodes( $menus[$i]['submenus'] );
				$hasMenus = true;
				
				escribirMenu( $menus[$i]['submenus'], $padre . "_" . $i, ucwords( strtolower( $menus[$i]['menu']['name'] ) ) );
			?>
				<?= $padre . "_" . $i ?>.hideOnMouseOut=true;
	   			<?= $padre . "_" . $i ?>.bgColor='#000000';
				<?= $padre . "_" . $i ?>.childMenuIcon="images/arrows_right.gif";
	   			<?= $padre . "_" . $i ?>.menuBorder=1;
   				<?= $padre . "_" . $i ?>.menuLiteBgColor='#000000';
   				<?= $padre . "_" . $i ?>.menuBorderBgColor='#000000';
			<?
			}
			?>
			window.<?= $padre ?> = new Menu( "<?= $menuName ?>",130,20,"Arial, Helvetica, sans-serif",11,"#FFFFFF","#000000","#000000","#FFEF03","left","middle",3,0,1000,3,0,true,true,true,0,false,false);
			<?
			for( $i = 0; $i < count( $menus ); $i++ ){
				if( count( $menus[$i]['submenus'] ) == 0 ){
			?>
					<?= $padre ?>.addMenuItem( "<?= ucwords( strtolower( $menus[$i]['menu']['name'] ) ) ?>", "location='<?= $menus[$i]['menu']['function'] ?>'" );
			<?
				}else{
			?>
					<?= $padre ?>.addMenuItem( <?= $padre . "_" . $i ?>, "location='<?= $menus[$i]['menu']['function'] ?>'" );
			<?
				}
			}
		}
	}
	
	function hasChildNodes( $opciones ){
		for( $i = 0; $i < count( $opciones ); $i++ ){
			if( count( $opciones[$i]['submenus'] ) != 0 )
				return true;
		}
		
		return false;
	}
	
	function alertHtml( $message ){
		?>
			<script>
				alert( "<?= $message ?>" );
			</script>
		<?
	}
	
	function reloadHtml( $reloadPage ){
		?>
			<script>
				<?= $reloadPage ?>.reload();
			</script>
		<?
	}
	
	function doLineScript( $lineScript ){
		?>
			<script>
				<?= $lineScript ?>;
			</script>
		<?
	}
	
	function initOrderedColors(){
		global $colors;
		reset( $colors );
	}
	
	function getOrderedColor(){
		global $colors;
		if( $current = current( $colors ) ){
			next( $colors );
			return $current;
		}else{
			return reset( $colors );
		}
	}
	
	function getRandomColor(){
		global $colors;
		return $colors[ rand( 0, count( $colors ) - 1 ) ];
	}
	
	function replaceChars( $string ){
		$string = str_replace( 'á', 'a', $string );
		$string = str_replace( 'é', 'e', $string );
		$string = str_replace( 'í', 'i', $string );
		$string = str_replace( 'ó', 'o', $string );
		$string = str_replace( 'ú', 'u', $string );
		
		return $string;
	}
	
	function getNameByIdCompare( $id, $compareElement ){
		switch( $compareElement ){
			case 1:
				return $_SESSION['application']->getProductNameById( $id );
			case 2:
				return $_SESSION['application']->getTypeNameById( $id );
			case 3:
				return $_SESSION['application']->getAgencyNameById( $id );
			case 4:
				return $_SESSION['application']->getChannelNameById( $id, $_SESSION['application']->client->getIdClient() );
			case 5:
				return $_SESSION['application']->getGroupNameById( $id );
		}
	}
	
?>