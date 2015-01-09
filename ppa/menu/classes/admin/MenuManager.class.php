<?
	$PATH = "/home/ppa/public_html/";
	require_once( $PATH . "menu/classes/utils/Arbol.class.php" );
	require_once( $PATH . "menu/classes/utils/Hoja.class.php" );
	require_once( $PATH . "menu/classes/Options.class.php" );
	
	class MenuManager{
	
		function getApplicationMenu(){
			$menus = array( );
			$arr = array( );
			
			$sql  = "SELECT * FROM options " .
					"ORDER BY id_parent, description";
				
			$result = db_query( $sql );
			while ($row = db_fetch_array( $result ) )
				$arr[] = $row;

			for ($c = 0; $c < count ($arr); $c++)	{
				if (is_null($arr[$c]['id_parent']))	{
					$pos = count ($menus);
					$menus[$pos]['menu']['id_option'] = $arr[$c]['id_option'];
					$menus[$pos]['menu']['name'] = $arr[$c]['description'];
					$menus[$pos]['menu']['function'] = $arr[$c]['function_'];
					$menus[$pos]['submenus'] = $this->searchChildMenuFrom ( $c, $arr[$c]['id_option'], $arr );
				}
			}			
			
			return $menus;
		}
		
		function searchChildMenuFrom ( $index, $parent, $arr )	{
			$menus = array ();
			for ($c = $index + 1; $c < count ($arr); $c++)	{
				if ($parent == $arr[$c]['id_parent'])	{
					$pos = count ($menus);
					$menus[$pos]['menu']['id_option'] = $arr[$c]['id_option'];
					$menus[$pos]['menu']['name'] = $arr[$c]['description'];
					$menus[$pos]['menu']['function'] = $arr[$c]['function_'];
					$menus[$pos]['submenus'] = $this->searchChildMenuFrom ( $c, $arr[$c]['id_option'], $arr );
				}
			}
			return $menus;
		}
		
		function createTreeFromSource( $BASE_URL, $arreglo ){
			
			$arbol = new Arbol();
			$imagenCarpeta = "images/carpeta.gif";
			$raiz = new Hoja( "/", "0", $imagenCarpeta, 1, "_self", "#", $BASE_URL );
			
			for( $i = 0; $i < count( $arreglo ); $i++ ){
				$menu = new Hoja( $arreglo[$i]['menu']['name'], $arreglo[$i]['menu']['id_option'], $imagenCarpeta, 0, "_self", "#", $BASE_URL );
				$this->addChildNodeToTree( $arreglo[$i]['submenus'], $menu, $BASE_URL );
				$raiz->agregarHoja( $menu  );
			}
			
			$arbol->setRaiz( $raiz );
			return $arbol;
		}
		
		function addChildNodeToTree( $arreglo, &$padre, $BASE_URL ){
			$imagenCarpeta = "images/carpeta.gif";
			for( $i = 0; $i < count( $arreglo ); $i++ ){
				$menu = new Hoja( $arreglo[$i]['menu']['name'], $arreglo[$i]['menu']['id_option'], $imagenCarpeta, 0, "_self",  "#", $BASE_URL );
				$this->addChildNodeToTree( $arreglo[$i]['submenus'], $menu, $BASE_URL );
				$padre->agregarHoja( $menu  );
			}
		}
		
		function createMenu( $data ){
			$option = new Options();
			$option->setIdParent( ( $data['id_parent'] != "0" )? $data['id_parent'] : NULL );
			$option->setDescription( $data['description'] );
			$option->setFunction( $data['_function'] );
			$option->setPosition( $data['position'] );
			$option->commit();
			
			return $option;
		}
		
		function updateMenu( $data ){
			$option = new Options( $data['id_option'] );
			$option->setDescription( $data['description'] );
			$option->setFunction( $data['_function'] );
			$option->setPosition( $data['position'] );
			$option->commit();
			
			return $option;
		}
		
		function deleteMenu( $id_option ){
			$option = new Options( $id_option );
			$sql = "DELETE FROM options " .
					"WHERE id_parent = " . $option->getIdOption();
			db_query( $sql );
			if( mysql_error() != "" ){
				return FALSE;
			}
			
			$sql = "DELETE FROM menus WHERE id_option = " . $option->getIdOption(); 
			db_query( $sql );
			if( mysql_error() != "" ){
				return FALSE;
			}
			
			$option->erase();
			return TRUE;
		}
	}
?>