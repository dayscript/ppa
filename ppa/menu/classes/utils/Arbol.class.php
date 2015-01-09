<?
	class Arbol{
	
		var $raiz;		// Class Hoja
		
		function Arbol( $raiz = NULL ){
			$this->raiz = $raiz;
		}
		
		function getRaiz(){
			return $this->raiz;
		}
		
		function setRaiz( $raiz ){
			$this->raiz = $raiz;
		}
		
		function renderizarArbol(){
			return $this->raiz->renderizarHoja( TRUE );
		}
		
	}
?>