<?
	require_once( "menu/classes/ApplicationGroup.class.php" );
	
	class GroupManager{
		var $__error;
		
		function GroupManager(){
		}
		
		function getAllGroups(){
			$sql = 	"SELECT id_group, name " .
					"FROM application_groups " .
					"ORDER BY name";
			
			$result = db_query( $sql );
			$groups = array();
			while( $row = db_fetch_array( $result ) )
				$groups[] = $row;
				
			return $groups;
		}
		
		function updateGroup( $data ){
			$appGroup = new ApplicationGroups( $data['id_group'] );
			$appGroup->setName( $data['name'] );
			$appGroup->setDescription( ( $data['description'] != "" )? $data['description'] : NULL );
			$appGroup->commit();
			
			if( mysql_error() != "" ){
				$this->__error = "Error al actualizar el grupo";
				return FALSE;
			}
			
			$this->__error = "";
			return $appGroup;
		}
		
		function addGroup( $data ){
			$appGroup = new ApplicationGroups();
			$appGroup->setName( $data['name'] );
			$appGroup->setDescription( ( $data['description'] != "" )? $data['description'] : NULL );
			$appGroup->commit();
			
			if( mysql_error() != "" ){
				$this->__error = "Error al crear el nuevo grupo";
				return FALSE;
			}
			
			$this->__error = "";
			return $appGroup;
		}
		
		function delGroup( $id_group ){
			$sql = "DELETE FROM " .
					"menus " .
					"WHERE id_group = " . $id_group;
			db_query( $sql );
			if( mysql_error() != "" ){
				$this->__error = "Error al eliminar los menus asociados";
				return FALSE;
			}
				
			$appGroup = new ApplicationGroups( $id_group );
			$appGroup->erase();
			
			if( mysql_error() != "" ){
				$this->__error = "Error al eliminar el grupo";
				return FALSE;
			}
			
			$this->__error = "";
			return TRUE;
		}
		
		function getLastError(){
			return $this->__error;
		}
	}
?>