<?
	class Hoja{
		
		var $nombre;   	// String
		var $id;
		var $imagen;		// String
		var $extendido;	// int
		var $url;			// String
		var $objetivo;		// String
		var $color;
		var $BASE_URL;
		var $style;

		var $hijos;			//	Class Hoja

		function Hoja( $nombre = "", $id = "", $imagen = "", $extendido = 0, $objetivo = "_self", $url = "", $BASE_URL = "", $style = "", $color = "#333333", $hijos = array() ){
			$this->id = $id;
			$this->nombre = $nombre;
			$this->imagen = $imagen;
			$this->extendido = $extendido;
			$this->objetivo = $objetivo;
			$this->url = $url;
			$this->BASE_URL = $BASE_URL;
			$this->style = $style;
			$this->hijos = $hijos;
			$this->color = $color;
		}
		
		function asignarHijos( $hojas ){
			$this->hijos = array();
			
			for( $i = 0; $i < count( $hojas ); $i++ ){
				$this->hijos[] = $hojas[$i];
			}
		}
		
		function agregarHoja( $hoja ){
			if( $this->hijos == NULL )
				$this->hijos = array();
				
			$this->hijos[] = $hoja;
		}
		
		function mostrarHoja(){
			echo "Nombre: " . $this->nombre . "<br>" .
					"imagen: " . $this->imagen . "<br>";
		} 
		
		function renderizarHoja( $raiz = FALSE ){
			$html = 	"<table cellpading='0' cellspacing='0' width=" . ( ( $raiz )?"350":"100%" ) ." border='0' >" .
							"<tr width=" . ( ( $raiz )?"200":"100%" ) ." >" .
								"<td width='5%' style='border-left: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;'>";
			if( count( $this->hijos ) > 0 )
				$html .=				"<img src='" . $this->BASE_URL . "images/menu_corner_" . ( ( $this->extendido == 1 )?"minus":"plus" ) . ".gif' border='0' onClick='cambiarModo( this )' >";
			else
				$html .=				"&nbsp;";

			$html .=				"</td>";
						if( $this->imagen != "" ){
							$html .= "<td width='5%'>" .
									 "<a href='" . $this->url . "' target='" . $this->objetivo . "' style='text-decoration: none;' border='0' onClick='javascript:cambiarModo2( this )' >" .
										"<img src=\"" . $this->BASE_URL . $this->imagen . "\" name='imagen" . $this->id . "' border='0' id='" . $this->id . "' >" .
									"</a>" .
									"</td>";
						}
						$html .= "<td class='textos' align='left' width='90%'><a href='" . $this->url . "' target='" . $this->objetivo . "' style='text-decoration: none;' ><font color='" . $this->color . "' id='" . $this->id . "' class='" . $this->style . "' >" . ucwords( strtolower( $this->nombre ) ) . "</font></a></td>" .
							"</tr>";
			if( count( $this->hijos ) > 0 ){
				$html .=	"<tr style='display: " . ( ( $this->extendido == 1 )?"":"none" ) . ";' >" .
								"<td align='center' valign='top' style='border-left: 1px solid #DDDDDD;' >&nbsp;</td>" .
								"<td colspan='" . ( ( $this->imagen != "" )? "2" : "3" ) . "' >";
				for( $i = 0; $i < count( $this->hijos ); $i++ )
					$html .=		$this->hijos[ $i ]->renderizarHoja();
								
				$html .=		"</td>" .
							"</tr>";
			}
			$html .=	"</table>";
			
			return $html;
		}
	}
?>