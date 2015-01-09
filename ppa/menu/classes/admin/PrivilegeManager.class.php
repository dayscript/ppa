<?
	$PATH = "/home/ppa/public_html/";
	require_once( $PATH . "menu/classes/utils/Arbol.class.php" );
	require_once( $PATH . "menu/classes/utils/Hoja.class.php" );
	
	class PrivilegeManager{
		
		function getAllowMenus( $id_group ){
			$menus = array( );
			$arr = array( );
			
			$sql  = "SELECT options.* FROM options, menus ";
			$sql .= "WHERE options.id_option = menus.id_option ";
			$sql .= "AND menus.id_group = " . $id_group . " ";
			$sql .= "ORDER BY options.id_parent, options.description, options.position";
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
		
		function getRestApplicationMenu(){
			$menus = array( );
			$arr = array( );
			
			$sql  = "SELECT * FROM options " .
					"ORDER BY id_parent, description, description";
				
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
				$menu = new Hoja( $arreglo[$i]['menu']['name'], $arreglo[$i]['menu']['id_option'], $imagenCarpeta, 0, "_self",  "#", $BASE_URL );
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
		
		function addMenuToPrivelege( $pathParent, $id_group ){
			$padre = strtok( $pathParent, "," );
			while( $padre ){
    			$sql = 	"SELECT * " .
						"FROM menus " .
						"WHERE id_group = " . $id_group . " AND id_option = " . $padre;
				
				$result = db_query( $sql );
				if( !db_fetch_array( $result ) ){
					$sql = 	"INSERT INTO menus " .
							"( id_group, id_option ) " .
							"VALUES " .
							"( " . $id_group . ", " . $padre . " )";
							
					db_query( $sql );
				}
				 
				$padre = strtok( "," );	
			}
		}
		
		function removeMenuToPrivelege( $pathChild, $id_group ){
			
			$child = strtok( $pathChild, "," );
			$sql1 = "";
			while( $child ){
				$sql = "DELETE FROM menus WHERE id_option = " . $child . " AND id_group = " . $id_group;
				db_query( $sql );
				$child = strtok( "," );
			}
		}
	}
?>